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
// DATABASE
$_CONFIG["db"]["type"]="pdo_sqlite";
$_CONFIG["db"]["host"]="localhost";
$_CONFIG["db"]["user"]="baseweb";
$_CONFIG["db"]["pass"]="baseweb";
$_CONFIG["db"]["name"]="baseweb";
$admindir=str_replace(DIRECTORY_SEPARATOR,"/",getcwd());
$admindir=str_replace("/admin","",$admindir)."/admin";
$_CONFIG["db"]["file"]="$admindir/files/baseweb.db";
$_CONFIG["db"]["link"]=null;
// DEFINES
//define("DEMO",1);
//define("DEBUG",1);
// OVERLOADS
//define("__FORCE_ISMAIL__",true);
//define("__CHECK_REFERER__",0);
//define("__CHECK_DEVEL__",1);
//define("__PHPTHUMB_USM__",0);
// LANGS
//$_LANG=array("lang"=>"es");
//$_LANG=array("lang"=>"ca");
//$_LANG=array("lang"=>"en");
// AUTOMATE THE DEBUG FEATURE
if(!defined("DEBUG") && file_exists($admindir."/DEBUG")) {
	define("DEBUG",file_get_contents($admindir."/DEBUG"));
}
?>