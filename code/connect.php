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
function connect () {
	dbConnect();
}

function disconnect() {
	dbDisconnect();
}

function getString($string) {
	$string=trim($string);
	if(ini_get("magic_quotes_gpc")!=1) $string=addslashes($string);
	return $string;
}

function getParam($index,$default="") {
	if(isset($_POST[$index])) return getString($_POST[$index]);
	if(isset($_GET[$index])) return getString($_GET[$index]);
	return $default;
}

function useSession($name,$value="",$default="") {
	if($value!="") $_SESSION[$name]=$value;
	elseif(isset($_SESSION[$name]) && $_SESSION[$name]!="") $value=$_SESSION[$name];
	else $value=$default;
	return $value;
}

function useCookie($name,$value="",$default="") {
	if(isset($pathbase)) {
		$path="/".$pathbase;
	} else {
		$path=$_SERVER["SCRIPT_NAME"];
		$path=dirname($path)."/";
	}
	if($value!="") setcookie($name,$value,time()+86400*30,$path);
	elseif(isset($_COOKIE[$name]) && $_COOKIE[$name]!="") $value=$_COOKIE[$name];
	else $value=$default;
	return $value;
}

function get_temp_directory() {
	static $temp="";
	if($temp!="") return $temp;
	$temp=getcwd()."/code/cache";
	if(isset($_ENV["TEMP"])) $temp=$_ENV["TEMP"];
	if(substr($temp,-1,1)!="/") $temp.="/";
	return $temp;
}

function cache_gc() {
	init_random();
	if(rand(0,100)>1) return;
	$files=glob(get_temp_directory()."*");
	$unix=time()-(86400*30);
	foreach($files as $file) {
		capture_next_error();
		$mtime=filemtime($file);
		$error=get_clear_error();
		if(!$error && $unix>$mtime) {
			capture_next_error();
			unlink($file);
			get_clear_error();
		}
	}
}

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
