<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, ADVER_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id = (int)$_GET["id"];


$listUrl = "adver_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$title		= htmlspecialchars(trim($_POST["title"]));
	$mode		= trim($_POST["mode"]);
	$url		= htmlspecialchars(trim($_POST["url"]));
	$width		= (int)$_POST["width"];
	$height		= (int)$_POST["height"];
	$time		= (int)$_POST["time"];  
	$pic_file	= &$_FILES["pic"];
	$state		= (int)$_POST["state"];

	if (empty($title) || ($mode != "popup" && $mode != "float" && $mode != "hangL" && $mode != "hangR" && $mode != "hangLR" && $mode != "bigScreen"))
	{
		$db->close();
		info("填写的参数不完整！");
	}
	
	//上传图片 uploadImg(文件对象, 允许的文件格式,以逗号隔开)
	$pic = uploadImg($pic_file, "gif,jpg,png,swf");

	if ($id < 1)
	{
		$sql = "insert into adver(id, title, url, pic, width, height, time, mode, state) values(" . ($db->getMax("adver", "id", "") + 1) . ", '$title', '$url', '$pic', $width, $height, $time, '$mode', $state)";
	}
	else
	{
		if ((int)$_POST["del_pic"] == 1 || !empty($pic))
		{
			$oldPic	= $db->getTableFieldValue("adver", "pic", "where id=$id");
			$sql	= "update adver set title='$title', url='$url', pic='$pic', width=$width, height=$height, time=$time, mode='$mode', state=$state where id=$id";
		}
		else
		{
			$sql = "update adver set title='$title', url='$url', width=$width, height=$height, time=$time, mode='$mode', state=$state where id=$id";
		}
	}
	
	$rst = $db->query($sql);
	$db->close();
	
	if($rst)
	{
		//删除老图片
		deleteFile($oldPic, 1);
		header("Location: $listUrl");
		exit();
	}
	else
	{
		info("添加/编辑广告失败！");
	}
}

if ($id < 1)
{
	$state	= 1;
	$mode	= "popup";
	$width	= 100;
	$height	= 100;
}
else
{
	$sql = "select title, mode, url, width, height, time, pic, state from adver where id=$id";
	$rst = $db->query($sql);
	if($row = $db->fetch_array($rst))
	{
		$title	= $row["title"];
		$mode	= $row["mode"];
		$url	= $row["url"];
		$width	= $row["width"];
		$height	= $row["height"];
		$time	= $row["time"];
		$pic	= $row["pic"];
		$state	= $row["state"];
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
		<script type="text/javascript">
		function check(form)
		{
			if(form.title.value == "")
			{
				alert("请填入标题名称!");
				form.title.focus();
				return false;
			}

			if (form.pic.value != "")
			{
				var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

				if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
				{
					alert("广告文件必须是GIF、JPG、PNG或SWF格式！");
					return false;
				}
			}

			if(form.width.value == "")
			{
				alert("请填入宽度!");
				form.width.focus();
				return false;
			}

			if(form.height.value == "")
			{
				alert("请填入高度!");
				form.height.focus();
				return false;
			}

			return true;
		}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt;  高级管理 -&gt; 广告管理</td>
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
					<td class="editLeftTd">是否显示</td>
					<td class="editRightTd">
						<input type="radio" name="state" value="0"<? if ($state == 0) echo " checked";?>>不显示
						<input type="radio" name="state" value="1"<? if ($state == 1) echo " checked";?>>显示
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">广告类型</td>
					<td class="editRightTd">
						<input type="radio" name="mode" value="popup"<? if ($mode == "popup") echo " checked";?>>弹出广告
						<input type="radio" name="mode" value="float"<? if ($mode == "float") echo " checked";?>>漂浮广告
						<input type="radio" name="mode" value="hangL"<? if ($mode == "hangL") echo " checked";?>>左侧门帘
						<input type="radio" name="mode" value="hangR"<? if ($mode == "hangR") echo " checked";?>>右侧门帘
						<input type="radio" name="mode" value="hangLR"<? if ($mode == "hangLR") echo " checked";?>>左右门帘
						<input type="radio" name="mode" value="bigScreen"<? if ($mode == "bigScreen") echo " checked";?>>拉屏广告
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">广告文件</td>
					<td class="editRightTd">
						<input type="file" name="pic" size="40">
						<?
						if (!empty($pic))
						{
						?>
							<input type="checkbox" name="del_pic" value="1"> 删除现有图片
						<?
						}
						?>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">标题名称</td>
					<td class="editRightTd"><input type="text" value="<?=$title?>" name="title" maxlength="50" size="50"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">链接网址</td>
					<td class="editRightTd"><input type="text" value="<?=$url?>" name="url" maxlength="100" size="70"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">宽度</td>
					<td class="editRightTd"><input type="text" value="<?=$width?>" name="width" maxlength="5" size="10"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">高度</td>
					<td class="editRightTd"><input type="text" value="<?=$height?>" name="height" maxlength="5" size="10"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">拉屏时间</td>
					<td class="editRightTd"><input type="text" value="<?=$time?>" name="time" maxlength="3" size="6"> 秒</td>
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
