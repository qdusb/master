<?
	require(dirname(__FILE__) . "/init.php");
	//连接数据库
	$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
	
	$sql = "select content,id from info";
	$rst = $db->query($sql);

	while($row = $db->fetch_array($rst))
	{
		$content	=$row['content'];
		$content2	=preg_replace("/\/azcy/","",$content);
		$content2	=preg_replace("/php\.ibw\.cn/",$_SERVER['HTTP_HOST'],$content2);
		$sql1 = "update info set content='".$content2."' where id=".$row['id'];
		$rst1 = $db->query($sql1);
		echo $content2;
	}
	$db->close();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
</head>
<body>
修改成功
</body>
</html>