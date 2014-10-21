<?php
/*
 *	分页函数
 *	完成时间：2004-08-14
 *	Cole modify:01/09/2009
*/

function Page($page, $pageCount, $baseURL = "", $mask = "<%PAGE%>")
{

	if (!$baseURL)
	{
		global $_POST, $_GET;

		$baseURL = $_SERVER["PHP_SELF"] . "?";

		if (is_array($_GET))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				$baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		if (is_array($_POST))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				if(!is_array($v)) $baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		$baseURL .= '&page=' . $mask;
	}
	else
	{
		$baseURL .= '&page=' . $mask;
	}

	$pageCount = $pageCount ? $pageCount : 1;

	if ($page > $pageCount) $page = $pageCount;
	if ($page < 1) $page = 1;


	$pages = "<a " . ($page > 1 ? "href='" . str_replace($mask, 1, $baseURL) . "'" : "") . ">首页</a>";
	$pages .= " <a " . ($page > 1 ? "href='" . str_replace($mask, $page - 1, $baseURL) . "'" : "") . ">上一页</a> ";

	for ($i = 1; $i <= $pageCount; $i++)
	{
		$URL = str_replace($mask, $i, $baseURL);
		
		if ($page == $i)
		{
			//$pages .= $i;
			$pages .= " <a href=". $URL ." style='color:#f00;'>". $i ."</a>";
		}
		else
		{
			$pages .= " <a href=". $URL .">". $i ."</a>";
		}
	}

	$pages .= " <a " . ($page < $pageCount ? "href='" . str_replace($mask, $page + 1, $baseURL) . "'" : "") . ">下一页</a> ";
	$pages .= "<a " . ($page < $pageCount ? "href='" . str_replace($mask, $pageCount, $baseURL) . "'" : "") . ">末页</a>";

	return $pages;
}



function page2($page, $pageCount, $pageSize, $baseURL = "", $mask = "<%PAGE%>")
{
	if (!$baseURL)
	{
		global $_POST, $_GET;

		$baseURL = $_SERVER["PHP_SELF"] . "?";

		if (is_array($_GET))
		{
			foreach($_GET as $k => $v)
			{
				if ($k == "page") continue;
				$baseURL .= $k . "=" . urlencode($v) . "&";
			}
		}

		$baseURL .= "page=" . $mask;
	}
	else
	{
		$baseURL .= "page=" . $mask;
	}

	$pageCount = $pageCount ? $pageCount : 1;
	if ($page > $pageCount) $page = $pageCount;
	if ($page < 1) $page = 1;


	$pages .= "Page " . $page . "/" . $pageCount . "&nbsp; Pieces " . $pageSize . "&nbsp; ";
	$pages .= "<a href='" . ($page > 1 ? str_replace($mask, 1, $baseURL) : "javascript:void(0);") . "''>First</a>&nbsp;";
	$pages .= "<a href='" . ($page > 1 ? str_replace($mask, $page - 1, $baseURL) : "javascript:void(0);") . "''>Prev</a>&nbsp;";

	/*
	for ($i = $page - 5; $i <= $page + 5; $i++)
	{
		if ($i >= 1 && $i <= $pageCount)
		{
			$pages .= "<a href='" . str_replace($mask, $i, $baseURL) . "'" . (($i == $page) ? " class='current'" : "") . ">[" . $i . "]</a>&nbsp;";
		}
	}
	*/

	$pages .= "<a href='" . ($page < $pageCount ? str_replace($mask, $page + 1, $baseURL) : "javascript:void(0);") . "''>Next</a>&nbsp;";
	$pages .= "<a href='" . ($page < $pageCount ? str_replace($mask, $pageCount, $baseURL) : "javascript:void(0);") . "' class='page'>Last</a>";

	return $pages;
}
?>