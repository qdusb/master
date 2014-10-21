<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

$id = (int)$_GET["id"];


$listUrl = "advanced_list.php";
$editUrl = "advanced_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($id > 0)
{
	$sql = "delete from advanced where id=$id";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
	else
	{
		info("删除记录失败！");
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
				<td class="position">当前位置: 管理中心 -&gt; 隐藏管理 -&gt; 高级功能管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
					<a href="<?=$editUrl?>">[增加]</a>&nbsp;
				</td>
				<td width="500" align="right">
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<tr class="listHeaderTr">
				<td width="5%">ID号</td>
				<td width="8%">序号</td>
				<td width="30%">功能名称</td>
				<td>首页文件</td>
				<td width="8%">状态</td>
				<td width="8%">删除</td>
			</tr>
			<?
			$sql = "select id, sortnum, name, default_file, state from advanced order by sortnum asc";
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				<tr class="<?=$css?>">
					<td><?=$row["id"]?></td>
					<td><?=$row["sortnum"]?></td>
					<td><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
					<td><?=$row["default_file"]?></td>
					<td><?=$row["state"] == 1 ? "显示" : "<font color=#FF6600>不显示</font>"?></td>
					<td><a href="<?=$listUrl?>?id=<?=$row["id"]?>" onClick="return del('<?=$row["name"]?>');">删除</a></td>
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
