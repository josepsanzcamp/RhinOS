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
$head=0;$main=0;$tail=0;
include("inicio.php");
ob_start();
$table=getParam("table");
$has_perm=0;
if(check_permissions("select",$table)) $has_perm=1;
if(check_permissions("insert",$table)) $has_perm=1;
if(check_permissions("update",$table)) $has_perm=1;
if(!$has_perm) intro();
$variable=getParam("variable");
$default=getParam("default");
$filter=getParam("filter");
$extra=getParam("extra");
// solo busca el value_ref para el IN del filtro
$temp=explode(".",$variable);
$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$temp[0]'";
$result=dbQuery($query);
$row=dbFetchRow($result);
$value_ref=(is_array($row) && isset($row["value_ref"]))?$row["value_ref"]:"";
$text_ref=(is_array($row) && isset($row["value_ref"]))?$row["text_ref"]:"";
dbFree($result);
// montar el campo real de la tabla
$temp2=explode(":",$temp[0]);
$temp[0]=$temp2[0];
$campo=implode(".",$temp);
if(count($temp2)==2) {
	if($text_ref=="") {
		// buscar el tipo de dato
		$padre=$temp2[1];
		$counter=100;
		while($counter>0) {
			$query="SELECT * FROM db_forms WHERE tbl='$table' AND row='".addslashes($padre)."'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$type=$row["type"];
			dbFree($result);
			if($type!="") break;
			$query="SELECT * FROM db_selects WHERE tbl='$table' AND row LIKE '$padre:%'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$temp=explode(":",$row["row"]);
			if(isset($temp[1])) $padre=$temp[1];
			else $counter=0;
			$counter--;
		}
		if($counter<=0) die("&nbsp;"._LANG("getfilter_message_error"));
		// sigue ...
	} else {
		$type=$text_ref;
	}
} else {
	$query="SELECT * FROM db_forms WHERE tbl='$table' AND row='".addslashes($temp2[0])."'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$type=(is_array($row) && isset($row["type"]))?$row["type"]:"";
	dbFree($result);
}
if($type=="") die("&nbsp;"._LANG("getfilter_message_error"));
// buscar text_ref real
$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='{$temp2[0]}'";
$result=dbQuery($query);
$row=dbFetchRow($result);
$text_ref=$row["text_ref"];
dbFree($result);
// continuar montando el filtro
if($filter=="") {
	$filter="0";
} elseif($filter=="*") {
	$filter="1";
} else {
	$temp=explode(",",$filter);
	$filter="(";
	$first=1;
	foreach($temp as $temp2) {
		if(!$first) $filter.=" OR ";
		$filter.="/*MYSQL FIND_IN_SET('".$temp2."',`$value_ref`) *//*SQLITE (`$value_ref` LIKE '".$temp2."' OR `$value_ref` LIKE '%,".$temp2."' OR `$value_ref` LIKE '%,".$temp2.",%' OR `$value_ref` LIKE '".$temp2.",%') */";
		$first=0;
	}
	$filter.=")";
}
if($extra!="") {
	$temp=explode(":",$text_ref);
	if($temp[0]=="concat") {
		unset($temp[0]);
		$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
		$text_ref=parseQuery($text_ref,getdbtype());
	}
	$filter=array("filter"=>$filter,"extra"=>"");
	$temp=explode(" ",$extra);
	foreach($temp as $temp2) {
		$filter["extra"].=" AND ($text_ref LIKE '%$temp2%')";
	}
}
$_withtd=0;
switch($type) {
	case "multiselect":
		putmultiselect($table,$campo,"",$default,$filter,$extra);
		break;
	case "select":
		putselect($table,$campo,"",$default,$filter,$extra);
		break;
}
disconnect();
$buffer=ob_get_clean();
ob_start_protected("ob_gzhandler");
header_powered();
header_expires(false);
echo $buffer;
ob_end_flush();
