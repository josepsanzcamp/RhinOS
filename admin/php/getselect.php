<?php
/*
 ____  _     _        ___  ____
|  _ \| |__ (_)_ __  / _ \/ ___|
| |_) | '_ \| | '_ \| | | \___ \
|  _ <| | | | | | | | |_| |___) |
|_| \_\_| |_|_|_| |_|\___/|____/

RhinOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2016 by Josep Sanz Campderrós
More information in http://www.saltos.org or info@saltos.org

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$head=0;$main=0;$tail=0;
include("inicio.php");
ob_start();
$table=getParam("table");
$has_perm=0;
if(check_permissions("select",$table)) $has_perm=1;
if(check_permissions("insert",$table)) $has_perm=1;
if(check_permissions("update",$table)) $has_perm=1;
if(!$has_perm) intro();
$variable=getParam("variable");
$default=getParam("default");
$temp=explode(".",$variable);
if(count($temp)==3) {
	$variable=$temp[0];
	$table=$temp[1];
	$j=$temp[2];
	$postvar=".$table.$j";
} else {
	$postvar="";
}
$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$variable'";
$result=dbQuery($query);
$row=dbFetchRow($result);
$text_ref=$row["text_ref"];
$table_ref=$row["table_ref"];
$value_ref=$row["value_ref"];
dbFree($result);
$query="SELECT * FROM db_forms WHERE tbl='$table_ref' AND row='".addslashes($text_ref)."'";
$result=dbQuery($query);
$row=dbFetchRow($result);
$tipo=$row["type"];
dbFree($result);
$temp=explode(":",$text_ref);
if($temp[0]=="concat") {
	unset($temp[0]);
	$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
}
$query="SELECT *,$text_ref FROM $table_ref WHERE `$value_ref`='$default'";
$result=dbQuery($query);
$valor="";
if($row=dbFetchRow($result)) $valor=$row[$text_ref];
$file="";
$size="";
$type="";
if(isset($row[$text_ref."_file"])) $file=$row[$text_ref."_file"];
if(isset($row[$text_ref."_size"])) $size=$row[$text_ref."_size"];
if(isset($row[$text_ref."_type"])) $type=$row[$text_ref."_type"];
dbFree($result);
$campo=$variable;
$_withtd=0;
switch($tipo) {
	case "text":
		putinput($campo,$valor,"show","","","");
		break;
	case "textarea":
		puttextarea($campo,$valor,"show");
		break;
	case "textareaold":
		puttextarea($campo,$valor,"show",false);
		break;
	case "select":
		putselect($table,$campo,"show",$valor);
		break;
	case "multiselect":
		putmultiselect($table,$campo,"show",$valor);
		break;
	case "file":
		putfile("$campo.$table.$j",$valor,$file,$size,$type,"show");
		break;
	case "photo":
		putphoto("$campo.$table.$j",$valor,$file,$size,$type,"show");
		break;
	case "password":
	case "md5password":
	case "sha1password":
		putinput($campo,$valor,"show","type='password'","","");
		break;
	case "boolean":
		putboolean($campo,$valor,"show");
		break;
	case "date":
		list($title,$text)=make_title_text("date",_LANG("getselect_button_date"),_LANG("getselect_button_date_title"),"");
		putinput($campo,substr($valor,0,10),"show","","width:100","&nbsp;<img src='lib/crystal/16x16/date.png' align='top' title='$title' />");
		break;
	case "time":
		list($title,$text)=make_title_text("clock",_LANG("getselect_button_time"),_LANG("getselect_button_time_title"),"");
		putinput($campo,substr($valor,0,5),"show","","width:100","&nbsp;<img src='lib/crystal/16x16/clock.png' align='top' title='$title' />");
		break;
	case "color":
		putcolor($campo,$table,$valor,$j,"show");
		break;
	case "ajaxselect":
		putajaxselect($table,$campo,"show",$valor);
		break;
	case "integer":
		putinput($campo,intval($valor),"show","","","");
		break;
	case "real":
	case "decimal":
	case "float":
	case "double":
	 putinput($campo,floatval($valor),"show","","","");
		break;
	default:
		putinput($campo,$valor,"show","","","");
		break;
}
disconnect();
$buffer=ob_get_clean();
ob_start_protected("ob_gzhandler");
header_powered();
header_expires(false);
echo $buffer;
ob_end_flush();
?>