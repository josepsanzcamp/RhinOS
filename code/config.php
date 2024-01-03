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
function config($param) {
	$temp=strtok($param," ");
	$key=strtok(" ");
	if($temp=="PRINT") {
	    include_once("dbapp2.php");
		$row[$key]=_config_get($key);
		_dbapp2_parser($row,__TAG1__." $param ".__TAG2__);
	} elseif($temp=="GET") {
		return _config_get($key);
	} elseif($temp=="SET") {
		$value=trim(strtok(""));
	    include_once("dbapp2.php");
	    $value=_dbapp2_replace($value);
	    $query="SELECT * FROM tbl_config WHERE `param`='$key'";
	    $result=dbQuery($query);
	    if(dbNumRows($result)==1) {
			$row=dbFetchRow($result);
			$type=$row["type"];
			$minval=$row["minval"];
			$maxval=$row["maxval"];
			if($type=="integer") {
				$value=intval($value);
				if($minval!="") {
					$minval=($minval!="")?intval($minval):"";
					if($value<$minval) $value=$minval;
				}
				if($maxval!="") {
					$maxval=($maxval!="")?intval($maxval):"";
					if($value>$maxval) $value=$maxval;
				}
			} elseif($type=="real") {
				$value=floatval($value);
				if($minval!="") {
					$minval=($minval!="")?floatval($minval):"";
					if($value<$minval) $value=$minval;
				}
				if($maxval!="") {
					$maxval=($maxval!="")?floatval($maxval):"";
					if($value>$maxval) $value=$maxval;
				}
			}
			$query="UPDATE tbl_config SET `value`='$value' WHERE `param`='$key'";
			dbQuery($query);
		} else {
			if(checkDebug("DEBUG_CONFIG")) echo_buffer(__TAG1__." CONFIG NOT FOUND: $param ".__TAG2__);
		}
		dbFree($result);
	} else {
		if(checkDebug("DEBUG_CONFIG")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	}
}

function _config_get($key) {
	static $stack=null;

	if($stack===null) {
		$stack=array();
		if(table_exists("tbl_config")) {
			$query="SELECT * FROM tbl_config";
			$result=dbQuery($query);
			while($row=dbFetchRow($result)) $stack[$row["param"]]=$row["value"];
			dbFree($result);
		}
	}
	if(!isset($stack[$key])) return "CONFIG '$key' NOT FOUND";
	return $stack[$key];
}
