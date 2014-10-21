<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BANNER_CLASS_ADVANCEDID) == false)
{
	info("没有权限！");
}

$class_id= trim($_GET["class_id"]);
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$class_id=empty($class_id)?"101101":$class_id;
$listUrl	= "batch_upload_list.php?class_id=$class_id&page=$page";
$editUrl	= "batch_upload_info.php";

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
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
				<td class="position">当前位置: 管理中心 -&gt; 图片批量上传管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
					<a href="<?=$editUrl."?class_id=".$class_id?>" style="display:none">[增加]</a>&nbsp;
					<a href="javascript:">请选择栏目</a>
					<select name="select_class" onChange="window.location='?class_id=' + this.options[this.selectedIndex].value;">
						<?
							$sql = "select id, name from info_class order by id asc";
							$rst = $db->query($sql);
							while ($row = $db->fetch_array($rst))
							{
								$id_str=(string)$row["id"];
								$class_name=$row['name'];
								if(strlen($id_str)==12)
								{
									$lab="|——————";
								}
								else if(strlen($id_str)==9)
								{
									$lab="|————";
								}
								else if(strlen($id_str)==6)
								{
									$lab="|——";
								}
								
								else if(strlen($id_str)==3)
								{
									$lab="|";
								}
								if($id_str==(string)$class_id)
								{
									echo "<option value='" . $id_str . "' selected>$lab".$row['name'];
								}
								else
								{
									echo "<option value='" . $id_str . "' >$lab".$row['name'];
								}
							}
							?>
					</select>
					<a href="batch_upload.php?id=<?=$class_id?>">[批量上传图片]</a>
				</td>
				<td align="right">
					<?
					//设置每页数
					$page_size = DEFAULT_PAGE_SIZE;
					//总记录数
					$sql = "select count(*) as cnt from info where class_id=$class_id";
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
				<td>名称</td>
				<td>缩略图</td>
				<td>状态</td>
			</tr>
			<?
			$sql = "select sortnum,id,title,state,pic from info where class_id=$class_id order by state desc,sortnum asc";
			$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				
				<tr class="<?=$css?>">
					<td><?=$row["sortnum"]?></td>
					<td><a href="<?=$editUrl?>?id=<?=$row["id"]."&class_id=$class_id&page=$page"?>"><?=$row["title"]?></a></td>
					<?
					if(trim($row["pic"])=="")
					{
						echo "<td>无</td>";
					}
					else
					{
						echo "<td><a href=".UPLOAD_PATH_FOR_ADMIN.$row['pic'].">图片</a></td>";
					}
					?>
					<td>
					<?
					if(trim($row["state"])=="1")
					{
						echo "正常";
					}
					else if(trim($row["state"])=="2")
					{
						echo "推荐";
					}
					else
					{
						echo "未审核";
					}
					?>
					</td>
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
