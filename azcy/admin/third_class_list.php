	<?
	require(dirname(__FILE__) . "/init.php");
	require(dirname(__FILE__) . "/isadmin.php");
	require(dirname(__FILE__) . "/config.php");
	
	
	$class_id	= trim($_GET["class_id"]);
	$sup_class	= ($_GET["sup_class"] == "") ? $class_id : trim($_GET["sup_class"]);
	$id			= trim($_GET["id"]);
	if (empty($class_id) || !checkClassID($class_id, 2))
	{
		info("指定了错误的二级分类ID号！");
	}
	
	if (strlen($sup_class) % CLASS_LENGTH != 0 && !checkClassID($sup_class, strlen($sup_class) / CLASS_LENGTH))
	{
		info("选择了错误的分类！");
	}
	
	$sup_level = strlen($sup_class) / CLASS_LENGTH;
	
	if ($id != "" && !checkClassID($id, $sup_level + 1))
	{
		info("指定了错误的分类ID号！");
	}
	
	
	$listUrl = "third_class_list.php?class_id=$class_id&sup_class=$sup_class";
	$editUrl = "third_class_edit.php?class_id=$class_id&sup_class=$sup_class";
	$baseUrl = "third_class_list.php?class_id=$class_id";
	$backUrl = "second_class_list.php?class_id=" . substr($class_id, 0, CLASS_LENGTH);
	
	
	//连接数据库
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
	
	
	//查询顶级分类的信息等
	$sql = "select name, max_level, sub_pic from info_class where id='" . substr($class_id, 0, CLASS_LENGTH) . "'";
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
		info("指定的二级分类不存在！");
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
	
		//是否有内容
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
					<td class="position">当前位置: 管理中心 -&gt; <?=$db->getTableFieldValue("info_class", "name", "where id='$class_id'")?> -&gt; 子类管理</td>
				</tr>
			</table>
			<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="30">
					<td>
						<a href="<?=$backUrl?>">[返回]</a>&nbsp;
						<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
						<a href="<?=$editUrl?>">[增加]</a>&nbsp;
						<select name="sup_class" style="width:160px;" onChange="window.location='<?=$baseUrl?>&sup_class=' + this.options[this.selectedIndex].value;">
							<?
							$sql = "select id, name from info_class where id like '" . $class_id . "%' and has_sub=1 order by sortnum asc";
							$rst = $db->query($sql);
							while ($row = $db->fetch_array($rst))
							{
								$data[] = array("id" => $row["id"], "name" => $row["name"]);
							}
							print_r($data);
							$data = getNodeData($data, substr($class_id, 0, strlen($class_id) - CLASS_LENGTH), CLASS_LENGTH);
							echo optionsTree($data, $sup_class);
							?>
						</select>
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
					<td>删除</td>
				</tr>
				<?
				$sql = "select id, sortnum, name, info_state, pic, has_sub, state  from info_class where id like '" . $sup_class . CLASS_SPACE . "' order by sortnum asc";
				$rst = $db->query($sql);
				while ($row = $db->fetch_array($rst))
				{
					$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
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
