<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
if ($id < 1)
{
	info("参数有误！");
}
$listUrl = "message_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum	= (int)$_POST["sortnum"];
	$reply		= trim($_POST["reply"]);
	$state		= (int)$_POST["state"];

	$sql = "update message set sortnum=$sortnum, reply='$reply', reply_time='" . date("Y-m-d H:i:s") . "', state=$state where id=$id";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		header("location: $listUrl");
		exit;
	}
	else
	{
		info("回复留言失败！");
	}
}

$sql = "select name, sortnum, phone, email, fax, company, content, create_time, reply, ip, state from message where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$name			= $row["name"];
	$sortnum		= $row["sortnum"];
	$phone			= $row["phone"];
	$email			= $row["email"];
	$fax			= $row["fax"];
	$company		= $row["company"];
	$content		= $row["content"];
	$create_time	= $row["create_time"];
	$reply			= $row["reply"];
	$ip				= $row["ip"];
	$state			= $row["state"];

	if ($state == 0)
	{
		$sql = "update message set state=1 where id=$id";
		$db->query($sql);

		$state = 2;
	}
}
else
{
	$db->close();
	info("指定的记录不存在！");
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
				var editor = K.create('textarea[name="reply"]', {
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

				if (!isDateTime(form.reply_time.value))
				{
					alert("回复时间的格式不正确！");
					form.reply_time.focus();
					return false;
				}

				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 留言簿</td>
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
					<td class="editHeaderTd" colSpan="2">留言簿</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd"><input type="text" name="sortnum" maxlength="20" size="24" value="<?=$sortnum?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">留言人姓名</td>
					<td class="editRightTd"><?=$name?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">联系电话</td>
					<td class="editRightTd"><?=$phone?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">电子信箱</td>
					<td class="editRightTd"><?=$email?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">传真</td>
					<td class="editRightTd"><?=$fax?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">工作单位</td>
					<td class="editRightTd"><?=$company?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">留言内容</td>
					<td class="editRightTd"><?=nl2br($content)?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">留言时间</td>
					<td class="editRightTd"><?=$create_time?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">留言IP</td>
					<td class="editRightTd"><?=$ip?></td>
				</tr>
				<!--tr class="editTr">
					<td colspan="2"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd"><input type="text" name="sortnum" value="<?=$sortnum?>" size="10" maxlength="5"></td>
				</tr-->
				<tr class="editTr">
					<td class="editLeftTd">是否显示</td>
					<td class="editRightTd">
						<input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>> 不显示
						<input type="radio" name="state" value="2"<? if ($state == 2) echo " checked"?>> 显示
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">回复</td>
					<td class="editRightTd"><textarea name="reply" cols="100" rows="10"><?=$reply?></textarea>
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
