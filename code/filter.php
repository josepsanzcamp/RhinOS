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
function filter($param) {
	static $row=array();
	include_once("dbapp2.php");

	$temp=strtok($param," ");
	if(in_array($temp,array("IF","ELIF","ELSEIF"))) {
		$cond=strtok("");
		$cond=trim($cond);
		$cond=explode(" ",$cond);
		$row["__STATIC__"]=0;
		$true=1;
		if(isset($cond[0]) && $cond[0]=="NOT") {
			array_shift($cond);
			$row["__STATIC__"]=1;
			$true=0;
		}
		$argv=get_argv();
		$argc=count($argv);
		$line=implode("/",$argv);
		foreach($cond as $c) {
			if(strpos($c,"/")===false) {
				if($argc>0) if($argv[0]==$c) $row["__STATIC__"]=$true;
			} else {
				if(strncmp($line,$c,strlen($c))==0) $row["__STATIC__"]=$true;
			}
		}
		$lang=get_lang();
		foreach($cond as $c) {
			if($lang==$c) $row["__STATIC__"]=$true;
		}
		foreach($_SESSION AS $key=>$val) {
			foreach($cond as $c) {
				if("SESSION[$key]"==$c && $val) $row["__STATIC__"]=$true;
			}
		}
		foreach($_POST AS $key=>$val) {
			foreach($cond as $c) {
				if("POST[$key]"==$c && $val) $row["__STATIC__"]=$true;
			}
		}
		foreach($_GET AS $key=>$val) {
			foreach($cond as $c) {
				if("GET[$key]"==$c && $val) $row["__STATIC__"]=$true;
			}
		}
		_dbapp2_parser($row,__TAG1__." $temp __STATIC__ ".__TAG2__);
	} elseif(in_array($temp,array("ELSE","ENDIF"))) {
		_dbapp2_parser($row,__TAG1__." $temp ".__TAG2__);
	}
}
?>