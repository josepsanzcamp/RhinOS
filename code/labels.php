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
function labels($param) {
	static $row=array();
	static $lang="";
	static $default="";
	$temp=strtok($param," ");
	if($temp=="PRINT") {
	    $key=strtok(" ");
	    if(strtok(" ")=="DEFAULT") $default=strtok(" ");
	    include_once("dbapp2.php");
	    $row[$key]=_labels_get($key,$lang);
	    if(!$row[$key] && $default) $row[$key]=_labels_get($key,$default);
		_dbapp2_parser($row,__TAG1__." $param ".__TAG2__);
	} elseif($temp=="GET") {
	    $key=strtok(" ");
	    if(strtok(" ")=="DEFAULT") $default=strtok(" ");
	    $row[$key]=_labels_get($key,$lang);
	    if(!$row[$key] && $default) $row[$key]=_labels_get($key,$default);
		return $row[$key];
	} elseif($temp=="IMAGE") {
	    $key=strtok(" ");
		$file=strtok(" ");
		$param=strtok("");
	    include_once("dbapp2.php");
	    $row[$key]=_labels_get($key,$lang);
		$row["file"]=$file;
		$row["file_file"]=$file;
		_dbapp2_parser($row,__TAG1__." IMAGE file TEXT $key $param ".__TAG2__);
	} elseif(in_array($temp,array("IF","ELIF","ELSEIF"))) {
	    $key=strtok(" ");
		$row["__STATIC__"]=0;
		if($key=="NOT") {
			$key=strtok(" ");
			$row["__STATIC__"]=1;
		}
		if(_labels_get($key,$lang)) $row["__STATIC__"]=$row["__STATIC__"]?0:1;
	    include_once("dbapp2.php");
		_dbapp2_parser($row,__TAG1__." $temp __STATIC__ ".__TAG2__);
	} elseif(in_array($temp,array("ELSE","ENDIF"))) {
	    include_once("dbapp2.php");
		_dbapp2_parser($row,__TAG1__." $temp ".__TAG2__);
	} elseif($temp=="SET") {
		$key=strtok(" ");
		$value=strtok(" ");
		if($key=="LANG") $lang=$value;
		elseif($key=="DEFAULT") $default=$value;
		elseif(checkDebug("DEBUG_LABELS")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	} elseif($temp=="RESET") {
		$key=strtok(" ");
		if($key=="LANG") $lang="";
		elseif($key=="DEFAULT") $default="";
		elseif(checkDebug("DEBUG_LABELS")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	} elseif($temp=="DEBUG") {
	    $key=strtok(" ");
		_labels_debug_session($key);
	} else {
		if(checkDebug("DEBUG_LABELS")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	}
}

function _labels_get($key,$lang="") {
	static $array=array();
	if(!$lang) $lang=get_lang();

	if(_labels_debug_session()) {
		return $key;
	}
	if(!isset($array[$lang])) {
		$array[$lang]=array();
		if(_labels_section_exists()) {
			$query="SELECT sec,tag,html,$lang FROM tbl_labels";
		} else {
			$query="SELECT tag,html,$lang FROM tbl_labels";
		}
		$result=dbQuery($query);
		while($row=dbFetchRow($result)) {
			if(_labels_section_exists()) {
				$array[$lang][$row["sec"]][$row["tag"]]=$row[$lang];
			} else {
				$array[$lang][$row["tag"]]=$row[$lang];
			}
		}
		dbFree($result);
	}
	$key=explode("/",$key);
	$count=count($key);
	if(_labels_section_exists()) {
		if($count==2) {
			if(isset($array[$lang][$key[0]][$key[1]])) {
				$label=$array[$lang][$key[0]][$key[1]];
			}
		} elseif($count==1) {
			foreach($array[$lang] as $val) {
				if(isset($val[$key[0]])) {
					$label=$val[$key[0]];
					break;
				}
			}
		} else {
			$key=implode("/",$key);
			$label="INCORRECT LABEL '$key'";
		}
	} else {
		if($count==1) {
			if(isset($array[$lang][$key[0]])) {
				$label=$array[$lang][$key[0]];
			}
		} else {
			$key=implode("/",$key);
			$label="INCORRECT LABEL '$key'";
		}
	}
	if(!isset($label)) {
		$key=implode("/",$key);
		$label="LABEL '$key' NOT FOUND";
	}
	return $label;
}

function _labels_section_exists() {
	return _labels_field_exists("sec");
}

function _labels_field_exists($field) {
	static $stack=array();
	if(!isset($stack[$field])) {
		$query="SELECT * FROM tbl_labels LIMIT 1";
		$result=dbQuery($query);
		if(dbNumRows($result)==0) {
			dbFree($result);
			$query="/*MYSQL SHOW COLUMNS FROM tbl_labels *//*SQLITE PRAGMA TABLE_INFO(tbl_labels) */";
			$result=dbQuery($query);
			$stack[$field]=false;
			$dbtemp=getdbtype();
			while($row=dbFetchRow($result)) {
				if($dbtemp=="MYSQL") if($row["Field"]==$field) $stack[$field]=true;
				if($dbtemp=="SQLITE") if($row["name"]==$field) $stack[$field]=true;
			}
			dbFree($result);
		} else {
			$stack[$field]=false;
			$numfields=dbNumFields($result);
			for($i=0;$i<$numfields;$i++) {
				if(dbFieldName($result,$i)==$field) $stack[$field]=true;
			}
			dbFree($result);
		}
	}
	return $stack[$field];
}

function _labels_debug_session($force="") {
	static $debug="";
	include_once("sessions.php");
	if($force!="") {
		sessions("SET __LABELS_DEBUG__ $force");
		$debug=$force;
	} elseif($debug=="") {
		$debug=sessions("GET __LABELS_DEBUG__");
	}
	return $debug;
}
