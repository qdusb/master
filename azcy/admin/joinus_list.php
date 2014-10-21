<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, JOINUS) == false)
{
	info("没有权限！");
}


$id		= trim($_GET["id"]);
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;


$listUrl = "joinus_list.php?page=$page";
$viewUrl = "joinus_view.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($id != "")
{
	$sql = "delete from joinus where id='$id'";
	$rst = $db->query($sql);
	$db->close();
	if ($rst)
	{
		header("Location: $listUrl");
		exit;
	}
	else
	{
		info("删除信息失败！");
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 在线加盟</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
            <form name="form1" action="" method="post">
                <tr class="listHeaderTr">
                    <td>序号</td>
                    <td>公司名</td>
                    <td>姓名</td>
                    <td>职位</td>
                    <td>性别</td>
                    <td>电话/传真</td>
                    <td>电子邮箱 </td>
                    <td>申请时间 </td>
                    <td>状态</td>
                    <td>删除</td>
                </tr>
                <?
				$page_size = DEFAULT_PAGE_SIZE;
				//总记录数
				$sql = "select count(*) as cnt from joinus";
				$rst = $db->query($sql);
				$row = $db->fetch_array($rst);
				$record_count = $row["cnt"];
				$page_count = ceil($record_count / $page_size);
				//分页
				$page_str		= page($page, $page_count);
				//列表
                $sql = "select * from joinus order by sortnum desc";
                $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
                $rst = $db->query($sql);
                while ($row = $db->fetch_array($rst))
                {
                    $css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
                ?>
                    <tr class="<?=$css?>">
                        <td><?=$row["sortnum"]?></td>
                        <td><?=$row["company"]?></td>
                        <td><a href="<?=$viewUrl?>&id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
                        <td><?=$row["job_post"]?></td>
                        <td><?=$row["sex"]?></td>
                        <td><?=$row["phone"]?></td>
                        <td><?=$row["email"]?></td>
                        <td><?=formatDate("Y-m-d", $row["create_time"])?></td>
                        <td>
                            <?
                            switch ($row["state"])
                            {
							case 0:
								echo "<font color='#FF6600'>未查看</font>";
								break;
							case 1:
								echo "<font color='#0066FF'>已查看</font>";
								break;
							default:
								echo "<font color='#FF0000'>错误</font>";
								break;
                            }
                            ?>
						<td><a href="<?=$listUrl?>&id=<?=$row["id"]?>" onClick="return del();">删除</a></td>
                    </tr>
                <?
                }
                ?>
                <tr class="listFooterTr">
                    <td colspan="10"><?=$page_str?></td>
                </tr>
            </form>
		</table>
		<?
        $db->close();
        ?>
	</body>
</html>