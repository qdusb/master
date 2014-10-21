<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");


$class_id		= trim($_GET["class_id"]);
$select_class	= empty($_GET["select_class"]) ? $class_id : trim($_GET["select_class"]);
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$keyword		= urlencode(trim($_GET["keyword"]));
$id				= (int)$_GET["id"];
if (empty($class_id) || !checkClassID($class_id, 2))
{
	info("指定了错误的分类！");
}

if (strlen($select_class) % CLASS_LENGTH != 0 && !checkClassID($select_class, strlen($select_class) / CLASS_LENGTH))
{
	info("选择了错误的分类！");
}

//权限检查
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && $session_admin_grade != ADMIN_ADVANCED && hasInclude($session_admin_popedom, substr($class_id, 0, CLASS_LENGTH)) != true && hasInclude($session_admin_popedom, $class_id) != true)
{
	info("没有权限！");
}


$listUrl = "batch_upload_list.php?class_id=$class_id&page=$page";
$editUrl = "batch_upload_info.php?class_id=$class_id&page=$page&id=$id";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if(!empty($id))
{
	$sql = "select class_id from info where id='$id' limit 1";
	$rst = $db->query($sql);
	$row = $db->fetch_array($rst);
	$class_id=$row['class_id'];
}

//查询顶级分类的记录设置
$baseClassID = substr($class_id, 0, CLASS_LENGTH);
$sql = "select * from info_class where id='$baseClassID'";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$hasViews	= $row["hasViews"];
	$hasState	= $row["hasState"];
	$hasPic		= $row["hasPic"];
	$hasAnnex	= $row["hasAnnex"];
	$hasIntro	= $row["hasIntro"];
	$hasContent	= $row["hasContent"];
	$hasWebsite	= $row["hasWebsite"];
	$hasAuthor	= $row["hasAuthor"];
	$hasSource	= $row["hasSource"];
	$hasKeyword	= $row["hasKeyword"];
	$hasLevel	= $row["hasLevel"];
	$hasShare	= $row["hasShare"];
	$hasSelect	= $db->getTableFieldValue("info_class", "has_sub", "where id='$class_id'");
	//$hasSelect=1;
}
else
{
	$db->close();
	info("指定的分类不存在！");
}


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum = (int)$_POST["sortnum"];
	if ($hasState == 1)
	{
		$state = (int)$_POST["state"];
	}
	else
	{
		$state = 1;
	}
	$share = (int)$_POST["share"];

	//权限 普通管理员只能发表未审核的信息
	if ($session_admin_grade == ADMIN_COMMON)
	{
		$state = 0;
	}

	$title	= htmlspecialchars(trim($_POST["title"]));
	$series	= htmlspecialchars(trim($_POST["series"]));
	$classcol	= htmlspecialchars(trim($_POST["classcol"]));
	$title2	= htmlspecialchars(trim($_POST["title2"]));

	$info_class = trim($_POST["info_class"]);
	
	if ($hasAuthor == 1)
	{
		$author = htmlspecialchars(trim($_POST["author"]));
	}
	else
	{
		$author = "";
	}

	if ($hasSource == 1)
	{
		$source = htmlspecialchars(trim($_POST["source"]));
	}
	else
	{
		$source = "";
	}

	if ($hasWebsite == 1)
	{
		$website = htmlspecialchars(trim($_POST["website"]));
	}
	else
	{
		$website = "";
	}

	if ($hasPic == 1)
	{
		$pic_file	= &$_FILES["pic"];
		$pic		= uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv");			//上传图片
		$del_pic	= (int)$_POST["del_pic"];
	}
	else
	{
		$pic = "";
	}

	if ($hasAnnex == 1)
	{
		$annex_file	= &$_FILES["annex"];
		$annex		= uploadImg($annex_file, "pdf,doc,xls,ppt,rar,zip,flv");	//上传附件
		$del_annex	= (int)$_POST["del_annex"];
	}
	else
	{
		$annex = "";
	}

	if ($hasKeyword == 1)
	{
		$keyword = htmlspecialchars(trim($_POST["keyword"]));
	}
	else
	{
		$keyword = "";
	}

	if ($hasIntro == 1)
	{
		$intro = $_POST["intro"];
	}
	else
	{
		$intro = "";
	}
	
	if ($hasLevel == 1)
	{
		$level = $_POST["level"];
	}
	else
	{
		$level = 0;
	}
	
	if ($hasContent == 1)
	{
		$content = $_POST["content"];
		$files	 = $_POST["content_files"];
	}
	else
	{
		$content = "";
		$files	 = "";
	}
	$No = $_POST["No"];
	$create_time	= formatDate("Y-m-d H:i:s", $_POST["create_time"]);
	$now			= date("Y-m-d H:i:s");

	if (empty($title))
	{
		$db->close();
		info("填写的参数错误！");
	}

	if ($id < 1)
	{
		$aid	= $db->getMax("info", "id", "") + 1;
		$sortnum = $db->getMax("info", "sortnum", "class_id='$info_class'") + 10;
		$sql = "insert into info(id,No,sortnum, title, title2, admin_id, class_id, author, source, website, pic, annex, keyword, intro, content, files, views, create_time, modify_time, state, share, series, classcol,level) values(" . ($db->getMax("info", "id", "") + 1) . ",'$No', $sortnum, '$title', '$title2', $session_admin_id, '$info_class', '$author', '$source', '$website', '$pic', '$annex', '$keyword', '$intro', '$content', '$files', 0, '$create_time', '$now', $state, $share, '$series', '$classcol',$level)";
	}
	else
	{
		//权限 普通管理员只能修改自己发表但未审核的信息
		if ($session_admin_grade == ADMIN_COMMON && ($db->getTableFieldValue("info", "state", "where id=$id") == 1 || $db->getTableFieldValue("info", "admin_id", "where id=$id") != $session_admin_id))
		{
			info("没有权限！");
		}

		if ((!empty($pic) || $del_pic == 1) && (!empty($annex) || $del_annex == 1))
		{
			$oldPic		= $db->getTableFieldValue("info", "pic", "where id=$id");
			$oldAnnex	= $db->getTableFieldValue("info", "annex", "where id=$id");
			$sql = "update info set sortnum=$sortnum, No='$No',title='$title', title2='$title2', class_id='$info_class', author='$author', source='$source', website='$website', pic='$pic', annex='$annex', keyword='$keyword', intro='$intro', content='$content', files='$files', create_time='$create_time', modify_time='$now', series='$series', classcol='$classcol', state=$state, share=$share,level=$level where id=$id";
		}
		else if (!empty($pic) || $del_pic == 1)
		{
			$oldPic		= $db->getTableFieldValue("info", "pic", "where id=$id");
			$oldAnnex	= "";
			$sql = "update info set sortnum=$sortnum, No='$No',title='$title', title2='$title2', class_id='$info_class', author='$author', source='$source', website='$website', pic='$pic', keyword='$keyword', intro='$intro', content='$content', files='$files', create_time='$create_time', modify_time='$now', series='$series', classcol='$classcol', state=$state, share=$share,level=$level where id=$id";
		}
		else if (!empty($annex) || $del_annex == 1)
		{
			$oldPic		= "";
			$oldAnnex	= $db->getTableFieldValue("info", "annex", "where id=$id");
			$sql = "update info set sortnum=$sortnum, No='$No',title='$title', title2='$title2', class_id='$info_class', author='$author', source='$source', website='$website', annex='$annex', keyword='$keyword', intro='$intro', content='$content', files='$files', create_time='$create_time', modify_time='$now', series='$series', classcol='$classcol', state=$state, share=$share,level=$level where id=$id";
		}
		else
		{
			$sql = "update info set sortnum=$sortnum, No='$No',title='$title', title2='$title2', class_id='$info_class', author='$author', source='$source', website='$website', keyword='$keyword', intro='$intro', content='$content', files='$files', create_time='$create_time', modify_time='$now', series='$series', classcol='$classcol', state=$state, share=$share,level=$level where id=$id";
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
			deleteFile($oldAnnex, 1);
		}

		header("Location: $listUrl");
		exit;
	}
	else
	{
		//添加或修改失败后 删除上传的图片、附件
		deleteFile($pic, 1);
		deleteFile($annex, 1);
		//添加失败还要删除编辑器内上传的图片
		if ($id < 1)
		{
			deleteFiles($files, 2);
		}

		info("添加/编辑信息失败！");
	}
}


if ($id < 1)
{
	$sortnum	 = $db->getMax("info", "sortnum", "class_id like '$class_id%'") + 10;
	$select_id	 = $select_class;
	$state		 = 1;
	$share		 = 0;
	$create_time = date("Y-m-d H:i:s");
}
else
{
	$sql = "select * from info where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$title			= $row["title"];
		$title2			= $row["title2"];
		$select_id		= $row["class_id"];
		$author			= $row["author"];
		$source			= $row["source"];
		$website		= $row["website"];
		$pic			= $row["pic"];
		$annex			= $row["annex"];
		$keyword		= $row["keyword"];
		$intro			= $row["intro"];
		$No				= $row["No"];
		$content		= $row["content"];
		$files			= $row["files"];
		$state			= $row["state"];
		$share			= $row["share"];
		$create_time	= $row["create_time"];
		$series			= $row["series"];
		$classcol		= $row["classcol"];
		$level			= $row["level"];
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
		<script charset="utf-8" src="kindeditor/kindeditor-min.js"></script>
		<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
		<script>
			KindEditor.ready(function(K) {
				var editor = K.create('textarea[name="content"]', {
					uploadJson : 'kindeditor/php/upload_json.php',
					fileManagerJson : 'kindeditor/php/file_manager_json.php',
					items : [
							'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
							'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
							'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
							'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
							'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
							'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',"multiimage",
							'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'map', 'code', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'
						],
					width : '700px',
					height : '300px',
					pasteType : 1,
					allowFileManager : false,
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
				var editor1 = K.create('textarea[name="intro"]', {
					width : '700px',
					height : '200px',
					pasteType : 1,
					items : [
							'source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
							'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
							'insertunorderedlist', '|', 'link'],
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
				if (form.sortnum.value.match(/\D/))
				{
					alert("请输入合法的序号！");
					form.sortnum.focus();
					return false;
				}

				if(form.title.value == "")
				{
					alert("请填入标题名称!");
					form.title.focus();
					return false;
				}

				<?
				if ($hasPic == 1)
				{
				?>
					if (form.pic.value != "")
					{
						var ext = form.pic.value.substr(form.pic.value.length - 3).toLowerCase();

						if (ext != "gif" && ext != "jpg" && ext != "png" && ext != "swf" && ext != "wmv")
						{
							alert("图片必须是GIF、JPG或PNG格式！");
							return false;
						}
					}
				<?
				}
				?>

				<?
				if ($hasAnnex == 1)
				{
				?>
					if (form.annex.value != "")
					{
						var ext = form.annex.value.substr(form.annex.value.length - 3).toLowerCase();

						if (ext != "pdf" && ext != "doc" && ext != "xls" && ext != "ppt" && ext != "zip" && ext != "rar" && ext != "flv")
						{
							alert("附件必须是PDF、DOC、XLS、PPT、ZIP、RAR或FLV格式！");
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
				<td class="position">当前位置: 管理中心 -&gt; <?=$db->getTableFieldValue("info_class", "name", "where id='$class_id'")?> -&gt; 新增/编辑</td>
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
				<?
				if ($hasState == 1 && $session_admin_grade != ADMIN_COMMON)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">状态</td>
						<td class="editRightTd">
							<select name="state" style="width:80px;">
								<option value="0"<? if ($state == 0) echo "selected";?>>未审核</option>
								<option value="1"<? if ($state == 1) echo "selected";?>>正常</option>
								<option value="2"<? if ($state == 2) echo "selected";?>>推荐</option>
							</select>
						</td>
					</tr>
				<?
				}
				if ($hasLevel == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">文章权限分配</td>
						<td class="editRightTd">
							<select name="level" style="width:80px;">
								<option value="0"  <? if($level == 0) echo "selected";?>>普通用户</option>
								<option value="1"  <? if($level == 1) echo "selected";?>>初级会员</option>
								<option value="2"  <? if($level == 2) echo "selected";?>>中级会员</option>
								<option value="3"  <? if($level == 3) echo "selected";?>>高级会员</option>
							</select>
						</td>
					</tr>
				<?
				}
				if ($hasShare == 1)
				{
				?>
				
				<tr class="editTr">
					<td class="editLeftTd">是否分享</td>
					<td class="editRightTd">
						<input type="radio" name="share" value="1"<? if ($share == 1) echo " checked"?>>是
						<input type="radio" name="share" value="0"<? if ($share == 0) echo " checked"?>>否
						　（注：启用后打开页面较慢，请根据实际需求启用！）
					</td>
				</tr>
				<?
				}
				?>
				<tr class="editTr">
					<td class="editLeftTd">发表时间</td>
					<td class="editRightTd"><input type="text" name="create_time" value="<?=$create_time?>" maxlength="20" size="24"> 时间格式为2009-01-01 00:00:00</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">标题名称</td>
					<td class="editRightTd"><input type="text" value="<?=$title?>" name="title" maxlength="100" size="50"></td>
				</tr>
				<?
				if(substr($class_id,0,3)=="103")
				{
				?>
				<tr class="editTr">
					<td class="editLeftTd">产品系列</td>
					<td class="editRightTd">
						<?
						$sql = "select * from link_class  order by sortnum asc";
						$rst = $db->query($sql);
						while ($row = $db->fetch_array($rst))
						{
						?>
						<input type="radio" name="series" value="<?=$row['id']?>" <?=$series==$row['id']?"checked":""?> /><?=$row['name']?>
						<?
						}
						?>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">产品型号</td>
					<td class="editRightTd"><input type="text" value="<?=$No?>" name="No" maxlength="100" size="50"></td>
				</tr>
				
				<?
				}
				
				?>
				<tr class="editTr" style="display:none">
					<td class="editLeftTd">标题2</td>
					<td class="editRightTd"><input type="text" value="<?=$title2?>" name="title2" maxlength="100" size="50"></td>
				</tr>
					<tr class="editTr">
						<td class="editLeftTd">文章类别</td>
						<td class="editRightTd">
							<select name="info_class" style="width:50%;">
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
									if($id_str==(string)$select_id)
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
						</td>
					</tr>
				<?

				if ($hasAuthor == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">作者</td>
						<td class="editRightTd">
							<input type="text" value="<?=$author?>" name="author" maxlength="50" size="30">
						</td>
					</tr>
				<?
				}

				if ($hasSource == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">来源</td>
						<td class="editRightTd">
							<input type="text" value="<?=$source?>" name="source" maxlength="50" size="30">
						</td>
					</tr>
				<?
				}

				if ($hasWebsite == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">链接网址</td>
						<td class="editRightTd"><input type="text" value="<?=$website?>" name="website" maxlength="300" size="50"></td>
					</tr>
				<?
				}

				if ($hasPic == 1)
				{
				?>
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
				<?
				}

				if ($hasAnnex == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">附件</td>
						<td class="editRightTd">
							<input type="file" name="annex" size="40">
							<?
							echo "";
							if ($annex != "")
							{
							?>
								<input type="checkbox" name="del_annex" value="1"> 删除现有附件
							<?
							}
							?>
						</td>
					</tr>
				<?
				}

				if ($hasKeyword == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">关键词</td>
						<td class="editRightTd">
							<input type="text" value="<?=$keyword?>" name="keyword" maxlength="50" size="46">
						</td>
					</tr>
				<?
				}



				if ($hasIntro == 1)
				{
					$second_id=substr($class_id,0,6);
					$intro_name=($second_id=="104101"||$second_id=="108101")?"经纬度":"简介";
				?>
					<tr class="editTr">
						<td class="editLeftTd"><?=$intro_name?></td>
						<td class="editRightTd"><textarea name="intro"><?php echo $intro; ?></textarea></td>
					</tr>
				<?
				}

				if ($hasContent == 1)
				{
				?>
					<tr class="editTr">
						<td class="editLeftTd">详细内容</td>
						<td class="editRightTd"><textarea name="content"><?php echo $content; ?></textarea></td>
					</tr>
				<?
				}
				?>
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
