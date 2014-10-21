<?php
ob_start();											//使用缓冲
//装载必须的文件
require("../config.php");
require("onlyDB.php");
require("onlyException.php");
require("page.php");
require("functions.php");

header("Content-Type:text/html;charset=utf-8;");	//指定字符集
//输入检查
function process_variables(&$val, $key)
{
	if (is_array($val))
	{
		foreach ($val as $k => $v)
		{
			process_variables($v, $k);
		}
	}
	else
	{
		$val = addslashes($val);
	}
}
if (!get_magic_quotes_gpc())
{
	array_walk($_GET, "process_variables");
	array_walk($_POST, "process_variables");
	array_walk($_FILES, "process_variables");
	array_walk($_COOKIE, "process_variables");
	if (is_array(@$_SESSION))
		array_walk($_SESSION, "process_variables");
}
//建立数据库连接对象
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);
?>