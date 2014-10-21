<?
require("init.php");
$class_id=$_GET['id'];
$listUrl="batch_upload_list.php?class_id=$class_id";
$path='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path=dirname($path);
$path=$path."/../include/batch_upload.php";
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
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 图片批量上传</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>
				</td>
			</tr>
		</table>

		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
			<form name="form1" action="" method="post" enctype="multipart/form-data" onSubmit="return check(this);">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">图片批量上传</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">开始上传</td>
					<td class="editRightTd">
						<embed width="740" height="400" align="left" type="application/x-shockwave-flash" salign="lt" allowscriptaccess="sameDomain" allowfullscreen="true" menu="true" name="flash" bgcolor="#FFFFFF" devicefont="false" wmode="transparent" scale="noscale" loop="true" play="true" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="images/batch_upload.swf" flashvars="url=<?=$path?>&id=<?=$class_id?>">
					</td>
				</tr>

			</form>
		</table>
	</body>
</html>
