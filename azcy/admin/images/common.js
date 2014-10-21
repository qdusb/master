//检查参数是否是日期加上时间
function isDateTime(str)
{
	var dateTime;

	dateTime = str.split(" ");

	if (dateTime.length != 2)
	{
		return false;
	}

	if (!isDate(dateTime[0]))
	{
		return false;
	}

	if (!isTime(dateTime[1]))
	{
		return false;
	}

	return true;
}


//检查参数是否是时间
function isTime(str)
{
	var time;

	time = str.split(":");

	if (time.length != 3)
	{
		return false;
	}


	if (!/^[0-9]{1,2}$/.exec(time[0]))
	{
		return false;
	}
	if (!/^[0-9]{1,2}$/.exec(time[1]))
	{
		return false;
	}
	if (!/^[0-9]{1,2}$/.exec(time[2]))
	{
		return false;
	}


	time[0] = parseInt(time[0], "10");
	time[1] = parseInt(time[1], "10");
	time[2] = parseInt(time[2], "10");

	if (time[0] < 0 || time[0] > 24)
	{
		return false;
	}
	if (time[1] < 0 || time[1] > 60)
	{
		return false;
	}
	if (time[2] < 0 || time[2] > 60)
	{
		return false;
	}


	return true;
}

//检查参数是否是日期
function isDate(str)
{
	var date;

	date = str.split("-");

	if (date.length != 3)
	{
		return false;
	}


	if (!/^(19|20){0,1}[0-9]{2}$/.exec(date[0]))
	{
		return false;
	}
	if (!/^[0-1]{0,1}[0-9]{1}$/.exec(date[1]))
	{
		return false;
	}
	if (!/^[0-3]{0,1}[0-9]{1}$/.exec(date[2]))
	{
		return false;
	}


	date[0] = parseInt(date[0], "10");
	date[1] = parseInt(date[1], "10");
	date[2] = parseInt(date[2], "10");

	if (date[1] < 1 || date[1] > 12)
	{
		return false;
	}
	if (date[2] < 1 || date[2] > 31)
	{
		return false;
	}
	if (date[1] == 4 || date[1] == 6 || date[1] == 9 || date[1] == 11)
	{
		if (date[2] > 30)
		{
			return false;
		}
	}
	if (date[1] == 2)
	{
		if (date[2] > 29)
		{
			return false;
		}
	}


	return true;
}

//检查是否选择了条目，并提示是否设置选中的条目的状态
function stateCheck(obj)
{
	var hasChecked = false;

	if (!obj)
	{
		document.getElementById("state").options[0].selected = true;
		return false;
	}

	if (obj.length)
	{
		for (i = 0; i < obj.length; i++)
		{
			if (obj[i].checked)
			{
				hasChecked = true;
				break;
			}
		}
	}
	else
	{
		hasChecked = obj.checked;
	}

	if (!hasChecked)
	{
		alert('请先选择准备设置状态的记录');
		document.getElementById("state").options[0].selected = true;
		return false;
	}
	else
	{
		if (document.getElementById("state").options[document.getElementById("state").selectedIndex].value == "-1")
		{
			alert('请选择状态');
			return false;
		}


		return true;
	}
}

//反向选中指定的选择框对象
function reverseCheck(obj)
{
	if (!obj) return;
	if (!obj.length)
	{
		if (obj.desabled) return false;
		obj.checked = !obj.checked;
	}

	for (i = 0; i < obj.length; i++)
	{
		if (obj[i].disabled) continue;
		if (obj[i].checked)
		{
			obj[i].checked = false;
		}
		else
		{
			obj[i].checked = true;
		}
	}
}



//检查是否选择了条目，并提示是否删除选中的条目
function delCheck(obj)
{
	var hasChecked = false;

	if (!obj)
	{
		return false;
	}

	if (obj.length)
	{
		for (i = 0; i < obj.length; i++)
		{
			if (obj[i].checked)
			{
				hasChecked = true;
				break;
			}
		}
	}
	else
	{
		hasChecked = obj.checked;
	}

	if (!hasChecked)
	{
		alert('请先选择准备删除的记录');
		return false;
	}
	else
	{
		if (confirm('即将删除所有选择的记录, 且该操作不能恢复! 是否继续 ?'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}


//是否删除 (一条记录)
function del()
{
	if (confirm('即将删除此记录 , 且该操作不能恢复! 是否继续 ?'))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function pic(id, tableName, idName, picName, widthName, heightName)
{
	var str
	str = "upload_pic.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName + "&widthName=" + widthName + "&heightName=" + heightName;
	var p = window.open(str, "pic", "width=550, height=400, scrollbars=1, left=100, top=100");
}
function adver(id, tableName, idName, picName, widthName, heightName)
{
	var str
	str = "upload_adver.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&picName=" + picName + "&widthName=" + widthName + "&heightName=" + heightName;
	var p = window.open(str, "adver", "width=550, height=400, scrollbars=1, left=100, top=100");
}
function file(id, tableName, idName, fileName, filenameName)
{
	var str
	str = "upload_file.php?id=" + id + "&tableName=" + tableName + "&idName=" + idName + "&fileName=" + fileName + "&filenameName=" + filenameName;
	var p = window.open(str, "file", "width=550, height=300, scrollbars=1, left=100, top=100");
}

// 新加函数  Cole (程启明) 2008/09/18  15:00

function managePic(table, id)
{
	window.open("manage_pic.php?table=" + table + "&id=" + id + "", "", "width=800, height=600, top=150, scrollbars=yes");
}

//检查操作
function operationCheck(obj)
{
	var hasChecked = false;

	if (!obj)
	{
		return false;
	}

	if (obj.length)
	{
		for (i = 0; i < obj.length; i++)
		{
			if (obj[i].checked)
			{
				hasChecked = true;
				break;
			}
		}
	}
	else
	{
		hasChecked = obj.checked;
	}

	if (!hasChecked)
	{
		alert("请先选择准备操作的记录");
		return false;
	}

	if (document.form1.operation.value == "")
	{
		alert("请选择操作选项");
		document.form1.operation.focus();
		return false;
	}

	if (confirm("即将操作所有选择的记录, 且该操作不能恢复! 是否继续 ?"))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function optionCheck()
{
	if (document.form1.operation.value == "move" || document.form1.operation.value == "copy")
	{
		document.getElementById("end_select").style.display = "block";
	}
	else
	{
		document.getElementById("end_select").style.display = "none";
	}
}