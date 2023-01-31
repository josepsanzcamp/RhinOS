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
if(!function_exists("getParam")) {
	$head=1;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();
	if(check_demo("user")) {
		msgbox("Prestaci&oacute;n desactivada en modo DEMO","back");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	$oldpass=sha1(getParam("oldpass"));
	$newpass=sha1(getParam("newpass"));
	$newpass2=sha1(getParam("newpass2"));
	$uid=getUID();
	$query="SELECT * FROM db_users WHERE `id`='$uid' AND `password`='$oldpass'";
	$result=dbQuery($query);
	$num_rows=dbNumRows($result);
	dbFree($result);
	if($num_rows==0) {
		msgbox(_LANG("password_message_error_old"),"inicio.php?page=password.php");
	} elseif($newpass!=$newpass2) {
		msgbox(_LANG("password_message_error_new"),"inicio.php?page=password.php");
	} else {
		$query="UPDATE db_users SET `password`='$newpass' WHERE `id`='$uid'";
		dbQuery($query);
		initsession();
		$_SESSION["pass"]=$newpass;
		closesession();
		msgbox(_LANG("password_message_ok"),"inicio.php");
	}
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}
openf1form();
puthidden("include","php/password.php");
escribe(get_migas());
escribe();
openform("400","","","","class='tabla'");
openrow();
settds("thead");
putcolumn(_LANG("password_old_password"),"right","50%","","texts2");
settds("tbody");
putinput("oldpass","","","type='password'","width:150px");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("password_new_password"),"right","50%","","texts2");
settds("tbody");
putinput("newpass","","","type='password'","width:150px");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("password_retype_new_password"),"right","50%","","texts2");
settds("tbody");
putinput("newpass2","","","type='password'","width:150px");
closerow();
openrow();
list($title,$text)=make_title_text("button_ok",_LANG("password_button_ok"),_LANG("password_button_ok_title"),_LANG("password_button_ok"));
$url="mysubmit();";
$temp=get_button($title,$url,"","22","",$text);
list($title,$text)=make_title_text("button_cancel",_LANG("password_button_cancel"),_LANG("password_button_cancel_title"),_LANG("password_button_cancel"));
$url="redir(\"inicio.php\");";
$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
settds("");
putcolumn($temp,"center","","2","","style='height:33px'");
closerow();
closeform();
closef1form();
put_javascript_code("$(document).ready(function() { myfocus('oldpass'); });");
