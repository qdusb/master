<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");
require(dirname(__FILE__) . "/uploadImg.php");
require "excel_class.php";

//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}


$page = (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;
$id=$_GET['id'];

$listUrl = "staff_list.php?page=$page";
$editUrl = "staff_edit.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);

//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

	$id_array = $_POST["ids"];
	$action		=$_POST['action'];
	if ($action == "upload")
	{
		$id_array = array($id_array);
		$annex_file	= &$_FILES["upload_file"];
		$annex		= uploadImg($annex_file, "pdf,doc,xls,ppt,rar,zip,flv");	//上传附件
		readExcel(UPLOAD_PATH_FOR_ADMIN.$annex,$db); 
	}
	else if ($action == "delete")
	{
		$sql = "select pic from staff where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	.= $row["pic"] . ",";
		}
		deleteFiles($pic, 1);
		
		$sql = "delete from staff where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		if ($rst)
		{
			$db->query("commit");
			$db->close();
			header("Location: $listUrl");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("删除失败！");
		}
	}
	else if ($action == "download")
	{
		
		$sql = "select * from staff order by sortnum asc";
		$rst = $db->query($sql);
		$date="<table border='1' bordercolor='#000000'>";
		$cnt=0;
		while ($row = $db->fetch_array($rst))
		{
			if($cnt%2==0)
			{
				$bg="bgcolor='#006666'";
			}
			else
			{
				$bg='';
			}
			$date.=("<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['sex']."</td><td>".$row['birthday']."</td><td>".$row['tel']."</td><td>".$row['depart']."</td></tr>");
			$cnt++;
		}
		$date.="</table>";
		Create_Excel_File("download_staff.xls",$date);
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
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 员工管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>&nbsp;
					<a href="<?=$editUrl?>">[增加]</a>&nbsp;
					<a href="javascript:reverseCheck(document.form1.ids);">[反向选择]</a>&nbsp;
					<a href="javascript:if(delCheck(document.form1.ids)) {document.form1.action.value = 'delete';document.form1.submit();}">[删除]</a>&nbsp;
				</td>
				<td align="right">
					<?
					//设置每页数
				   $page_size = DEFAULT_PAGE_SIZE;
					//总记录数
					
					$record_count	= $db->getCount("staff","");
					$page_count		= ceil($record_count / $page_size);
					//分页
					$page_str		= page($page, $page_count);
					echo $page_str
					?>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<form id="form1" name="form1" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="">
				<tr class="listHeaderTr">
					<td width="4%"></td>
					<td width="4%">序号</td>
					<td width="12%">姓名</td>
					<td width="10%">头像</td>
					<td width="10%">性别</td>
					<td width="20%">生日</td>
					<td width="20%">电话</td>
					<td width="20%">部门</td>
				</tr>
				<?
				$sql = "select * from staff order by sortnum asc";
				$sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
				$rst = $db->query($sql);
				while ($row = $db->fetch_array($rst))
				{
					
					$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
				?>
					
					<tr class="<?=$css?>">
						<td><input type="checkbox" id="ids" name="ids[]" value="<?=$row["id"]?>"></td>
						<td><?=$row["sortnum"]?></td>
						<td><a href="<?=$editUrl."?id=".$row["id"]?>"><?=$row["name"]?></a></td>
						<td>
						<?
						if(empty($row['pic']))
						{
							echo "无头像";
						}
						else
						{
							$imgSrc=UPLOAD_PATH_FOR_ADMIN.$row['pic'];
							echo "<a href='".$imgSrc."'><font color='#FF0000'>有头像</font></a>";
						}
						?>
						</td>
						<td><?=$row["sex"]?></td>
						<td><?=$row["birthday"]?></td>
						<td><?=$row["tel"]?></td>
						<td><?=$row["depart"]?></td>
				<?
				}
				?>
				<tr class="listFooterTr">
					<td colspan="10"><?=$page_str?></td>
				</tr>
				 <tr class="listFooterTr">
					<td colspan="2" align="center">导入员工表格：</td>
					<td colspan="5" align="left"><input name="upload_file" type="file" />&nbsp;&nbsp;&nbsp;&nbsp;
				   
				   <a href="javascript:document.form1.action.value = 'upload';document.form1.submit();">导入文件</a></td>
					<td colspan="1" align="center"><a href="images/staff.xls">员工模板下载&nbsp;</a>&nbsp;&nbsp;
				   <a href="javascript:document.form1.action.value = 'download';document.form1.submit();">员工数据导出</a>&nbsp;&nbsp;</td>
				</tr>
			  
			  
			</form>
		</table>
		<?
		function readExcel($file,$db)
		{ 	
			Read_Excel_File($file,$return);
			for ($i=1;$i<count($return[Sheet1]);$i++) 
			{
				$id 		= $db->getMax("staff","id")+1;
				$sortnum	= $db->getMax("staff","sortnum")+10;
				
				$name		= (string)$return[Sheet1][$i][0];
				$sex		= (string)$return[Sheet1][$i][1]; 
				$depart		= (string)$return[Sheet1][$i][2];
				$tel		= $return[Sheet1][$i][3];
				$birthday	= $return[Sheet1][$i][4];
				$sql = "insert into staff(id,sortnum,name,sex,depart,tel,birthday) values('$id','$sortnum','$name','$sex','$depart','$tel','$birthday')";
				$rst = $db->query($sql);
			}
			header("Location: $listUrl");
		}
	$db->close();
	?>
	</body>
</html>
