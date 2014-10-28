//创建和初始化地图函数：
function baiduMapInit(long,lat,title){
	// [FF]切换模式后报错
		if (!window.BMap) {
		return;
	}

	long=long||"117.282117";
	lat=lat||"31.861612";
	title=title||"安徽网新";
	
	createMap(long,lat);//创建地图
	setMapEvent();//设置地图事件
	addMapControl();//向地图添加控件

	// 创建标注
	var point = new BMap.Point(long,lat);
	
	var marker = new BMap.Marker(point,"ditu");
	map.addOverlay(marker); // 将标注添加到地图中
	var label = new BMap.Label(title,{"offset":new BMap.Size(10,-20)});
	label.setStyle({ color : "red", fontSize : "12px",border:"0px"});
	marker.setLabel(label);
}

//创建地图函数：
function createMap(long,lat){
	delete $("#dituContent").html();
	var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
	var point = new BMap.Point(long,lat);//定义一个中心点坐标
	map.centerAndZoom(point,17);//设定地图的中心点和坐标并将地图显示在地图容器中
	window.map = map;//将map变量存储在全*/局
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