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
if(!check_user()) intro();
if($table=="") intro();
if($form=="show") {
	if(!check_permissions("select",$table)) intro();
} else {
	if($form=="copy") {
		if(!check_permissions("insert",$table)) intro();
		if(!check_permissions("select",$table) && !check_permissions("update",$table)) intro();
		$iter=count(explode(",",$id));
		$id="";
	} else {
		if($id=="") $role="insert"; else $role="update";
		if(!check_permissions($role,$table)) intro();
	}
	openf1form();
	puthidden("page","process");
	puthidden("id",$id);
	if($iter=="") $iter="1";
	puthidden("iter",$iter);
	puthidden("table",$table);
	puthidden("action","update");
	$returnhere=intval(getParam("returnhere"));
	puthidden("returnhere",$returnhere);
}
escribe(get_migas());
escribe();
puterrores();
$count_error=count($error);
getformconfig($table);
if(!count($campos)) {
	$error[]="WARNING:"._LANG("form_config_not_found");
	puterrores();
	unset($error[count($error)-1]);
}
if($form=="show") reparaformconfig();
if($count_error==0) {
	$myid=($form=="copy")?getParam("id"):$id;
	if($id!="" || $myid!="") {
		$query="SELECT * FROM ".$table." WHERE id IN ($myid)";
		$result=dbQuery($query);
		$newrow=array();
		$count_iter=0;
		$id_real=array();
		$mylistids=array();
		while($row=dbFetchRow($result)) {
			$j=($form=="copy")?$count_iter:$row["id"];
			$id_real[$count_iter]=$row["id"];
			$mylistids[$count_iter]=$count_iter;
			$count_campos=count($campos);
			for($i=0;$i<$count_campos;$i++) {
				$campo=$campos[$i];
				$temp=explode(":",$campo);
				$campo_real=$temp[0];
				$type=$types[$i];
				if($type=="date") $row[$campo_real]=convert_date($row[$campo_real]);
				if($type=="time") $row[$campo_real]=substr($row[$campo_real],0,5);
				if($type=="unixtime" || $type=="timestamp") $row[$campo_real]=date("d/m/Y H:i",$row[$campo_real]);
				if($type=="datetime") $row[$campo_real]=convert_date(substr($row[$campo_real],0,10))." ".substr($row[$campo_real],11,5);
				$newrow["$campo.$table.$j"]=$row[$campo_real];
				$newrow["$campo_real.$table.$j"]=$row[$campo_real];
				if($type=="file" || $type=="photo") {
					$newrow[$campo_real."_file.$table.$j"]=$row[$campo_real."_file"];
					$newrow[$campo_real."_size.$table.$j"]=$row[$campo_real."_size"];
					$newrow[$campo_real."_type.$table.$j"]=$row[$campo_real."_type"];
				}
			}
			$count_iter++;
		}
		dbFree($result);
		$row=$newrow;
		$listids=($form=="copy")?$mylistids:explode(",",$id);
	} else {
		$listids=array();
		$row=array();
		for($j=0;$j<$iter;$j++) {
			$listids[$j]=$j;
			$count_campos=count($campos);
			for($i=0;$i<$count_campos;$i++) {
				$campo=$campos[$i];
				$type=$types[$i];
				if($type=="date") $row["$campo.$table.$j"]=get_date();
				if($type=="time") $row["$campo.$table.$j"]=get_time();
				if($type=="unixtime" || $type=="timestamp") $row["$campo.$table.$j"]=date("d/m/Y H:i",time());
				if($type=="datetime") $row["$campo.$table.$j"]=get_date()." ".get_time();
			}
		}
	}
} else {
	if($id=="") {
		$listids=array();
		for($j=0;$j<$iter;$j++) $listids[$j]=$j;
	} else {
		$listids=explode(",",$id);
	}
}
$first=1;
$ajaxdinamic=has_ajaxdinamic();
foreach($listids as $j) {
	if(!$first) {
		if($ajaxdinamic) {
			getformconfig($table);
			if($form=="show") reparaformconfig();
		}
	}
	put_form($table,$j,$row,$id);
	$first=0;
}
if($count_error>0) msgbox(_LANG("form_message_form_error"));
if($form!="show") {
	if(is_array($types)) {
		$i=0;
		$valids="text,textarea,textareaold,date,time,unixtime,timestamp,datetime,color,password,md5password,sha1password,integer,real,decimal,float,double";
		$valids=explode(",",$valids);
		foreach($types as $key=>$type) {
			if(in_array($type,$valids)) {
				$i=$key;
				break;
			}
		}
		$campo=$campos[$i];
		$j=$listids[0];
		echo "<script language=javascript type=text/javascript>\n";
		echo "var obj=document.getElementById('$campo.$table.$j');\n";
		echo "if(!isNull(obj)) myfocus(obj.id);\n";
		echo "</script>\n";
	}
	closef1form();
}
