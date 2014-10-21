<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id = (int)$_GET["id"];


$listUrl = "advanced_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//提交表单
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum		= (int)$_POST["sortnum"];
	$state			= (int)$_POST["state"];
	$name			= htmlspecialchars(trim($_POST["name"]));
	$default_file	= htmlspecialchars(trim($_POST["default_file"]));
	
	if (empty($name) || empty($default_file))
	{
		$db->close();
		info("填写的参数不完整！");
	}
	
	if ($id < 1)
	{
		$sql = "insert into advanced(id, sortnum, name, default_file, state) values(" . ($db->getMax("advanced", "id", "") + 1) . ", $sortnum, '$name', '$default_file', $state)";
	}
	else
	{
		$sql = "update advanced set sortnum=$sortnum, name='$name', default_file='$default_file', state=$state where id=$id";
	}
	
	$rst = $db->query($sql);
	$db->close();
	
	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
	else
	{
		info("添加/编辑失败！");
	}
}

if ($id < 1)
{
	$sortnum = $db->getMax("advanced", "sortnum", "") + 10;
	$state	 = 1;
}
else
{
	$sql = "select sortnum, name, default_file, state from advanced where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$sortnum		= $row["sortnum"];
		$state			= $row["state"];
		$name			= $row["name"];
		$default_file	= $row["default_file"];
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
		<script type="text/javascript">
		function check(form)
		{
			if (form.sortnum.value.match(/\D/))
			{
				alert("请输入合法的序号！");
				form.sortnum.focus();
				return false;
			}

			if (form.name.value == "")
			{
				alert("功能名称不能为空！");
				form.name.focus();
				return false;
			}

			if (form.default_file.value == "")
			{
				alert("默认文件不能为空！");
				form.default_file.focus();
				return false;
			}
			
			return true;
		}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position"> 
				<td class="position">当前位置: 管理中心 -&gt; 隐藏管理 -&gt; 高级功能管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>&nbsp;
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
            <form name="form1" action="" method="post" onSubmit="return check(this);">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">高级功能管理</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd">
						<input type="text" name="sortnum" value="<?=$sortnum?>" size="10" maxlength="4">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">状态</td>
					<td class="editRightTd">
						<input type="radio" name="state" value="1"<? if ($state == 1) echo " checked"?>> 显示
						<input type="radio" name="state" value="0"<? if ($state == 0) echo " checked"?>> 不显示
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">功能名称</td>
					<td class="editRightTd">
						<input type="text" name="name" value="<?=$name?>" size="50" maxlength="50">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">首页文件</td>
					<td class="editRightTd">
						<input type="text" name="default_file" value="<?=$default_file?>" size="50" maxlength="50">
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">说明</td>
					<td class="editRightTd">
						当新增了新功能，必须在config.php文件中新增自定义常量的值，否则无法正确判断权限。
					</td>
				</tr>
				<tr class="editFooterTr">
					<td class="editFooterTd" colSpan="2">
						<input type="submit" value=" 确 定 ">
						<input type="reset" value=" 重 填 ">
					</td>
				</tr>
			</form>
		</table>
		<script type="text/javascript">document.form1.name.focus();</script>
		<?
        $db->close();
		?>
	</body>
</html>
