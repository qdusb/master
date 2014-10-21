<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BANNER_CLASS_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id		= trim($_GET["id"]);
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl	= "banner_class_list.php?page=$page";
$editUrl		= "banner_class_edit.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($id != "")
{
	//是否有内容
	if ($db->getCount("banner", "class_id='$id'") > 0)
	{
		$db->close();
		info("分类下有信息，请先删除信息！");
	}

	$sql = "delete from banner_class where id='$id'";
	$rst = $db->query($sql);
	if ($rst)
	{
		$db->query("commit");
		$db->close();
		header("Location: $listUrl");
		exit;
	}
	else
	{
		$db->query("rollback");
		$db->close();
		info("删除分类失败！");
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
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; Banner分类管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
					<a href="<?=$editUrl?>">[增加]</a>&nbsp;
				</td>
				<td align="right">
					<?
					//设置每页数
					$page_size = DEFAULT_PAGE_SIZE;
					//总记录数
					$sql = "select count(*) as cnt from banner_class";
					$rst = $db->query($sql);
					$row = $db->fetch_array($rst);
					$record_count = $row["cnt"];
					$page_count = ceil($record_count / $page_size);

					$page_str = page($page, $page_count, $pageUrl);
					//echo $page_str;
					?>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<tr class="listHeaderTr">
				<td>序号</td>
				<td>分类名称</td>
                <td>禁止增加</td>
				<td>禁止删除</td>
				<td>删除</td>
			</tr>
			<?
			$sql = "select id, sortnum, name, add_deny, delete_deny from banner_class order by sortnum asc";
			$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				<tr class="<?=$css?>">
					<td><?=$row["sortnum"]?></td>
					<td><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
                    <td>
                    	<?
                        switch ($row["add_deny"])
						{
							case 0:
								echo "否";
								break;
							case 1:
								echo "是";
								break;
						}
						?>
                    </td>
                    <td>
                    	<?
                        switch ($row["delete_deny"])
						{
							case 0:
								echo "否";
								break;
							case 1:
								echo "是";
								break;
						}
						?>
                    </td>
					<td><a href="<?=$listUrl?>&id=<?=$row["id"]?>" onClick="return del();">删除</a></td>
				</tr>
			<?
			}
			?>
			<tr class="listFooterTr">
				<td colspan="10"><?=$page_str?></td>
			</tr>
		</table>
		<?
        $db->close();
		?>
	</body>
</html>
