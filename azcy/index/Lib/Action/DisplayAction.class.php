<?php
/*
author reevesc cui
update 2014.6.10
常规页面，用于展示
*/
class DisplayAction extends BasicAction{
	public function index(){
		$id=I("id","","htmlspecialchars");
		if(empty($id)){
			$this->error("抱歉，此页面不存在,马上跳回首页",U("Index/index"));
		}
		$db=M("info");
		$data=$db->where("id=$id")->find();
		$data["time"]=date("Y-m-d H:i:s",strtotime($data['create_time']));
		$db-> where("id=$id")->setField('views',intval($data['views'])+1);

		$class_id=$data['class_id'];
		$configs=getClassValue($class_id);
		$info_state=$configs['info_state'];
		$base_id=$configs['base_id'];
		$class_id=$configs['class_id'];
		$class_name=$configs['class_name'];
		$this->assign("id_configs",$configs);
		$seconds=getSecondClasses($base_id);
		$this->assign("seconds",$seconds);
		
		$this->assign("data",$data);
		$this->assign("banner_pic",C("UPLOAD_PATH").$this->banner[substr($class_id,0,3)]['pic']);
		$this->assign("title",$data['title']."-$class_name-".C("CONFIG_TITLE"));
		$this->assign("css_file","inside.css");
		$this->display("index");
	}
}
?>