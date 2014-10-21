<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, SURVEY) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
if ($id < 1)
{
	info("参数有误！");
}


$listUrl = "survey_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


$sql = "select id, sortnum, address, address1, name, sex, phone, email, zuqu, buy, shouru, liaojie, wangshang, xuangou, shuangshu, shihou, leixing, zhushi, kongjiang, caizhi, ip, create_time, state from survey where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$sortnum		= $row["sortnum"];
	$address		= $row["address"];
	$address1		= $row["address1"];
	$name			= $row["name"];
	$sex			= $row["sex"];
	$phone			= $row["phone"];
	$email			= $row["email"];
	
	$zuqu			= $row["zuqu"];
	$buy			= $row["buy"];
	$shouru			= $row["shouru"];
	$liaojie		= $row["liaojie"];
	$wangshang		= $row["wangshang"];
	$xuangou		= $row["xuangou"];
	$shuangshu		= $row["shuangshu"];
	$shihou			= $row["shihou"];
	$leixing		= $row["leixing"];
	$zhushi			= $row["zhushi"];
	$kongjiang		= $row["kongjiang"];
	$caizhi			= $row["caizhi"];
	$ip				= $row["ip"];
	$create_time	= $row["create_time"];
	$state			= $row["state"];

	if ($state == 0)
	{
		$sql = "update survey set state=1 where id=$id";
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 在线调查</td>
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
					<td class="editLeftTd">性别</td>
					<td class="editRightTd"><?=$sex?></td>
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
					<td class="editLeftTd">1、目前，您属于哪个群族？</td>
					<td class="editRightTd"><?=$zuqu?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">2、是否购买过客来福家具？</td>
					<td class="editRightTd"><?=$buy?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">3、您目前的月收入如何？</td>
					<td class="editRightTd"><?=$shouru?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">4、您是通过什么渠道了解客来福？</td>
					<td class="editRightTd"><?=$liaojie?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">5、您在网上获取客来福衣柜、移门相关信息的主要渠道是什么？</td>
					<td class="editRightTd"><?=$wangshang?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">6、您选购衣柜、移门喜欢通过哪些渠道获取产品信息？</td>
					<td class="editRightTd"><?=$xuangou?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">7、购买家具谁说的话算数儿？</td>
					<td class="editRightTd"><?=$shuangshu?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">8、您会在什么时候购买衣柜、移门呢？</td>
					<td class="editRightTd"><?=$shihou?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">9、您喜欢哪种类型的衣柜、移门？</td>
					<td class="editRightTd"><?=$leixing?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">10、在选购衣柜、移门时，您最注视的问题是？</td>
					<td class="editRightTd"><?=$zhushi?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">11、喜欢布置家里的哪个空间？</td>
					<td class="editRightTd"><?=$kongjiang?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">12、喜欢什么材质的家具？</td>
					<td class="editRightTd"><?=$caizhi?></td>
				</tr>
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
