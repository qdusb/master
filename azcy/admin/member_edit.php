<?
require(dirname(__FILE__) . "/init.php");
require(dirname(__FILE__) . "/isadmin.php");
require(dirname(__FILE__) . "/config.php");


//高级管理权限
if ($session_admin_grade != ADMIN_HIDDEN && $session_admin_grade != ADMIN_SYSTEM && hasInclude($session_admin_advanced, MESSAGE_ADVANCEDID) == false)
{
	info("没有权限！");
}


$id		= (int)$_GET["id"];
$page	= (int)$_GET["page"] > 0 ? (int)$_GET["page"] : 1;

$listUrl = "member_list.php?page=$page";


//连接数据库
$db = new onlyDB($config["db_host"], $config["db_user"], $config["db_pass"], $config["db_name"]);


if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$sortnum	= (int)$_POST["sortnum"];
	$user_name	=$_POST['user_name'];
	$pass		=md5(trim($_POST['pass']));
	$real_name	=$_POST['real_name'];
	$phone		=$_POST['phone'];
	$email		=$_POST['email'];
	$sex		=$_POST['sex'];
	$job		=$_POST['job'];
	$age		=(int)$_POST['job'];
	$address	=$_POST['address'];
	$level		=$_POST['level'];
	$remark		= htmlspecialchars(trim($_POST["remark"]));
	
	if($id<1)
	{
		$sql = "select count(*) as cnt from member where user_name='$user_name'";
		$rst = $db->query($sql);
		$row = $db->fetch_array($rst);
		if($row['cnt']>0)
		{
			info("用户名已存在，不能新增");
		}
		else
		{
			$create_time=date("Y:m:d H:s");
			$aid=$db->getMax("member", "id") + 1;
			$sql = "insert into member(id,sortnum,user_name,pass,real_name,job,sex,age,phone,email,address,remark,level,create_time) values($aid,$sortnum,'$user_name','$pass','$real_name','$job','$sex','$age','$phone','$email','$address','$remark',$level,'$create_time')";
			echo $sql;
			$rst = $db->query($sql);
			$db->close();
			if(!$rst)
			{
				
				info("新增失败");
			}
			else
			{
				header("location: $listUrl");
				exit;
			}
		}
		
	}
	else
	{
		if($pass=="")
		{
			$sql = "update member set sortnum=$sortnum, user_name='$user_name',level=$level, real_name='$real_name',job='$job',sex='$sex',age='$age',phone='$phone',email='$email',address='$address',remark='$remark' where id=$id";
		}
		else
		{
			$sql = "update member set sortnum=$sortnum, user_name='$user_name',level=$level, pass='$pass',real_name='$real_name',job='$job',sex='$sex',age='$age',phone='$phone',email='$email',address='$address',remark='$remark' where id=$id";
		}
		
		$rst = $db->query($sql);
		$db->close();
		if ($rst)
		{
			header("location: $listUrl");
			exit;
		}
		else
		{
			info("修改失败！");
		}
	}
	
}

if(!empty($id))
{
	$sql = "select * from member where id=$id";
	$rst = $db->query($sql);
	if ($row = $db->fetch_array($rst))
	{
		$name			= $row["name"];
		$sortnum		= $row["sortnum"];
		$user_name		= $row["user_name"];
		$pass			= $row["pass"];
		$phone			= $row["phone"];
		$email			= $row["email"];
		$sex			= $row["sex"];
		$job			= $row["job"];
		$address		= $row["address"];
		$remark			= $row["remark"];
		$real_name		= $row["real_name"];
		$level			= $row["level"];
	
		if ($state == 0)
		{
			$sql = "update message set state=1 where id=$id";
			$db->query($sql);
			$state = 2;
		}
	}
	else
	{
		$db->close();
		info("指定的记录不存在！");
	}
}
else
{
	//$id=$db->getMax("member", "id", "") + 1;
	$sortnum=$db->getMax("member", "sortnum", "") + 10;
}
?>


<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Expires" content="-1000">
		<link href="images/admin.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="images/common.js"></script>
		<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
		<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
		<script>
			KindEditor.ready(function(K) {
				var editor = K.create('textarea[name="reply"]', {
					width : '700px',
					height : '200px',
					pasteType : 1,
					items : [
							'source', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
							'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
							'insertunorderedlist', '|', 'link'],
					afterCreate : function() {
						var self = this;
						K.ctrl(document, 13, function() {
							self.sync();
							K('form[name=form1]')[0].submit();
						});
						K.ctrl(self.edit.doc, 13, function() {
							self.sync();
							K('form[name=form1]')[0].submit();
						});
					}
				});
			});
		</script>
		<script type="text/javascript">
			function check(form)
			{
				if (form.sortnum.value.match(/\D/))
				{
					alert("请输入合法的序号！");
					form.sortnum.focus();
					return false;
				}

				if (form.user_name.value=="")
				{
					alert("请输入用户名！");
					form.user_name.focus();
					return false;
				}
				return true;
			}
		</script>
	</head>
	<body>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr class="position">
				<td class="position">当前位置: 管理中心 -&gt; 高级管理 -&gt; 留言簿</td>
			</tr>
		</table>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr height="30">
				<td>
					<a href="<?=$listUrl?>">[返回列表]</a>&nbsp;
				</td>
			</tr>
		</table>
		<form name="form1" action="" method="post" onSubmit="return check(this);">
			<table width="100%" border="0" cellSpacing="1" cellPadding="0" align="center" class="editTable">
				<tr class="editHeaderTr">
					<td class="editHeaderTd" colSpan="2">会员管理</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">序号</td>
					<td class="editRightTd"><input type="text" name="sortnum" maxlength="20" size="24" value="<?=$sortnum?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">会员用户名</td>
					<td class="editRightTd"><input type="text" name="user_name" maxlength="20" size="60" value="<?=$user_name?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">会员密码</td>
					<td class="editRightTd"><input type="password" name="pass" maxlength="20" size="60" value=""/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">会员等级</td>
					<td class="editRightTd">
					<select name="level" style="width:80px;">
						<option value="0"  <? if($level == 0) echo "selected";?>>普通用户</option>
						<option value="1"  <? if($level == 1) echo "selected";?>>初级会员</option>
						<option value="2"  <? if($level == 2) echo "selected";?>>中级会员</option>
						<option value="3"  <? if($level == 3) echo "selected";?>>高级会员</option>
					</select>
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">会员姓名</td>
					<td class="editRightTd"><input type="text" name="real_name" maxlength="20" size="60" value="<?=$real_name?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">电话</td>
					<td class="editRightTd"><input type="text" name="phone" maxlength="20" size="60" value="<?=$phone?>"/></td>
				</tr>
	
				<tr class="editTr">
					<td class="editLeftTd">电子邮箱</td>
					<td class="editRightTd"><input type="text" name="email" maxlength="20" size="60" value="<?=$email?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">性别</td>
					
					<td class="editRightTd">
					<input type="radio" name="sex" value="男" <? if ($sex == "男") echo "checked";?> />&nbsp;男&nbsp;
					<input type="radio" name="sex" value="女" <? if ($sex == "女") echo "checked";?>/>&nbsp;女&nbsp;
					</td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">工作单位</td>
					<td class="editRightTd"><input type="text" name="job" maxlength="20" size="60" value="<?=$job?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">家庭地址</td>
					<td class="editRightTd"><input type="text" name="address" maxlength="20" size="60" value="<?=$address?>"/></td>
				</tr>
				<tr class="editTr">
					<td class="editLeftTd">备注</td>
					<td class="editRightTd"><input type="text" name="remark" maxlength="20" size="60" value="<?=$remark?>"/></td>
				</tr>
				
				<tr class="editFooterTr">
					<td class="editFooterTd" colSpan="2">
						<input type="submit" value=" 确 定 ">
						<input type="reset" value=" 重 填 ">
					</td>
				</tr>
			</table>
		</form>
		<?
		$db->close();
		?>
	</body>
</html>
