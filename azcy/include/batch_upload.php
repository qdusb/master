<?php
require("init.php");
require("../admin/uploadImg.php");

$path='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path=dirname($path);

$pic_file 	= $_FILES['Filedata'];//获得选择的文件
$class_id	= $_POST['id'];
if($pic_file)
{
	$now=date("Y-m-d H:i:s");
	$pic_name=$pic_file['name'];
	$seri=strrpos($pic_name,".");
	$name=substr($pic_name,0,$seri);
	$id = $db->getMax("info", "id") + 1;
	$sortnum = $db->getMax("info", "sortnum","class_id=$class_id") + 10;
	$pic= uploadImg($pic_file, "gif,jpg,png");
	$icoWidth=710;
	$icoHeight=483;
	$ico=makeThumbnail("../upload/".$pic,getTmpName(),$icoWidth,$icoHeight);
	if(empty($ico)){
		$ico=$pic;
	}
	$content="<img src=\"".$path."/../upload/".$pic."\" />";
	$sql = "insert into info(id,sortnum,class_id,title,pic,content,create_time,state) values($id,$sortnum,'$class_id','$name','$ico','$content','$now',1)";
	$rst = $db->query($sql);
	$db->close();
}

function makeThumbnail($srcImage,$toFile,$maxWidth = 100,$maxHeight = 100,$imgQuality=100)
{
    list($width, $height, $type, $attr) = getimagesize($srcImage);
	
    if($width < $maxWidth  || $height < $maxHeight) return ;
    switch ($type) 
	{
  	  	case 1: $img = imagecreatefromgif($srcImage); break;
    	case 2: $img = imagecreatefromjpeg($srcImage); break;
   	 	case 3: $img = imagecreatefrompng($srcImage); break;
    }
    $scale = min($maxWidth/$width, $maxHeight/$height); //求出压缩比例
   
    if($scale < 1) 
	{
		$newWidth = floor($scale*$width);
		$newHeight = floor($scale*$height);
		$newImg = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		
		$ym = date("Y-m");
		$dir_path="../upload/".$ym . "/";
		if (!is_dir($dir_path))
		{
			if (!mkdir($dir_path, 0777))
			{
				info("无法建立保存图片的目录！");
			}
		}
		$toFile = preg_replace("/(.gif|.jpg|.jpeg|.png)/i","",$toFile);
		$return_file="";
		switch($type) 
		{
			case 1:if(imagegif($newImg, $dir_path."$toFile.gif", $imgQuality))
			$return_file=$dir_path."$toFile.gif"; break;
			case 2: if(imagejpeg($newImg, $dir_path."$toFile.jpg", $imgQuality))
			$return_file=$dir_path."$toFile.jpg"; break;
			case 3: if(imagepng($newImg, $dir_path."$toFile.png", $imgQuality))
			$return_file=$dir_path."$toFile.png"; break;
			default: if(imagejpeg($newImg, "$toFile.jpg", $imgQuality))
			$return_file=$dir_path."$toFile.jpg"; break;
		}
   		imagedestroy($return_file);
		return $return_file;
    }
	else
	{
		imagedestroy($img);
		return $srcImage;
	}
    
    return $srcImage;
}
?>