<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


$action		= trim($_GET["action"]);
$sortType	= (int)$_GET["sortType"];
$class_id	= trim($_GET["class_id"]);
$id			= trim($_GET["id"]);
$moveNum	= (int)$_GET["moveNum"];
$returnUrl	= "<script>history.back();</script>";

//sortType 1 info_class 2 info
if (($action != "up" && $action != "down") || ($sortType != 1 && $sortType != 2) || empty($class_id) || empty($id) || $moveNum < 1)
{
	echo $returnUrl;
	eixt;
}


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


if ($sortType == 1) $sql_ = "id='$id'";		//分类排序
elseif ($sortType == 2) $sql_ = "id=$id";	//信息排序
elseif ($sortType == 3) $sql_ = "id=$id";	//属性排序


//当前记录的sortnum
$sortnum = 0;


//事务开始
$db->query("begin");


//向上移动
if ($action == "up")
{
	//分类排序
	if ($sortType == 1)
	{
		$sortnum = $db->getTableFieldValue("info_class", "sortnum", "where ". $sql_);
		$sql = "select id, sortnum from info_class where id like '" . $class_id . CLASS_SPACE . "' and sortnum < $sortnum order by sortnum desc limit " . $moveNum;
		$rst = $db->query($sql);
		$num = $db->num_rows($rst);
		if ($num < 1)
		{
			echo $returnUrl;
			exit;
		}
		//更新序号
		$i = 1;
		while ($row = $db->fetch_array($rst))
		{
			$sql = "update info_class set sortnum=sortnum+10 where id='" . $row["id"] . "'";
			if (!$db->query($sql))
			{
				$db->query("rollback");
				$db->close();
				echo $returnUrl;
				exit;
			}
			if ($i == $num) $fSortNum = $row["sortnum"];
			$i++;
		}
		
		//更新哪个往上移
		$sql = "update info_class set sortnum=$fSortNum where " . $sql_;
		if ($db->query($sql))
		{
			$db->query("commit");
			$db->close();
		}
		else
		{
			$db->query("rollback");
			$db->close();
		}
		
		echo $returnUrl;
		exit;
	}
	//信息排序
	elseif ($sortType == 2)
	{
		$sortnum = $db->getTableFieldValue("info", "sortnum", "where ". $sql_);
		$sql = "select id, sortnum from info where class_id='$class_id' and sortnum > $sortnum order by sortnum asc limit " . $moveNum;
		$rst = $db->query($sql);
		$num = $db->num_rows($rst);
		if ($num < 1)
		{
			echo $returnUrl;
			exit;
		}
		//更新序号
		$i = 1;
		while ($row = $db->fetch_array($rst))
		{
			$sql = "update info set sortnum=sortnum-10 where id=" . $row["id"];
			if (!$db->query($sql))
			{
				$db->query("rollback");
				$db->close();
				echo $returnUrl;
				exit;
			}
			if ($i == $num) $fSortNum = $row["sortnum"];
			$i++;
		}
		
		//更新哪个往上移
		$sql = "update info set sortnum=$fSortNum where " . $sql_;
		if ($db->query($sql))
		{
			$db->query("commit");
			$db->close();
		}
		else
		{
			$db->query("rollback");
			$db->close();
		}
		
		echo $returnUrl;
		exit;
	}
	//产品属性排序
	elseif ($sortType == 3)
	{
		$sortnum = $db->getTableFieldValue("product", "sortnum", "where ". $sql_);
		$sql = "select id, sortnum from product where class_id='$class_id' and sortnum > $sortnum order by sortnum asc limit " . $moveNum;
		$rst = $db->query($sql);
		$num = $db->num_rows($rst);
		if ($num < 1)
		{
			echo $returnUrl;
			exit;
		}
		//更新序号
		$i = 1;
		while ($row = $db->fetch_array($rst))
		{
			$sql = "update product set sortnum=sortnum-10 where id=" . $row["id"];
			if (!$db->query($sql))
			{
				$db->query("rollback");
				$db->close();
				echo $returnUrl;
				exit;
			}
			if ($i == $num) $fSortNum = $row["sortnum"];
			$i++;
		}
		
		//更新哪个往上移
		$sql = "update product set sortnum=$fSortNum where " . $sql_;
		if ($db->query($sql))
		{
			$db->query("commit");
			$db->close();
		}
		else
		{
			$db->query("rollback");
			$db->close();
		}
		
		echo $returnUrl;
		exit;
	}
}
//向下移动
elseif ($action == "down")
{
	//分类排序
	if ($sortType == 1)
	{
		$sortnum = $db->getTableFieldValue("info_class", "sortnum", "where ". $sql_);
		$sql = "select id, sortnum from info_class where id like '" . $class_id . CLASS_SPACE . "' and sortnum > $sortnum order by sortnum asc limit " . $moveNum;
		$rst = $db->query($sql);
		$num = $db->num_rows($rst);
		if ($num < 1)
		{
			echo $returnUrl;
			exit;
		}
		//更新序号
		$i = 1;
		while ($row = $db->fetch_array($rst))
		{
			$sql = "update info_class set sortnum=sortnum-10 where id='" . $row["id"] . "'";
			if (!$db->query($sql))
			{
				$db->query("rollback");
				$db->close();
				echo $returnUrl;
				exit;
			}
			if ($i == $num) $fSortNum = $row["sortnum"];
			$i++;
		}
		
		//更新哪个往上移
		$sql = "update info_class set sortnum=$fSortNum where " . $sql_;
		if ($db->query($sql))
		{
			$db->query("commit");
			$db->close();
		}
		else
		{
			$db->query("rollback");
			$db->close();
		}
		
		echo $returnUrl;
		exit;
	}
	//信息排序
	elseif ($sortType == 2)
	{
		$sortnum = $db->getTableFieldValue("info", "sortnum", "where ". $sql_);
		$sql = "select id, sortnum from info where class_id='$class_id' and sortnum < $sortnum order by sortnum desc limit " . $moveNum;
		$rst = $db->query($sql);
		$num = $db->num_rows($rst);
		if ($num < 1)
		{
			echo $returnUrl;
			exit;
		}
		//更新序号
		$i = 1;
		while ($row = $db->fetch_array($rst))
		{
			$sql = "update info set sortnum=sortnum+10 where id=" . $row["id"];
			if (!$db->query($sql))
			{
				$db->query("rollback");
				$db->close();
				echo $returnUrl;
				exit;
			}
			if ($i == $num) $fSortNum = $row["sortnum"];
			$i++;
		}
		
		//更新哪个往上移
		$sql = "update info set sortnum=$fSortNum where " . $sql_;
		if ($db->query($sql))
		{
			$db->query("commit");
			$db->close();
		}
		else
		{
			$db->query("rollback");
			$db->close();
		}
		
		echo $returnUrl;
		exit;
	}
	//产品属性排序
	elseif ($sortType == 3)
	{
		$sortnum = $db->getTableFieldValue("product", "sortnum", "where ". $sql_);
		$sql = "select id, sortnum from product where class_id='$class_id' and sortnum < $sortnum order by sortnum desc limit " . $moveNum;
		$rst = $db->query($sql);
		$num = $db->num_rows($rst);
		if ($num < 1)
		{
			echo $returnUrl;
			exit;
		}
		//更新序号
		$i = 1;
		while ($row = $db->fetch_array($rst))
		{
			$sql = "update product set sortnum=sortnum+10 where id=" . $row["id"];
			if (!$db->query($sql))
			{
				$db->query("rollback");
				$db->close();
				echo $returnUrl;
				exit;
			}
			if ($i == $num) $fSortNum = $row["sortnum"];
			$i++;
		}
		
		//更新哪个往上移
		$sql = "update product set sortnum=$fSortNum where " . $sql_;
		if ($db->query($sql))
		{
			$db->query("commit");
			$db->close();
		}
		else
		{
			$db->query("rollback");
			$db->close();
		}
		
		echo $returnUrl;
		exit;
	}
}
?>
