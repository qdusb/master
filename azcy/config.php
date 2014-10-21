<?php
define("DEBUG", true);
error_reporting(1);

//数据库连接
$config["db_host"] = 'localhost';
$config["db_user"] = "root";
$config["db_pass"] = "";
$config["db_name"] = 'azcy';

//系统配置
define("DEFAULT_PAGE_SIZE", 18);					//	默认分页时每页的记录数(对后台)
define("MAX_IMAGE_SIZE", 1024 * 1024 * 40);				//	图片最大 20M
define("UPLOAD_PATH_FOR_ADMIN", "../upload/");		//	文件上传路径(对后台)

return $array = array(
	'URL_MODEL' 	=> 2,
	//'URL_PATHINFO_DEPR'=>'-',
	//'URL_CASE_INSENSITIVE'=>1,
	'DB_TYPE'		=>	'mysql',
	'DB_HOST'		=>	"localhost",
	'DB_NAME'		=>	$config["db_name"],
	'DB_USER'		=>	$config["db_user"],
	'DB_PWD'		=>	$config["db_pass"],
	'DB_PREFIX'		=>	'',
	'LOG_RECORD'	=> false,
	'LOG_TYPE' 		=> 3, 
	'URL_HTML_SUFFIX'       => '',
	/*上线后这里的地址要做改动*/
	'UPLOAD_PATH'	=>	"http://".$_SERVER['HTTP_HOST']."/azcy/upload/",
);
?>
