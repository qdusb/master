<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, JOB_APPLY_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "job_apply_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

if ($id != "")
{
	$sql = "select ja.id, ja.name, ja.sortnum, ja.sex, ja.age, ja.major, ja.graduate_time, ja.college, ja.phone, ja.email, ja.resumes, ja.appraise, ja.create_time, ja.state, j.name as job_name from job_apply ja inner join job j on ja.job_id=j.id where ja.id='$id'";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$id				= $row["id"];
		$sortnum		= $row["sortnum"];
		$name			= $row["name"];
		$job_name		= $row["job_name"];
		$sex			= $row["sex"];
		$age			= $row["age"];
		$major			= $row["major"];
		$graduate_time	= $row["graduate_time"];
		$college		= $row["college"];
		$phone			= $row["phone"];
		$email			= $row["email"];
		$resumes		= $row["resumes"];
		$appraise		= $row["appraise"];
		$create_time	= $row["create_time"];
		$state			= $row["state"];

		if ($state == 0)
		{
			$sql = "update job_apply set state=1 where id=$id";
			$db->query($sql);
			$state = 1;
		}
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
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 应聘人员</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>&nbsp;
				</td>
			</tr>
		</table>
		<form name="form1" action="" method="post">
			<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">招聘职位</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd"><?=$sortnum?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">应聘职位</td>
					<td class="editRightTd"><?=$job_name?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">姓名</td>
					<td class="editRightTd"><?=$name?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">性别</td>
					<td class="editRightTd"><?=$row["sex"]?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">年龄</td>
					<td class="editRightTd"><?=$age?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">所学专业</td>
					<td class="editRightTd"><?=$major?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">毕业时间</td>
					<td class="editRightTd"><?=$graduate_time?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">毕业院校</td>
					<td class="editRightTd"><?=$college?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">电话</td>
					<td class="editRightTd"><?=$phone?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">邮箱</td>
					<td class="editRightTd"><?=$email?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">个人履历</td>
					<td class="editRightTd"><?=$resumes?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">自我评价</td>
					<td class="editRightTd"><?=$appraise?></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">应聘时间</td>
					<td class="editRightTd"><?=$create_time?></td>
				</tr>
			</table>
		</form>
		<?
		$db->close();
		?>
	</body>
</html>
