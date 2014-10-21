<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$oldpass = trim($_POST["oldpass"]);
	$newpass = trim($_POST["newpass"]);

	if (empty($oldpass) || empty($newpass))
	{
		info("参数填写不正确！");
	}
	else
	{
		$oldpass = md5($oldpass);
		$newpass = md5($newpass);
	}


	//连接数据库
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
	

	$sql = "select pass from admin where id=$session_admin_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		if ($oldpass != $row["pass"])
		{
			$db->close();
			info("原密码不正确！");
		}
	}
	else
	{
		$db->close();
		info("当前帐号不存在！");
	}

	$sql = "update admin set pass='$newpass' where id=$session_admin_id";
	$rst = $db->query($sql);
	$db->close();

	if($rst)
	{
		info("修改密码成功！");
	}
	else
	{
		info("修改密码失败，可能是原密码不正确！");
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
			if (form.oldpass.value == "")
			{
				alert("原密码不能为空！");
				form.oldpass.focus();
				return false;
			}

			if (form.newpass.value == "")
			{
				alert("新密码不能为空！");
				form.newpass.focus();
				return false;
			}

			if (form.newpass.value.length < 8)
			{
				alert("密码长度不能少于8位！");
				form.newpass.focus();
				return false;
			}

			if (form.newpass.value == form.oldpass.value)
			{
				alert("新密码不能和原密码相同！");
				form.newpass.focus();
				return false;
			}

			if (form.newpass2.value != form.newpass.value)
			{
				alert("两次输入的密码不一致！");
				form.newpass2.focus();
				return false;
			}
			return true;
		}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 个人管理 -&gt; 修改口令</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td></td>
			</tr>
		</table>
		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
			<form name="form1" action="" method="post" onSubmit="return check(this);">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">修改口令</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">登陆帐号</td>
					<td class="editRightTd"><?=$session_admin_name?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">原密码</td>
					<td class="editRightTd"><input type="password" name="oldpass" value="" size="30" maxlength="20"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">新密码</td>
					<td class="editRightTd"><input type="password" name="newpass" value="" size="30" maxlength="20"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">确认新密码</td>
					<td class="editRightTd"><input type="password" name="newpass2" value="" size="30" maxlength="20"></td>
				</tr>
				<tr class="editFooterTr">
					<td class="editFooterTd" colSpan="2">
						<input type="submit" value=" 确 定 ">
						<input type="reset" value=" 重 填 ">
					</td>
				</tr>
			</form>
		</table>
		<script type="text/javascript">document.form1.oldpass.focus();</script>
	</body>
</html>
