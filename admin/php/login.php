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
$head=1;$main=0;$tail=0;
include("inicio.php");
$action=getParam("action");
if($action=="login") {
	$user=getParam("user");
	$pass=getParam("pass");
	// CONVERT FROM MD5 TO SHA1 FORMAT
	$oldcahe=setUseCache("false");
	$query="SELECT * FROM db_users WHERE login='{$user}'";
	$result=dbQuery($query);
	if(dbNumRows($result)==1) {
		$row=dbFetchRow($result);
		if($user==$row["login"] && $row["password"]==md5($pass)) {
			$query="UPDATE db_users SET password='".sha1($pass)."' WHERE login='{$user}'";
			dbQuery($query);
		}
	}
	dbFree($result);
	setUseCache($oldcahe);
	// CONTINUE
	$remember=getParam("remember");
	if($pass!="") $pass=sha1($pass);
	initsession();
	$_SESSION["user"]=$user;
	$_SESSION["pass"]=$pass;
	$_SESSION["login"]=1;
	closesession();
	setcookie("remember",$remember,time()+86400*30);
	if($remember) {
		setcookie("user",$user,time()+86400*30);
		setcookie("pass",$pass,time()+86400*30);
	}
	$querystring=getParam("querystring");
	if($querystring!="") $querystring="?".$querystring;
	location("inicio.php".$querystring);
} elseif($action=="logout") {
	initsession();
	$_SESSION["user"]="";
	$_SESSION["pass"]="";
	closesession();
	$remember=0;
	if(isset($_COOKIE["remember"])) $remember=$_COOKIE["remember"];
	if($remember) {
		setcookie("user","",time()+86400*30);
		setcookie("pass","",time()+86400*30);
	}
	location("inicio.php");
}
$head=0;$main=0;$tail=1;
include("inicio.php");
die();
