<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");


$id		= (int)$_GET["id"];
$table	= trim($_GET["table"]);
$action	= trim($_GET["action"]);
$file	= trim($_GET["file"]);
if ($id < 1)
{
	echo "<script type='text/javascript'>window.close();</script>";
	exit;
}

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($action == "delete" && $file != "")
{
	deleteFile($file, 2);
	header("Location: ?table=$table&id=$id");
	exit();
}

$sql = "select files from $table where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$files = $row["files"];
	$db->close();
}
else
{
	$db->close();
	info("指定的记录不存在！");
}
?>


<html>
	<head>
		<title>管理图片</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="-1000">
		<link href="images/admin.css" rel="stylesheet" type="text/css">
        <style type="text/css">
			#img
			{
				background-color:#FFFFFF;
				border:3px solid #B1CEEE;
			}
			#img .i1
			{
				font-size:18px;
				height:30px;
				line-height:30px;
				padding-left:12px;
				background-color:#B1CEEE;
			}
			#img ul
			{
				margin:12px 12px;
				padding:0 0;
			}
			#img ul li
			{
				list-style-type:none;
				border:1px solid #B1CEEEE;
				margin:3px 0;
			}
		</style>
		<script type="text/javascript" src="images/common.js"></script>
	</head>
	<body>
    	<div id="img">
        	<div class="i1">图片管理</div>
            <div>
                <ul>
                    <?
                    if (trim($files) != "")
                    {
                        $files_array = explode(",", $files);
                        foreach($files_array as $value)
                        {
                            if (file_exists($_SERVER["DOCUMENT_ROOT"] . $value))
                            {
                    ?>
                                <li><span style="width:700px;"><a href="<?=$value?>" target="_blank"><img src="<?=$value?>" onload="javascript:if (this.width > 600) {this.width=600;}" border="0" /></a></span><span><a href="?table=<?=$table?>&id=<?=$id?>&action=delete&file=<?=$value?>">删除</a></span></li>
                    <?
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="i1"></div>
        </div>
    </body>
</html>
