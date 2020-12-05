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
function files ($param) {
	include_once("code/dbapp2.php");
	$tag=_dbapp2_replace(strtok($param," "));
	$param2=trim(strtok(""));
    $query="SELECT * FROM tbl_files WHERE tag='$tag'";
	$result=dbQuery($query);
	$numrows=dbNumRows($result);
	$row=dbFetchRow($result);
	dbFree($result);
	if($numrows) {
		include_once("dbapp2.php");
		if($row["image"]) {
			_dbapp2_parser($row,__TAG1__." IMAGE file $param2 ".__TAG2__);
		} else {
			_dbapp2_parser($row,__TAG1__." FILE file $param2 ".__TAG2__);
		}
	} else {
		if(checkDebug("DEBUG_FILES")) echo_buffer(__TAG1__." TAG NOT FOUND: $param ".__TAG2__);
	}
}
?>