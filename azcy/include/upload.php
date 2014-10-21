<?php
require("init.php");
require("../admin/uploadImg.php");

$pic_file 	= $_FILES['Filedata'];//获得选择的文件
$class_id	= $_POST['class_id'];
if($pic_file)
{
	$now=date("Y-m-d H:i:s");
	$name=$pic_file['name'];
	$pic=UPLOAD_PATH_FOR_ADMIN.uploadImg($pic_file, "gif,jpg,png,swf,fla,wmv");
	$id = $db->getMax("album", "id") + 1;
	$sortnum = $db->getMax("album", "sortnum", "class_id='$class_id'") + 10;
	$sql = "insert into album(id,sortnum,class_id,pic,name,create_time) values('$id','$sortnum','$class_id','$pic','$name','$now')";
	$rst = $db->query($sql);
}
?>