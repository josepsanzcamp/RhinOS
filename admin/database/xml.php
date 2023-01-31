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
function dbConnect_xml() {
	dbError_xml("Connect not implemented");
}

function dbQuery_xml($query) {
	global $_CONFIG;
	static $stack=array();

	@list($file,$query)=explode("|",$query);
	$file=trim($file);
	$query=trim($query);
	// CHECK CACHE (FILE AND QUERY)
	$cache=md5($file.$query);
	$usecache=evalBool(getUseCache($file."|".$query));
	if($usecache && isset($stack[$cache])) {
		$result=$stack[$cache];
		return $result;
	}
	// CHECK CACHE (ONLY FILE)
	$cache2=md5($file);
	if($usecache && isset($stack[$cache2])) {
		$doc=$stack[$cache2];
	} else {
		// CREATE A NEW XML DOCUMENT
		@$xml=file_get_contents($file);
		if(!$xml) {
			dbError_xml(array("query"=>$query,"details"=>"File '$file' not found"));
		}
		$doc=new DomDocument;
		$out=@$doc->loadXML($xml,LIBXML_COMPACT);
		if($out===false) {
			dbError_xml(array("query"=>$query,"details"=>"File '$file' not loaded"));
		}
		// IDENTIFY ALL NODES
		$nodos=$doc->getElementsByTagName("*");
		$counter=1;
		foreach($nodos as $nodo) {
			$nodo->setAttribute("id",$counter);
			$counter++;
		}
		if($usecache) $stack[$cache2]=$doc;
	}
	// DO QUERY
	$result=array("total"=>0,"count"=>0,"header"=>array(),"rows"=>array());
	$xpath=new DOMXPath($doc);
	@$data=$xpath->query($query);
	// DUMP RESULT TO MATRIX
	if($data) {
		foreach($data as $row) {
			$temp=array();
			$temp["id"]=$row->getAttribute("id");
			for($nodo=$row->firstChild;$nodo!==null;$nodo=$nodo->nextSibling) $temp[$nodo->nodeName]=$nodo->nodeValue;
			$result["rows"][]=$temp;
		}
		$result["total"]=count($result["rows"]);
		if($result["total"]>0) $result["header"]=array_keys($result["rows"][0]);
	}
	// AND RETURN
	if($usecache) $stack[$cache]=$result;
	return $result;
}

function dbFetchRow_xml(&$result) {
	$row=null;
	if($result["total"]>$result["count"]) {
		$row=$result["rows"][$result["count"]];
		unset($result["rows"][$result["count"]]);
		$result["count"]++;
	}
	return $row;
}

function dbNumRows_xml($result) {
	return $result["total"];
}

function dbFree_xml(&$result) {
	$result=array("total"=>0,"count"=>0,"header"=>array(),"rows"=>array());
}

function dbDisconnect_xml() {
	dbError_xml("Disconnect not implemented");
}

function dbNumFields_xml($result) {
	return count($result["header"]);
}

function dbError_xml($query) {
	$array=array();
	if(is_array($query)) {
		foreach($query as $key=>$val) $array[$key]=$val;
	} else {
		$array["query"]=$query;
	}
	show_php_error($array);
}

function dbFieldName_xml($result,$index) {
	if(!isset($result["header"][$index])) dbError_pdo("Unknown field name at position {$index}");
	return $result["header"][$index];
}
