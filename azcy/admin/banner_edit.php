<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, BANNER_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id				= (int)$_GET["id"];
$class_id		= trim($_GET["class_id"]);

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if (!empty($class_id))
{
	$sql = "select id, name, add_deny, delete_deny from banner_class where id=$class_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$class_name		= $row["name"];
		$add_deny		= $row["add_deny"];
		$delete_deny	= $row["delete_deny"];
	}
	if($add_deny == 1)
	{
		info("此分类下不允许增加信息！");
	}
}
else
{
	info("指定了错误的分类！");
}

$listUrl = "banner_list.php?class_id=$class_id";
$editUrl = "banner_edit.php?class_id=$class_id&id=$id";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$title			= htmlspecialchars(trim($_POST["title"]));
	$url 			= htmlspecialchars(trim($_POST["url"]));
	$width			= (int)$_POST["width"];
	$height			= (int)$_POST["height"];
	$content		=$_POST["content"];
	$pic_file		= &$_FILES["pic"];
	$pic			= uploadImg($pic_file, "gif,jpg,png,swf,flv,wmv");			//上传图片
	$del_pic		= (int)$_POST["del_pic"];
	
	$pic_file2		= &$_FILES["pic2"];
	$pic2			= uploadImg($pic_file2, "gif,jpg,png,swf,flv,wmv");			//上传图片
	$del_pic2		= (int)$_POST["del_pic2"];

	$state			= (int)$_POST["state"];

	if (empty($title))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($id < 1)
	{
		$sortnum = $db->getMax("banner", "sortnum", "class_id='$class_id'") + 10;
		$sql = "insert into banner (id, class_id, sortnum, title,content, url, width, height, pic,pic2, state) values(" . ($db->getMax("banner", "id", "") + 1) . ", $class_id, $sortnum, '$title','$content', '$url', $width, $height, '$pic','$pic2', $state)";
	}
	else
	{
		if (((!empty($pic2) || $del_pic2 == 1))&&((!empty($pic) || $del_pic == 1)))
		{
			$oldPic2		= $db->getTableFieldValue("banner", "pic2", "where id=$id");
			$oldPic		= $db->getTableFieldValue("banner", "pic", "where id=$id");
			
			$sql = "update banner set sortnum=$sortnum, title='$title',content='$content', url='$url', width=$width, height=$height, pic2='$pic2',pic='$pic', state=$state where id=$id";
		}
		else if ((!empty($pic) || $del_pic == 1))
		{
			$oldPic		= $db->getTableFieldValue("banner", "pic", "where id=$id");
			$sql = "update banner set sortnum=$sortnum, title='$title',content='$content', url='$url', width=$width, height=$height, pic='$pic', state=$state where id=$id";
		}
		else if ((!empty($pic2) || $del_pic2 == 1))
		{
			$oldPic2		= $db->getTableFieldValue("banner", "pic2", "where id=$id");
			$sql = "update banner set sortnum=$sortnum, title='$title',content='$content', url='$url', width=$width, height=$height, pic2='$pic2', state=$state where id=$id";
		}
		else
		{
			$sql = "update banner set sortnum=$sortnum, title='$title',content='$content', url='$url', width=$width, height=$height, state=$state where id=$id";
		}
	}

	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		//修改成功后删除老图片、附件
		if ($id > 0)
		{
			deleteFile($oldPic, 1);
			deleteFile($oldPic2, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片、附件
		deleteFile($pic, 1);
		deleteFile($pic2, 1);
	}
}


if ($id < 1)
{
	$sortnum		= $db->getMax("banner", "sortnum", "class_id=$class_id") + 10;
	$state			= 1;
}
else
{
	$sql = "select sortnum, title, url, width, height, pic,pic2,content,state from banner where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$title			= $row["title"];
		$url			= $row["url"];
		$width			= $row["width"];
		$height			= $row["height"];
		$pic			= $row["pic"];
		$pic2			= $row["pic2"];
		$state			= $row["state"];
		$content		= $row["content"];
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
		<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
		<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
		
		<script type="text/javascript">
		KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="content"]', {
					width : '700px',
					height : '200px',
					pasteType : 1,
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
		})
		</script>
		<script>
			function check(form)
			{
				if (form.sortnum.value.match(/\D/))
				{
					alert("请输入合法的序号！");
					form.sortnum.focus();
					return false;
				}

				if(form.name.value == "")
				{
					alert("请填入标题名称!");
					form.name.focus();
					return false;
				}
				
				if (form.pic.value != "")
				{
					var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

					if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv"&& ext != "flv")
					{
						alert("图片格式不符合要求！");
						return false;
					}
				}

				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; <?echo $class_name?> -&gt; 新增/编辑</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>
				</td>
			</tr>
		</table>
	<form name="form1" action="" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
			
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">修改资料</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">排列序号</td>
					<td class="editRightTd">
						<input type="text" name="sortnum" value="<?=$sortnum?>" maxlength="10" size="5">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">名称</td>
					<td class="editRightTd"><input type="text" value="<?=$title?>" name="title" maxlength="100" size="50"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">详细内容</td>
					<td class="editRightTd"><textarea name="content" style="width:700px;height:300px"><?php echo $content; ?></textarea></td>
				</tr>
				
				<tr class="editTr">
					<td class="editLeftTd">链接网址</td>
					<td class="editRightTd"><input type="text" value="<?=$url?>" name="url" maxlength="300" size="50"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">宽度</td>
					<td class="editRightTd"><input type="text" value="<?=$width?>" name="width" maxlength="50" size="10"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">高度</td>
					<td class="editRightTd"><input type="text" value="<?=$height?>" name="height" maxlength="50" size="10"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">状态</td>
					<td class="editRightTd">
						<input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>>显示
						<input type="radio" name="state" value="0"<? if ($state == 0) echo " checked"?>>不显示
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">缩略图</td>
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
				<tr class="editTr">
					<td class="editLeftTd">小图</td>
					<td class="editRightTd">
						<input type="file" name="pic2" size="40">
						<?
						if ($pic2 != "")
						{
						?>
							<input type="checkbox" name="del_pic2" value="1"> 删除现有图片
						<?
						}
						?>
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
		<script type="text/javascript">document.form1.title.focus();</script>
		<?
		$db->close();
		?>
	</body>
</html>
