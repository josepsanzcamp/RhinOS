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
function extcache($param) {
	static $cache="";

	$temp=intval(strtok($param," "));
	if(!$temp) $temp=300;
	$uri=$_SERVER["QUERY_STRING"];
	$cache=get_cache_file($uri,".cache");
	$data="";
	if(!_extcache_checktime($cache,$temp)) {
		@$data=file_get_contents($uri);
		if($data) {
			$valids=array("Content-Type","Content-Length");
			$header=array();
			foreach($http_response_header as $h) {
				foreach($valids as $v) {
					if(stripos($h,$v)!==false) $header[]=$h;
				}
			}
			$temp=array("header"=>$header,"data"=>$data);
			$temp=serialize($temp);
			file_put_contents($cache,$temp);
		}
	}
	if(!$data && file_exists($cache)) {
		$temp=file_get_contents($cache);
		$temp=unserialize($temp);
		$header=$temp["header"];
		$data=$temp["data"];
	}
	if($data) {
		foreach($header as $h) header($h);
		echo $data;
		die();
	}
}

function _extcache_checktime($cache,$offset) {
	if(!file_exists($cache)) return 0;
	if(time()-$offset>filemtime($cache)) return 0;
	return 1;
}
