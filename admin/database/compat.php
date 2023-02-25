<?php
/*
 ____        _ _    ___  ____
/ ___|  __ _| | |_ / _ \/ ___|
\___ \ / _` | | __| | | \___ \
 ___) | (_| | | |_| |_| |___) |
|____/ \__,_|_|\__|\___/|____/

SaltOS: Framework to develop Rich Internet Applications
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
function evalBool($arg) {
	return eval_bool($arg);
}

function dbFieldType($result,$index) {
	if($result["total"]==0) return "";
	$data=(string)$result["rows"][0][$result["header"][$index]];
	if(strlen($data)==10 && $data[4]=="-" && $data[7]=="-") return "date";
	if(strlen($data)==8 && $data[2]==":" && $data[5]==":") return "time";
	return "";
}

function getUseCache($query="") {
	return get_use_cache($query);
}

function setUseCache($bool) {
	return set_use_cache($bool);
}

function parseQuery($query,$type) {
	return parse_query($query,$type);
}

function dbConnect() {
	return db_connect();
}

function dbQuery($query,$fetch="query") {
	db_query("/*MYSQL SET SQL_MODE='ALLOW_INVALID_DATES'*/");
	return db_query($query,$fetch);
}

function dbDisconnect() {
	return db_disconnect();
}

function dbError($array) {
	return db_error($array);
}

function dbFetchRow(&$result) {
	return db_fetch_row($result);
}

function dbFetchAll(&$result) {
	return db_fetch_all($result);
}

function dbNumRows($result) {
	return db_num_rows($result);
}

function dbFree(&$result) {
	return db_free($result);
}

function dbNumFields($result) {
	return db_num_fields($result);
}

function dbFieldName($result,$index) {
	return db_field_name($result,$index);
}

if(!isset($_CONFIG)) {
	$_CONFIG=array();
}
if(!isset($_CONFIG["db"]["type"]) && isset($dbtype)) {
	$_CONFIG["db"]["type"]=$dbtype;
}
if(!isset($dbtype) && isset($_CONFIG["db"]["type"])) {
	$dbtype=$_CONFIG["db"]["type"];
}
if(!isset($dbtype)) {
	die("Unknown dbtype");
}
if(!isset($_CONFIG["db"]["host"]) && isset($dbhost)) {
	$_CONFIG["db"]["host"]=$dbhost;
}
if(!isset($dbhost) && isset($_CONFIG["db"]["host"])) {
	$dbhost=$_CONFIG["db"]["host"];
}
if(!isset($dbhost)) {
	die("Unknown dbhost");
}
if(!isset($_CONFIG["db"]["user"]) && isset($dbuser)) {
	$_CONFIG["db"]["user"]=$dbuser;
}
if(!isset($dbuser) && isset($_CONFIG["db"]["user"])) {
	$dbuser=$_CONFIG["db"]["user"];
}
if(!isset($dbuser)) {
	die("Unknown dbuser");
}
if(!isset($_CONFIG["db"]["pass"]) && isset($dbpass)) {
	$_CONFIG["db"]["pass"]=$dbpass;
}
if(!isset($dbpass) && isset($_CONFIG["db"]["pass"])) {
	$dbpass=$_CONFIG["db"]["pass"];
}
if(!isset($dbpass)) {
	die("Unknown dbpass");
}
if(!isset($_CONFIG["db"]["name"]) && isset($dbname)) {
	$_CONFIG["db"]["name"]=$dbname;
}
if(!isset($dbname) && isset($_CONFIG["db"]["name"])) {
	$dbname=$_CONFIG["db"]["name"];
}
if(!isset($dbname)) {
	die("Unknown dbname");
}
if(!isset($_CONFIG["db"]["file"]) && isset($dbfile)) {
	$_CONFIG["db"]["file"]=$dbfile;
}
if(!isset($dbfile) && isset($_CONFIG["db"]["file"])) {
	$dbfile=$_CONFIG["db"]["file"];
}
if(!isset($dbfile)) {
	die("Unknown dbfile");
}
if(!isset($_CONFIG["db"]["port"])) {
	$_CONFIG["db"]["port"]="3306";
}
if(!isset($_CONFIG["db"]["usecache"])) {
	$_CONFIG["db"]["usecache"]="true";
}
if(!isset($_CONFIG["db"]["nocaches"])) {
	$_CONFIG["db"]["nocaches"]=array();
	$_CONFIG["db"]["nocaches"]["nocache"]="INSERT";
	$_CONFIG["db"]["nocaches"]["nocache#1"]="UPDATE";
	$_CONFIG["db"]["nocaches"]["nocache#2"]="DELETE";
}
if(!isset($_CONFIG["debug"])) {
	$_CONFIG["debug"]=array();
}
if(!isset($_CONFIG["debug"]["logfile"])) {
	$_CONFIG["debug"]["logfile"]="rhinos.log";
}
if(!isset($_CONFIG["debug"]["maxlines"])) {
	$_CONFIG["debug"]["maxlines"]=1000;
}
if(!isset($_CONFIG["debug"]["errorfile"])) {
	$_CONFIG["debug"]["errorfile"]="error.log";
}
if(!isset($_CONFIG["dirs"]["filesdir"])) {
	$filesdir=getcwd()."/cache/";
	if(!file_exists($filesdir)) $filesdir=getcwd()."/code/cache/";
	$_CONFIG["dirs"]["filesdir"]=$filesdir;
}
if(!isset($_LANG)) {
	$_LANG=array();
}
if(!isset($_LANG["default"])) {
	$_LANG["default"]="common";
}
if(!isset($_CONFIG["info"])) {
	$_CONFIG["info"]=array();
}

program_error_handler();
