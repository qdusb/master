<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, JOINUS) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
if ($id < 1)
{
	info("参数有误！");
}


$listUrl = "joinus_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

$sql = "select * from joinus where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$sortnum		= $row["sortnum"];
	$name			= $row["name"];
	$sex			= $row["sex"];
	$edu			= $row["eduction"];
	$birth			= $row["birthday"];
	$address		= $row["address"];
	$tel			= $row["phone"];
	$email			= $row["email"];
	$company		= $row["company"];
	$address		= $row["address"];
	$job_post		= $row["job_post"];
	$job_intention	= $row["job_intention"];
	$zip_code		= $row["zip_code"];
	$tribe			= $row["tribe"];
	
	$create_time	= $row["create_time"];
	$state			= $row["state"];

	if ($state == 0)
	{
		$sql = "update joinus set state=1 where id=$id";
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 在线加盟</td>
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
				<tr class="editHeaderTr">
                    <td class="editHeaderTd" colSpan="2">一、申请人资料</td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">序号</td>
                    <td class="editRightTd"><?=$sortnum?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">姓名</td>
                    <td class="editRightTd"><?=$name?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">性别</td>
                    <td class="editRightTd"><?=$sex?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">学历</td>
                    <td class="editRightTd"><?=$edu?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">出生年月</td>
                    <td class="editRightTd"><?=$birth?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">电话/传真</td>
                    <td class="editRightTd"><?=$tel?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">电子邮箱</td>
                    <td class="editRightTd"><?=$email?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">担任职位</td>
                    <td class="editRightTd"><?=$job_post?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">民族</td>
                    <td class="editRightTd"><?=$tribe?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">邮编</td>
                    <td class="editRightTd"><?=$zip_code?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">公司名称</td>
                    <td class="editRightTd"><?=$company?></td>
                </tr>

                <tr class="editTr">
                    <td class="editLeftTd">公司地址</td>
                    <td class="editRightTd"><?=$address?></td>
                </tr>
                <tr class="editTr">
                    <td class="editLeftTd">申请职位意向</td>
                    <td class="editRightTd"><?=$job_intention?></td>
                </tr>
               
                <tr class="editTr">
                    <td class="editLeftTd">申请时间</td>
                    <td class="editRightTd"><?=$create_time?></td>
                </tr>
			</form>
		</table>
		<?
        $db->close();
		?>
	</body>
</html>
