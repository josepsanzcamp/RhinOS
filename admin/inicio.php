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
error_reporting(0);
if(isset($_GET["include"]) || isset($_POST["include"])) {
	if(isset($_GET["include"])) {
		$include=$_GET["include"];
		unset($_GET["include"]);
	}
	if(isset($_POST["include"])) {
		$include=$_POST["include"];
		unset($_POST["include"]);
	}
	if(substr($include,0,4)!="php/") die();
	if(strpos(substr($include,4,-4),".")!==false) die();
	if(strpos(substr($include,4,-4),"/")!==false) die();
	if(substr($include,-4,4)!=".php") die();
	include($include);
	die();
}
include_once("config.php");
if(!function_exists("checkDebug")) {
	function checkDebug($key) {
		static $stack=array();
		if(!defined("DEBUG")) return false;
		$cache=md5("DEBUG=".DEBUG."&KEY=".$key);
		if(!isset($stack[$cache])) {
			$result=false;
			if(strpos(DEBUG,"1")!==false) $result=true;
			if(strpos(DEBUG,"ALL")!==false) $result=true;
			if(strpos(DEBUG,"E_ALL")!==false) $result=true;
			if(strpos(DEBUG,"DEBUG_ALL")!==false) $result=true;
			if(strpos(DEBUG,$key)!==false) $result=true;
			$stack[$cache]=$result;
		}
		return $stack[$cache];
	}
}
if(checkDebug("DEBUG_ADMIN")) {
	error_reporting(defined("E_DEPRECATED")?(E_ALL & ~E_DEPRECATED):(E_ALL));
}
if(!function_exists("_LANG")) {
	function _LANG($key) {
		global $_LANG;
		return isset($_LANG[$key])?$_LANG[$key]:$key;
	}
}
if(!file_exists("php/lang_"._LANG("lang").".php")) {
	$_LANG=array("lang"=>"es");
}
include_once("php/lang_"._LANG("lang").".php");
include_once("database/database.php");
include_once("php/sessions.php");
include_once("php/functions.php");
include_once("php/mailer.php");
include_once("php/connect.php");
include_once("php/db_spec.php");
$head=isset($head)?$head:1;
$main=isset($main)?$main:1;
$tail=isset($tail)?$tail:1;
$void=isset($void)?$void && !$main:!$main;
if($head) {
	ob_start_protected("ob_gzhandler");
	header_powered();
	header_expires(false);
	openbody($pagename,"class='inicio ui-widget-content'");
	opentable("width='100%'");
	include_once("php/head.php");
}
if($main) {
	include_once("php/main.php");
}
if($tail) {
	if($void) escribe("&nbsp;","","id='fixmax'");
	include_once("php/tail.php");
	closetable();
	if(!$void && usefloatmenu()) putfloatmenu();
	closebody();
	disconnect();
	ob_end_flush();
}
