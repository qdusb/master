<?php
/*
author reevesc cui
update 2014.6.10
常规页面，列表，图片，内容，图文列表，自定义
*/
class InfoAction extends BasicAction{
	public function home(){
		$this->error("抱歉，此页面不存在,马上跳回首页",U("Index/index"));
	}
	public function index(){
		$page_id=I("page_id","1","htmlspecialchars");
		if(empty($this->class_id)){
			$this->error("页面不存在");
		}
		$class_id=$this->configs["class_id"];
		$info_state=$this->configs["info_state"];

		/*分页设置*/
		switch($info_state)
		{
			case "pic":
			$page_size=15;
			break;
			case "list":
			$page_size=16;
			break;
			case "pictxt":
			$page_size=15;
			break;
			default:
			$page_size=15;
			break;
		}
		$db=M("info");
		if($info_state!="content"){
			/*分页*/
			$recordCnt=$db->where("class_id=$class_id and state>0")->count("id");
			$page_num=ceil(($recordCnt-1)/$page_size);
			$page_config=page($page_id,$page_num,"Info/index",array("class_id"=>$class_id));
			$this->assign("page_config",$page_config);

			$hot=$db->where("class_id=$class_id and state>0")->order("state desc,sortnum desc")->find();
			
			$hot['url']=U("Display/index?id=".$hot['id']);
			$hot['pic']=C("UPLOAD_PATH").$hot["pic"];
			$annex=$hot['annex'];
			$hot['annex']=empty($annex)?"":C("UPLOAD_PATH").$hot["annex"];
			$this->assign("hot",$hot);

			$page_start=($page_id-1)*$page_size+1;
			$infos=$db->where("class_id=$class_id and state>0")->order("state desc,sortnum desc")->limit($page_start,$page_size)->select();
			foreach($infos as $key=>$info){
				$infos[$key]['url']=U("Display/index?id=".$info['id']);
				//$infos[$key]['pic']=C("UPLOAD_PATH").$info["pic"];
				$annex=$infos[$key]['annex'];
				$infos[$key]['annex']=empty($annex)?"":C("UPLOAD_PATH").$info["annex"];
			}
		}else{
			$infos=$db->where("class_id=$class_id and state>0")->limit(1)->select();
		}
		$this->assign("infos",$infos);
		$this->assign("title",$this->configs['class_name'] ."-".C("CONFIG_TITLE"));
		$this->display("index");
	}
	public function show(){
		$class_id=I("class_id","","htmlspecialchars");
		if(empty($this->class_id)){
			$this->error("页面不存在");
		}
		
		$this->configs['class_id']=$class_id;
		$this->assign("id_configs",$this->configs);

		$db=M("info_class");
		$second_id=$this->configs['second_id'];

		$third_nav=$db->where("id like '{$second_id}___'")->order("sortnum asc")->select();
		$fnav=array("id"=>$second_id,"name"=>"全部图片");
		array_unshift($third_nav, $fnav);
		$this->assign("third_nav",$third_nav);

		$db=M("info");
		$infos=$db->where("class_id like '{$class_id}%' and state>0")->select();
		foreach($infos as $key=>$info){
			$infos[$key]['url']=U("Display/index?id=".$info['id']);
			$infos[$key]['pic']=C("UPLOAD_PATH").$info["pic"];
		}
		$this->assign("infos",$infos);
		$this->assign("title",$this->configs['class_name'] ."-".C("CONFIG_TITLE"));
		$class_id=$this->configs["class_id"];
		$this->display("show");
	}
}
?>