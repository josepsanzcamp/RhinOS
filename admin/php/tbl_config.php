<?php
/*
 ____  _     _        ___  ____
|  _ \| |__ (_)_ __  / _ \/ ___|
| |_) | '_ \| | '_ \| | | \___ \
|  _ <| | | | | | | | |_| |___) |
|_| \_\_| |_|_|_| |_|\___/|____/

RhinOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2024 by Josep Sanz Campderrós
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
if(!function_exists("getParam")) {
	$head=1;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();
	$param=strtoupper(encode_bad_chars(getParam("param")));
	$type=getParam("type");
	$title=getParam("titulo");
	$minval=getParam("minval");
	$maxval=getParam("maxval");
	$deltag=getParam("deltag");
	$somedata=($param!="" || $type!="" || $title!="");
	$fulldata=($param!="" && $type!="" && $title!="");
	if($somedata && !$fulldata) {
		if(!check_admin()) die();
		msgbox(_LANG("tblconfig_message_fulldata_needed"),"inicio.php?page=$page");
	} elseif($fulldata) {
		if(!check_admin()) die();
		if($type=="integer") {
			$minval=($minval!="")?intval($minval):"";
			$maxval=($maxval!="")?intval($maxval):"";
		} elseif($type=="real") {
			$minval=($minval!="")?floatval($minval):"";
			$maxval=($maxval!="")?floatval($maxval):"";
		} else {
			$minval="";
			$maxval="";
		}
		$query="INSERT INTO tbl_config(`id`,`param`,`value`,`type`,`title`,`minval`,`maxval`) VALUES(NULL,'$param','','$type','$title','$minval','$maxval')";
		dbQuery($query);
		location("inicio.php?page=$page");
	} elseif($deltag!="") {
		if(!check_admin()) die();
		$deltag_array=explode(",",$deltag);
		foreach($deltag_array as $deltag) {
			$query="DELETE FROM tbl_config WHERE param='$deltag'";
			dbQuery($query);
		}
		location("inicio.php?page=$page");
	} else {
		$cache=set_db_cache("false");
		foreach($_POST as $key=>$val) {
			if(substr($key,0,5)=="hash_") {
				$hash=getString($val);
				$id=str_replace("hash_","",$key);
				$value=getParam("param_".$id);
				$hash2=md5($value);
				if($hash2!=$hash) {
					$query="SELECT * FROM tbl_config WHERE id='$id'";
					$result=dbQuery($query);
					if(dbNumRows($result)==1) {
						$row=dbFetchRow($result);
						$type=$row["type"];
						$minval=$row["minval"];
						$maxval=$row["maxval"];
						if($type=="integer") {
							$value=intval($value);
							if($minval!="") {
								$minval=intval($minval);
								if($value<$minval) $value=$minval;
							}
							if($maxval!="") {
								$maxval=intval($maxval);
								if($value>$maxval) $value=$maxval;
							}
						} elseif($type=="real") {
							$value=floatval($value);
							if($minval!="") {
								$minval=floatval($minval);
								if($value<$minval) $value=$minval;
							}
							if($maxval!="") {
								$maxval=floatval($maxval);
								if($value>$maxval) $value=$maxval;
							}
						}
						$query="UPDATE tbl_config SET `value`='$value' WHERE id='$id'";
						dbQuery($query);
					}
					dbFree($result);
				}
			}
		}
		set_db_cache($cache);
		location("inicio.php?page=$page");
	}
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}

if(!check_user()) die();

function row_tail() {
	openrow();
	list($title,$text)=make_title_text("button_ok",_LANG("tblconfig_button_ok"),_LANG("tblconfig_button_ok_title"),_LANG("tblconfig_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("tblconfig_button_cancel"),_LANG("tblconfig_button_cancel_title"),_LANG("tblconfig_button_cancel"));
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

openf1form();
puthidden("include","php/$page");
puthidden("page",$page);
escribe(get_migas());
escribe();
openform("900","","","","class='tabla'");

if(check_admin()) {
	row_header(_LANG("tblconfig_title_create"));
	openrow();
	settds("thead");
	putcolumn(_LANG("tblconfig_label_param"),"right","","","texts2");
	settds("tbody");
	putinput("param","","edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblconfig_label_type"),"right","","","texts2");
	settds("tbody");
	$options=array();
	$options[]="text:"._LANG("tblconfig_option_text");
	$options[]="integer:"._LANG("tblconfig_option_integer");
	$options[]="real:"._LANG("tblconfig_option_real");
	$options=implode(",",$options);
	putselect("","type","edit","",$options);
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblconfig_label_title"),"right","","","texts2");
	settds("tbody");
	putinput("titulo","","edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblconfig_label_minval"),"right","","","texts2");
	settds("tbody");
	putinput("minval","","edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblconfig_label_maxval"),"right","","","texts2");
	settds("tbody");
	putinput("maxval","","edit");
	closerow();
	row_tail();
	escribe();
	row_header(_LANG("tblconfig_title_delete"));
	openrow();
	settds("thead");
	putcolumn(_LANG("tblconfig_label_delete"),"right","","","texts2");
	settds("tbody");
	putmultiselect("tbl_config","deltag","edit");
	closerow();
	row_tail();
	escribe();
}

$query="SELECT * FROM tbl_config";
$result=dbQuery($query);
if(dbNumRows($result)>0) {
	row_header(_LANG("tblconfig_title_managament"));
	while($row=dbFetchRow($result)) {
		openrow();
		settds("thead");
		putcolumn($row["title"].":","right","","","texts2");
		settds("tbody");
		$temp="";
		if($row["minval"]!="" && $row["maxval"]!="") {
			$temp.="&nbsp;("._LANG("tblconfig_label_from_min_to_max").")";
			$temp=str_replace("#minimo#",$row["minval"],$temp);
			$temp=str_replace("#maximo#",$row["maxval"],$temp);
		} elseif($row["minval"]!="") {
			$temp.="&nbsp;("._LANG("tblconfig_label_from_min").")";
			$temp=str_replace("#minimo#",$row["minval"],$temp);
		} elseif($row["maxval"]!="") {
			$temp.="&nbsp;("._LANG("tblconfig_label_to_max").")";
			$temp=str_replace("#maximo#",$row["maxval"],$temp);
		}
		if($row["type"]=="integer") {
			$js="onkeyup='javascript:mascara_num(this,1)'";
			$temp=getinput("","param_".$row["id"],$row["value"],25,"",0,$js).$temp;
			putcolumn($temp,"left",$width_obj);
		} elseif($row["type"]=="real") {
			$js="onkeyup='javascript:mascara_num(this,0)'";
			$temp=getinput("","param_".$row["id"],$row["value"],25,"",0,$js).$temp;
			putcolumn($temp,"left",$width_obj);
		} elseif($row["type"]=="text") {
			putinput("param_".$row["id"],$row["value"],"edit");
		} else {
			die(_LANG("tblconfig_error_unknown_type"));
		}
		puthidden("hash_".$row["id"],md5($row["value"]));
		closerow();
	}
	row_tail();
}

if(!check_admin() && !dbNumRows($result)) {
	msgbox(_LANG("tblconfig_message_nodata"),"inicio.php");
}

dbFree($result);

closeform();
closef1form();
