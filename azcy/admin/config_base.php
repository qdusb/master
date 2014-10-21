<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, CONFIG_ADVANCEDID) == false)
{
	info("没有权限！");
}


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name			= htmlspecialchars(trim($_POST["name"]));
	$title			= htmlspecialchars(trim($_POST["title"]));
	$icp			= htmlspecialchars(trim($_POST["icp"]));
	$keyword		= $_POST["keyword"];
	$address		= $_POST["address"];
	$address2		= $_POST["address2"];
	$description	= $_POST["description"];
	$contact		= $_POST["copyright"];
	$javascript		= trim($_POST["javascript"]);

	if (empty($name) || empty($title) || empty($icp))
	{
		$db->close();
		info("填写的参数不完整！");
	}

	$sql = "update config_base set name='$name',address='$address',address2='$address2',title='$title', icp='$icp', keyword='$keyword', description='$description', contact='$contact', javascript='$javascript' where id=1";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		info("编辑基本信息成功！");
	}
	else
	{
		info("编辑基本信息失败！");
	}
}

$sql = "select * from config_base where id=1";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$name			= $row["name"];
	$title			= $row["title"];
	$icp			= $row["icp"];
	$keyword		= $row["keyword"];
	$description	= $row["description"];
	$copyright		= $row["contact"];
	$javascript		= $row["javascript"];
	$address		= $row["address"];
	$address2		= $row["address2"];

	$db->close();
}
else
{
	$db->close();
	info("还没有记录！");
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
				var editor = K.create('textarea[name="copyright"]', {
					resizeType : 1,
					allowFileManager : false,
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
				var editor1 = K.create('textarea[name="address"]', {
					resizeType : 1,
					allowFileManager : false,
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
				if (form.name.value == "")
				{
					alert("请输入公司名称！");
					form.name.focus();
					return false;
				}

				if (form.title.value == "")
				{
					alert("请输入网站标题！");
					form.title.focus();
					return false;
				}

				if (form.icp.value == "")
				{
					alert("请输入ICP备案号！");
					form.icp.focus();
					return false;
				}

				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心  -&gt; 高级管理 -&gt; 基本设置</td>
			</tr>
		</table>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="20">
				<td></td>
			</tr>
		</table>
		<form name="form1" action="" method="post" onSubmit="return check(this);">
			<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">基本设置</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">公司名称</td>
					<td class="editRightTd">
						<input type="text" name="name" value="<?=$name?>" size="50" maxlength="100">
					</td>
				</tr>
              	<tr class="editTr">
					<td class="editLeftTd">公司地址</td>
					<td class="editRightTd">
						<input type="text" name="address2" value="<?=$address2?>" size="50" maxlength="100">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">网站标题</td>
					<td class="editRightTd">
						<input type="text" name="title" value="<?=$title?>" size="50" maxlength="100">
					</td>
				</tr>
                
				<tr class="editTr">
					<td class="editLeftTd">ICP备案号</td>
					<td class="editRightTd">
						<input type="text" name="icp" value="<?=$icp?>" size="30" maxlength="30">
					</td>
				</tr>
                
				<tr class="editTr">
					<td class="editLeftTd">网站关键字</td>
					<td class="editRightTd" style="padding:10px;">
						<input type="text" name="keyword" value="<?=$keyword?>" size="100" maxlength="200">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">网站描述</td>
					<td class="editRightTd" style="padding:10px;">
						<input type="text" name="description" value="<?=$description?>" size="100" maxlength="200">
					</td>
				</tr>
                <tr class="editTr">
					<td class="editLeftTd">公司联系信息</td>
					<td class="editRightTd">
						<textarea name="address"><?php echo $address; ?></textarea>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">版权信息</td>
					<td class="editRightTd" style="padding:10px;">
						<textarea name="copyright"><?php echo $copyright; ?></textarea>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">Javascript 代码</td>
					<td class="editRightTd" style="padding:10px;">
						<textarea name="javascript" cols="105" rows="5"><?=$javascript?></textarea>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">备注</td>
					<td class="editRightTd">
						请确保Javascript代码的安全性，防止可能引用错误甚至恶意的代码，造成网站瘫痪和数据丢失。
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
	</body>
</html>
