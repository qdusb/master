<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id			= (int)$_GET["id"];
$clear_id	= (int)$_GET["clear_id"];


$listUrl = "admin_list.php";
$editUrl = "admin_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除  admin 不可删除
if ($id > 1)
{
	if ($db->getCount("info", "admin_id=$id") > 0)
	{
		$db->close();
		info("该管理员已发表过信息，不允许删除！");
	}
	
	//事务开始
	$db->query("begin");
	
	//删除登陆日志
	$sql = "delete from admin_login where admin_id=$id";
	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}
	
	//删除分类栏目权限
	$sql = "delete from admin_popedom where admin_id=$id";
	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}
	
	//删除高级管理权限
	$sql = "delete from admin_advanced where admin_id=$id";
	if (!$db->query($sql))
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}
	
	//删除管理员
	$sql = "delete from admin where id=$id";
	if ($db->query($sql))
	{
		$db->query("commit");
		$db->close();
		header("Location: $listUrl");
		exit();
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("删除管理员失败！");
	}
}

//清空日志
if ($clear_id > 0)
{
	$db->query("begin");
	
	$sql= "delete from admin_login where admin_id=$clear_id";
	$rst = $db->query($sql);
	if ($rst)
	{
		$db->query("commit");
		$db->close();
		info("清空登录日志成功！");
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("清空登录日志失败！");
	}
}
?>


<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="-1000">
		<link href="images/admin.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="images/common.js"></script>
		<script type="text/javascript">
			function clearLogin()
			{
				if (confirm("即将清空登陆日志 , 且该操作不能恢复! 是否继续 ?"))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 系统管理 -&gt; 系统管理员</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>
					<a href="<?=$editUrl?>">[增加]</a>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<tr class="listHeaderTr">
				<td>登陆帐号</td>
				<td width="30%">真实姓名</td>
				<td width="12%">管理等级</td>
				<td width="8%">登陆次数</td>
				<td width="12%">登陆日志</td>
				<td width="8%">状态</td>
				<td width="8%">删除</td>
			</tr>
			<?
			$sql = "select id, name, realname, grade, login_count, state from admin order by id asc";
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				<tr class="<?=$css?>">
					<td><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
					<td><?=$row["realname"]?></td>
					<td>
						<?
						switch ($row["grade"])
						{
							case 8:
								echo "<font color='#FF6600'>系统管理员</font>";
								break;
							case 7:
								echo "<font color='#0066FF'>高级管理员</font>";
								break;
							case 6:
								echo "审核管理员";
								break;
							case 5:
								echo "普通管理员";
								break;
							default:
								echo "<font color='#FF0000'>错误</font>";
								break;
						}
						?>
					</td>
					<td><?=$row["login_count"]?></td>
					<td><a href="admin_login_list.php?id=<?=$row["id"]?>">查看日志</a>&nbsp;&nbsp;<a href="<?=$listUrl?>?clear_id=<?=$row["id"]?>" onClick="return clearLogin()">清空</a></td>
					<td><?=($row["state"] == 1) ? "正常" : "<font color='#FF6600'>锁定</font>"?></td>
					<td><a href="<?=$listUrl?>?id=<?=$row["id"]?>" onClick="return del();">删除</a></td>
				</tr>
			<?
			}
			?>
			<tr class="listFooterTr">
				<td colspan="10"></td>
			</tr>
		</table>
		<?
		$db->close();
		?>
	</body>
</html>
