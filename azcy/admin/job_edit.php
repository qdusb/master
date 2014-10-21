<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, JOB_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "job_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$state			= (int)$_POST["state"];
	$num			= (string)$_POST["num"];
	$showForm		= (int)$_POST["showForm"];
	$class_id		= $_POST["class_id"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$email			= htmlspecialchars(trim($_POST["email"]));
	$publishdate	= htmlspecialchars(trim($_POST["publishdate"]));
	$deadline		= htmlspecialchars(trim($_POST["deadline"]));
	$content		= $_POST["content"];
	$content2		= $_POST["content2"];

	if (empty($id))
	{
		$id = $db->getMax("job", "id") + 1;
		$sql = "insert into job(id,class_id, sortnum,num, state, showForm, name, email, publishdate, deadline, content,content2) values('$id','$class_id', $sortnum, $state,'$num', $showForm, '$name', '$email', '$publishdate', '$deadline', '$content','$content2')";
	}
	else
	{
		$sql = "update job set class_id='$class_id',sortnum=$sortnum, state=$state,num='$num', showForm=$showForm, name='$name', email='$email', publishdate='$publishdate', deadline='$deadline', content='$content',content2='$content2' where id='$id'";
	}
	$rst = $db->query($sql);
	$db->close();
	header("Location: $listUrl");
}
else
{
	if ($id == "")
	{
		$sortnum 	= $db->getMax("job", "sortnum") + 10;
		$state		= 1;
		$showForm	= 1;
	}
	else
	{
		$sql = "select * from job where id='$id'";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$id				= $row["id"];
			$sortnum		= $row["sortnum"];
			$state			= $row["state"];
			$num			= $row["num"];
			$class_id		= $row["class_id"];
			$showForm		= $row["showForm"];
			$name			= $row["name"];
			$email			= $row["email"];
			$publishdate	= $row["publishdate"];
			$deadline		= $row["deadline"];
			$content		= $row["content"];
			$content2		= $row["content2"];
		}
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
		<script>
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
				var editor2 = K.create('textarea[name="content2"]', {
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
				if (form.sortnum.value.match(/\D/))
				{
					alert("请输入合法的序号！");
					form.sortnum.focus();
					return false;
				}

				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 招聘职位</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>&nbsp;
				</td>
			</tr>
		</table>
		<form name="form1" action="" method="post">
			<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">招聘职位</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd"><input type="text" name="sortnum" value="<?=$sortnum?>" size="5" maxlength="5"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">状态</td>
					<td class="editRightTd">
						<input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>>显示
						<input type="radio" name="state" value="0"<? if ($state == 0) echo " checked"?>>不显示
					</td>
				</tr>
				<tr class="editTr" style="display:none">
					<td class="editLeftTd">有无表单</td>
					<td class="editRightTd">
						<input type="radio" name="showForm" value="1"<? if ($showForm == 1) echo " checked"?>>有
						<input type="radio" name="showForm" value="0"<? if ($showForm == 0) echo " checked"?>>无
					</td>
				</tr>
				 <tr class="editTr" style="display:none">
					<td class="editLeftTd">工作种类</td>
					<td class="editRightTd">
						<select name="class_id">
						<?
						$sql = "select * from job_class  order by sortnum asc";
						$rst = $db->query($sql);
						while ($row = $db->fetch_array($rst))
						{
							if($row['id']==$class_id)
							{
								echo "<option value='".$row['id']."' selected>".$row['name']."</option>";
							}
							else
							{
								echo "<option value='".$row['id']."'>".$row['name']."</option>";
							}
							
						}
						?>
						</select>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">招聘职位</td>
					<td class="editRightTd"><input type="text" name="name" value="<?=$name?>" maxlength="50" size="30"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">招聘人数</td>
					<td class="editRightTd"><input type="text" name="num" value="<?=$num?>" maxlength="50" size="30"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">电子信箱</td>
					<td class="editRightTd"><input type="text" name="email" value="<?=$email?>" maxlength="50" size="30"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">发布日期</td>
					<td class="editRightTd"><input type="text" name="publishdate" value="<?=$publishdate?>" maxlength="50" size="30"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">截止日期</td>
					<td class="editRightTd"><input type="text" name="deadline" value="<?=$deadline?>" maxlength="50" size="30"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">任职资格</td>
					<td class="editRightTd"><textarea name="content" cols="100" rows="10"><?=$content?></textarea>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">岗位职责</td>
					<td class="editRightTd"><textarea name="content2" cols="100" rows="10"><?=$content2?></textarea>
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
		<?
		$db->close();
		?>
	</body>
</html>
