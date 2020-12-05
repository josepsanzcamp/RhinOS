<?php
/*
 ____  _     _        ___  ____
|  _ \| |__ (_)_ __  / _ \/ ___|
| |_) | '_ \| | '_ \| | | \___ \
|  _ <| | | | | | | | |_| |___) |
|_| \_\_| |_|_|_| |_|\___/|____/

RhinOS: Framework to develop Rich Internet Applications
Copyright (C) 2007-2016 by Josep Sanz Campderrós
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
function _sessions_open($save_path,$session_name) {
	global $sess_save_path;
	$sess_save_path=$save_path;
	return true;
}

function _sessions_close() {
	return true;
}

function _sessions_read($id) {
	global $sess_save_path;
	global $sess_hash;
	$sess_file="${sess_save_path}/${id}";
	$oldcache=setUseCache("false");
	$query="SELECT sess_data FROM db_sessions WHERE sess_file='${sess_file}'";
	$result=dbQuery($query);
	setUseCache($oldcache);
	$numrows=dbNumRows($result);
	if($numrows==1) {
		$row=dbFetchRow($result);
		$sess_data=$row["sess_data"];
		$sess_data=base64_decode($sess_data);
	} else {
		$sess_data="";
	}
	$sess_hash=md5($sess_data);
	dbFree($result);
	return($sess_data);
}

function _sessions_write($id,$sess_data) {
	global $sess_save_path;
	global $sess_hash;
	$sess_file="${sess_save_path}/${id}";
	$sess_time=time();
	$sess_temp=md5($sess_data);
	$sess_data=base64_encode($sess_data);
	$oldcache=setUseCache("false");
	$query="SELECT id FROM db_sessions WHERE sess_file='${sess_file}'";
	$result=dbQuery($query);
	setUseCache($oldcache);
	$numrows=dbNumRows($result);
	dbFree($result);
	if($numrows>1) {
		$query="DELETE FROM db_sessions WHERE sess_file='${sess_file}'";
		dbQuery($query);
		$numrows=0;
	}
	if($numrows>0) {
		$query="UPDATE db_sessions SET sess_data='${sess_data}', sess_time='${sess_time}' WHERE sess_file='${sess_file}'";
		if($sess_hash==$sess_temp) $query="UPDATE db_sessions SET sess_time='${sess_time}' WHERE sess_file='${sess_file}'";
		dbQuery($query);
	} else {
		$query="INSERT INTO db_sessions (id,sess_file,sess_data,sess_time) VALUES (NULL,'${sess_file}','${sess_data}','${sess_time}')";
		dbQuery($query);
	}
	return true;
}

function _sessions_destroy($id) {
	global $sess_save_path;
	$sess_file="${sess_save_path}/${id}";
	$query="DELETE FROM db_sessions WHERE sess_file='${sess_file}'";
	dbQuery($query);
	return true ;
}

function _sessions_gc($maxlifetime) {
	$sess_time=time()-$maxlifetime;
	$query="DELETE FROM db_sessions WHERE sess_time<${sess_time}";
	dbQuery($query);
	return true;
}

function initsession() {
	ini_set("session.gc_maxlifetime",3600);
	ini_set("session.gc_probability",1);
	ini_set("session.gc_divisor",100);
	ini_set("session.use_trans_sid",0);
	ini_set("session.use_only_cookie",1);
	session_set_save_handler ("_sessions_open","_sessions_close","_sessions_read","_sessions_write","_sessions_destroy","_sessions_gc");
	session_save_path("admin");
	session_start();
	$globals=array("user","pass","querystring","order","limit","offset","table","search","login");
	foreach($globals as $global) if(!isset($_SESSION[$global])) $_SESSION[$global]="";
}

function closesession() {
	session_write_close();
}
?>