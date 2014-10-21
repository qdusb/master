<?
require(dirname(__FILE__) . "/isadmin.php");
?>


<html>
	<head>
		<title>管理中心</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="-1000">
		<link href="images/admin.css" rel="stylesheet" type="text/css">
	</head>
	<frameset rows="60, *" border="0" frameborder="0" framespacing="0">
		<frame name="header" src="header.php" frameborder="0" scrolling="no" noresize>
		<frameset cols="170, *">
			<frame name="menu" src="menu.php" frameborder="0" scrolling="yes" noresize>
			<frame name="main" src="main.php" frameborder="0" scrolling="yes" noresize>
		</frameset>
	</frameset><noframes></noframes>
</html>
