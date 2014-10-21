<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, LINK_CLASS_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);

$listUrl = "link_class_list.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$haspic			= (int)$_POST["haspic"];

	if (empty($id))
	{
		$id = $db->getMax("link_class", "id") + 1;
		$sql = "insert into link_class(id, sortnum, name, haspic) values('$id', $sortnum, '$name', $haspic)";
	}
	else
	{
		$sql = "update link_class set sortnum=$sortnum, name='$name', haspic=$haspic where id='$id'";
	}
	$rst = $db->query($sql);
	$db->close();
	header("Location: $listUrl");
}
else
{
	if ($id == "")
	{
		$sortnum 	= $db->getMax("link_class", "sortnum") + 10;
		$haspic		= 1;
	}
	else
	{
		$sql = "select id, sortnum, name, haspic from link_class where id='$id'";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$id				= $row["id"];
			$sortnum		= $row["sortnum"];
			$name			= $row["name"];
			$haspic			= $row["haspic"];
		}
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
        <script type="text/javascript" src="images/ajax.js"></script>
		<script type="text/javascript">
			function check(form)
			{
				if (form.sortnum.value == "" || form.sortnum.value.match(/\D/))
				{
					alert("请输入合法的序号！");
					form.sortnum.focus();
					return false;
				}

				if (form.name.value == "")
				{
					alert("请输入分类名称！");
					form.name.focus();
					return false;
				}
				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 链接分类管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
			<form name="form1" action="" method="post" onSubmit="return check(this);">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">子分类</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">排列序号</td>
					<td class="editRightTd"><input type="text" name="sortnum" value="<?=$sortnum?>" size="10" maxlength="5"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">分类名称</td>
					<td class="editRightTd"><input type="text" name="name" value="<?=$name?>" maxlength="50" size="30"></td>
				</tr>
				<tr class="editTr" style="display:none">
					<td class="editLeftTd">链接模式</td>
					<td class="editRightTd">
						<input type="radio" name="haspic" value="1"<? if ($haspic == 1) echo " checked"?>>图片
						<input type="radio" name="haspic" value="0"<? if ($haspic == 0) echo " checked"?>>文字
					</td>
				</tr>
				<tr class="editFooterTr">
					<td class="editFooterTd" colSpan="2">
						<input type="submit" value=" 确 定 ">
						<input type="reset" value=" 重 填 ">
					</td>
				</tr>
			</form>
		</table>
		<script type="text/javascript">document.form1.name.focus();</script>
		<?
		$db->close();
		?>
	</body>
</html>
