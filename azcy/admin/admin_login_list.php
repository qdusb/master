<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;


$listUrl = "admin_login_list.php?page=$page&id=$id";
$backUrl = "admin_list.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


$sql = "select name, realname, login_count from admin where id=$id";
$rst = $db->query($sql);
if ($row = $db->fetch_array($rst))
{
	$name			= $row["name"];
	$realname		= $row["realname"];
	$login_count	= $row["login_count"];
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
				<td class="position">当前位置: 管理中心 -&gt; 管理员登录日志</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
                	<a href="<?=$backUrl?>">[返回]</a>
					<a href="<?=$listUrl?>">[刷新列表]</a>
				</td>
                <td align="right">登录帐号：<?=$name?> 真实姓名：<?=$realname?> 登录次数：<?=$login_count?></td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<tr class="listHeaderTr">
				<td width="8%"></td>
				<td>登录IP</td>
				<td>登录时间</td>
			</tr>
			<?
			//设置每页数
			$page_size   = DEFAULT_PAGE_SIZE;
			//总记录数
			$sql = "select count(*) as cnt from admin_login where admin_id=$id";
			$rst = $db->query($sql);
			$row = $db->fetch_array($rst);
			$record_count = $row["cnt"];
			$page_count = ceil($record_count / $page_size);
			//分页
			$page_str = page($page, $page_count, $pageUrl);
			
			$sql = "select admin_id, login_time, login_ip from admin_login where admin_id=$id order by login_time desc";
			$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
			$rst = $db->query($sql);
			$i = 1;
			while ($row = $db->fetch_array($rst))
			{
				$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
			?>
				<tr class="<?=$css?>">
					<td><?=$i?></td>
					<td><?=$row["login_ip"]?></td>
					<td><?=$row["login_time"]?></td>
				</tr>
			<?
				$i++;
			}
			?>
            <tr class="listFooterTr">
                <td colspan="10"><?=$page_str?></td>
            </tr>
		</table>
		<?
        $db->close();
		?>
	</body>
</html>
