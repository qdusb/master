<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$class_id		= trim($_GET["class_id"]);
$select_class	= empty($_GET["select_class"]) ? $class_id : trim($_GET["select_class"]);
$select_state	= (int)$_GET["select_state"];
$keyword		= urlencode(trim($_GET["keyword"]));
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;


if (empty($class_id) || !checkClassID($class_id, 2))
{
	info("参数有误！");
}


if (strlen($select_class) % CLASS_LENGTH != 0 && !checkClassID($select_class, strlen($select_class) / CLASS_LENGTH))
{
	info("参数有误！");
}

//权限检查
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && hasInclude($session_admin_popedom, substr($class_id, 0, CLASS_LENGTH)) != true && hasInclude($session_admin_popedom, $class_id) != true)
{
	info("没有权限！");
}

$listUrl	= "info_list.php?class_id=$class_id&select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$editUrl	= "info_edit.php?class_id=$class_id&select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$baseUrl	= "info_list.php?class_id=$class_id";
$csvUrl		= "product_csv.php?select_class=$select_class&select_state=$select_state&keyword=$keyword";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
$sql = "select info_state from info_class where id='" .$class_id. "'";
$rst = $db->query($sql);
$row = $db->fetch_array($rst);

//查询顶级分类的记录设置
$sql = "select hasViews, hasState, hasPic, hasContent, hasAnnex, hasWebsite, hasAuthor, hasSource,info_state from info_class where id='" . substr($class_id, 0, CLASS_LENGTH) . "'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$hasViews	= $row["hasViews"];
	$hasState	= $row["hasState"];
	$hasPic		= $row["hasPic"];
	$hasContent	= $row["hasContent"];
	$hasAnnex	= $row["hasAnnex"];
	$hasWebsite	= $row["hasWebsite"];
	$hasAuthor	= $row["hasAuthor"];
	$hasSource	= $row["hasSource"];
	$info_state	= $row["info_state"];
}
else
{
	$db->close();
	info("参数有误！");
}

//批量操作
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array	= $_POST["ids"];
	$action		= trim($_POST["action"]);
	if (empty($action))
	{
		$db->close();
		info("参数有误！");
	}

	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}

	//事务开始
	$db->query("begin");

	//删除记录
	if ($action == "delete")
	{
		//权限检查
		if ($session_admin_grade == ADMIN_COMMON)
		{
			$sql = "select pic, annex, files from info where id in (" . implode(",", $id_array) . ") and state=0 and admin_id=$session_admin_id";
		}
		else
		{
			$sql = "select pic, annex, files from info where id in (" . implode(",", $id_array) . ")";
		}

		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	.= $row["pic"] . ",";
			$annex	.= $row["annex"] . ",";
			$files	.= $row["files"] . ",";
		}

		//权限检查
		if ($session_admin_grade == ADMIN_COMMON)
		{
			$sql = "delete from info where id in (" . implode(",", $id_array) . ") and state=0 and admin_id=$session_admin_id";
		}
		else
		{
			$sql = "delete from info where id in (" . implode(",", $id_array) . ")";
		}

		if (!$db->query($sql))
		{
			$db->query("rollback");
			$db->close();
			info("删除信息失败！");
		}
	}
	//设置状态
	elseif ($action == "state")
	{
		$state = (int)$_POST["state"];
		$sql = "update info set state=$state where id in (" . implode(",", $id_array) . ")";
		if (!$db->query($sql))
		{
			$db->query("rollback");
			$db->close();
			info("设置状态失败！");
		}
	}

	$db->query("commit");
	$db->close();
	if ($action == "delete")
	{
		//删除图片
		deleteFiles($pic, 1);
		deleteFiles($annex, 1);
		deleteFiles($files, 2);
	}
	header("Location: $listUrl");
	exit();
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
				<td class="position">当前位置: 管理中心 -&gt; <?=$db->getTableFieldValue("info_class", "name", "where id='$class_id'")?> -&gt; 列表</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>
					<a href="<?=$editUrl?>">[增加]</a>
					<a href="javascript:reverseCheck(document.form1.ids);">[反向选择]</a>&nbsp;
					<a href="javascript:if(delCheck(document.form1.ids)) {document.form1.action.value = 'delete';document.form1.submit();}">[删除]</a>&nbsp;
					<?
					if ($db->getTableFieldValue("info_class", "has_sub", "where id='$class_id'") == 1)
					{
						$sql = "select id, name from info_class where id like '" . $class_id . "%' order by sortnum asc";
						$rst = $db->query($sql);
						while ($row = $db->fetch_array($rst))
						{
							$data[] = array("id" => $row["id"], "name" => $row["name"]);
						}
						$data = getNodeData($data, substr($class_id, 0, strlen($class_id) - CLASS_LENGTH), CLASS_LENGTH);
					?>
						<select name="select_class" style="width:250px;" onChange="window.location='<?=$baseUrl?>&select_class=' + this.options[this.selectedIndex].value;">
							<?=optionsTree($data, $select_class)?>
						</select>
					<?
					}
					?>
					<select name="select_state" style="width:90px;" onChange="window.location='<?=$baseUrl?>&select_class=<?=$select_class?>&select_state=' + this.options[this.selectedIndex].value;">
						<option value="">请选择</option>
						<option value="1"<? if ($select_state == 1) echo " selected"?>>未审核</option>
						<option value="2"<? if ($select_state == 2) echo " selected"?>>正常</option>
						<option value="3"<? if ($select_state == 3) echo " selected"?>>推荐</option>
					</select>
					<select name="state" id="state" onChange="if(stateCheck(document.form1.ids)) {document.form1.action.value = 'state';document.form1.state.value='' + this.options[this.selectedIndex].value + '';document.form1.submit();}">
						<option value="-1">设置状态为</option>
						<option value="0">未审核</option>
						<option value="1">正常</option>
						<option value="2">推荐</option>
					</select>
				</td>
				<td align="right">
					<form name="searchForm" method="get" action="" style="margin:0px;">
						查询：<input name="keyword" type="text" value="<?=urldecode($keyword)?>" size="30" maxlength="50" />
						<input type="submit" value="查询" style="width:60px;">
						<input type="hidden" name="class_id" value="<?=$class_id?>" />
						<input type="hidden" name="select_class" value="<?=$select_class?>" />
						<input type="hidden" name="select_state" value="<?=$select_state?>" />
					</form>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<form name="form1" action="" method="post">
				<input type="hidden" name="action" value="">
				<input type="hidden" name="state" value="">
				<tr class="listHeaderTr">
					<td width="30"></td>
					<td width="60">序号</td>
					<td>标题</td>
					<?
					if ($hasPic == 1)
					{
					?>
						<td width="60">缩略图</td>
					<?
					}

					if ($hasAnnex == 1)
					{
					?>
						<td width="60">附件</td>
					<?
					}

					if ($hasViews == 1)
					{
					?>
						<td width="60">浏览量</td>
					<?
					}

					if ($hasState == 1)
					{
					?>
						<td width="60">状态</td>
					<?
					}
					?>
					<td width="120">发表时间</td>
				</tr>
				<?
				//筛选条件、权限
				if ($session_admin_grade == ADMIN_COMMON)
				{
					$SQL_ = "and a.state=0 and a.admin_id=$session_admin_id and title like '%" . urldecode($keyword) . "%'";
				}
				else
				{
					switch ($select_state)
					{
						case 1:
							$SQL_ = "and a.state=0";
							break;
						case 2:
							$SQL_ = "and a.state=1";
							break;
						case 3:
							$SQL_ = "and a.state=2";
							break;
						default:
							$SQL_ = "";
							break;
					}
				}

				//设置每页数
				$page_size		= DEFAULT_PAGE_SIZE;
				//总记录数
				$sql			= "select count(*) as cnt from info a where a.class_id like '" . $select_class . "%' and a.title like '%" . urldecode($keyword) . "%' $SQL_";
				$rst			= $db->query($sql);
				$row			= $db->fetch_array($rst);
				$record_count	= $row["cnt"];
				$page_count		= ceil($record_count / $page_size);
				//分页
				$page_str		= page($page, $page_count);
				//列表
				$sql = "select a.id, a.sortnum, a.title, a.author, a.source, a.website, a.pic, a.annex, a.views, a.files, a.create_time, a.state, b.name from info a left join info_class b on a.class_id=b.id where a.class_id like '" . $select_class . "%' and a.title like '%" . urldecode($keyword) . "%' $SQL_ order by a.state desc,a.sortnum desc, a.create_time desc";
				$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
				$rst = $db->query($sql);
				while ($row = $db->fetch_array($rst))
				{
					$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
				?>
					<tr class="<?=$css?>">
						<td><input type="checkbox" id="ids" name="ids[]" value="<?=$row["id"]?>"></td>
						<td><?=$row["sortnum"]?></td>
						<td><a href="<?=$editUrl?>&id=<?=$row["id"]?>"><?=$row["title"]?></a></td>
						<?
						if ($hasPic == 1)
						{
						?>
							<td><?=(empty($row["pic"])) ? "无" : "<a href='" . UPLOAD_PATH_FOR_ADMIN . $row["pic"] . "' target='_blank'>图片</a>"?></td>
						<?
						}

						if ($hasAnnex == 1)
						{
						?>
							<td><?=$row["annex"] == "" ? "无" : "<a href='" . UPLOAD_PATH_FOR_ADMIN . $row["annex"] . "' target='_blank'>附件</a>"?></td>
						<?
						}

						if ($hasViews == 1)
						{
						?>
							<td><?=$row["views"]?></td>
						<?
						}

						if ($hasState == 1)
						{
						?>
							<td>
								<?
								switch ($row["state"])
								{
									case 0:
										echo "<font color=#FF9900>未审核</font>";
										break;
									case 1:
										echo "正常";
										break;
									case 2:
										echo "<font color=#FF3300>推荐</font>";
										break;
									default :
										echo "<font color=#FF0000>错误</font>";
										exit;
								}
								?>
							</td>
						<?
						}
						?>
						<td><?=formatDate("Y-m-d", $row["create_time"])?></td>
					</tr>
				<?
				}
				?>
				<tr class="listFooterTr">
					<td colspan="15"><?=$page_str?></td>
				</tr>
			</form>
		</table>
		<?
		$db->close();
		?>
	</body>
</html>
