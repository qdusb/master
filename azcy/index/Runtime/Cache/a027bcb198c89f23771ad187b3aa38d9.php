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

				<?php if($info_state == 'list'): ?><div class="hotNews clearfix">
	<div class="pic"><a href="<?php echo ($hot["url"]); ?>"><img src="<?php echo ($hot["pic"]); ?>" width="165" height="165" /></a></div>
	<dl>
		<dt><a href="<?php echo ($hot["url"]); ?>"><?php echo ($hot["title"]); ?></a></dt>
		<dd class="date"><?php echo (date("Y.m.d",strtotime($hot["create_time"]))); ?></dd>
		<dd class="info"><?php echo (msubstr(strip_tags($hot["content"]),0,170)); ?></dd>
		<dd class="more"><a href="<?php echo ($hot["url"]); ?>">more&gt;&gt;</a></dd>
	</dl>
</div>
<div class="list">
	<ul>
	<?php if(is_array($infos)): foreach($infos as $key=>$v): ?><li><span><?php echo (date("Y-m-d",strtotime($v["create_time"]))); ?></span>
			<a href="<?php echo U('Display/index',array('id'=>$v['id']));?>"><?php echo (msubstr($v["title"],0,60)); ?></a>
		</li><?php endforeach; endif; ?>
	</ul>
</div>
					<div class="page">
	<a href="<?php echo ($page_config["home"]); ?>">首页</a>
	<a href="<?php echo ($page_config["prev"]); ?>">上一页</a>
	<?php if(is_array($page_config['page'])): $i = 0; $__LIST__ = $page_config['page'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo ($v["url"]); ?>" <?php if($v['page'] == $page_config['page_id']): ?>class="current"<?php endif; ?>><?php echo ($v["label"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
	<a href="<?php echo ($page_config["next"]); ?>">下一页</a>
	<a href="<?php echo ($page_config["end"]); ?>">尾页</a>
</div>
				<?php elseif($info_state == 'pictxt'): ?>
					<div class="hotNews clearfix">
	<dl>
		<dt><a href="<?php echo ($hot["url"]); ?>"><?php echo ($hot["title"]); ?></a></dt>
		<dd class="date"><?php echo (date("Y.m.d",strtotime($hot["create_time"]))); ?></dd>
		<dd class="info"><?php echo (substr($hot["content"],0,200)); ?></dd>
		<dd class="down"><a href="<?php echo ($hot["annex"]); ?>">我要下载</a></dd>
	</dl>
</div>
<div class="list">
	<ul>
		<?php if(is_array($infos)): foreach($infos as $key=>$v): ?><li <?php if($v['annex'] != ''): ?>class="hover"<?php endif; ?>>
				<span class="down"><a href="<?php echo ($v["annex"]); ?>">我要下载</a></span>
				<a href="<?php echo ($v["url"]); ?>"><?php echo (msubstr($v["title"],0,40)); ?></a>
			</li><?php endforeach; endif; ?>
	</ul>
</div>
					<div class="page">
	<a href="<?php echo ($page_config["home"]); ?>">首页</a>
	<a href="<?php echo ($page_config["prev"]); ?>">上一页</a>
	<?php if(is_array($page_config['page'])): $i = 0; $__LIST__ = $page_config['page'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo ($v["url"]); ?>" <?php if($v['page'] == $page_config['page_id']): ?>class="current"<?php endif; ?>><?php echo ($v["label"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
	<a href="<?php echo ($page_config["next"]); ?>">下一页</a>
	<a href="<?php echo ($page_config["end"]); ?>">尾页</a>
</div>
				<?php elseif($info_state == 'content'): ?>
					<div class="article">
	<div class="bd">
		<?php echo ($infos[0]["content"]); ?>
	</div>
</div><?php endif; ?>
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