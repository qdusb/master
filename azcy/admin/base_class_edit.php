<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

$id = trim($_GET["id"]);
if (!empty($id) && !checkClassID($id, 1))
{
	info("指定了错误的分类ID号！");
}


$listUrl = "base_class_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id2			= htmlspecialchars(trim($_POST["id"]));
	$sortnum		= (int)$_POST["sortnum"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$en_name		= htmlspecialchars(trim($_POST["en_name"]));
	$state			= (int)$_POST["state"];
	$sub_content	= (int)$_POST["sub_content"];
	$sub_pic		= (int)$_POST["sub_pic"];
	$max_level		= (int)$_POST["max_level"];
	$info_state		= trim($_POST["info_state"]);
	$hasViews		= (int)$_POST["hasViews"];
	$hasState		= (int)$_POST["hasState"];
	$hasPic			= (int)$_POST["hasPic"];
	$hasAnnex		= (int)$_POST["hasAnnex"];
	$hasIntro		= (int)$_POST["hasIntro"];
	$hasContent		= (int)$_POST["hasContent"];
	$hasWebsite		= (int)$_POST["hasWebsite"];
	$hasAuthor		= (int)$_POST["hasAuthor"];
	$hasSource		= (int)$_POST["hasSource"];
	$hasKeyword		= (int)$_POST["hasKeyword"];
	$hasLevel		= (int)$_POST["hasLevel"];
	$hasShare		= (int)$_POST["hasShare"];
	$content 		= $_POST["content"];

	$pic_file	= &$_FILES["pic"];
	$pic		= uploadImg($pic_file, "jpg,gif,png");		//上传图片
	$del_pic	= (int)$_POST["del_pic"];
	if (empty($id2) || empty($name) || ($info_state != "content" && $info_state != "list" && $info_state != "pic" && $info_state != "pictxt" && $info_state != "custom") || ($max_level < 2 || $max_level > 6))
	{
		$db->close();
		info("填写的参数不完整！");
	}
	
	if (empty($id))
	{
		//检查填写的分类ID
		if (!checkClassID($id2, 1))
		{
			$db->close();
			info("填写的分类ID号错误！");
		}
		
		//检查分类ID是否存在
		if ($db->getCount("info_class", "id='$id2'") > 0)
		{
			$id2 = $db->getMax("info_class", "id", "id like '" . CLASS_SPACE . "'");
			$id2 = empty($id2) ? CLASS_DEFAULT : $id2 + 1;
		}
		if (!empty($pic) || $del_pic == 1)
		{
			$oldPic = $db->getTableFieldValue("info_class", "pic", "where id='$id'");
			$sql = "insert into info_class(id, sortnum, name,en_name, pic, content, files, info_state, max_level, has_sub, sub_content, sub_pic, hasViews, hasState, hasPic, hasAnnex, hasIntro, hasContent, hasWebsite, hasAuthor, hasSource, hasKeyword,hasShare, state) values('$id2', $sortnum, '$name','$en_name', '$pic', '$content', '', '$info_state', $max_level, 1, $sub_content, $sub_pic, $hasViews, $hasState, $hasPic, $hasAnnex, $hasIntro, $hasContent, $hasWebsite, $hasAuthor, $hasSource, $hasKeyword,$hasShare, $state)";
		}
		else
		{
			$sql = "insert into info_class(id, sortnum, name,en_name, content, files, info_state, max_level, has_sub, sub_content, sub_pic, hasViews, hasState, hasPic, hasAnnex, hasIntro, hasContent, hasWebsite, hasAuthor, hasSource, hasKeyword,hasLevel,hasShare, state) values('$id2', $sortnum, '$name', '$en_name', '$content', '', '$info_state', $max_level, 1, $sub_content, $sub_pic, $hasViews, $hasState, $hasPic, $hasAnnex, $hasIntro, $hasContent, $hasWebsite, $hasAuthor, $hasSource, $hasKeyword,$hasLevel,$hasShare, $state)";
		}
		
	}
	else
	{
		if (!empty($pic) || $del_pic == 1)
		{
			$oldPic = $db->getTableFieldValue("info_class", "pic", "where id='$id'");
			$sql = "update info_class set pic='$pic',content='$content', hasShare=$hasShare,sortnum=$sortnum, name='$name',en_name='$en_name', state=$state, sub_content=$sub_content, sub_pic=$sub_pic, max_level=$max_level, info_state='$info_state', hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword ,hasLevel=$hasLevel where id='$id'";
		}
		else
		{
			$sql = "update info_class set sortnum=$sortnum, name='$name', en_name='$en_name',content='$content',hasShare=$hasShare,state=$state, sub_content=$sub_content, sub_pic=$sub_pic, max_level=$max_level, info_state='$info_state', hasViews=$hasViews, hasState=$hasState, hasPic=$hasPic, hasAnnex=$hasAnnex, hasIntro=$hasIntro, hasContent=$hasContent, hasWebsite=$hasWebsite, hasAuthor=$hasAuthor, hasSource=$hasSource, hasKeyword=$hasKeyword,hasLevel=$hasLevel where id='$id'";
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
		info("添加/编辑分类失败！");
	}
}

if (empty($id))
{
	$id			= $db->getMax("info_class", "id", "id like '" . CLASS_SPACE . "'");
	$id			= empty($id) ? CLASS_DEFAULT : $id + 1;
	$sortnum	= $db->getMax("info_class", "sortnum", "id like '" . CLASS_SPACE . "'") + 10;
	$state		= 1;
	$max_level	= 2;
	$info_state	= "custom";
	$hasViews	= 1;
	$hasState	= 1;
	$hasPic		= 1;
	$hasContent	= 1;
}
else
{
	$sql = "select * from info_class where id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$en_name		= $row["en_name"];
		$info_state		= $row["info_state"];
		$max_level		= $row["max_level"];
		$sub_content	= $row["sub_content"];
		$sub_pic		= $row["sub_pic"];
		$hasViews		= $row["hasViews"];
		$hasState		= $row["hasState"];
		$hasPic			= $row["hasPic"];
		$hasAnnex		= $row["hasAnnex"];
		$hasIntro		= $row["hasIntro"];
		$hasContent		= $row["hasContent"];
		$hasWebsite		= $row["hasWebsite"];
		$hasAuthor		= $row["hasAuthor"];
		$hasSource		= $row["hasSource"];
		$hasKeyword		= $row["hasKeyword"];
		$hasLevel		= $row["hasLevel"];
		$hasShare		= $row["hasShare"];
		$state			= $row["state"];
		$pic			= $row["pic"];
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
				if (form.id.value == "")
				{
					alert("请输入ID号！");
					form.id.focus();
					return false;
				}

				if (!/^[1-9][0-9]{<?=CLASS_LENGTH - 1?>}$/.exec(form.id.value))
				{
					alert("请输入合法的ID号！");
					form.id.focus();
					return false;
				}
				
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

				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 隐藏管理 -&gt; 一级分类管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td><a href="<?=$listUrl?>">[返回列表]</a></td>
			</tr>
		</table>
		<form name="form1" action="" method="post"  enctype="multipart/form-data" onSubmit="return check(this);">
		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
			
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">一级分类</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">ID号</td>
					<td class="editRightTd"><input type="text" name="id" value="<?=$id?>" size="10" maxlength="20"></td>
				</tr>
                <tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd"><input type="text" name="sortnum" value="<?=$sortnum?>" size="10" maxlength="5"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">分类名称</td>
					<td class="editRightTd"><input type="text" name="name" value="<?=$name?>" maxlength="50" size="30"></td>
				</tr>
                <tr class="editTr">
					<td class="editLeftTd">分类名称2</td>
					<td class="editRightTd"><input type="text" name="en_name" value="<?=$en_name?>" maxlength="50" size="30"></td>
				</tr>
                <tr class="editTr">
					<td class="editLeftTd">二级分类</td>
					<td class="editRightTd">
                        <input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>>允许
                        <input type="radio" name="state" value="0"<? if ($state == 0) echo " checked"?>>拒绝
                        <input type="checkbox" name="sub_content" value="1"<? if ($sub_content == 1) echo " checked"?>>有内容
                        <input type="checkbox" name="sub_pic" value="1"<? if ($sub_pic == 1) echo " checked"?>>有图片
                    </td>
				</tr>
                <tr class="editTr">
					<td class="editLeftTd">最大分类层次</td>
					<td class="editRightTd">
                        <select name="max_level">
                        	<option value="2"<? if ($max_level == 2) echo " selected"?>>2</option>
                            <option value="3"<? if ($max_level == 3) echo " selected"?>>3</option>
                            <option value="4"<? if ($max_level == 4) echo " selected"?>>4</option>
                            <option value="5"<? if ($max_level == 5) echo " selected"?>>5</option>
							<option value="6"<? if ($max_level == 6) echo " selected"?>>6</option>
                        </select>
				    </td>
				</tr>
                <tr class="editTr">
					<td class="editLeftTd">记录状态</td>
					<td class="editRightTd">
                        <input type="radio" name="info_state" value="content"<? if ($info_state == "content") echo " checked"?>>图文模式
                        <input type="radio" name="info_state" value="list"<? if ($info_state == "list") echo " checked"?>>新闻列表
                        <input type="radio" name="info_state" value="pic"<? if ($info_state == "pic") echo " checked"?>>图片列表
                        <input type="radio" name="info_state" value="pic"<? if ($info_state == "pictxt") echo " checked"?>>图文列表
                        <input type="radio" name="info_state" value="custom"<? if ($info_state == "custom") echo " checked"?>>自定义
                    </td>
                </tr>
                <tr class="editTr">
					<td class="editLeftTd">记录设置</td>
					<td class="editRightTd">
                        <input type="checkbox" name="hasViews" value="1" <? if ($hasViews == 1) echo "checked"?>> 点击次数
						<input type="checkbox" name="hasState" value="1" <? if ($hasState == 1) echo "checked"?>> 是否显示
                        <input type="checkbox" name="hasAuthor" value="1" <? if ($hasAuthor == 1) echo "checked"?>> 文章作者
						<input type="checkbox" name="hasSource" value="1" <? if ($hasSource == 1) echo "checked"?>> 文章来源
                        <input type="checkbox" name="hasKeyword" value="1" <? if ($hasKeyword == 1) echo "checked"?>> 关键词
                        
						<br>

						<input type="checkbox" name="hasPic" value="1" <? if ($hasPic == 1) echo "checked"?>> 图片上传
						<input type="checkbox" name="hasAnnex" value="1" <? if ($hasAnnex == 1) echo "checked"?>> 附件上传
						<input type="checkbox" name="hasIntro" value="1" <? if ($hasIntro == 1) echo "checked"?>> 简要介绍
						<input type="checkbox" name="hasContent" value="1" <? if ($hasContent == 1) echo "checked"?>> 详细内容
						<input type="checkbox" name="hasWebsite" value="1" <? if ($hasWebsite == 1) echo "checked"?>> 链接地址
                        <br>
                        <input type="checkbox" name="hasLevel" value="1" <? if ($hasLevel == 1) echo "checked"?>> 等级设置
                        <input type="checkbox" name="hasShare" value="1" <? if ($hasShare == 1) echo "checked"?>> 是否分享
                    </td>
                </tr>
                <tr class="editTr">
						<td class="editLeftTd">图片</td>
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
						<td class="editLeftTd">内容</td>
						<td class="editRightTd"><textarea name="content"><?=$content;?></textarea></td>
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
