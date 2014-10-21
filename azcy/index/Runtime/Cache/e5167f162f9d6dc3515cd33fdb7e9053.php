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
			<p class="set"><a href="#">企业邮箱</a>|<a href="#">办公OA</a>|<a href="#">快速链接</a></p>
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
							<p class="info"><?php echo (msubstr($v['baseNav']["content"],0,200)); ?></p>
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
		<div class="banner"><img src="<?php echo ($banner_pic); ?>" width="1600" height="429"></div>
	</div>

	<div class="container">
		<div class="wrap clearfix">
			<div class="sidebar">
				<h2 class="leftTxtTitle">在线搜索</h2>
				<div class="menu">
					<dl>
						<dt><a href="" class="current">在线搜索</a></dt>
					</dl>
				</div>
				<ul class="leftPic">
					<li><a href="<?php echo U('Advanced/contact');?>"><img src="__PUBLIC__/images/leftPic_01.jpg" width="205" height="70" alt="联系我们" /></a></li>
				</ul>
			</div>
			<div class="main">
				<div class="location clearfix">
					<h2>在线搜索</h2>
					<div class="breadcrumbs">
						<a href="index.php">首页</a> 
						&gt; <a href="#">在线搜索</a>
						&gt; <a href="#" class="current">在线搜索</a>
					</div>
				</div>
				<div class="list">
	<ul>
		<?php if(is_array($infos)): foreach($infos as $key=>$v): ?><li><span><?php echo (date("Y-m-d",strtotime($v["create_time"]))); ?></span>
			<a href="<?php echo U('Display/index',array('id'=>$v['id']));?>"><?php echo (msubstr($v["title"],0,40)); ?></a>
		</li><?php endforeach; endif; ?>
	</ul>
</div>
				<div class="page">
	<a href="<?php echo ($page_config["home"]); ?>">首页</a>
	<a href="<?php echo ($page_config["prev"]); ?>">上一页</a>
	<?php if(is_array($page_config['page'])): $i = 0; $__LIST__ = $page_config['page'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo ($v["url"]); ?>" <?php if($v['label'] == $page_config['page_id']): ?>class="current"<?php endif; ?>><?php echo ($v["label"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
	<a href="<?php echo ($page_config["next"]); ?>">下一页</a>
	<a href="<?php echo ($page_config["end"]); ?>">尾页</a>
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
					<a href="<?php echo U('Info/index?class_id=101102');?>">资讯中心</a>|
					<a href="<?php echo U('Info/index?class_id=103102');?>">下载中心</a>|
					<a href="<?php echo U('Advanced/contact');?>">联系我们</a></p>
				<p><?php echo C("CONFIG_CONTACT");?> <a href="http://www.miibeian.gov.cn" target='_blank'><?php echo C("CONFIG_ICP");?></a></p>
				<p>技术支持：<a href="http://www.ibw.cn" target="_blank">网新科技</a></p>
			</div>
			<div class="fr">
				<ul>
					<li>分享：</li>
					<li>分享：</li>
					<li><a href="#">理财咨询<i><img src="__PUBLIC__/images/p90x90.jpg" width="90" height="90"></i></a></li>
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