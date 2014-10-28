/*
	Author Reevesc IBW
	2014/1/13 create
	2014/1/17 update
*/

/*
Config 配置ajax文件的路径和版本号，作者，更新时间
*/
var Config=function(){throw new "Config cnnot be instantiated"}
Config.ajaxURL="http://webapp.5.ibw.cc/ajax/";
//Config.ajaxURL="http://localhost/Html5Web/IBW/ajax/";

Config.version=1.0;
Config.author="Reevesc IBW";
Config.updated="2014/01/18";

//获取页面参数
function getURLParam(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); 
	var r = window.location.search.substr(1).match(reg);  
	if (r!=null) return unescape(r[2]); return null;
}
//检查版本
function checkVersion(){
	/*
	检查版本信息，version版本号，address 此版本下载地址
	*/
	$.ajax({type:'POST',url:Config.ajaxURL+"version.php",data:{version:Config.version},success:function(data,textStatus){
		data=eval("("+data+")")
		var version=data.version;
		var address=data.address;
		version=2;
		if(version>Config.version){
			navigator.notification.confirm("有新版本,是否更新新版本!",isUpdateVersion,"检查更新","更新,取消");
		}
		
		},error:function(){
			navigator.notification.alert("检查更新失败",null,"检查更新");
		}
	});
}
function isUpdateVersion(button){
	if(parseInt(button)==1){
		loadFile();
	}
}
function downloadFile(sourceUrl,targetUrl){  
	var fileTransfer = new FileTransfer();   
	var uri = encodeURI(sourceUrl);    

	fileTransfer.download(  
	uri,targetUrl,function(entry){    
		 console.log("成功下载网络文件");  
	},function(error){  
		console.log("下载网络文件出现错误");  
	});    
}  
function loadFile() { 

	window.requestFileSystem(  
    LocalFileSystem.PERSISTENT, 0, function onFileSystemSuccess(fileSystem) { 
		var dirName="ibw/download";
        fileSystem.root.getDirectory(dirName, {create: true, exclusive: false}, function(dirEntry){  
            var dirPath = dirEntry.fullPath;  
            console.log(dirPath);  
			var fileName="ibw.apk";
            var fileTransfer = new FileTransfer();  
            var filePath = dirPath +"/"+ fileName;  
			var downUrl="http://webapp.5.ibw.cc/ibw.apk";
            fileTransfer.download(downUrl, filePath,  
                 function(theFile) {  
				 	alert("down Sucess");
                 },  
                 function(error) {  
					alert(error.code); 
                 }  
             );  
        }, fail);  
       },   
fail); 
}

function getFileSucess(fileSystem){
	 fileSystem.root.getDirectory("ibw/download", {create:true,exclusive : false},function(fileEntry){  },   
                function(error){  alert("Failed to retrieve Directory:" + error.code);    });  
  
	 var _localFile = "ibw_mobile/download/ibw.apk";  
	 var _url = "http://webapp.5.ibw.cc/download.php";  
	 fileSystem.root.getFile(_localFile, {create:true}, function(fileEntry){  
			var targetURL = fileEntry.toURL();  
			downloadFile(_url,targetURL);   
		 },function(){  
			navigator.notification.alert("下载文件出错"); 
			return;
		 }); 
}
function getFileSucessFail(){
	//console.log("加载文件系统出现错误");  
	
}
/*
	获取ajax数据 domID，file->ajax file options,call->callback function
	获得的数据是一段html代码，赋值给domID
*/
function ajaxData(domID,file,options,call){
	
	//调用数据的loading
	var ajaxbg = $("#background,#progressBar"); 
	ajaxbg.hide(); 
		
	$(domID).ajaxStart(function () { 
		ajaxbg.show(); 
	}).ajaxStop(function () { 
		ajaxbg.hide(); 
	}); 
	
	var opts=options||{};
	
	//callBack 回调函数 domID 填充返回数据的dom
	var callBack=call||function(){}
	$.ajax({type:'POST',url:Config.ajaxURL+file,data:opts,success:function(data,textStatus){
		delete $(domID).html();
		$(domID).html(data);
			callBack();
		},error:function(){
			$(domID).html("<span>内容获取失败......</span>");
		}
	});
}
//分页栏目调取信息内容
function getPageData(s,classID,pageID){
	/*
	s 为点击的页码标签
	domID 填充返回数据的dom，此块是针对id为subviewports模板的调用，如果模板发生更改，此块需要更改
	*/
	var page_id=pageID||1;
	var class_id=classID||"101101";
	var id=$(s).parents(".viewports").attr("id");
	var domID="#"+id+" .context";
	
	ajaxData(domID,"info.php",{class_id:class_id,page_id:page_id});
	$(document).scrollTop(0);
}
//在subviewports的模板下，针对二级栏目调取数据的处理函数
function getWebData(s,class_id){
	
	/*
	s 为点击的二级栏目
	domID 填充返回数据的dom，此块是针对id为subviewports模板的调用，如果模板发生更改，此块需要更改
	*/
	$(s).siblings("dd").removeClass("active");
	$(s).addClass("active");
	
	var id=$(s).parents(".viewports").attr("id");
	var domID="#"+id+" .context";
	ajaxData(domID,"info.php",{class_id:class_id});
	$(document).scrollTop(0);
}
// --------------------------------------------------------------------------------------//
/*
以上为数据处理的函数，现在是phonegap 的功能
*/
//顾名思义 退出app
function quit(){
	navigator.app.exitApp();
}
//获取摄像头
function capturePhoto(){
	 navigator.camera.getPicture(onPhotoDataSuccess, onFail, { quality: 50 });
}
function onFail(message){
 	
}
function onPhotoDataSuccess(imageData) {
	$("#smallImage").show().width($("body").width()).attr("src","data:image/jpeg;base64," + imageData);
}
function onBackKeyDown() {
	if(prevDoms.length>=2){ 
		gotoPrevWeb();
	}else{
		if(confirm("是否退出应用程序")){
			quit();
		}
	}
} 

function onMenuKeyDown() { 
	//处理菜单按钮操作 
	gotoWeb("#phoneMenu",2);
}
function onOnline() {
	// 处理online事件
}
function onOffline() {
	// 处理offline事件 
}
/* --------------------------------------------------------------------------------------*/
/*
页面显示....
*/
//显示百度地图
function showMap(long,lat,title){
	
	long=long||"117.282117";
	lat=lat||"31.861612";
	title=title||"百度地图";
	
	var domID=prevDoms[prevDoms.length-1];
	if(domID!= "#baidumapweb"){
		$(domID).hide();
		prevDoms.push("#baidumapweb");
	}
	$("#baidumapweb").show().find(".title").html(title);;
	baiduMapInit(long,lat,title);
}
/*
	获取本地地址，在模拟器上测试成功 但是处理时间太长...
*/
function showMyselfMap(){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(showMapSucess, handleMapError, {enableHighAccuracy:true, maximumAge:1000});
	}else{
		alert("获取地理位置失败");
	}
	
}
function showMapSucess(value)
{
	var long = value.coords.longitude;
	var lat = value.coords.latitude;
	var title="我的当前位置";
	showMap(long,lat,title);
}
function handleMapError(value)
{
	switch(value.code){
	case 1:
	alert("位置服务被拒绝");
	break;
	case 2:
	alert("暂时获取不到位置信息");
	break;
	case 3:
	alert("获取信息超时");
	break;
	case 4:
	alert("未知错误");
	break;
	}
}
//调用display模块 通过ajax数据调用 给display模块赋值
function showDisplay(id){
	if(id==""||id==null||id=="null"){
		return;
	}
	var domID=prevDoms[prevDoms.length-1]; 
	$(domID).hide();
	prevDoms.push("#displayweb");
	
	$("#displayweb").show();
	
	$.ajax({type:'POST',url:Config.ajaxURL+"display.php",data:{id:id},success:function(data,textStatus){
		var webdata={};	
		var pd=eval("("+data+")");
		webdata=$.extend({},pd);
		var displayweb=$("#displayweb");
		delete displayweb.html();
		var html=bt('display',webdata);
		displayweb.html(html);
		displayweb.find(".display_article").html(pd.content);
		$(document).scrollTop(0);
		}
	});
}

//因为本身就一个页面 采用了ajax的数据调用的方法，导致系统的backbutton 失效，这里重写了返回上一步的操作函数
function gotoPrevWeb(){
	var domID=prevDoms.pop(); 
	$(domID).hide();  
	var prevID=prevDoms[prevDoms.length-1];
	$(document).scrollTop(0);
	$(prevID).show();
}

//跳转页面 原则上是跳转至分页,当然也能跳转至主页
function gotoWeb(domID,isAjax){
	isAjax=isAjax||0;
	var index=prevDoms.lastIndexOf(domID);
	if(index<prevDoms.length-1){
		prevDoms.push(domID);
	}
	if(prevDoms.length>=2){
		hideID=prevDoms[prevDoms.length-2];
		if(hideID)$(hideID).hide();
	}
	$(document).scrollTop(0);
	if(typeof $(domID).attr("isAjax")=="undefined"){
		$(domID).attr("isAjax",0)
	}
	$(domID).show();
	if(index<=0&&$(domID).attr("isAjax")==0){
		var dd=$(domID).find("dd:eq("+0+")");
		var class_id=dd.attr("id");
		getWebData(dd,class_id);
		$(domID).attr("isAjax",2);
	}
}
// --------------------------------------------------------------------------------------//
/*
 表单类别的处理函数...
*/
//提交在线留言表单
function sendMessage(){
	var title=$("#IBWMessageTitle").val();
	var content=$("#IBWMessageContent").val();
	var options={"title":title,"content":content};
	$.ajax({type:'POST',url:Config.ajaxURL+"message.php",data:options,success:function(data,textStatus){
			var returnInfo=eval("("+data+")");
			if(returnInfo.status=="ok"){
			//提交成功
			}else{
			//提交失败
			}
		},error:function(){
		//提交失败
		}
	});
}

//提交工作申请表单
function sendJobApply(options){
	var name=$("#IBWJobApplicantsName").val();
	var phone=$("#IBWJobApplicantsPhone").val();
	var job=$("#IBWJobName").val();
	
	var options={"name":name,"phone":phone,"job":job};
	options=options||{};
	$.ajax({type:'POST',url:Config.ajaxURL+"job.php",data:options,success:function(data,textStatus){
			var returnInfo=eval("("+data+")");
			if(returnInfo.status=="ok"){
			//提交成功
			}else{
			//提交失败
			}
		},error:function(){
			//提交失败
		}
	});
}

/*-----------------------我是万恶的分界线--------------------------------*/
/*
需要重新配置的数据.............
*/
document.addEventListener("deviceready", onDeviceReady, false); 

function onDeviceReady() {
	document.addEventListener("backbutton", onBackKeyDown, false); 
	document.addEventListener("menubutton", onMenuKeyDown, false); 
	//document.addEventListener("online", onOnline, false);
	//pictureSource=navigator.camera.PictureSourceType;
	//destinationType=navigator.camera.DestinationType;
	
}
	
//存储操作的页面的数组......
var prevDoms=["#mainweb"];
var pictureSource,destinationType;

/*
	栏目设置......
	灰常想用异步调用的方式....但是大大影响初次启动的速度
*/
/*
	异步获取menu
*/
function getMenu(){
	
}
var menu={
	"101":{"id":"about","title":"关于网新","101101":"企业介绍","101102":"企业文化","101103":"我们的优势","101104":"企业环境"},
	"102":{"id":"news","title":"企业动态","102101":"网新动态","102102":"员工风采","102103":"优惠活动"},
	"103":{"id":"solution","title":"解决方案","103101":"小型企业解决方案","103102":"大型企业解决方案","103103":"品牌营销解决方案"},
	"104":{"id":"case","title":"成功案例","104101":"所有案例"," ":"网站建设类","104103":"百度推广类","104104":"400电话类","104105":"可信网站认证类","104106":"263企业邮局类","104107":"域名空间服务器","104108":"整合营销类"},
	"105":{"id":"web","title":"网站建设","105101":"网站建设"},
	"106":{"id":"baidu","title":"百度推广","106101":"百度推广"},
	"107":{"id":"phone","title":"手机网站","107101":"手机网站"},
	"108":{"id":"moreProduct","title":"更多产品","108101":"网站SEO","108102":"400电话","108103":"263企业邮局","108104":"域名空间服务器","108105":"可信网站认证","108106":"网络整合营销","108107":"合肥美食网","108108":"网站联盟广告"},
	"109":{"id":"message","title":"在线咨询","109101":"在线咨询"},
	"110":{"id":"hr","title":"人才招聘","110101":"销售副总裁","110102":"研发副总裁","110103":"技术研发总监","110104":"销售总监","110105":"行政总监/经理"},
	"111":{"id":"contact","title":"联系信息","111101":"联系信息"}
	}

/*
	感谢百度模板提供者,模板模板 又是模板......
*/
var bt=baidu.template;

