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
if(!check_user()) intro();
if($table=="") intro();
if($action=="setboolean") {
	if(!check_permissions("update",$table)) intro();
	$campo=getParam("campo");
	$value=getParam("value");
	$query="UPDATE $table SET `$campo`='$value' WHERE id='$id'";
	dbQuery($query);
	if(column_exists($table,"_modified")) {
		$campo="_modified";
		$value=time();
		$query="UPDATE $table SET `$campo`='$value' WHERE id='$id'";
		dbQuery($query);
	}
	location("inicio.php?page=list&table=$table");
	$head=0;$main=0;$void=1;$tail=1;
	include("inicio.php");
	die();
} elseif($action=="delete") {
	if(!check_permissions("delete",$table)) intro();
	$error=find_dependencies($table,$id);
	if(is_needed($table,$id)) {
		$error[]="ERROR:"._LANG("process_message_error_delete_needed");
	}
	if(count($error)>0) {
		$page="list";
		$id="";
		initsession();
		control();
		closesession();
	} else {
		getformconfig($table);
		$count_campos=count($campos);
		for($i=0;$i<$count_campos;$i++) {
			$campo=$campos[$i];
			$type=$types[$i];
			if($type=="ajaxdinamic") {
				$listids=explode(",",$id);
				foreach($listids as $j) {
					$row=array();
					show_data_dinamic($campo,$table,$j,"",$row);
				}
			}
		}
		$count_campos=count($campos);
		for($i=0;$i<$count_campos;$i++) {
			$campo=$campos[$i];
			$type=$types[$i];
			if($type=="file" || $type=="photo") {
				$query="SELECT $campo"."_file FROM $table WHERE id IN ($id)";
				$result=dbQuery($query);
				while($row=dbFetchRow($result)) {
					$file=$row[$campo."_file"];
					if($file!="") if(file_exists("files/".$file)) unlink("files/".$file);
				}
				dbFree($result);
			}
		}
		$query="DELETE FROM $table WHERE id IN ($id)";
		dbQuery($query);
		location("inicio.php?page=list&table=$table");
		$head=0;$main=0;$void=1;$tail=1;
		include("inicio.php");
		die();
	}
} elseif($action=="update") {
	if($id=="") $role="insert"; else $role="update";
	if(!check_permissions($role,$table)) intro();
	getformconfig($table);
	getdefaults($table);
	reparaformconfig();
	$error=array();
	if($id!="") {
		$listids=explode(",",$id);
	} else {
		$listids=array();
		for($j=0;$j<$iter;$j++) $listids[$j]=$j;
	}
	$row=array();
	$first=1;
	$ajaxdinamic=has_ajaxdinamic();
	foreach($listids as $j) {
		checkMultipleParameters($table,$j,$id);
	}
	if(count($error)>0) {
		$first=1;
		$ajaxdinamic=has_ajaxdinamic();
		foreach($listids as $j) {
			if(!$first && $ajaxdinamic) {
				getformconfig($table);
				getdefaults($table);
				reparaformconfig();
			}
			$count_campos=count($campos);
			for($i=0;$i<$count_campos;$i++) {
				$campo=$campos[$i];
				$type=$types[$i];
				if($type=="ajaxdinamic") {
					check_data_dinamic($campo,$table,$j);
				}
			}
			$first=0;
		}
		$page="form";
	} else {
		$first=1;
		$ajaxdinamic=has_ajaxdinamic();
		foreach($listids as $j) {
			if(!$first && $ajaxdinamic) {
				getformconfig($table);
				getdefaults($table);
				reparaformconfig();
			}
			$count_campos=count($campos);
			for($i=0;$i<$count_campos;$i++) {
				$campo=$campos[$i];
				$type=$types[$i];
				if($type=="ajaxdinamic") {
					update_data_dinamic($campo,$table,$j);
				}
			}
			processMultipleParameters($table,$j,$id);
			$first=0;
		}
		$returnhere=intval(getParam("returnhere"));
		if($returnhere) {
			$page="form";
		} else {
			location("inicio.php?page=list&table=$table&id=$id");
			$head=0;$main=0;$void=1;$tail=1;
			include("inicio.php");
			die();
		}
	}
} elseif($action=="save") {
	getlistconfig($table);
	getdefaults($table);
	$rows=getParam("row");
	$error=array();
	if($rows=="") {
		$error[]="ERROR:"._LANG("process_message_error_nodata");
		$page="list";
		initsession();
		control();
		closesession();
	} else {
		$ids=explode(",",$id);
		$rows=explode(",",$rows);
		$rowdata=array();
		$count_ids=count($ids);
		$count_rows=count($rows);
		for($i=0;$i<$count_ids;$i++) {
			$sets="";
			for($j=0;$j<$count_rows;$j++) {
				$myid=$ids[$i];
				$campo=$campos[$rows[$j]];
				$texto=$textos[$rows[$j]];
				$type=$types[$rows[$j]];
				$needed=$neededs[$rows[$j]];
				$unique=$uniques[$rows[$j]];
				$value=getParam("$campo"."_$table"."_$myid");
				if($type=="boolean" && $value=="") $value="0";
				$rowdata["$campo.$table.$myid"]=$value;
				if($unique) {
					$query="SELECT $campo FROM $table WHERE $campo='$value' AND id<>'$myid'";
					$result=dbQuery($query);
					if(dbNumRows($result)>0) {
						$error[]="ERROR:"._LANG("process_message_error_unique").$texto;
						$marked[]="$campo"."_$table"."_$myid";
					}
					dbFree($result);
				}
				if($needed && $value=="") {
					$error[]="ERROR:".str_replace("#campo#",$texto,_LANG("process_message_error_needed"));
					$marked[]="$campo"."_$table"."_$myid";
				}
			}
		}
		if(count($error)>0) {
			$page="list";
			initsession();
			control();
			closesession();
		} else {
			$count_ids=count($ids);
			$count_rows=count($rows);
			for($i=0;$i<$count_ids;$i++) {
				$sets="";
				for($j=0;$j<$count_rows;$j++) {
					$myid=$ids[$i];
					$campo=$campos[$rows[$j]];
					$type=$types[$rows[$j]];
					$needed=$neededs[$rows[$j]];
					$unique=$uniques[$rows[$j]];
					$value=getParam("$campo"."_$table"."_$myid");
					$default="";
					if(isset($defaults[$rows[$j]])) $default=$defaults[$rows[$j]];
					if($type=="date") $value=invert_date($value);
					if($type=="time") $value=substr($value,0,5);
					if($type=="unixtime" || $type=="timestamp") $value=strtotime(invert_date(substr($value,0,10))." ".substr($value,11,5));
					if($type=="datetime") $value=invert_date(substr($value,0,10))." ".substr($value,11,5);
					if($type=="text") $value=some_htmlentities($value);
					if($value=="") $value=$default;
					if(strlen($sets)>0) $sets.=",";
					$sets.="`$campo`='$value'";
				}
				if(column_exists($table,"_modified")) {
					$campo="_modified";
					$value=time();
					if(strlen($sets)>0) $sets.=",";
					$sets.="`$campo`='$value'";
				}
				$query="UPDATE $table SET $sets WHERE id='$myid'";
				dbQuery($query);
			}
			location("inicio.php?page=list&table=$table&id=$id");
			$head=0;$main=0;$void=1;$tail=1;
			include("inicio.php");
			die();
		}
	}
}
