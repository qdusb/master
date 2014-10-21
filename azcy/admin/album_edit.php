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

$id						= (int)$_GET["id"];
$class_id					= (int)$_GET["class_id"];
$page					= (int)$_GET["page"];
//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
if(!empty($id))
{
	$sql = "select * from album where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$pic			= $row["pic"];
		$sortnum		= $row["sortnum"];
	}
}
else
{
	$sortnum = $db->getMax("album", "sortnum", "class_id='$class_id'") + 10;
}

$listUrl = "album_list.php?page=$page&class_id=$class_id";
$editUrl = "album_edit.php?id=$id";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		=$_POST["sortnum"];
	$pic_file		= &$_FILES["pic"];
	$del_pic		= (int)$_POST["del_pic"];
	if (!empty($pic_file))
	{
		$pic		= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv");			//上传图片
	}
	else
	{
		$pic = "";
	}
	if($id<1)
	{
		$name=$pic_file['name'];
		$aid = $db->getMax("album", "sortnum","") + 1;
		$pic=UPLOAD_PATH_FOR_ADMIN.$pic;
		$now=date("Y-m-d H:i:s");
		$sql = "insert into album(id, class_id, name,sortnum, pic,create_time) values('$aid','$class_id','$name', $sortnum,'$pic','$now')";
	}
	else
	{
		if(!empty($pic))
		{
			$sql = "update album set pic='".UPLOAD_PATH_FOR_ADMIN.$pic."',sortnum=$sortnum where id=$id";
		}
		else
		{
			$sql = "update album set sortnum=$sortnum where id=$id";
		}
	}
	
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		if(!empty($pic))
		{
			deleteFile($oldpic, 1);
		}
		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片、附件
		deleteFile($pic, 1);
	}
	if($del_pic==1)
	{
		deleteFile($oldpic, 1);
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
		<script type="text/javascript">
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

					if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
					{
						alert("图片必须是GIF、JPG或PNG格式！");
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

		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
			<form name="form1" action="" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">修改资料</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd">
					<input type="text" value="<?=$sortnum?>" name="sortnum">
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
							<input type="checkbox" name="del_pic" value="1"> 删除现有文件
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
			</form>
		</table>
		<script type="text/javascript">document.form1.title.focus();</script>
		<?
		$db->close();
		?>
	</body>
</html>
