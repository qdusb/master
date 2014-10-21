<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, LINK_ADVANCEDID) == false)
{
	info("没有权限！");
}

$id				= (int)$_GET["id"];

//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);



$listUrl = "web_list.php";
$editUrl = "web_edit.php?id=$id";

//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum	= (int)$_POST["sortnum"];
	$name 		= htmlspecialchars(trim($_POST["name"]));
	$url		=$_POST["url"];

	if ($id < 1)
	{
		//$sortnum = $db->getMax("depart", "sortnum") + 10;
		$sql = "insert into webshop(id, sortnum, name, url) values(" . ($db->getMax("webshop", "id") + 1) . ",  $sortnum, '$name', '$url')";
	}
	else
	{
		$sql = "update webshop set sortnum=$sortnum, name='$name', url='$url' where id=$id";
	}
	$rst = $db->query($sql);
	$db->close();

	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
}


if ($id < 1)
{
	$sortnum	 = $db->getMax("webshop", "sortnum") + 10;
}
else
{
	$sql = "select * from webshop where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$url			= $row["url"];
	}
	else
	{
		$db->close();
		info("指定的记录不存在！");
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
		<script src="js/My97DatePicker/WdatePicker.js"></script>
		<script type="text/javascript">
		
			function check(form)
			{
				if (form.sortnum.value.match(/\D/))
				{
					alert("请输入合法的序号！");
					form.sortnum.focus();
					return false;
				}

				if(form.name.value == "")
				{
					alert("请填入标题名称!");
					form.name.focus();
					return false;
				}


				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; <?echo $class_name?> -&gt; 新增/编辑</td>
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
					<td class="editHeaderTd" colSpan="2">修改资料</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">排列序号</td>
					<td class="editRightTd">
						<input type="text" name="sortnum" value="<?=$sortnum?>" maxlength="10" size="5">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">网站名</td>
					<td class="editRightTd"><input type="text" value="<?=$name?>" name="name" maxlength="100" size="50"></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">网站地址</td>
					<td class="editRightTd"><input type="text" value="<?=$url?>" name="url" maxlength="100" size="50"></textarea></td>
				</tr>
				   
			  
				<tr class="editFooterTr">
					<td class="editFooterTd" colSpan="2">
						<input type="submit" value=" 确 定 ">
						<input type="reset" value=" 重 填 ">
					</td>
				</tr>
			</form>
		</table>
		<script type="text/javascript">document.form1.title.focus();</script>
		<?
		$db->close();
		?>
	</body>
</html>
