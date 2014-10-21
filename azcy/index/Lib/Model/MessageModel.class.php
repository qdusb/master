<?php
class MessageModel extends Model{
	protected $_validate = array(
    array('name','require','留言人不能为空'), 
    array('phone','checkPhone','手机号码格式不正确',2,'callback',3),
    array('email','email','邮箱格式不正确',2),
    array('content','require','留言内容不能为空'),
  );
	public function checkPhone($data){
		$strRule="/^1[358]\d{9}$/";
		if(preg_match($strRule,$data)==0)
		{
			return false;
		}
		return true;
	}
}
?>