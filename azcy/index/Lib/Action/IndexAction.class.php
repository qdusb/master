<?php
/*
author reevesc cui
update 2014.6.10
首页
*/
class IndexAction extends BasicAction {
    public function index(){

		/*新闻*/
    	$news_id="104101";
    	$db=M("info");
    	$hot=$db->where("class_id='$news_id' and pic<>'' and state>0")->order("sortnum desc")->find();

    	$hot['pic']=C("UPLOAD_PATH").$hot['pic'];
    	$hot['url']=U("Display/index",array("id"=>$hot['id']));
    	$this->assign("hot",$hot);

    	$comNews=$db->where("class_id='$news_id' and state>0 and id<>{$hot['id']}")->field("id,title,create_time")->order("sortnum desc")->limit(2)->select();
    	$this->assign("comNews",$comNews);

    	/*产品与服务*/
    	$pro_ser=array();
    	$cdb=M("info_class");
    	$psclass=$cdb->where("id like '103___'")->field("name,id")->order("sortnum asc")->select();
    	
    	foreach($psclass as $key=>$ps){
    		$s_id=$ps['id'];
    		$info=$db->where("class_id =$s_id and state>0 and pic<>''")->order("state desc,sortnum desc")->find();
            $info['url']=U("Display/index",array("id"=>$info['id']));
            $info['pic']=C("UPLOAD_PATH").$info['pic'];
    		$psclass[$key]['info']=$info;
    	}
        $this->assign("psclass",$psclass);
        /*友情链接*/
        $db=M("link");
        $links=$db->where("class_id=1 and state>0")->select();
        foreach($links as $key=>$link){
            $links[$key]['pic']=C("UPLOAD_PATH").$link['pic'];
        }
        /*Banner*/
        $db=M("banner");
        $banner_pic=$db->where("class_id=2 and state>0")->select();
        foreach($banner_pic as $key=>$v){
            $banner_pic[$key]['pic']=C("UPLOAD_PATH").$v['pic'];
        }

        $this->assign("links",$links);
        $this->assign("banner_pic",$banner_pic);
		$this->assign("css_file","home.css");
		$this->assign("title","网站首页-".C("CONFIG_TITLE"));
		$this->display("index");
    }
}