<?php
/*
author reevesc cui
update 2014.6.10
人力资源页面，非常规页面
可以展示工作列表，显示工作详情，申请页面
*/
class HRAction extends BasicAction{
	public function index(){
		$class_id=105101;
		$this->setIDConfig($class_id);
		$page_id=I("page_id","1","htmlspecialchars");
		/*分页*/
		$db          =M("job");
		$page_size   =6;
		$recordCnt   =$db->where("state>0")->count("id");
		$page_num    =ceil(($recordCnt)/$page_size);
		$page_config =page($page_id,$page_num,"HR/index",array());
		$this->assign("page_config",$page_config);
		
		$page_start  =($page_id-1)*$page_size;
		$infos       =$db->where("state>0")->limit($page_start,$page_size)->select();
		$this->assign("infos",$infos);
		
		$this->assign("title",$this->configs['class_name'] ."-".C("CONFIG_TITLE"));
		$this->display("index");
	}

	public function displayJob(){
		$this->error("抱歉，应聘页面正在加紧制作中....");
		exit;
		$id=I("id","","htmlspecialchars");
		if(empty($id))
		{
			$this->error("抱歉，此页面不存在,马上跳回首页",U("Index/index"));
		}
		else
		{
			$db=M("job");
			$info=$db->where("id=$id")->select();
			$this->assign("info",$info);
		}	
		$this->assign("title",$info['name'] ."-我要应聘-".C("CONFIG_TITLE"));
		$this->display("job_display");
	}
	public function applyJob(){
		$this->error("抱歉，此页面不存在,马上跳回首页",U("Index/index"));
		if($this->isPost())
		{
			$name=I("name","","htmlspecialchars");
			$job_id=I("job_id","","htmlspecialchars");
			$db=M("job_apply");
			$data=array(
				"job_id"=>$job_id,
				"name"=>$name
				);
		}
		else
		{
			$this->error("抱歉，此页面不存在,马上跳回首页",U("Index/index"));
		}
	}
}
?>