<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo ($title); ?></title>
<meta content="<?php echo ($config_keyword); ?>" name="keywords"/>
<meta content="<?php echo ($config_description); ?>" name="description"/>
<link rel="stylesheet" href="__PUBLIC__/images/base.css" />
<link rel="stylesheet" href="__PUBLIC__/images/inside.css" />
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
							<div class="pic"><img src="__PUBLIC__/images/p242x163.jpg" width="242" height="163"></div>
							<h2>诚信投资  和谐共存</h2>
							<p class="info">安徽安振投资有限公司是安徽省国资委直属的国有独资企业，成立于2001年9月，注册资本1.5亿元。公司地处安徽省合肥市庐阳区，经营范围：实业及项目投资，资产管理，财务顾问，投资咨询等。</p>
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
		<div class="banner"><img src="__PUBLIC__/images/banner_about.jpg" width="1600" height="429"></div>
	</div>

<script src="__PUBLIC__/js/jquery.SuperSlide.2.1.1.js"></script>
	<div class="container">
		<div class="wrap">
			<div class="sm">
				<div class="smt">
					<p>
						<a href="javascript:history.back(-1)" class="o">返回</a>
						<?php if(is_array($third_nav)): foreach($third_nav as $key=>$v): ?><a href="<?php echo U('Info/show',array('class_id'=>$v['id']));?>" <?php if($id_configs['class_id'] == $v['id']): ?>class="current"<?php endif; ?>><?php echo ($v["name"]); ?></a><?php endforeach; endif; ?>
					</p>
				</div>
				<div class="smb">
					<h2><?php echo ($id_configs["second_name"]); ?></h2>
					<div class="preview clearfix">
						<div class="bigImg">
							<ul>
								<?php if(is_array($infos)): foreach($infos as $key=>$v): ?><li><img src="<?php echo ($v["pic"]); ?>" width="710" height="483" /><span><?php echo ($v["title"]); ?></span></li><?php endforeach; endif; ?>
							</ul>
						</div>
						<div class="smallScroll">
							<div class="smallImg">
								<ul>
									<?php if(is_array($infos)): foreach($infos as $key=>$v): ?><li class="on"><img src="<?php echo ($v["pic"]); ?>" width="184" height="123" /><i></i></li><?php endforeach; endif; ?>
								</ul>
							</div><a class="sPrev" href="javascript:void(0)">←</a><a class="sNext" href="javascript:void(0)">→</a>
						</div>
					</div>
					<script>
						jQuery(".preview").slide({ titCell:".smallImg li", mainCell:".bigImg ul", effect:"fold",delayTime:200});
						jQuery(".preview .smallScroll").slide({ mainCell:"ul",delayTime:100,vis:3,effect:"top",prevCell:".sPrev",nextCell:".sNext",pnLoop:false });
					</script>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
		<div class="copy clearfix">
			<div class="fl">
				<p class="ftNav"><a href="#">关于我们</a>|<a href="#">资讯中心</a>|<a href="#">下载中心</a>|<a href="#">联系我们</a></p>
				<p><?php echo C("CONFIG_CONTACT");?> <a href="http://www.miibeian.gov.cn" target='_blank'><?php echo C("CONFIG_ICP");?></a></p>
				<p>技术支持：<a href="http://www.ibw.cn" target="_blank">网新科技</a></p>
			</div>
			<div class="fr">
				<ul>
					<li>分享：</li>
					<li>分享：</li>
					<li><a href="#">理财咨询<i><img src="images/p90x90.jpg" width="90" height="90"></i></a></li>
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