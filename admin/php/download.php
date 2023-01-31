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
$head=0;$main=0;$tail=0;
include("inicio.php");
if(!check_user()) die();
$name=getParam("name");
$file="files/".basename(getParam("file"));
$size=getParam("size");
$type=getParam("type");
if(!file_exists($file)) die();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, no-transform");
header("Content-Type: $type");
header("Content-Length: $size");
header("Content-Disposition: attachment; filename=\"$name\"");
header("Content-Transfer-Encoding: binary");
$fp=fopen($file,"rb");
while(!feof($fp)) echo fread($fp,1048576);
fclose($fp);
disconnect();
die();
