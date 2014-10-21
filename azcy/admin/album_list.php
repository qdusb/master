<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}
$page = (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$class_id=	(string)$_GET["class_id"];
if(empty($class_id))
{
	info("参数错误！");
}
$listUrl = "album_list.php?page=$page&class_id=$class_id";
$editUrl = "album_edit.php?page=$page&class_id=$class_id";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	
	$id_array = $_POST["ids"];
	if (is_array($id_array))
	{
		$pic ="";
		$sql = "select pic from album where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	.= $row["pic"] . ",";
		}

		$db->query("begin");
		$sql = "delete from album where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
	
		if ($rst)
		{
			deleteFiles($pic, 1);
			$db->query("commit");
			$db->close();
			header("Location: album_list.php?page=$page&class_id=$class_id");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("删除失败！");
		}
	}
	if(isset($_POST["class_id"]))
	{
		$class_id= $_POST["class_id"];
		
		if($class_id!="102")
		{
			$page=1;
		}
		$listUrl = "album_list.php?page=$page&class_id=$class_id";
		header("Location: $listUrl");
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 会员中心</td>
			</tr>
		</table>
		 <script type="text/javascript" language="javascript">
			function check(obj)
			{
				var   myid   =   document.getElementById("tryselect"); 
				var   val    = myid.options[myid.selectedIndex].text;
				var table = document.getElementById("tr_list"); 
				for(var i=0;i<table.rows.length;i++)
				{
					table.rows[i].style.display="table-row";
					/*for(var j=0;j<table.rows[i].cells.length;j++)
					{
					  // alert(table.rows[i].cells[j].innerHTML);
					}
					*/
					if(table.rows[i].title!=""&&table.rows[i].title!=val)
					{
						table.rows[i].style.display="none"
					}
					
					
				}
   
			}
		</script>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
					<a href="album_class_list.php">[返回]</a>
					<a href="<?=$editUrl?>">[增加]</a>
					<a href="javascript:reverseCheck(document.form1.ids);">[反向选择]</a>&nbsp;
					<a href="javascript:if(delCheck(document.form1.ids)) {document.form1.submit();}">[删除]</a>&nbsp;
					
				  
				</td>
				<td align="right">
					<?
					//设置每页数
				   $page_size = DEFAULT_PAGE_SIZE;
					//总记录数
					
				
					$sql = "select count(*) as cnt from album where class_id=$class_id";
					
					$rst			= $db->query($sql);
					$row			= $db->fetch_array($rst);
					$record_count	= $row["cnt"];
					$page_count		= ceil($record_count / $page_size);
					//分页
					$page_str		= page($page, $page_count);
					echo $page_str
					?>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable" id="tr_list">
			<form name="form1" action="" method="post">
			<input type="hidden" name="action" value="">
			<input type="hidden" name="class_id" value="">
				<tr class="listHeaderTr">
					<td width="3%"></td>
					<td width="8%">序号</td>
					<td>排序</td>
					<td>图片名称</td>
					<td>图片</td>
					<td>创建时间</td>
				</tr>
				<?
				
				$sql = "select * from album  where class_id=$class_id order by sortnum asc";
				$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
				$rst = $db->query($sql);
				while ($row = $db->fetch_array($rst))
				{
					$sql1 = "select id,name,pic from info_class where id =".$row["class_id"];
					$rst1 = $db->query($sql1);
					$info = $db->fetch_array($rst1);
					
					$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
				?>
					<tr class="<?=$css?>" title=<?=$info['name']?>>
						<td><input type="checkbox" id="ids" name="ids[]" value="<?=$row["id"]?>"></td>
						<td><?=$row["id"]?></td>
						<td><?=$row["sortnum"]?></td>
						
			 
						 <?
						 echo "<td><a href='".$editUrl."&id=$row[id]'>".$row['name']."</a></td>";
							if(trim($row["pic"])=="")
							{
								echo "<td>无</td>";
							}
							else
							{
								echo "<td><a href=".$row['pic'].">图片</a></td>";
							}
						?>
						<td><?=$row["create_time"]?></td>
						</tr>

				<?
				}
				?>
				<tr class="listFooterTr">
					<td colspan="6"><?=$page_str?></td>
				</tr>
			</form>
		</table>
	</body>
</html>
