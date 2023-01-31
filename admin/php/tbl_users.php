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
function get_apps($user="") {
	$apps=array();
	if($user=="") {
		$query="SELECT * FROM db_tables WHERE tbl!='' ORDER BY position";
		$result=dbQuery($query);
		while($row=dbFetchRow($result)) {
			if(check_permissions("list",$row["tbl"])) {
				$apps[]=$row["tbl"].":".$row["name"];
			}
		}
		dbFree($result);
	} else {
		$query="SELECT * FROM db_perms WHERE `user`='$user'";
		$result=dbQuery($query);
		while($row=dbFetchRow($result)) {
			$apps[]=$row["tbl"];
		}
		dbFree($result);
	}
	$apps=implode(",",$apps);
	return $apps;
}

function has_apps($app,$apps) {
	$valid=true;
	$app=explode(",",$app);
	$apps=explode(",",$apps);
	foreach($apps as $key=>$val) $apps[$key]=strtok($val,":");
	$intersect=array_intersect($app,$apps);
	$valid=count($app)==count($intersect);
	return $valid;
}

if(!function_exists("getParam")) {
	$head=1;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();
	$user=getParam("user");
	$pass=getParam("pass");
	$pass_retype=getParam("pass_retype");
	$apps=getParam("apps");
	$some=($user!="" || $pass!="" || $pass_retype!="" || $apps!="");
	$full=($user!="" && $pass!="" && $pass_retype!="" && $apps!="");
	if($some && !$full) {
		msgbox(_LANG("tblusers_message_full_needed"),"inicio.php?page=$page");
	} elseif($full) {
		if($pass_retype!=$pass) {
			msgbox(_LANG("tblusers_message_password_error"),"inicio.php?page=$page");
		} else {
			$query="SELECT * from db_users WHERE `login`='$user'";
			$result=dbQuery($query);
			$numrows=dbNumRows($result);
			dbFree($result);
			if($numrows>0) {
				msgbox(_LANG("tblusers_message_username_exists"),"inicio.php?page=$page");
			} else {
				$pass=sha1($pass);
				$query="INSERT INTO tbl_users(`id`,`user`) VALUES(NULL,'$user')";
				dbQuery($query);
				$query="INSERT INTO db_users(`id`,`login`,`password`) VALUES(NULL,'$user','$pass')";
				dbQuery($query);
				$apps=explode(",",$apps);
				foreach($apps as $app) {
					$query="INSERT INTO db_perms(`id`,`user`,`tbl`,`role`,`allow`) VALUES(NULL,'$user','$app','all','allow')";
					dbQuery($query);
				}
				location("inicio.php?page=$page");
			}
		}
	} else {
		$query="SELECT * FROM db_users WHERE `login` IN (SELECT `user` FROM tbl_users) ORDER BY `login` ASC";
		$result=dbQuery($query);
		$problem=0;
		while(($row=dbFetchRow($result)) && !$problem) {
			$row["login"]=getString($row["login"]);
			if(has_apps(get_apps($row["login"]),get_apps())) {
				$id=$row["id"];
				$user=$row["login"];
				$pass=getParam("pass_$id");
				$pass_retype=getParam("pass_retype_$id");
				$apps=getParam("apps_$id");
				$del=getParam("del_$id");
				if($del) {
					$query="DELETE FROM tbl_users WHERE `user`='$user'";
					dbQuery($query);
					$query="DELETE FROM db_users WHERE `login`='$user'";
					dbQuery($query);
					$query="DELETE FROM db_perms WHERE `user`='$user'";
					dbQuery($query);
				} elseif($pass!="" && $pass_retype!=$pass) {
					msgbox(_LANG("tblusers_message_password_error"),"inicio.php?page=$page");
					$problem=1;
				} elseif($apps=="") {
					msgbox(_LANG("tblusers_message_apps_needed"),"inicio.php?page=$page");
					$problem=1;
				}
			}
		}
		dbFree($result);
		if(!$problem) {
			$query="SELECT * FROM db_users WHERE `login` IN (SELECT `user` FROM tbl_users) ORDER BY `login` ASC";
			$result=dbQuery($query);
			while($row=dbFetchRow($result)) {
				$row["login"]=getString($row["login"]);
				if(has_apps(get_apps($row["login"]),get_apps())) {
					$id=$row["id"];
					$user=$row["login"];
					$pass=getParam("pass_$id");
					$pass_retype=getParam("pass_retype_$id");
					$apps=getParam("apps_$id");
					$hash1=md5($apps);
					$hash2=getParam("hash_$id");
					if($pass!="") {
						$pass=sha1($pass);
						$query="UPDATE db_users SET `password`='$pass' WHERE id='$id'";
						dbQuery($query);
					}
					if($hash1!=$hash2) {
						$query="DELETE FROM db_perms WHERE `user`='$user'";
						dbQuery($query);
						$apps=explode(",",$apps);
						foreach($apps as $app) {
							$query="INSERT INTO db_perms(`id`,`user`,`tbl`,`role`,`allow`) VALUES(NULL,'$user','$app','all','allow')";
							dbQuery($query);
						}
					}
				}
			}
			dbFree($result);
			location("inicio.php?page=$page");
		}
	}
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}

if(!check_user()) die();

function row_tail() {
	openrow();
	list($title,$text)=make_title_text("button_ok",_LANG("tblusers_button_ok"),_LANG("tblusers_button_ok_title"),_LANG("tblusers_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("tblusers_button_cancel"),_LANG("tblusers_button_cancel_title"),_LANG("tblusers_button_cancel"));
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

row_header(_LANG("tblusers_title_create"));
openrow();
settds("thead");
putcolumn(_LANG("tblusers_label_username"),"right","","","texts2");
settds("tbody");
putinput("user","","edit");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("tblusers_label_password"),"right","","","texts2");
settds("tbody");
putinput("pass","","edit","type='password'","","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("tblusers_label_retype"),"right","","","texts2");
settds("tbody");
putinput("pass_retype","","edit","type='password'","","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("tblusers_label_applications"),"right","","","texts2");
settds("tbody");
putmultiselect("","apps","edit","",get_apps());
closerow();
row_tail();

escribe();

$query="SELECT * FROM db_users WHERE `login` IN (SELECT `user` FROM tbl_users) ORDER BY `login` ASC";
$result=dbQuery($query);
$first=1;
while($row=dbFetchRow($result)) {
	$row["login"]=getString($row["login"]);
	if(has_apps(get_apps($row["login"]),get_apps())) {
		if($first) {
			row_header(_LANG("tblusers_title_modify"));
			$first=0;
		}
		openrow();
		settds("thead");
		putcolumn(_LANG("tblusers_label_username"),"right","","","texts2");
		settds("tbody");
		putinput("user",stripslashes($row["login"]),"show");
		closerow();
		openrow();
		settds("thead");
		putcolumn(_LANG("tblusers_label_password"),"right","","","texts2");
		settds("tbody");
		putinput("pass_".$row["id"],"","edit","type='password'","","");
		closerow();
		openrow();
		settds("thead");
		putcolumn(_LANG("tblusers_label_retype"),"right","","","texts2");
		settds("tbody");
		putinput("pass_retype_".$row["id"],"","edit","type='password'","","");
		closerow();
		openrow();
		settds("thead");
		putcolumn(_LANG("tblusers_label_applications"),"right","","","texts2");
		settds("tbody");
		putmultiselect("","apps_".$row["id"],"edit",get_apps($row["login"]),get_apps());
		puthidden("hash_".$row["id"],md5(get_apps($row["login"])));
		closerow();
		openrow();
		settds("thead");
		putcolumn(_LANG("tblusers_label_delete"),"right","","","texts2");
		settds("tbody");
		putboolean("del_".$row["id"],"0","edit");
		closerow();
		row_tail();
	}
}

dbFree($result);

closeform();
closef1form();
