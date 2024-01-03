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
	if(check_demo("user")) {
		msgbox(_LANG("tblmailing_demo_disabled"),"back");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	// UPDATE THE SMTP CONFIGURATION
	$host=getParam("host");
	$user=getParam("user");
	$pass=getParam("pass");
	$from=getParam("from");
	$fromname=getParam("fromname");
	$query="UPDATE tbl_mailing SET `host`='$host',`user`='$user',`pass`='$pass',`from`='$from',`fromname`='$fromname'";
	dbQuery($query);
	// SEND THE MAILING IF ALL PARAMETERS ARRIVE
	$to=getParam("to");
	$subject=getParam("subject");
	$body=getParam("body");
	$body=str_replace("\\\"","\"",$body);
	$body=str_replace("\\'","'",$body);
	if(!$to || !$subject || !$body) {
		msgbox(_LANG("tblmailing_message_error"),"back");
	} else {
		include("dbmailer.php");
		dbmailer($from,$fromname,$to,$subject,$body,$host,$user,$pass);
		msgbox(_LANG("tblmailing_message_ok"),"back");
	}
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}

if(!check_user()) die();

function row_tail() {
	openrow();
	list($title,$text)=make_title_text("button_ok",_LANG("tblmailing_button_ok"),_LANG("tblmailing_button_ok_title"),_LANG("tblmailing_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("tblmailing_button_cancel"),_LANG("tblmailing_button_cancel_title"),_LANG("tblmailing_button_cancel"));
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

$query="SELECT COUNT(*) count FROM tbl_mailing";
$result=dbQuery($query);
$row=dbFetchRow($result);
$count=$row["count"];
dbFree($result);

if(!$count) {
	$query="INSERT INTO tbl_mailing(`id`,`host`,`user`,`pass`,`from`,`fromname`) VALUES(NULL,'','','','','')";
	dbQuery($query);
}

$lista=array("host"=>_LANG("tblmailing_label_smtp_host"),"user"=>_LANG("tblmailing_label_username"),"pass"=>_LANG("tblmailing_label_password"),"from"=>_LANG("tblmailing_label_from_email"),"fromname"=>_LANG("tblmailing_label_from_name"));

$query="SELECT * FROM tbl_mailing";
$result=dbQuery($query);
$row=dbFetchRow($result);
dbFree($result);

row_header(_LANG("tblmailing_label_smtp_config"));
foreach($lista as $key=>$val) {
	openrow();
	settds("thead");
	putcolumn($val,"right","","","texts2");
	settds("tbody");
	putinput($key,$row[$key],"edit");
	closerow();
}
row_tail();

escribe();

row_header("Mensaje a enviar");
openrow();
settds("thead");
putcolumn(_LANG("tblmailing_label_to"),"right","","","texts2 bigfield");
settds("tbody");
puttextarea("to",getParam("to"),"edit");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("tblmailing_label_subject"),"right","","","texts2");
settds("tbody");
putinput("subject",getParam("subject"),"edit");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("tblmailing_label_body"),"right","","","texts2 bigfield");
settds("tbody");
puttextarea("body",getParam("body"),"edit");
closerow();
row_tail();

closeform();
closef1form();
