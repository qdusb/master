<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo ($title); ?></title>
<meta content="<?php echo ($config_keyword); ?>" name="keywords"/>
<meta content="<?php echo ($config_description); ?>" name="description"/>
<link rel="stylesheet" href="__PUBLIC__/images/base.css" />
<link rel="stylesheet" href="__PUBLIC__/images/<?php echo ($css_file); ?>" />
<script src="__PUBLIC__/js/jquery-1.7.2.min.js"></script>
<script src="__PUBLIC__/js/jquery.SuperSlide.js"></script>
<script src="__PUBLIC__/js/adver.js"></script>
</head>
<body>
<div class="wrapper">
	<div class="header">
		<div class="topArea">
			<h2 class="logo"><a href="<?php echo U('Index/index');?>">安徽安振产业投资集团有限公司</a></h2>
			<div class="set">
				<ul>
					<li><a href="http://mail.aztzjt.com/" target="_blank">企业邮箱</a>|</li>
					<li><a href="http://218.22.20.213:2013/" target="_blank">办公OA</a>|</li>
					<li class="c"><a href="javascript:void(0)">旗下企业</a><span><i>◆</i><a href="#">皖投融资担保公司</a><a href="#">安徽天成投资公司</a><a href="#">安振小额贷款公司</a><a href="#">马鞍山信宜实业公司</a></span></li>
				</ul>
			</div>
			<script>
			$(function(){
				$('.set .c').hover(function(){
					$(this).find('span').show();
				},function(){
					$(this).find('span').hide();
				})
			})
			</script>
			<form class="sForm" name="search" method="get" action="<?php echo U('Advanced/search');?>">
				<div class="sInputBox">
					<input type="text" name="keyword" value="" placeholder="请输入关键字" x-webkit-speech />
				</div>
				<div class="sBtn"><input type="submit" value="搜索" /></div>
			</form>
		</div>
		<div class="nav">
			<ul class="navs clearfix">
				<li class="noBg"><a href="<?php echo U('Index/index');?>">安振首页</a></li>
				<?php if(is_array($navs)): foreach($navs as $key=>$v): ?><li><a href="<?php echo ($v["url"]); ?>" <?php if($v['id'] == $id_configs['base_id']): ?>class="current"<?php endif; ?> ><?php echo ($v["name"]); ?></a></li><?php endforeach; endif; ?>
			</ul>
			<div class="subNav">
				<?php if(is_array($categorys)): $i = 0; $__LIST__ = $categorys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="sub clearfix">
						<ul>
							<?php if(is_array($v['subNav'])): $i = 0; $__LIST__ = $v['subNav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($t["url"]); ?>"><?php echo ($t["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>

						<div class="pictxt">
							<div class="pic"><img src="<?php echo ($v['baseNav']['pic']); ?>" width="242" height="163"></div>
							<h2><?php echo ($v['baseNav']["en_name"]); ?></h2>
							<p class="info"><?php echo ($v['baseNav']["content"]); ?></p>
						</div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<script>
				$(function(){
					$(".subNav .sub").each(function(x){
						$(this).addClass('sub'+ x);
						var subn = $(this).find('li').length
						if(subn > 4){
							$(this).addClass('dbSub');
						}
					});
					$(".navs li").each(function(i){
						$(this).mousemove(function(){
							$(".subNav").show();
							$(".subNav").children().hide();
							var t = i - 1;
							$(".sub"+t).show();
							var tt = $(".sub"+t).find('ul').height();
							$(".sub"+t).find('ul').css({'margin-top':-(tt-7)/2-20})
							$(".navs li").removeClass("on");
							$(this).addClass("on");
						});
					});
					$(".nav").mouseleave(function(){
						$(".navs li").removeClass("on");
						$(".subNav").hide();
						$(".subNav").children().hide();
					});
				});
			</script>
		</div>
		<?php if($css_file == 'inside.css'): ?><div class="banner">
			<img src="<?php echo ($banner_pic); ?>" width="1600" height="429">
		</div>
		<?php else: ?>
		<div class="banner">
			<div class="bd">
				<ul>
					<?php if(is_array($banner_pic)): foreach($banner_pic as $key=>$v): ?><li><img src="<?php echo ($v["pic"]); ?>" width="1600" height="530"></li><?php endforeach; endif; ?>
				</ul>
			</div>
			<div class="hd">
				<ul></ul>
			</div>
		</div>
		<script>jQuery(".banner").slide({titCell:".hd ul",mainCell:".bd ul",effect:"fold",autoPlay:true,autoPage:true,delayTime:700});</script><?php endif; ?>
	</div>

	<div class="container">
		<div class="wrap clearfix">
			<div class="sidebar">
	<h2 class="leftTxtTitle"><?php echo ($id_configs["base_name"]); ?></h2>
	<div class="menu">
		<dl>
			<?php if(is_array($seconds)): $i = 0; $__LIST__ = $seconds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><dt><a href="<?php echo ($v["second"]["url"]); ?>" <?php if($v['second']['id'] == $id_configs['second_id']): ?>class="current"<?php endif; ?>><?php echo ($v["second"]["name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
		</dl>
	</div>
	<ul class="leftPic">
		<li><a href="<?php echo U('Advanced/contact');?>"><img src="__PUBLIC__/images/leftPic_01.jpg" width="205" height="70" alt="联系我们" /></a></li>
	</ul>
</div>
			<div class="main">
				<div class="location clearfix">
	<h2><?php echo ($id_configs["class_name"]); ?></h2>
	<div class="breadcrumbs">
		<a href="<?php echo U('Index/index');?>">首页</a> 
		&gt; <a href="<?php echo U('Info/index',array('class_id'=>$id_configs['second_id']));?>"><?php echo ($id_configs["second_name"]); ?></a>
		<?php if($id_configs["third_id"] != ''): ?>&gt; <a href="<?php echo U('Info/index',array('class_id'=>$id_configs['third_id']));?>"><?php echo ($id_configs["third_name"]); ?></a><?php endif; ?>
	</div>
</div>
<script>
$(".breadcrumbs a:last").addClass("current");
</script>
				<div class="article">
					<div class="hd">
					</div>
					<div class="bd">
						<?php echo ($infos[0]["content"]); ?>
						<!--引用百度地图API-->
<style type="text/css">
	html,body{margin:0;padding:0;}
	.iw_poi_title {color:#CC5522;font-size:14px;font-weight:bold;overflow:hidden;padding-right:13px;white-space:nowrap}
	.iw_poi_content {font:12px arial,sans-serif;overflow:visible;padding-top:4px;white-space:-moz-pre-wrap;word-wrap:break-word}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script>
 <!--百度地图容器-->
 <div style="width:680px;height:370px;border:#ccc solid 1px;" id="dituContent"></div>
<script type="text/javascript">
	//创建和初始化地图函数：
	function initMap(){
		createMap();//创建地图
		setMapEvent();//设置地图事件
		addMapControl();//向地图添加控件
		addMarker();//向地图中添加marker
	}
	
	//创建地图函数：
	function createMap(){
		var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
		

		var point = new BMap.Point(117.306893,31.858008);//定义一个中心点坐标标
		map.centerAndZoom(point,13);//设定地图的中心点和坐标并将地图显示在地图容器中
		window.map = map;//将map变量存储在全局
	}
	
	//地图事件设置函数：
	function setMapEvent(){
		map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
		map.enableScrollWheelZoom();//启用地图滚轮放大缩小
		map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
		map.enableKeyboard();//启用键盘上下左右键移动地图
	}
	
	//地图控件添加函数：
	function addMapControl(){
		//向地图中添加缩放控件
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
	map.addControl(ctrl_nav);
		//向地图中添加缩略图控件
	var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
	map.addControl(ctrl_ove);
		//向地图中添加比例尺控件
	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
	map.addControl(ctrl_sca);
	}
	
	//标注点数组
	var markerArr = [{title:"安徽安振产业投资集团有限公司",content:"地址：合肥市马鞍山路99号<br>电话：0551-62225991<br>传真：0551-62225991",point:"117.306893|31.858008",isOpen:0,icon:{w:23,h:25,l:46,t:21,x:9,lb:12}}];
	//创建marker
	function addMarker(){

		for(var i=0;i<markerArr.length;i++){
			var json = markerArr[i];
			var p0 = json.point.split("|")[0];
			var p1 = json.point.split("|")[1];
			json.isOpen=1;
			var point = new BMap.Point(p0,p1);
			var iconImg = createIcon(json.icon);
			var marker = new BMap.Marker(point,{icon:iconImg});
			var iw = createInfoWindow(i);
			var label = new BMap.Label(json.title,{"offset":new BMap.Size(json.icon.lb-json.icon.x+10,-20)});
			marker.setLabel(label);
			map.addOverlay(marker);
			label.setStyle({
						borderColor:"#808080",
						color:"#333",
						cursor:"pointer"
			});
			
			(function(){
				var index = i;
				var _iw = createInfoWindow(i);
				var _marker = marker;
				_marker.addEventListener("click",function(){
					this.openInfoWindow(_iw);
				});
				_iw.addEventListener("open",function(){
					_marker.getLabel().hide();
				})
				_iw.addEventListener("close",function(){
					_marker.getLabel().show();
				})
				label.addEventListener("click",function(){
					_marker.openInfoWindow(_iw);
				})
				if(!!json.isOpen){
					label.hide();
					_marker.openInfoWindow(_iw);
				}
			})()
		}
	}
	//创建InfoWindow
	function createInfoWindow(i){
		var json = markerArr[i];
		var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>"+json.content+"</div>");
		return iw;
	}
	//创建一个Icon
	function createIcon(json){
		var icon = new BMap.Icon("http://app.baidu.com/map/images/us_mk_icon.png", new BMap.Size(json.w,json.h),{imageOffset: new BMap.Size(-json.l,-json.t),infoWindowOffset:new BMap.Size(json.lb+5,1),offset:new BMap.Size(json.x,json.h)})
		return icon;
	}
	initMap();//创建和初始化地图
</script>
					</div>
				</div>
			</div>
		</div>
		<div class="wrapBt"></div>
	</div>
	<div class="footer">
		<div class="copy clearfix">
			<div class="fl">
				<p class="ftNav">
					<a href="<?php echo U('Info/index?class_id=101101');?>">关于我们</a>|
					<a href="<?php echo U('Info/index?class_id=104102');?>">资讯中心</a>|
					<a href="<?php echo U('Info/index?class_id=103102');?>">下载中心</a>|
					<a href="<?php echo U('Advanced/contact');?>">联系我们</a></p>
				<p><?php echo C("CONFIG_CONTACT");?></p>
				
			</div>
			<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js?uid=1393982821107652" charset="utf-8"></script>
			<div class="fr">
				<ul>
					<li>分享：</li>
					<li><div class="jiathis_style"><a class="jiathis_button_qzone"></a><a class="jiathis_button_tsina"></a><a class="jiathis_button_tqq"></a><a class="jiathis_button_weixin"></a><a class="jiathis_button_renren"></a><a class="jiathis_button_xiaoyou"></a><a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a></div></li>
					<li class="weixin"><a href="#">理财咨询<i>
						<img src="__PUBLIC__/images/p90x90.jpg" width="90" height="90"></i></a></li>
				</ul>

			</div>
		</div>
	</div>
</div>
<!--[if lte IE 6]><script src="js/iepng.js"></script><![endif]-->
<?php echo C('CONFIG_AD');?>
<?php echo C('CONFIG_JAVASCRIPT');?>
</body>
</html>