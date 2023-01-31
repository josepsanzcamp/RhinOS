<?php
/*
 ____  _     _        ___  ____
|  _ \| |__ (_)_ __  / _ \/ ___|
| |_) | '_ \| | '_ \| | | \___ \
|  _ <| | | | | | | | |_| |___) |
|_| \_\_| |_|_|_| |_|\___/|____/

RhinOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2023 by Josep Sanz Campderrós
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
function section_exists() {
	return field_exists("sec");
}

function field_exists($field) {
	static $stack=array();

	if(!isset($stack[$field])) {
		$query="SELECT * FROM tbl_labels LIMIT 1";
		$result=dbQuery($query);
		if(dbNumRows($result)==0) {
			dbFree($result);
			$query="/*MYSQL SHOW COLUMNS FROM tbl_labels *//*SQLITE PRAGMA TABLE_INFO(tbl_labels) */";
			$result=dbQuery($query);
			$stack[$field]=false;
			$dbtemp=getdbtype();
			while($row=dbFetchRow($result)) {
				if($dbtemp=="MYSQL") if($row["Field"]==$field) $stack[$field]=true;
				if($dbtemp=="SQLITE") if($row["name"]==$field) $stack[$field]=true;
			}
			dbFree($result);
		} else {
			$stack[$field]=false;
			$numfields=dbNumFields($result);
			for($i=0;$i<$numfields;$i++) {
				if(dbFieldName($result,$i)==$field) $stack[$field]=true;
			}
			dbFree($result);
		}
	}
	return $stack[$field];
}

function get_langs() {
	$langs=array();
	if(count($langs)==0) {
		$query="SELECT * FROM tbl_labels LIMIT 1";
		$result=dbQuery($query);
		if(dbNumRows($result)==0) {
			dbFree($result);
			$query="/*MYSQL SHOW COLUMNS FROM tbl_labels *//*SQLITE PRAGMA TABLE_INFO(tbl_labels) */";
			$result=dbQuery($query);
			$dbtemp=getdbtype();
			while($row=dbFetchRow($result)) {
				if($dbtemp=="MYSQL") $field=$row["Field"];
				if($dbtemp=="SQLITE") $field=$row["name"];
				if($field!="id" && strlen($field)==2) $langs[]=$field;
			}
			dbFree($result);
		} else {
			$fields=dbNumFields($result);
			for($i=0;$i<$fields;$i++) {
				$field=dbFieldName($result,$i);
				if($field!="id" && strlen($field)==2) $langs[]=$field;
			}
			dbFree($result);
		}
	}
	return implode(",",$langs);
}

if(!function_exists("getParam")) {
	$head=1;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();
	$deflang=isset($_LANG["lang"])?$_LANG["lang"]:"es";
	$lang2=getParam("lang2");
	$langs=get_langs();
	$lang=strtolower(getParam("lang"));
	if($lang=="") $lang=$deflang;
	$sec2=getParam("sec2");
	$sec=getParam("sec");
	$deftag=getParam("newtag");
	$newtag=strtoupper(encode_bad_chars($deftag));
	$defsec=getParam("newsec1");
	if($defsec=="") $defsec=getParam("newsec2");
	$newsec=strtoupper(encode_bad_chars($defsec));
	$html=getParam("html");
	$deltag=getParam("deltag");
	$delsec=getParam("delsec");
	$option1=getParam("option1");
	$option2=strtolower(getParam("option2"));
	if($option1!="") {
		if(!check_admin()) die();
		$process=0;
		$message="";
		if($option1=="1") {
			if($option2!="") {
				$message=_LANG("tbllabels_message_error_sec_not_lang");
			} elseif(!section_exists()) {
				$createsec="\t`sec` TEXT NOT NULL DEFAULT '',\n";
				$selectsec="'',";
				$createlang="";
				foreach(explode(",",$langs) as $l) $createlang.="\t`$l` TEXT NOT NULL DEFAULT '',\n";
				$selectlang=$langs;
				$process=1;
				$message=_LANG("tbllabels_message_ok_add_sec");
			} else {
				$message=_LANG("tbllabels_message_error_sec_exists");
			}
		}
		if($option1=="2") {
			if($option2!="") {
				$message=_LANG("tbllabels_message_error_sec_not_lang");
			} elseif(section_exists()) {
				$createsec="";
				$selectsec="";
				$createlang="";
				foreach(explode(",",$langs) as $l) $createlang.="\t`$l` TEXT NOT NULL DEFAULT '',\n";
				$selectlang=$langs;
				$process=1;
				$message=_LANG("tbllabels_message_ok_del_sec");
			} else {
				$message=_LANG("tbllabels_message_error_sec_not_exists");
			}
		}
		if($option1=="3") {
			if($option2=="") {
				$message=_LANG("tbllabels_message_error_lang_not_found");
			} elseif(strlen($option2)!=2) {
				$message=_LANG("tbllabels_message_error_lang_2_chars");
			} elseif(!field_exists($option2)) {
				if(section_exists()) {
					$createsec="\t`sec` TEXT NOT NULL DEFAULT '',\n";
					$selectsec="sec,";
				} else {
					$createsec="";
					$selectsec="";
				}
				$createlang="";
				foreach(explode(",",$langs) as $l) $createlang.="\t`$l` TEXT NOT NULL DEFAULT '',\n";
				$createlang.="\t`$option2` TEXT NOT NULL DEFAULT '',\n";
				$selectlang=$langs.",".$deflang;
				$process=1;
				$message=_LANG("tbllabels_message_ok_add_lang")."[".$option2."]";
			} else {
				$message=_LANG("tbllabels_message_error_lang_exists")."[".$option2."]";
			}
		}
		if($option1=="4") {
			if($option2=="") {
				$message=_LANG("tbllabels_message_error_lang_not_found");
			} elseif(strlen($option2)!=2) {
				$message=_LANG("tbllabels_message_error_lang_2_chars");
			} elseif($option2==$deflang) {
				$message=str_replace("#default#",$deflang,_LANG("tbllabels_message_error_lang_del_default"));
			} elseif(field_exists($option2)) {
				if(section_exists()) {
					$createsec="\t`sec` TEXT NOT NULL DEFAULT '',\n";
					$selectsec="sec,";
				} else {
					$createsec="";
					$selectsec="";
				}
				$createlang="";
				$langs=explode(",",$langs);
				unset($langs[array_search($option2,$langs)]);
				$langs=implode(",",$langs);
				foreach(explode(",",$langs) as $l) $createlang.="\t`$l` TEXT NOT NULL DEFAULT '',\n";
				$selectlang=$langs;
				$process=1;
				$message=_LANG("tbllabels_message_ok_del_lang")."[".$option2."]";
			} else {
				$message=_LANG("tbllabels_message_error_lang_not_exists")."[".$option2."]";
			}
		}
		if($process) {
			$createlang=substr($createlang,0,-2)."\n";
			$query=array();
			$query[]="ALTER TABLE tbl_labels RENAME TO __tbl_labels__";
			$query[]="CREATE TABLE `tbl_labels` (\n\t`id` INTEGER PRIMARY KEY /*MYSQL AUTO_INCREMENT *//*SQLITE AUTOINCREMENT */,\n".$createsec."\t`tag` TEXT NOT NULL DEFAULT '',\n\t`html` INTEGER NOT NULL DEFAULT '0',\n".$createlang.") /*MYSQL ENGINE=MyISAM CHARSET=utf8 */";
			$query[]="INSERT INTO tbl_labels SELECT id,".$selectsec."tag,html,".$selectlang." FROM __tbl_labels__";
			$query[]="DROP TABLE __tbl_labels__";
			foreach($query as $q) dbQuery($q);
			msgbox($message,"inicio.php?page=$page&lang=$lang&sec=$sec");
		} else {
			msgbox($message,"inicio.php?page=$page&lang=$lang&sec=$sec");
		}
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	} elseif($option2!="") {
		msgbox(_LANG("tbllabels_message_error_lang_with_not_option"),"inicio.php?page=$page&lang=$lang&sec=$sec");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	if($newtag!="") {
		if(!check_admin()) die();
		$count=count(explode(",",$langs));
		$temp=array();
		for($i=0;$i<$count;$i++) $temp[]="'$deftag'";
		$temp=implode(",",$temp);
		if(section_exists()) {
			$query="SELECT sec,tag FROM tbl_labels WHERE sec='$newsec' AND tag='$newtag'";
		} else {
			$query="SELECT tag FROM tbl_labels WHERE tag='$newtag'";
		}
		$result=dbQuery($query);
		$numrows=dbNumRows($result);
		dbFree($result);
		if(!$numrows) {
			if(section_exists()) {
				$query="INSERT INTO tbl_labels(id,sec,tag,html,$langs) VALUES(NULL,'$newsec','$newtag','$html',$temp)";
			} else {
				$query="INSERT INTO tbl_labels(id,tag,html,$langs) VALUES(NULL,'$newtag','$html',$temp)";
			}
			dbQuery($query);
			location("inicio.php?page=$page&lang=$lang&sec=$sec");
		} else {
			msgbox(_LANG("tbllabels_message_error_tag_exists"),"inicio.php?page=$page&lang=$lang&sec=$sec");
		}
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	if($deltag!="") {
		if(!check_admin()) die();
		$deltag_array=explode(",",$deltag);
		foreach($deltag_array as $deltag) {
			if(section_exists()) {
				$query="DELETE FROM tbl_labels WHERE sec='$delsec' AND tag='$deltag'";
			} else {
				$query="DELETE FROM tbl_labels WHERE tag='$deltag'";
			}
			dbQuery($query);
		}
		location("inicio.php?page=$page&lang=$lang&sec=$sec");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	if($sec2!=$sec) {
		location("inicio.php?page=$page&lang=$lang&sec=$sec");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	if($lang2!=$lang) {
		location("inicio.php?page=$page&lang=$lang&sec=$sec");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	$cache=set_db_cache("false");
	foreach($_POST as $key=>$val) {
		$hash=getParam($key."_hash");
		if($hash) {
			$val=getString($val);
			$some=getParam($key."_some");
			if($some) $val=some_htmlentities($val);
			$hash2=md5($val);
			if($hash2!=$hash) {
				if(section_exists()) {
					$query="UPDATE tbl_labels SET $lang='$val' WHERE sec='$sec' AND tag='$key'";
				} else {
					$query="UPDATE tbl_labels SET $lang='$val' WHERE tag='$key'";
				}
				dbQuery($query);
			}
		}
	}
	set_db_cache($cache);
	location("inicio.php?page=$page&lang=$lang&sec=$sec");
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}

if(!check_user()) die();
$deflang=isset($_LANG["lang"])?$_LANG["lang"]:"es";

function row_tail() {
	openrow();
	list($title,$text)=make_title_text("button_ok",_LANG("tbllabels_button_ok"),_LANG("tbllabels_button_ok_title"),_LANG("tbllabels_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("tbllabels_button_cancel"),_LANG("tbllabels_button_cancel_title"),_LANG("tbllabels_button_cancel"));
	$url="redir(\"inicio.php\");";
	$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
	settds("tnada");
	putcolumn($temp,"center","","2","","style='height:33px'");
	closerow();
}

function row_header($text="") {
	openrow();
	settds("tdsh");
	putcolumn($text,"center","","2","texts2","style='height:33px'");
	closerow();
}

function row_header2($text="") {
	openrow();
	settds("thead");
	putcolumn($text,"center","","","texts2");
	putcolumn(_LANG("tbllabels_label_literal"),"center","","","texts2");
	closerow();
}

if(section_exists()) {
	$query="UPDATE tbl_labels SET sec='GENERAL' WHERE sec=''";
	dbQuery($query);
	$query="SELECT DISTINCT sec from tbl_labels ORDER BY sec ASC";
	$result=dbQuery($query);
	$secs=array();
	while($row=dbFetchRow($result)) {
		$secs[]=$row["sec"];
	}
	$secs=implode(",",$secs);
	dbFree($result);
}

$sec=getParam("sec");
if(section_exists()) {
	$secs=explode(",",$secs);
	if($sec=="") {
		$sec=$secs[0];
	} else {
		if(!in_array($sec,$secs)) {
			$sec=$secs[0];
		}
	}
	$secs=implode(",",$secs);
}

$lang=strtolower(getParam("lang"));
if($lang=="") $lang=$deflang;

if(section_exists()) {
	$query="SELECT * FROM tbl_labels WHERE sec='$sec' ORDER BY tag";
} else {
	$query="SELECT * FROM tbl_labels ORDER BY tag";
}
$result=dbQuery($query);

openf1form();
puthidden("include","php/$page");
puthidden("page",$page);
puthidden("lang2",$lang);
puthidden("sec2",$sec);
if(section_exists()) {
	puthidden("secs",$secs);
}
escribe(get_migas());
escribe();
openform("900","","","","class='tabla'");

$langs=get_langs();
$langs=explode(",",$langs);
if(!in_array($lang,$langs)) $lang=$langs[0];
$langs=implode(",",$langs);

if(check_admin()) {
	row_header(_LANG("tbllabels_title_table_options"));
	openrow();
	settds("thead");
	putcolumn(_LANG("tbllabels_label_table_options"),"right","","","texts2");
	settds("tbody");
	$options=array();
	$options[]="1:"._LANG("tbllabels_label_option_add_sec");
	$options[]="2:"._LANG("tbllabels_label_option_del_sec");
	$options[]="3:"._LANG("tbllabels_label_option_add_lang");
	$options[]="4:"._LANG("tbllabels_label_option_del_lang");
	$options=implode(",",$options);
	putselect("","option1","edit","",$options);
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tbllabels_label_lang_option"),"right","","","texts2");
	settds("tbody");
	putinput("option2","","edit");
	closerow();
	openrow();
	settds("thead");
	$temp1=section_exists()?_LANG("tbllabels_label_sec_exists"):_LANG("tbllabels_label_sec_not_exists");
	$temp2=_LANG("tbllabels_label_current_langs").str_replace(",",", ",strtoupper($langs)).".";
	putcolumn(_LANG("tbllabels_label_notes")."$temp1 $temp2","center","","2","errors");
	closerow();
	row_tail();
	escribe();
	row_header(_LANG("tbllabels_title_create_tag"));
	openrow();
	settds("thead");
	putcolumn(_LANG("tbllabels_label_new_literal"),"right","","","texts2");
	settds("tbody");
	putinput("newtag","","edit");
	closerow();
	if(section_exists()) {
		openrow();
		settds("thead");
		putcolumn(_LANG("tbllabels_label_new_sec"),"right","","","texts2");
		settds("tbody");
		putinput("newsec1","","edit");
		closerow();
		openrow();
		settds("thead");
		putcolumn(_LANG("tbllabels_label_existent_sec"),"right","","","texts2");
		settds("tbody");
		putselect("","newsec2","edit",$sec,$secs);
		closerow();
	}
	openrow();
	settds("thead");
	putcolumn(_LANG("tbllabels_label_ishtml"),"right","","","texts2");
	settds("tbody");
	putboolean("html","0","edit");
	closerow();
	row_tail();
	escribe();
	row_header(_LANG("tbllabels_title_delete_tag"));
	if(section_exists()) {
		openrow();
		settds("thead");
		putcolumn(_LANG("tbllabels_label_section"),"right","","","texts2");
		settds("tbody");
		putselect("","delsec","edit",$sec,$secs);
		closerow();
		openrow();
		settds("thead");
		putcolumn(_LANG("tbllabels_label_del_literal"),"right","","","texts2");
		settds("tbody");
		putajaxfilter("tbl_labels","deltag:delsec","edit","-1");
		closerow();
	} else {
		openrow();
		settds("thead");
		putcolumn(_LANG("tbllabels_label_del_literal"),"right","","","texts2");
		settds("tbody");
		putmultiselect("tbl_labels","deltag","edit");
		closerow();
	}
	row_tail();
	escribe();
}

$haslangs=0;
if(count(explode(",",$langs))>1 && dbNumRows($result)) {
	$haslangs=1;
}

$hassecs=0;
if(section_exists()) {
	$secs=explode(",",$secs);
	if($secs[0]!="") $hassecs=1;
	$secs=implode(",",$secs);
}

if($haslangs || $hassecs) {
	row_header(_LANG("tbllabels_title_change_lang_sec"));
}

if($haslangs) {
	openrow();
	settds("thead");
	putcolumn(_LANG("tbllabels_label_lang"),"right","","","texts2");
	settds("tbody");
	putselect("","lang","edit",strtoupper($lang),strtoupper($langs));
	closerow();
}

if($hassecs) {
	$secs=explode(",",$secs);
	openrow();
	settds("thead");
	putcolumn(_LANG("tbllabels_label_section"),"right","","","texts2");
	settds("tbody");
	$secs=implode(",",$secs);
	putselect("","sec","edit",$sec,$secs);
	closerow();
}

if($haslangs || $hassecs) {
	row_tail();
	escribe();
}

$old_letter="";
while($row=dbFetchRow($result)) {
	$letter=substr($row["tag"],0,1);
	if($old_letter=="") {
		row_header(_LANG("tbllabels_title_current_labels"));
		row_header2(_LANG("tbllabels_label_letter").strtoupper($letter));
		$old_letter=$letter;
	}
	if($letter!=$old_letter) {
		row_tail();
		escribe();
		row_header2(_LANG("tbllabels_label_letter").strtoupper($letter));
		$old_letter=$letter;
	}
	openrow();
	settds("thead");
	$temp=$row["tag"];
	$temp=str_replace("_"," ",$temp);
	$temp=strtolower($temp);
	$temp=ucfirst($temp);
	$bigfield=$row["html"]?" bigfield":"";
	putcolumn($temp.":","right","","","texts2".$bigfield);
	settds("tbody");
	if(!$row["html"]) {
		putinput($row["tag"],$row[$lang],"edit");
		puthidden($row["tag"]."_some","1");
	} else {
		puttextarea($row["tag"],$row[$lang],"edit");
	}
	puthidden($row["tag"]."_hash",md5($row[$lang]));
	closerow();
}
if($old_letter!="") {
	row_tail();
}

if(!check_admin() && !dbNumRows($result)) {
	msgbox(_LANG("tbllabels_message_nodata"),"inicio.php");
}

dbFree($result);

closeform();
closef1form();
