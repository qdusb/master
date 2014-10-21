<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}

$class_id	= $_GET["class_id"];
$page = (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;


$listUrl = "transfer.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$action		= trim($_POST["action"]);
	$id_array	= $_POST["ids"];
	$mude_class	=$_POST["mude_class"];
	if ($action == "transfer")
	{
		$sql = "update info set class_id='$mude_class' where id in (" . implode(",", $id_array) . ") and class_id='$class_id'";
		$rst = $db->query($sql);
		$db->fetch_array($rst);
	}
	else if($action == "copy")
	{
		foreach($id_array as $val)
		{
			$sql="select * from info where id=$val";
			$rst = $db->query($sql);
			if($row=$db->fetch_array($rst))
			{
				$aid			= $db->getMax("info", "id") + 1;
				$sortnum		= $db->getMax("info", "sortnum","class_id=$mude_class") + 10;
				$title			= $row["title"];
				$title2			= $row["title2"];
				$author			= $row["author"];
				$source			= $row["source"];
				$website		= $row["website"];
				$pic			= $row["pic"];
				$annex			= $row["annex"];
				$keyword		= $row["keyword"];
				$intro			= $row["intro"];
				$content		= $row["content"];
				$files			= $row["files"];
				$state			= $row["state"];
				$share			= $row["share"];
				$create_time	= $row["create_time"];
				$modify_time	= $row["modify_time"];
				$series			= $row["series"];
				$classcol		= $row["classcol"];
				$level			= $row["level"];
				
				$sql2	= "insert into info(id, sortnum, title, title2, admin_id, class_id, author, source, website, pic, annex, keyword, intro, content, files, views, create_time, modify_time, state, share, series, classcol,level) values($aid, $sortnum, '$title', '$title2', $session_admin_id, '$mude_class', '$author', '$source', '$website', '$pic', '$annex', '$keyword', '$intro', '$content', '$files', 0, '$create_time', '$modify_time', $state, $share, '$series', '$classcol',$level)";
				$db->query($sql2);
			}
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 信息批量转移</td>
			</tr>
		</table>
		<form name="form1" action="" method="post">
	   <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td width="8%" height="5"></td>
				<td width="8%"></td>
				<td width="8%"></td>
				 <td></td>
			</tr>
			
			<tr>
				<td height="30" align="left">　<a href="<?=$listUrl."?class_id=".$class_id."&mude_class=".$mude_class?>">[刷新列表]</a></td>
				<td align="center"><a href="javascript:reverseCheck(document.form1.ids);">[反向选择]</a></td>
				<td>　　<a href="javascript:document.form1.action.value = 'transfer';document.form1.submit();">[开始转移]</a>&nbsp;</td>
				<td>　　<a href="javascript:document.form1.action.value = 'copy';document.form1.submit();">[开始复制]</a>&nbsp;</td>
				 <td>&nbsp;</td>
			</tr>
		</table>
		
		   <input type="hidden" name="action" value=""/>
		
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
				<tr class="listHeaderTr">
					<td width="8%">选择栏目：</td>
					<td  colspan="3" align="left">
						<select name="info_class" style="width:25%;" onChange="window.location='transfer.php?class_id='+this.options[this.selectedIndex].value">
						<?
						$sql = "select id, name from info_class order by id asc";
						$rst = $db->query($sql);
						while ($row = $db->fetch_array($rst))
						{
							$id_str=(string)$row["id"];
							$class_name=$row['name'];
							if(strlen($id_str)==9)
							{
								$lab="————|";
							}
							else if(strlen($id_str)==6)
							{
								$lab="——|";
							}
							else if(strlen($id_str)==3)
							{
								$lab="";
							}
							if($row['id']==$class_id)
							{
								 echo "<option value='" . $id_str . "' selected >$lab".$row['name'];
							}
							else
							{
								echo "<option value='" . $id_str . "' >$lab".$row['name'];
							}
						   
						}
						?>
					</select>
					</td>
					</tr>
					<tr class="listHeaderTr">
					<td width="8%">目标栏目：</td>
					<td  colspan="3"  align="left">
						<select name="mude_class" style="width:25%;">
						<?
						$sql = "select id, name from info_class order by id asc";
						$rst = $db->query($sql);
						while ($row = $db->fetch_array($rst))
						{
							$id_str=(string)$row["id"];
							$class_name=$row['name'];
							if(strlen($id_str)==9)
							{
								$lab="————|";
							}
							else if(strlen($id_str)==6)
							{
								$lab="——|";
							}
							else if(strlen($id_str)==3)
							{
								$lab="";
							}
							
						  if($row['id']==$mude_class)
							{
								 echo "<option value='" . $id_str . "' selected >$lab".$row['name'];
							}
							else
							{
								echo "<option value='" . $id_str . "' >$lab".$row['name'];
							}
						}
						?>
					</select>
					</td>
				</tr>
				<tr class="listHeaderTr">
					<td width="3%"></td>
					<td width="8%">序号</td>
					<td width="50%">标题</td>
					<td>发布时间</td>
				</tr>
			   <?
					$sql = "select * from info where class_id='$class_id' order by sortnum desc";
					$rst = $db->query($sql);
					while ($row = $db->fetch_array($rst))
					{
				?>
				<tr class="listFooterTr">
				 <td><input type="checkbox" id="ids" name="ids[]" checked value="<?=$row["id"]?>"></td>
					<td><?=$row['id']?></td>
					<td><?=$row['title']?></td>
					<td><?=$row['create_time']?></td>
				</tr>
				<?
					}
				?>
		 
		</table>
		</form> 
		<?
		$db->close();
		?>
	</body>
</html>
