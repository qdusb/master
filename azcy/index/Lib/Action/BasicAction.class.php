<?php
/*
author reevesc cui
update 2014.6.10
基本控制器
*/
class BasicAction extends Action{
	public $categorys=array();
	public $configs;
	public $class_id;
	public $banner;
	/*
	自动化函数，自动调用
	*/
	public function _empty(){
		U("Empty/index","","html",true);
      }
	public function _initialize(){
		/*banner*/
		$db=M("banner");
		$banner=$db->where("class_id=1")->order("sortnum asc")->select();
		$bannerKey=array("101","102","103","104","105","106","107","108");
		$this->banner=array_combine(array_slice($bannerKey,0,count($banner)),$banner);
		
		$this->class_id=I("class_id","","htmlspecialchars");
		if(!empty($this->class_id)){
			$this->setIDConfig($this->class_id);
		}
		
		setBaseWebConfig();
		/*一级导航*/
		$navs=getBaseClass(7);
		$this->assign("navs",$navs);
		/*所有的二级导航*/
		$this->categorys=getAllCategorys();
		$this->assign("categorys",$this->categorys);
		/*默认为内页样式*/
		$this->assign("css_file","inside.css");

	}
	protected function setIDConfig($class_id){
		
		$configs=getClassValue($class_id);
		$this->assign("info_state",$configs['info_state']);
		$seconds=getSecondClasses($configs['base_id']);
		$this->assign("seconds",$seconds);
		$this->assign("id_configs",$configs);
		$this->configs=$configs;
		$base_id=substr($class_id,0,3);
		$this->assign("banner_pic",C("UPLOAD_PATH").$this->banner[$base_id]['pic']);
	}
}
?>