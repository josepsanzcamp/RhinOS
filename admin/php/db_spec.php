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
function get_db_spec($tables="",$orders="") {
	global $dbname;
	global $error;

	$cache=set_db_cache("false");
	if(!$tables) $tables=array("db_config","db_tables","db_lists","db_forms","db_selects","db_dinamics");
	if(!$orders) $orders=array("id","position","tbl,position","tbl,position","tbl","dinamic");
	$texto="";
	$texto.="#\n";
	$texto.="DATABASE $dbname\n";
	$texto.="#\n";
	$texto.="\n";
	$count_tables=count($tables);
	for($i=0;$i<$count_tables;$i++) {
		$table=$tables[$i];
		$order=$orders[$i];
		$query="SELECT * FROM $table ORDER BY $order";
		capture_next_error();
		$result=dbQuery($query);
		$error_query=get_clear_error();
		if($error_query) {
			$error[]=$error_query;
		} else {
			$texto.="#\n";
			$texto.="TABLE $table\n";
			$texto.="#\n";
			$maxlength=array();
			$num_fields=dbNumFields($result);
			for($j=1;$j<$num_fields;$j++) {
				$temp=dbFieldName($result,$j);
				$len=mb_strlen($temp,"UTF-8")+3;
				$maxlength[$j]=$len;
			}
			while($row=dbFetchRow($result)) {
				for($j=1;$j<$num_fields;$j++) {
					$temp=dbFieldName($result,$j);
					$len=mb_strlen($row[$temp],"UTF-8")+3;
					if($len>$maxlength[$j]) $maxlength[$j]=$len;
				}
			}
			dbFree($result);
			$result=dbQuery($query);
			$texto.="SPEC ";
			for($j=1;$j<$num_fields;$j++) {
				$coma=($j<$num_fields-1)?",":"";
				$campo=dbFieldName($result,$j);
				$spaces=($j<$num_fields-1)?str_repeat(" ",$maxlength[$j]-mb_strlen($campo,"UTF-8")-3):"";
				$texto.="\"${campo}\"${coma}${spaces}";
			}
			$texto.="\n";
			while($row=dbFetchRow($result)) {
				$texto.="ROW  ";
				for($j=1;$j<$num_fields;$j++) {
					$coma=($j<$num_fields-1)?",":"";
					$temp=dbFieldName($result,$j);
					$campo=$row[$temp];
					$spaces=($j<$num_fields-1)?str_repeat(" ",$maxlength[$j]-mb_strlen($campo,"UTF-8")-3):"";
					$texto.="\"${campo}\"${coma}${spaces}";
				}
				$texto.="\n";
			}
			$texto.="\n";
			dbFree($result);
		}
	}
	set_db_cache($cache);
	return $texto;
}

function form_db_spec($texto) {
	openf1form();
	puthidden("func","save_db_spec.adm");
	escribe(get_migas());
	escribe();
	puterrores("900");
	openform("100%");
	openrow();
	settds("tdsh");
	putcolumn("<textarea name='db_spec' class='edits ui-state-default ui-corner-all' wrap='off'>$texto</textarea>");
	closerow();
	list($title,$text)=make_title_text("button_ok",_LANG("db_spec_button_ok"),_LANG("db_spec_button_ok_title"),_LANG("db_spec_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("db_spec_button_cancel"),_LANG("db_spec_button_cancel_title"),_LANG("db_spec_button_cancel"));
	$url="redir(\"inicio.php\");";
	$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
	openrow();
	putcolumn($temp,"center","","","","style='height:33px'");
	closerow();
	closeform();
	closef1form();
}

function exists_db_spec() {
	global $dbname;
	global $error;

	$name=$dbname."_db_spec.txt";
	if(file_exists($name)) {
		$msg=_LANG("db_spec_message_file_detected").$name;
		$error[]="WARNING:$msg";
		return $msg;
	}
	return "";
}

function edit_db_spec() {
	$exists=exists_db_spec();
	form_db_spec(get_db_spec());
	if($exists) msgbox($exists,"inicio.php");
}

function upload_db_spec() {
	global $width_obj;

	$exists=exists_db_spec();
	openf1form();
	puthidden("func","save_db_spec.adm");
	escribe(get_migas());
	escribe();
	puterrores("900");
	openform("900","","","","class='tabla'");
	settds("thead");
	putcolumn(_LANG("db_spec_field_file"),"right","33%","","texts2");
	settds("tbody");
	putcolumn("<input type='file' style='width:600px' size='45' name='fichero' class='inputs ui-state-default ui-corner-all'>","left",$width_obj,"","texts2");
	closerow();
	list($title,$text)=make_title_text("button_ok",_LANG("db_spec_button_ok"),_LANG("db_spec_button_ok_title"),_LANG("db_spec_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("db_spec_button_cancel"),_LANG("db_spec_button_cancel_title"),_LANG("db_spec_button_cancel"));
	$url="redir(\"inicio.php\");";
	$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
	openrow();
	settds("");
	putcolumn($temp,"center","","2","","style='height:33px'");
	closerow();
	closeform();
	closef1form();
	if($exists) msgbox($exists,"inicio.php");
}

function download_db_spec() {
	global $dbname;

	$texto=get_db_spec();
	$size=strlen($texto);
	$name=$dbname."_db_spec.txt";
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, no-transform");
	header("Content-Type: application/octet-stream");
	header("Content-Length: ".$size);
	header("Content-Disposition: attachment; filename=".$name);
	header("Content-Transfer-Encoding: binary");
	echo $texto;
	die();
}

function download_backup() {
	global $dbhost,$dbuser,$dbpass,$dbname;
	global $dbfile;

	$name=$dbname."_backup.sql";
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, no-transform");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$name);
	header("Content-Transfer-Encoding: binary");
	$dbtemp=getdbtype();
	if($dbtemp=="MYSQL") echo system("mysqldump -u".$dbuser." -p".$dbpass." -h".$dbhost." ".$dbname);
	if($dbtemp=="SQLITE") echo system("sqlite3 ".$dbfile." .dump");
	die();
}

function save_db_spec() {
	global $error;

	if(isset($_FILES["fichero"])) {
		if($_FILES["fichero"]["tmp_name"]=="") {
			msgbox(_LANG("db_spec_message_error"),"inicio.php?func=upload_db_spec.adm");
		} else {
			$texto=file_get_contents($_FILES["fichero"]["tmp_name"]);
		}
	} else {
		$texto=getParam("db_spec");
		if(ini_get("magic_quotes_gpc")==1) $texto=stripslashes($texto);
	}
	if(isset($texto)) {
		$backup=get_db_spec();
		$error_db_spec=set_db_spec($texto);
		if(!$error_db_spec) {
			set_db_config("spechash",md5($texto));
			msgbox(_LANG("db_spec_message_ok"),"inicio.php");
		} else {
			set_db_spec($backup);
			$texto=stripslashes($texto);
			$error_db_spec=error_replace($error_db_spec);
			$error[]=$error_db_spec;
			form_db_spec($texto);
		}
	}
}

function error_replace($error) {
	$error=trim($error);
	$error=str_replace(array("<h1>","<p>"),"<br/>&nbsp;",$error);
	$error=str_replace(array("</h1>","</p>"),"",$error);
	if(substr($error,0,5)=="<br/>") $error=substr($error,5);
	return $error;
}

function set_db_spec($texto) {
	global $dbname;

	$error=0;
	$database="";
	$table="";
	$spec="";
	$db_spec=explode("\n",$texto);
	$count_db_spec=count($db_spec);
	$cache=set_db_cache("false");
	for($i=0;$i<$count_db_spec;$i++) {
		$line=trim($db_spec[$i]);
		if(substr($line,0,8)=="DATABASE") {
			if($database!="") $error=_LANG("db_spec_message_set_database");
			if($error) break;
			$database=substr($line,9);
			if($database!=$dbname) $error=_LANG("db_spec_message_diff_database")."($database!=$dbname)";
			if($error) break;
		}
		if(substr($line,0,5)=="TABLE") {
			if($database=="") $error=_LANG("db_spec_message_null_database");
			if($error) break;
			$table=substr($line,6);
			$spec="";
			$query="DELETE FROM $table";
			capture_next_error();
			dbQuery($query);
			$error=get_clear_error();
			if($error) break;
		}
		if(substr($line,0,4)=="SPEC") {
			if($database=="") $error=_LANG("db_spec_message_null_database");
			if($error) break;
			if($table=="") $error=_LANG("db_spec_message_null_table");
			if($error) break;
			if($spec!="") $error=_LANG("db_spec_message_set_spec");
			if($error) break;
			$spec=substr($line,5);
			$spec=str_replace("\\\"","`",$spec);
			$spec=str_replace("\"","`",$spec);
		}
		if(substr($line,0,3)=="ROW") {
			if($database=="") $error=_LANG("db_spec_message_null_database");
			if($error) break;
			if($table=="") $error=_LANG("db_spec_message_null_table");
			if($error) break;
			if($spec=="") $error=_LANG("db_spec_message_null_spec");;
			if($error) break;
			$row=substr($line,4);
			$query="INSERT INTO $table ($spec) VALUES ($row)";
			capture_next_error();
			dbQuery($query);
			$error=get_clear_error();
			if($error) break;
		}
	}
	set_db_cache($cache);
	return $error;
}

function update_db_spec_from_file() {
	global $dbname;
	global $error;
	global $spechash;

	if(!check_admin()) return;
	$name=$dbname."_db_spec.txt";
	if(file_exists($name)) {
		$texto=file_get_contents($name);
		$newhash=md5($texto);
		if(!isset($spechash)) $spechash="";
		if($spechash!=$newhash) {
			$backup=get_db_spec();
			$result=set_db_spec($texto);
			if(!$result) {
				$error[]="WARNING:"._LANG("db_spec_message_ok");
				set_db_config("spechash",$newhash);
			} else {
				$result=error_replace($result);
				$error[]=$result;
				set_db_spec($backup);
			}
		}
	}
}

if(!function_exists("getParam")) {
	$head=0;$main=0;$tail=0;
	include("inicio.php");
	if(!check_admin()) {
		$head=1;$main=0;$tail=0;
		include("inicio.php");
		msgbox(_LANG("main_message_denied"),"inicio.php?include=php/login.php?action=logout");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	$direct=array("download_db_spec.adm","download_backup.adm");
	if(in_array($func,$direct)) {
		$func=substr($func,0,-4);
		$func();
	}
}
?>