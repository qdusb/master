<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");


$class_id	= trim($_GET["class_id"]);
$sup_class	= empty($_GET["sup_class"]) ? $class_id : trim($_GET["sup_class"]);
$id			= trim($_GET["id"]);
if (empty($class_id) || !checkClassID($class_id, 2))
{
	info("参数有误！");
}

if (strlen($sup_class) % CLASS_LENGTH != 0 && !checkClassID($sup_class, strlen($sup_class) / CLASS_LENGTH))
{
	info("参数有误！");
}

$sup_level = strlen($sup_class) / CLASS_LENGTH;

if (!empty($id) && !checkClassID($id, $sup_level + 1))
{
	info("参数有误！");
}


$listUrl = "third_class_list.php?class_id=$class_id&sup_class=$sup_class";
$backUrl = "second_class_list.php?class_id=" . substr($class_id, 0, CLASS_LENGTH);


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//查询顶级分类的记录设置等
$sql = "select max_level, sub_content, sub_pic, info_state, hasViews, hasState, hasPic, hasAnnex, hasIntro, hasContent, hasWebsite, hasAuthor, hasSource, hasKeyword from info_class where id='" . substr($class_id, 0, CLASS_LENGTH) . "'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$max_level		 = $row["max_level"];
	$sub_pic		 = $row["sub_pic"];
	$sub_content	 = $row["sub_content"];
	//$sup_info_state	 = $row["info_state"];
	$sup_info_state	 = $db->getTableFieldValue("info_class", "info_state", "where id='$sup_class'");
	$hasViews		 = $row["hasViews"];
	$hasState		 = $row["hasState"];
	$hasPic			 = $row["hasPic"];
	$hasAnnex		 = $row["hasAnnex"];
	$hasIntro		 = $row["hasIntro"];
	$hasContent		 = $row["hasContent"];
	$hasWebsite		 = $row["hasWebsite"];
	$hasAuthor		 = $row["hasAuthor"];
	$hasSource		 = $row["hasSource"];
	$hasKeyword		 = $row["hasKeyword"];
}
else
{
	$db->close();
	info("指定的二级分类不存在！");
}


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$en_name	= htmlspecialchars(trim($_POST["en_name"]));
	$state			= (int)$_POST["state"];
	$info_state		= trim($_POST["info_state"]);
	$has_sub		= (int)$_POST["has_sub"];

	if ($sub_pic == 1)
	{
		$pic_file	= &$_FILES["pic"];
		$pic		= uploadImg($pic_file, "gif,jpg,png");
		$del_pic	= (int)$_POST["del_pic"];
	}
	else
	{
		$pic	 = "";
		$del_pic = 0;
	}

	if ($sub_content == 1)
	{
		$content = $_POST["content"];
		$files	 = $_POST["content_files"];
	}
	else
	{
		$content = "";
		$files	 = "";
	}

	if ($name == "" || ($info_state != "content" && $info_state != "list" && $info_state != "pic" && $info_state != "pictxt" && $info_state != "custom"))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if (empty($id))
	{
		$id = $db->getMax("info_class", "id", "id like '" . $sup_class . CLASS_SPACE . "'");
		$id = empty($id) ? $sup_class . CLASS_DEFAULT : substr($id, 0, strlen($id) - CLASS_LENGTH) . (substr($id, strlen($id) - CLASS_LENGTH) + 1);

		//检查分类ID号是否存在
		if ($db->getCount("info_class", "id='$id'") > 0)
		{
			info("不能再增加分类！");
		}

		$sql = "insert into info_class(id, sortnum, name,en_name, pic, content, files, info_state, max_level, has_sub, sub_content, sub_pic, hasViews, hasState, hasPic, hasAnnex, hasIntro, hasContent, hasWebsite, hasAuthor, hasSource, hasKeyword, state) values('$id', $sortnum, '$name', '$en_name','$pic', '$content', '$files', '$info_state', 0, $has_sub, $sub_content, $sub_pic, $hasViews, $hasState, $hasPic, $hasAnnex, $hasIntro, $hasContent, $hasWebsite, $hasAuthor, $hasSource, $hasKeyword, $state)";
	}
	else
	{
		//若分类下有子类，则这个分类是否允许子类只能是允许
		if ($has_sub == 0 && $db->getCount("info_class", "id like '" . $id . CLASS_SPACE . "'") > 0)
		{
			$db->close();
			info("分类下有子类，是否有子类不可以拒绝！");
		}

		if (!empty($pic) || $del_pic == 1)
		{
			$oldPic	= $db->getTableFieldValue("info_class", "pic", "where id='$id'");
			$sql	= "update info_class set sortnum=$sortnum, name='$name',en_name='$en_name', pic='$pic', content='$content', files='$files', info_state='$info_state', max_level=0, has_sub=$has_sub, sub_content=$sub_content, sub_pic=$sub_pic, hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword, state=$state where id='$id'";
		}
		else
		{
			$sql = "update info_class set sortnum=$sortnum, name='$name',en_name='$en_name', content='$content', files='$files', info_state='$info_state', max_level=0, has_sub=$has_sub, sub_content=$sub_content, sub_pic=$sub_pic, hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword, state=$state where id='$id'";
		}
	}

	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		//修改时 删除老图片
		if (!empty($id) && (!empty($pic) || $del_pic == 1))
		{
			deleteFile($oldPic, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加失败了 删除上传的图片
		if (empty($id))
		{
			deleteFile($pic, 1);
			deleteFiles($files, 2);
		}

		info("添加/编辑分类失败！");
	}
}

if ($id == "")
{
	$sortnum 	= $db->getMax("info_class", "sortnum", "id like '" . $sup_class . CLASS_SPACE . "'") + 10;
	$info_state = "content";
	$state		= 1;
	$has_sub	= 1;
}
else
{
	$sql = "select * from info_class where id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$id				= $row["id"];
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$en_name		= $row["en_name"];
		if ($sub_pic == 1)
		{
			$pic = $row["pic"];
		}
		else
		{
			$pic = "";
		}

		if ($sub_content == 1)
		{
			$content = $row["content"];
			$files	 = $row["files"];
		}
		else
		{
			$content = "";
			$files	 = "";
		}

		$info_state		= $row["info_state"];
		$has_sub		= $row["has_sub"];
		$state			= $row["state"];
	}
	else
	{
		$db->close();
		info("指定的记录不存在！");
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
		<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
		<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				var editor = K.create('textarea[name="content"]', {
					uploadJson : 'kindeditor/php/upload_json.php',
					fileManagerJson : 'kindeditor/php/file_manager_json.php',
					width : '700px',
					height : '300px',
					pasteType : 1,
					allowFileManager : true,
					afterCreate : function() {
						var self = this;
						K.ctrl(document, 13, function() {
							self.sync();
							K('form[name=form1]')[0].submit();
						});
						K.ctrl(self.edit.doc, 13, function() {
							self.sync();
							K('form[name=form1]')[0].submit();
						});
					}
				});
			});
		</script>
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

				<?
				if ($sub_pic == 1)
				{
				?>
					if (form.pic.value != "")
					{
						var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

						if (ext != "gif" && ext != "jpg" && ext != "png")
						{
							alert('图片必须是GIF、JPG、PNG格式！');
							return false;
						}
					}
				<?
				}
				?>

				return true;
			}
		</script>
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
					<a href="<?=$backUrl?>">[返回]</a>
					<a href="<?=$listUrl?>">[返回列表]</a>
				</td>
			</tr>
		</table>
		<form name="form1" action="" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
			<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
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
				 <tr class="editTr">
					<td class="editLeftTd">分类英文名称</td>
					<td class="editRightTd"><input type="text" name="en_name" value="<?=$en_name?>" maxlength="50" size="30"></td>
				</tr>
				<?
				if ($session_admin_grade == ADMIN_HIDDEN)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">是否允许删除</td>
						<td class="editRightTd">
							<input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>>允许
							<input type="radio" name="state" value="0"<? if ($state == 0) echo " checked"?>>拒绝
						</td>
					</tr>
				<?
				}
				else
				{
				?>
					<input type="hidden" name="state" value="<?=$state?>">
				<?
				}
				?>

				<tr class="editTr">
					<td class="editLeftTd">记录状态</td>
					<td class="editRightTd">
						<input type="radio" name="info_state" value="content"<? if ($info_state == "content") echo " checked"?>>图文模式
						<input type="radio" name="info_state" value="list"<? if ($info_state == "list") echo " checked"?>>新闻列表
						<input type="radio" name="info_state" value="pic"<? if ($info_state == "pic") echo " checked"?>>图片列表
						<input type="radio" name="info_state" value="pictxt"<? if ($info_state == "pictxt") echo " checked"?>>图文列表
						<input type="radio" name="info_state" value="custom"<? if ($info_state == "custom") echo " checked"?>>自定义
					</td>
				</tr>

				<?
				if ($max_level > $sup_level + 1)
				{
				?>
					<input type="hidden" name="has_sub" value="1">
				<?
				}
				else
				{
				?>
					<input type="hidden" name="has_sub" value="0">
				<?
				}

				if ($sub_pic == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">图片</td>
						<td class="editRightTd">
							<input type="file" name="pic" size="40">
							<?
							if ($pic != "")
							{
							?>
								<input type="checkbox" name="del_pic" value="1"> 删除现有图片
							<?
							}
							?>
						</td>
					</tr>
				<?
				}

				if ($sub_content == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">内容</td>
						<td class="editRightTd"><textarea name="content"><?=$content;?></textarea>
					</tr>
				<?
				}
				?>
				<tr class="editTr">
					<td class="editLeftTd">注意事项</td>
					<td class="editRightTd">
						1、编辑时修改ID号无效。<br>
						2、修改记录状态的值后，需要重新编辑此分类的下级分类。<br>
					</td>
				</tr>
				<tr class="editFooterTr">
					<td class="editFooterTd" colSpan="2">
						<input type="submit" value=" 确 定 ">
						<input type="reset" value=" 重 填 ">
					</td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">document.form1.name.focus();</script>
		<?
		$db->close();
		?>
	</body>
</html>
