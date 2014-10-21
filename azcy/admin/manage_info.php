<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$select_class	= trim($_GET["select_class"]);
$select_state	= (int)$_GET["select_state"];
$keyword		= urlencode(trim($_GET["keyword"]));
$page			= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;


$listUrl = "manage_info.php?select_class=$select_class&select_state=$select_state&keyword=$keyword&page=$page";
$baseUrl = "manage_info.php";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


//删除
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$id_array = $_POST["ids"];
	if (!is_array($id_array))
	{
		$id_array = array($id_array);
	}
	
	$operation	= trim($_POST["operation"]);
	$end_class	= trim($_POST["end_class"]);
	
	if (empty($operation) || (!empty($operation) && $operation != "delete" && empty($end_class)))
	{
		$db->close();
		info("填写的参数错误！");
	}
	
	if ($operation == "delete") //删除
	{
		$sql = "select pic, annex, files from info where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		while ($row = $db->fetch_array($rst))
		{
			$pic	.= $row["pic"] . ",";
			$annex	.= $row["annex"] . ",";
			$files	.= $row["files"] . ",";
		}
		
		$db->query("begin");
		
		$sql = "delete from info where id in (" . implode(",", $id_array) . ")";
		
		$rst = $db->query($sql);
		if ($rst)
		{
			deleteFiles($pic, 1);
			deleteFiles($annex, 1);
			deleteFiles($files, 2);
			$db->query("commit");
			$db->close();
			header("Location: $listUrl");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("删除信息失败！");
		}
	}
	elseif ($operation == "move") // 转移
	{
		$db->query("begin");
		
		$sql = "update info set class_id='$end_class' where id in (" . implode(",", $id_array) . ")";
		$rst = $db->query($sql);
		if ($rst)
		{
			$db->query("commit");
			$db->close();
			info("转移信息成功！");
			exit();
		}
		else
		{
			$db->query("rollback");
			$db->close();
			info("转移信息失败！");
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
				<td class="position">当前位置: 管理中心 -&gt; 系统管理 -&gt; 信息管理</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[刷新列表]</a>
					<a href="javascript:reverseCheck(document.form1.ids);">[反向选择]</a>&nbsp;
					<a href="javascript:if(operationCheck(document.form1.ids)) {document.form1.submit();}">[操作]</a>&nbsp;
					<select name="select_class" onChange="window.location='<?=$baseUrl?>?select_class=' + this.options[this.selectedIndex].value;">
						<option value="">请选择栏目</option>
						<?
						$sql = "select id, name from info_class where id like '" . CLASS_SPACE . "%' order by sortnum asc";
						$rst = $db->query($sql);
						while ($row = $db->fetch_array($rst))
						{
							$data[] = array("id" => $row["id"], "name" => $row["name"]);
						}
						$data = getNodeData($data, '', CLASS_LENGTH);
						echo optionSTree($data, $select_class);
						?>
					</select>
					<select name="select_state" onChange="window.location='<?=$baseUrl?>?select_class=<?=$select_class?>&select_state=' + this.options[this.selectedIndex].value;">
						<option value="">请选择</option>
						<option value="1"<? if ($select_state == 1) echo " selected"?>>未审核</option>
						<option value="2"<? if ($select_state == 2) echo " selected"?>>正常</option>
						<option value="3"<? if ($select_state == 3) echo " selected"?>>推荐</option>
					</select>
				</td>
				<td align="right">
					<form name="searchForm" method="get" action="" style="margin:0px;">
						查询：<input name="keyword" type="text" value="<?=urldecode($keyword)?>" size="30" maxlength="50" />
						<input type="submit" value="查询" style="width:60px;">
						<input type="hidden" name="select_class" value="<?=$select_class?>" />
						<input type="hidden" name="select_state" value="<?=$select_state?>" />
					</form>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" align="center" class="listTable">
			<form name="form1" action="" method="post">
                <tr class="listHeaderTr">
                    <td width="4%"></td>
                    <td>标题</td>
                    <td width="12%">类别</td>
                    <td width="8%">状态</td>
                    <td width="10%">发表时间</td>
                </tr>
				<?
				//筛选条件
				if (!empty($select_class))
				{	
                	$SQL_ = "and a.class_id like '$select_class%' ";
				}

				switch ($select_state)
				{
					case 1:
						$SQL_ .= "and a.state=0";
						break;
					case 2:
						$SQL_ .= "and a.state=1";
						break;
					case 3:
						$SQL_ .= "and a.state=2";
						break;
					default:
						$SQL_ .= "";
						break;
				}
				
				//设置每页数
				$page_size = DEFAULT_PAGE_SIZE;
				//总记录数
				$sql = "select count(*) as cnt from info a where a.title like '%" . urldecode($keyword) . "%' $SQL_";
				$rst = $db->query($sql);
				$row = $db->fetch_array($rst);
				$record_count = $row["cnt"];
				$page_count = ceil($record_count / $page_size);
				//分页
				$page_str = page($page, $page_count, $pageUrl);
				//列表
				$sql = "select a.id, a.title, a.author, a.source, a.website, a.pic, a.views, a.files, a.create_time, a.state, b.name from info a left join info_class b on a.class_id=b.id where a.title like '%" . urldecode($keyword) . "%' $SQL_ order by a.class_id asc, a.sortnum desc";
                $sql .= " limit " . ($page - 1) * $page_size . ", " . $page_size;
                $rst = $db->query($sql);
                while ($row = $db->fetch_array($rst))
                {
                	$css = ($css == "listTr") ? "listAlternatingTr" : "listTr";
                ?>
                    <tr class="<?=$css?>">
                        <td><input type="checkbox" id="ids" name="ids[]" value="<?=$row["id"]?>"></td>
                        <td><?=$row["title"]?></td>
                        <td><?=$row["name"]?></td>
                        <td>
                            <?
                            switch ($row["state"])
                            {
                                case 0:
                                    echo "<font color=#FF9900>未审核</font>";
                                    break;
                                case 1:
                                    echo "正常";
                                    break;
                                case 2:
                                    echo "<font color=#FF3300>推荐</font>";
                                    break;
                                default :
                                    echo "<font color=#FF0000>错误</font>";
                                    exit;
                            }
                            ?>
                        </td>
                        <td><?=formatDate("Y-m-d", $row["create_time"])?></td>
                    </tr>
				<?
                }
                ?>
                <tr class="listTr">
                    <td colspan="15" align="left" style="padding-left:18px;">
                    	<div style="float:left;">
                            操作选项
                            <select name="operation" style="width:120px;" onChange="optionCheck()">
                                <option value="">请选择</option>
                                <option value="delete">删除</option>
                                <option value="move">转移</option>
                            </select>
                        </div>
                        <div id="end_select" style="float:left; margin-left:5px; display:none">
                            <select name="end_class">
								<?
								//$data = NULL;
                                //$sql = "select id, name from info_class where id like '" . CLASS_SPACE . "%' order by sortnum asc";
                                //$rst = $db->query($sql);
                                //while ($row = $db->fetch_array($rst))
                                //{
                                //    $data[] = array("id" => $row["id"], "name" => $row["name"]);
								//}
								//$data = getNodeData($data, '', CLASS_LENGTH);
								echo optionsTree($data);
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr class="listFooterTr">
                    <td colspan="15"><?=$page_str?></td>
                </tr>
			</form>
		</table>
		<?
        $db->close();
		?>
	</body>
</html>
