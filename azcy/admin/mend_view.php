<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MEND) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
if ($id < 1)
{
	info("参数有误！");
}


$listUrl = "mend_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


$sql = "select id, sortnum, address, name, address1, phone, email, content, ip, create_time, state from mend where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$sortnum		= $row["sortnum"];
	$address		= $row["address"];
	$name			= $row["name"];
	$address1		= $row["address1"];
	$phone			= $row["phone"];
	$email			= $row["email"];
	$content		= $row["content"];
	$ip				= $row["ip"];
	$create_time	= $row["create_time"];
	$state			= $row["state"];

	if ($state == 0)
	{
		$sql = "update mend set state=1 where id=$id";
		$db->query($sql);

		$state = 1;
	}
}
else
{
	$db->close();
	info("指定的记录不存在！");
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
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 在线报修</td>
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
			<form name="form1" action="" method="post">
                <tr class="editTr">
                    <td class="editLeftTd">序号</td>
                    <td class="editRightTd"><?=$sortnum?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">姓名</td>
                    <td class="editRightTd"><?=$name?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">所在地</td>
                    <td class="editRightTd"><?=$address?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">详细地址</td>
                    <td class="editRightTd"><?=$address1?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">联系电话</td>
                    <td class="editRightTd"><?=$phone?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">电子邮箱</td>
                    <td class="editRightTd"><?=$email?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">问题内容</td>
                    <td class="editRightTd"><?=$content?></td>
                </tr
                <tr class="editTr">
                    <td class="editLeftTd">留言IP</td>
                    <td class="editRightTd"><?=$ip?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">留言时间</td>
                    <td class="editRightTd"><?=$create_time?></td>
                </tr>
			</form>
		</table>
		<?
        $db->close();
		?>
	</body>
</html>
