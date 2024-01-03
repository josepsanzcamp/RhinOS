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
if(!check_user()) $page="intro";
if($page=="") $page="intro";
if(file_exists($page."_".$table.".php")) {
	$php=""; $page=$page."_".$table; $ext=".php";
} elseif(file_exists($page.".php")) {
	$php=""; $ext=".php";
} elseif(file_exists($page)) {
	$php=""; $ext="";
} elseif(file_exists("php/".$page."_".$table.".php")) {
	$php="php/"; $page=$page."_".$table; $ext=".php";
} elseif(file_exists("php/".$page.".php")) {
	$php="php/"; $ext=".php";
} elseif(file_exists("php/".$page)) {
	$php="php/"; $ext="";
} else {
	$php="php/"; $page="intro"; $ext=".php";
}
if($action!="") include($php.$page.$ext);
if(!isset($error)) $error=array();
if(!isset($marked)) $marked=array();
openmain();
if($func!="") $func=substr($func,0,-4);
if(check_admin() && $func!="") $func();
elseif(!check_admin() && $func!="") msgbox(_LANG("main_message_denied"),"inicio.php?include=php/login.php?action=logout");
elseif(!check_admin() && check_block_admin()) msgbox(_LANG("main_message_maintenance"),"inicio.php?include=php/login.php?action=logout");
else include($php.$page.$ext);
closemain();
