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
		<div class="wrap">
			<div class="gird-s501m501 clearfix">
				<div class="sidebar">
					<div class="title news">
						<div class="hd">
							<h2>新闻中心</h2>
							<p class="more"><a href="<?php echo U('Info/index?class_id=104101');?>">MORE</a></p>
						</div>
						<div class="bd">
							<div class="hotNews">
								<p class="pic"><img src="<?php echo ($hot["pic"]); ?>" width="141" height="141"></p>
								<div class="info">
									<h3><a href="<?php echo ($hot["url"]); ?>"><?php echo (msubstr($hot["title"],0,17)); ?></a></h3>
									<p class="d"><?php echo (date("Y-m-d",strtotime($hot["create_time"]))); ?></p>
									<p class="i"><?php echo (msubstr(strip_tags($hot["content"]),0,90)); ?></p>
									<p class="m"><a href="<?php echo ($hot["url"]); ?>">了解更多&gt;&gt;</a></p>
								</div>
							</div>
							<ul class="list">
								<?php if(is_array($comNews)): foreach($comNews as $key=>$v): ?><li><span><?php echo (date("Y-m-d",strtotime($v["create_time"]))); ?></span><a href="<?php echo U('Display/index',array('id'=>$v['id']));?>"><?php echo (msubstr($v["title"],0,30)); ?></a></li><?php endforeach; endif; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="main">
					<div class="title service">
						<div class="hd">
							<h2>产品与服务</h2>
							<p class="more"><a href="<?php echo U('Info/index?class_id=103101');?>">MORE</a></p>
						</div>
						<div class="bd">
							<div class="mt">
								<ul class="tabPanel clearfix">
									<?php if(is_array($psclass)): foreach($psclass as $key=>$v): ?><li <?php if($key == 0): ?>class="on"<?php endif; ?>><a href="#"><?php echo ($v["name"]); ?></a></li><?php endforeach; endif; ?>
								</ul>
							</div>
							<div class="mc tab-bd">
								<?php if(is_array($psclass)): foreach($psclass as $key=>$v): ?><div class="picnews clearfix">
									<p class="pic"><img src="<?php echo ($v['info']["pic"]); ?>" width="169" height="169"></p>
									<div class="info">
										<h3><a href="#"><?php echo ($v['info']["title"]); ?></a></h3>
										<p class="i"><?php echo (msubstr(strip_tags($v['info']["content"]),0,100)); ?></p>
										<p class="m"><a href="<?php echo ($v['info']["url"]); ?>">了解更多&gt;&gt;</a></p>
									</div>
								</div><?php endforeach; endif; ?>
							</div>

						</div>
					</div>
					<script>jQuery(".service").slide({ titCell:".mt .tabPanel li", mainCell:".tab-bd",delayTime:0 });</script>
				</div>
			</div>
			<div class="title partner">
				<div class="hd">
					<h2>合作伙伴</h2>
				</div>
				<div class="bd">
					<ul class="clearfix">
						<?php if(is_array($links)): foreach($links as $key=>$v): ?><li><a href="<?php echo ($v["url"]); ?>" target="_blank"><img src="<?php echo ($v["pic"]); ?>" width="153" height="46"></a></li><?php endforeach; endif; ?>
					</ul>
				</div>
			</div>
			<script>jQuery(".partner").slide({mainCell:".bd ul",autoPlay:true,effect:"leftMarquee",vis:6,interTime:25,trigger:"click"});</script>
		</div>
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