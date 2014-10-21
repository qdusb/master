<?php
abstract class Filter { //filter parent class
private $blackstr = array();
private $whitestr = array();
function filtit($str) {
//do something
}
}
class LoginFilter extends Filter { //for user login filte username(过滤注册的用户名)
function filtit($str) {
$this -> blackstr = array(
'/[\x7f-\xff]/', //filter chinese include chinese symbol
'/\W/' //filter all english symbol
);
return preg_replace($this->blackstr, '', $str);
}
}
class EditorFilter extends Filter { //for article editor filter(过滤在线编辑器内容)
function filtit($str) {
$this -> blackstr = array(
'/\&/',
'/\'/',
'/\"/',
'/\</',
'/\>/',
'/\\\\/',
'/\//',
'/-/',
'/\*/',
'/ /'
);
$this -> whitestr = array(
'&',
"'",
'"',
'<',
'>',
'/',
"-",
"*",
" "
);
return preg_replace($this->blackstr, $this -> whitestr, $str);
}
}
class SQLFilter extends Filter { //for filte sql query string(过滤如查询或其它sql语句)
function filtit($str) {
$this -> blackstr = array(
"/\'/",
"/-/"
);
return preg_replace($this->blackstr, '', $str);
}
}
class FileNameFilter extends Filter { //for filte a file name(过滤文件名如下载文件名)
function filtit($str) {
$this -> blackstr = array(
"/[^A-za-z0-9_\.]|\\\\|\^|\[|\]/"
);
return preg_replace($this->blackstr, '', $str);
}
}
?> 