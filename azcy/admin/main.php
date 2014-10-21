<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");


if ($session_admin_grade != ADMIN_HIDDEN)
{
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

	$sql = "select realname, login_count from admin where id=$session_admin_id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$realname		= $row["realname"];
		$login_count	= $row["login_count"];
		
		$sql = "select login_time, login_ip from admin_login where admin_id=$session_admin_id order by login_time desc limit 1";
		$rst = $db->query($sql);
		if ($row = $db->fetch_array($rst))
		{
			$last_login_time	= $row["login_time"];
			$last_login_ip		= $row["login_ip"];
		}

		$db->close();
	}
	else
	{
		$db->close();
		header("Location: login.php");
		exit();
	}
}
else
{
	$realname		 = $session_admin_name;
	$login_count	 = $session_admin_name;
	$last_login_time = $session_admin_name;
	$last_login_ip	 = $session_admin_name;
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
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="28">
				<td background="images/title_bg1.jpg">当前位置: 管理中心</td>
			</tr>
			<tr>
				<td bgcolor="#B1CEEF" height="1"></td>
			</tr>
			<tr height="20">
				<td background="images/shadow_bg.jpg"></td>
			</tr>
		</table>
		<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="100">
				<td align="center" width="100"><img src="images/admin_p.gif" width="90" height="100"></td>
				<td width="60">&nbsp;</td>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100">
						<tr>
							<td>当前时间：<?=date("Y-m-d H:m:s")?></td>
						</tr>
						<tr>
							<td style="font-size:16px;font-weight:bold;"><?=$session_admin_name?></td>
						</tr>
						<tr>
							<td>欢迎进入网站管理中心！</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" height="10"></td>
			</tr>
		</table>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="20">
				<td></td>
			</tr>
			<tr height="22">
				<td background="images/title_bg2.jpg" align="center" style="PADDING-LEFT:20px;FONT-WEIGHT:bold;COLOR:#ffffff">您的相关信息</td>
			</tr>
			<tr height="12" bgcolor="#ECF4FC">
				<td></td>
			</tr>
			<tr height="20">
				<td></td>
			</tr>
		</table>
		<table width="95%" border="0" cellspacing="0" cellpadding="2" align="center">
			<tr>
				<td width="100" align="right">登陆帐号：</td>
				<td style="color:#880000;"><?=$session_admin_name?></td>
			</tr>
			<tr>
				<td align="right">真实姓名：</td>
				<td style="color:#880000;"><?=$realname?></td>
			</tr>
			<tr>
				<td align="right">登陆次数：</td>
				<td style="color:#880000;"><?=$login_count?></td>
			</tr>
			<tr>
				<td align="right">上线时间：</td>
				<td style="color:#880000;"><?=$last_login_time?></td>
			</tr>
			<tr>
				<td align="right">IP地址：</td>
				<td style="color:#880000;"><?=$last_login_ip?></td>
			</tr>
		</table>
	</body>
</html>
