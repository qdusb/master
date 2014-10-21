/*
preload pic and resize
by niewei
2011-05-16
*/
(function($)
{
	$.fn.marquee = function (opt, callback, endCallback)
	{
		var _this	= $(this);  //滚动对象
		var defa	= {  //默认值
			direction	: 'up',
			next		: 'next',
			prev		: 'prev',
			play		: 'play',
			pause		: 'pause',
			autoPlay	: false,
			perPage		: 22,
			step		: 1,
			waitTime	: 2000,
			delayTime	: 500,
			length		: 0,
			conType		: 'ul > li',
			controller	: '',
			cType		: 'click',
			loop		: true
		}
		var opts	= $.fn.extend(defa, opt);
		var dArray	= {  //方向JSON
			'top'		: 0,
			'up'		: 0,
			'bottom'	: 1,
			'down'		: 1,
			'left'		: 2,
			'right'		: 3
		};
		var marqId	= [null, null];  //时间ID
		var marqInd	= 0;  //当前序号
		if (opts.length > 0)
		{
			var marqLen	= opts.length;
		}
		else
		{
			var marqLen	= _this.find('>' + opts.conType).length;
		}
		var hasCallBack		= arguments[1] ? true : false;
		var hasEndCallBack	= arguments[2] ? true : false;
		if (typeof opts.direction == 'string')
		{
			opts.direction = dArray[opts.direction.toString().toLowerCase()];
		}
		if (opts.direction < 0 || opts.direction > 3)
		{
			opts.direction = 0;
		}

		if (opts.loop)
		{
			//生成HTML
			if (opts.direction <= 1)
			{
				_this.html('<div>' + _this.html() + '</div><div>' + _this.html() + '</div>');
			}
			else
			{
				_this.html('<div style="width:8000px;"><div style="float:left;">' + _this.html() + '</div><div style="float:left;">' + _this.html() + '</div></div>');
			}
		}

		//初始化
		function initialize ()
		{
			clsTimeId();
			var isWait	= arguments[0] == null ? true : false;

			if (isWait)
			{
				marqId[1]	= setInterval(function(){
					scroll();
				}, opts.waitTime);
			}
			else
			{
				scroll();
				if (arguments[1] != null) opts.direction = parseInt(arguments[1]);
				if (opts.autoPlay)
				{
					initialize();
				}
			}
		}

		//控制器
		if (opts.controller != '')
		{
			var cs = $(opts.controller);
			cs.each(function(i){
				$(this).unbind(opts.cType);
				$(this).bind(opts.cType, function(){
					if (opts.cType == 'mouseover')
					{
						var cid = setTimeout(function(){
							marqInd = i - 1;
							pause();
							initialize(false);
						}, 100);
						$(this).bind('mouseout', function(){
							if (cid) clearTimeout(cid);
						});
					}
					else
					{
						marqInd = i - 1;
						pause();
						initialize(false);
					}
				});
			});
		}

		//滚动核心函数
		function scroll ()
		{
			switch (opts.direction)
			{
				case 0:  //向上
					_this.animate({
						'scrollTop' : (marqInd + opts.step) * opts.perPage
					}, opts.delayTime, 'swing', function(){
						marqInd	= marqInd + opts.step;
						if (marqInd >= marqLen)
						{
							if (opts.loop)
							{
								marqInd	= 0;
								_this.scrollTop(0);
							}
							else
							{
								pause();
								if (hasEndCallBack) endCallback('end');
							}
						}
						if (hasCallBack) callback(marqInd);
					});
				break;

				case 1:  //向下
					_this.animate({
						'scrollTop' : (marqInd - opts.step) * opts.perPage
					}, opts.delayTime, 'swing', function(){
						marqInd	= marqInd - opts.step;
						if (marqInd <= 0)
						{
							if (opts.loop)
							{
								marqInd	= marqLen;
								_this.scrollTop(marqLen * opts.perPage);
							}
							else
							{
								pause();
								if (hasEndCallBack) endCallback('begin');
							}
						}
						if (hasCallBack) callback(marqInd);
					});
				break;

				case 2:  //向左
					_this.animate({
						'scrollLeft' : (marqInd + opts.step) * opts.perPage
					}, opts.delayTime, 'swing', function(){
						marqInd	= marqInd + opts.step;
						if (marqInd >= marqLen)
						{
							if (opts.loop)
							{
								marqInd	= 0;
								_this.scrollLeft(0);
							}
							else
							{
								pause();
								if (hasEndCallBack) endCallback('end');
							}
						}
						if (hasCallBack) callback(marqInd);
					});
				break;

				case 3:  //向右
					_this.animate({
						'scrollLeft' : (marqInd - opts.step) * opts.perPage
					}, opts.delayTime, 'swing', function(){
						marqInd	= marqInd - opts.step;
						if (marqInd <= 0)
						{
							if (opts.loop)
							{
								marqInd	= marqLen;
								_this.scrollLeft(marqLen * opts.perPage);
							}
							else
							{
								pause();
								if (hasEndCallBack) endCallback('begin');
							}
						}
						if (hasCallBack) callback(marqInd);
					});
				break;
			}
		}

		//暂停
		function pause ()
		{
			_this.stop();
			clsTimeId();
		}
		//继续
		function cont ()
		{
			if (opts.autoPlay)
			{
				initialize();
			}
		}

		//如果自动播放，则开启滚动
		if (opts.autoPlay)
		{
			initialize();
		}

		//添加控制按钮：向左，向右，暂停/播放
		if ($('#' + opts.next).length == 0)
		{
			$('body').append('<div id="'+ opts.next +'" style="display:none;"></div>');
		}
		$('#' + opts.next).click(function(){
			pause();
			initialize(false);
			return false;
		});

		if ($('#' + opts.prev).length == 0)
		{
			$('body').append('<div id="'+ opts.prev +'" style="display:none;"></div>');
		}
		$('#' + opts.prev).click(function(){
			pause();
			switch (opts.direction)
			{
				case 0:
					opts.direction = 1;
					initialize(false, 0);
				break;

				case 1:
					opts.direction = 0;
					initialize(false, 1);
				break;

				case 2:
					opts.direction = 3;
					initialize(false, 2);
				break;

				case 3:
					opts.direction = 2;
					initialize(false, 3);
				break;
			}
			return false;
		});

		if ($('#' + opts.pause).length == 0)
		{
			$('body').append('<div id="'+ opts.pause +'" style="display:none;"></div>');
		}
		$('#' + opts.pause).click(function(){
			pause();
			return false;
		});

		if ($('#' + opts.play).length == 0)
		{
			$('body').append('<div id="'+ opts.play +'" style="display:none;"></div>');
		}
		$('#' + opts.play).click(function(){
			initialize();
			return false;
		});
		//===============

		//添加鼠标移入/移出 事件
		_this.hover(function(){
			pause();
		}, function(){
			cont();
		});

		//清除时钟
		function clsTimeId ()
		{
			var id = arguments[0] == null ? 1 : arguments[0];
			if (marqId[id] != null)
			{
				clearInterval(marqId[id]);
			}
		}
	}
})(jQuery);

(function($)
{
	$.fn.loadpic = function (opt, callback)
	{
		var _this		= $(this);
		var defa		= {
			src		: '',
			wrapObj	: null
		}
		var opts		= $.fn.extend(defa, opt);
		var hasCallBack	= arguments[1] ? true : false;

		_this.hide();

		var _img = new Image();
		$(_img).load(function(){
			img		= {};
			img.w	= _img.width;
			img.h	= _img.height;

			var imgNew	= $.resizePic({
				'w' : $(opts.wrapObj).width(),
				'h' : $(opts.wrapObj).height()
			}, img);
			var imgMarg = $.centerPic({
				'w' : $(opts.wrapObj).width(),
				'h' : $(opts.wrapObj).height()
			}, img);
			_this.css({
				width		: img.w,
				height		: img.h,
				marginLeft	: imgMarg.left,
				marginTop	: imgMarg.top
			});

			_this.attr('src', opts.src).fadeIn('slow');
			if (hasCallBack) callback(imgNew, imgMarg);  //返回当前图片的宽高和上左margin
		}).attr('src', opts.src);
		return _this;
	}
})(jQuery);

jQuery.resizePic = function (maxWrap, img)
{
	if (img.w > 0 && img.h > 0)
	{
		var percent = (maxWrap.w / img.w > maxWrap.h / img.h) ? maxWrap.w / img.w : maxWrap.h / img.h;
		if (percent <= 1)
		{
			img.w = img.w * percent;
			img.h = img.h * percent;
		}
	}
	return img;
}

jQuery.centerPic = function (maxWrap, img)
{
	return {
		'top'	: ((maxWrap.h - img.h) / 2) <= 0 ? 0 : (maxWrap.h - img.h) / 2,
		'left'	: (maxWrap.w - img.w) / 2
	};
}

jQuery.scrollto = function (obj, target)
{
	if (obj == null) obj = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html, body');
	//obj.animate({scrollTop : target}, 1000);
	return false;
}

jQuery.scrollto2 = function (obj, target)
{
	if (obj == null) obj = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html, body');
	obj.animate({scrollTop : target.y, scrollLeft : target.x}, 1000);
	return false;
}