<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, ADVER_ADVANCEDID) == false)
{
	info("没有权限！");
}


$listUrl = "adver_list.php";
$editUrl = "adver_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array = $_POST["ids"];
	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	$sql = "select pic from adver where id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);
	while ($row = $db->fetch_array($rst))
	{
		$pic .= $row["pic"] . ",";
	}

	$sql = "delete from adver where id in (" . implode(",", $id_array) . ")";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		deleteFiles($pic, 1);
		header("Location: $listUrl");
		exit();
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 广告管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>
					<a href="<?=$editUrl?>">[增加]</a>
					<a href="javascript:reverseCheck(document.form1.ids);">[反向选择]</a>&nbsp;
					<a href="javascript:if(delCheck(document.form1.ids)) {document.form1.submit();}">[删除]</a>&nbsp;
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<form name="form1" action="" method="post">
				<tr class="listHeaderTr">
					<td width="3%"></td>
					<td>标题名称</td>
					<td width="8%">广告方式</td>
					<td width="8%">宽度</td>
					<td width="8%">高度</td>
					<td width="8%">链接</td>
					<td width="8%">广告文件</td>
					<td width="8%">状态</td>
				</tr>
				<?
				$sql = "select id, title, mode, url, width, height, pic, state from adver order by id asc";
				$rst = $db->query($sql);
				while ($row = $db->fetch_array($rst))
				{
					$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
				?>
					<tr class="<?=$css?>">
						<td><input type="checkbox" id="ids" name="ids[]" value="<?=$row["id"]?>"></td>
						<td><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["title"]?></a></td>
						<td>
							<?
							switch($row["mode"])
							{
								case "popup":
									echo "弹出广告";
									break;
								case "float":
									echo "漂浮广告";
									break;
								case "hangL":
									echo "左侧门帘";
									break;
								case "hangR":
									echo "右侧门帘";
									break;
								case "hangLR":
									echo "左右门帘";
									break;
								case "bigScreen":
									echo "拉屏广告";
									break;
								default:
									echo "<font color='#FF0000'>错误</font>";
									break;
							}
							?>
						</td>
						<td><?=$row["width"]?></td>
						<td><?=$row["height"]?></td>
						<td>
							<?
							if ($row["url"] != "")
							{
							?>
								<a href="<?=$row["url"]?>" target="_blank">有</a>
							<?	
							}
							else
							{
								echo "无";
							}
							?>
						</td>
						<td>
							<?
								if ($row["pic"] != "")
								{
							?>
									<a href="<?=UPLOAD_PATH_FOR_ADMIN . $row["pic"]?>" target="_blank">有</a>
							<?
								}
								else
								{
									echo "无";
								}
							?>
						</td>
						<td><?=($row["state"] == 1) ? "显示" : "<font color='#FF6600'>不显示</font>"?></td>
					</tr>
				<?
				}
				?>
				<tr class="listFooterTr">
					<td colspan="10"></td>
				</tr>
			</form>
		</table>
		<?
		$db->close();
		?>
	</body>
</html>
