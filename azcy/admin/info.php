<?
$msg = str_replace("\n", "<br>", htmlspecialchars($_GET["msg"]));
$url = $_GET["url"]
?>


<html>
	<head>
		<title>提示信息</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="-1000">
		<link href="images/admin.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<table width="100%" height="60%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td>
					<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr height="30" bgcolor="#EBF4FD">
							<td style="font-weight:bold;padding-left:10px;">提示信息</td>
						</tr>
						<tr height="80">
							<td style="padding-left:20px;"><?=$msg?></td>
						</tr>
						<tr height="30" bgcolor="#EBF4FD" align="center">
							<td style="font-weight:bold;"><a href="<?=$url?>">请点击这里继续</a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
