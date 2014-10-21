<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$class_id	= trim($_GET["class_id"]);
$id			= trim(@$_GET["id"]);
if (empty($class_id) || !checkClassID($class_id, 1))
{
	info("参数有误！");
}

if (!empty($id) && !checkClassID($id, 2))
{
	info("参数有误！");
}


$listUrl = "second_class_list.php?class_id=$class_id";
$editUrl = "second_class_edit.php?class_id=$class_id";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


$sql = "select name, max_level, sub_pic from info_class where id='$class_id'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$sup_class_name	 = $row["name"];
	$max_level		 = $row["max_level"];
	$sub_pic		 = $row["sub_pic"];
}
else
{
	$db->close();
	info("指定的一级分类不存在！");
}


//删除
if ($id != "")
{
	//是否允许删除
	if ($db->getTableFieldValue("info_class", "state", "where id='$id'") != 1 && $session_admin_grade != ADMIN_HIDDEN)
	{
		$db->close();
		info("分类不允许删除！");
	}

	//是否有子类
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

	$sql = "select pic, files from info_class where id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$pic	= $row["pic"];
		$files	= $row["files"];
	}
	else
	{
		$db->close();
		info("指定的分类不存在！");
	}

	$sql = "delete from info_class where id='$id'";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		//删除图片、附件
		deleteFile($pic, 1);
		deleteFiles($files, 2);

		header("Location: $listUrl");
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
				<td class="position">当前位置: 管理中心 -&gt; <?=$sup_class_name?> -&gt; 二级分类管理</td>
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
				<td>序号</td>
				<td>分类名称</td>
                <?
                if ($sub_pic == 1)
				{
				?>
                    <td>图片</td>
                <?
                }
				?>
                <td>记录状态</td>
                <?
                if ($max_level > 2)
				{
				?>
                	<td>子类管理</td>
                <?
                }
				?>
				<td>删除</td>
			</tr>
			<?
			$sql = "select id, sortnum, name, info_state, pic, has_sub, state from info_class where id like '" . $class_id . CLASS_SPACE . "' order by sortnum asc";
			$rst = $db->query($sql);
			while ($row = $db->fetch_array($rst))
			{
				@$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				<tr class="<?=$css?>">
					<td><?=$row["sortnum"]?></td>
					<td><a href="<?=$editUrl?>&id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
					<?
                    if ($sub_pic == 1)
                    {
                    ?>
                    	<td>
                        	<?
							if (!empty($row["pic"]))
							{
							?>
								<a href="<?=UPLOAD_PATH_FOR_ADMIN . $row["pic"]?>" target="_blank">图片</a>
							<?
							}
							else
							{
								echo "无";
							}
							?>
                        </td>
                    <?
                    }
					?>
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
                    if ($max_level > 2)
                    {
                    ?>
                        <td>
						<?
                        if ($row["has_sub"] == 1)
                        {
                        ?>
                            <a href="third_class_list.php?class_id=<?=$row["id"]?>" title="管理 <?=$row["name"]?> 子类">管理</a>
                        <?
                        }
                        ?>
                        </td>
                    <?
                    }
					?>
					<td>
                    	<?
                        if ($row["state"] == 1 || $session_admin_grade == ADMIN_HIDDEN)
						{
							if ($row["state"] == 1)
							{
						?>
                    			<a href="<?=$listUrl?>&id=<?=$row["id"]?>" onClick="return del();">删除</a>
                        <?
							}
							else
							{
						?>
                        		<a href="<?=$listUrl?>&id=<?=$row["id"]?>" onClick="return del();"><font color="#FF0000">删除</font></a>
						<?
							}
                        }
						?>
                    </td>
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
