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
	$params=explode(",",getParam("params"));
	$labels=explode(",",getParam("labels"));
	$types=explode(",",getParam("types"));
	$from=getParam("from");
	$fromname=getParam("fromname");
	$to=getParam("to");
	$subject=getParam("subject");
	$message=beginreport($subject);
	$count=count($params);
	for($i=0;$i<$count;$i++) {
		$label=$labels[$i];
		$param=$params[$i];
		$type=$types[$i];
		$value=getParam($param);
		if($value=="" || $value=="&nbsp;") $error=1;
		if($type=="text") $message.=textreport($label,$value);
		elseif($type=="textarea") $message.=textareareport($label,$value);
		elseif($type=="mail") $message.=mailreport($label,$value);
	}
	$message.=endreport();
	if(isset($error) && $error) {
		msgbox(_LANG("contact_message_full"),"inicio.php?page=contact.php");
	} else {
		$result=sendmail($from,$fromname,"","",$to,$subject,$message);
		if($result!="") msgbox(_LANG("contact_message_error").$to.": ".$result,"inicio.php?page=contact.php");
		else msgbox(_LANG("contact_message_ok").$to,"inicio.php");
	}
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}
openf1form();
puthidden("include","php/contact.php");
puthidden("params","nombre,email,comment");
puthidden("labels",_LANG("contact_field_name").","._LANG("contact_field_email").","._LANG("contact_field_comments"));
puthidden("types","text,mail,textarea");
puthidden("from",$pagemail);
puthidden("fromname",$pagename);
puthidden("to",$pagemail);
$subject=getnametable("contact.php")." - ".$pagename;
puthidden("subject",$subject);
escribe(get_migas());
escribe();
openform("900","","","","class='tabla'");
openrow();
settds("thead");
putcolumn(_LANG("contact_field_name").":","right","33%","","texts2");
settds("tbody");
putinput("nombre","","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("contact_field_email").":","right","33%","","texts2");
settds("tbody");
putinput("email","","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("contact_field_comments").":","right","33%","","texts2 bigfield");
settds("tbody");
puttextarea("comment","","");
closerow();
openrow();
list($title,$text)=make_title_text("button_ok",_LANG("contact_button_ok"),_LANG("contact_button_ok_title"),_LANG("contact_button_ok"));
$url="mysubmit();";
$temp=get_button($title,$url,"","22","",$text);
list($title,$text)=make_title_text("button_cancel",_LANG("contact_button_cancel"),_LANG("contact_button_cancel_title"),_LANG("contact_button_cancel"));
$url="redir(\"inicio.php\");";
$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
settds("");
putcolumn($temp,"center","","2","","style='height:33px'");
closerow();
closeform();
closef1form();
put_javascript_code("$(document).ready(function() { myfocus('nombre'); });");
