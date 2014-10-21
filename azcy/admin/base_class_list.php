<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id	= trim($_GET["id"]);


$listUrl = "base_class_list.php";
$editUrl = "base_class_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if (!empty($id))
{
	//是否有分类
	if ($db->getCount("info_class", "id like '" . $id . CLASS_SPACE . "'") > 0)
	{
		$db->close();
		info("分类下有子类，请先删除子类！");
	}
	
	//是否有信息
	if ($db->getCount("info", "class_id='$id'") > 0)
	{
		$db->close();
		info("分类下有信息，请先删除信息！");
	}

	$sql = "delete from info_class where id='$id'";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		//删除后刷新menu.php 显示最新分类信息
		
		echo "<script type='text/javascript'>window.location='" . $listUrl . "';</script>";
		exit;
	}
	else
	{
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
				<td class="position">当前位置: 管理中心 -&gt; 隐藏管理 -&gt; 一级分类管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
					<a href="<?=$editUrl?>">[增加]</a>&nbsp;
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<tr class="listHeaderTr">
				<td width="10%">ID号</td>
                <td width="10%">序号</td>
				<td>分类名称</td>
                <td>图片</td>
                <td width="12%">记录状态</td>
                <td width="12%">最大层次</td>
                <td width="12%">二级分类</td>
				<td width="8%">删除</td>
			</tr>
			<?
			$sql = "select id, sortnum, name, info_state, max_level,pic, state from info_class where id like '" . CLASS_SPACE . "' order by sortnum asc";
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				<tr class="<?=$css?>">
					<td><?=$row["id"]?></td>
                    <td><?=$row["sortnum"]?></td>
					<td><a href="<?=$editUrl?>?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
                    <td>
                    	<?
                        switch ($row["info_state"])
						{
							case "content":
								echo "图文模式";
								break;
							case "list":
								echo "新闻列表";
								break;
							case "pic":
								echo "图片列表";
								break;
							case "pictxt":
								echo "图文列表";
								break;
							case "custom":
								echo "<font color=#FF6600>自定义</font>";
								break;
							default :
								echo "<font color=#FF0000>错误</font>";
								break;
						}
						?>
                    </td>
                    <?
					if(empty($row['pic']))
					{
					?>
                     <td><a href="">无图片</a></td>
                    <?
					}
					else
					{
                    ?>
                    <td><a href="<?=UPLOAD_PATH_FOR_ADMIN.$row['pic']?>">图片</a></td>
                   	<?
					}
				   	?>
                    <td><?=$row["max_level"]?></td>
                    <td><?=($row["state"] == 1) ? "允许": "<font color='#FF6600'>拒绝</font>"?></td>
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
