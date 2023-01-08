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
$head=0;$main=0;$tail=0;
include("inicio.php");
// BEGIN THE PHPTHUMB WRAPPER
if(!isset($_SERVER["HTTP_REFERER"])) $_SERVER["HTTP_REFERER"]="";
$_SERVER["PHP_SELF"]=dirname($_SERVER["SCRIPT_NAME"])."/lib/phpthumb/phpThumb.php";
require_once("lib/phpthumb/phpthumb.class.php");
$phpThumb = new phpThumb();
$phpThumb->src=realpath(getcwd()."/files/".getParam("src"));
$phpThumb->config_temp_directory=get_temp_directory();
$phpThumb->config_cache_directory=get_temp_directory();
$phpThumb->config_cache_maxage=86400*30;
$phpThumb->config_cache_maxsize=10*1024*1024;
$phpThumb->config_cache_maxfiles=200;
$phpThumb->config_cache_force_passthru=false;
if(getParam("w")) {
	$phpThumb->w=intval(getParam("w"));
	if($phpThumb->w>2000) die();
}
if(getParam("h")) {
	$phpThumb->h=intval(getParam("h"));
	if($phpThumb->h>2000) die();
}
if(getParam("far")) $phpThumb->far=intval(getParam("far"));
if(getParam("bg")) $phpThumb->bg=getParam("bg");
$format=image_type_from_extension($phpThumb->src);
if(getParam("f")) $format=getParam("f");
$phpThumb->config_output_format=$format;
$phpThumb->q=100;
$phpThumb->config_allow_src_above_docroot=true;
$usm=defined("__PHPTHUMB_USM__")?__PHPTHUMB_USM__:1;
if($usm) $phpThumb->fltr[]="usm|80|0.5|3";
$phpThumb->SetCacheFilename();
$cache=$phpThumb->cache_filename;
$cache=pathinfo($cache,PATHINFO_DIRNAME)."/".md5(pathinfo($cache,PATHINFO_FILENAME)).".".pathinfo($cache,PATHINFO_EXTENSION);
if(!file_exists($cache)) {
	if(!$phpThumb->GenerateThumbnail()) die();
	if(!$phpThumb->RenderToFile($cache)) die();
	@chmod($cache,0666);
}
$hash=md5(file_get_contents($cache));
header_etag($hash);
ob_start_protected("ob_gzhandler");
header_powered();
header_expires($hash);
$type=my_mime_content_type($cache);
header("Content-Type: $type");
readfile($cache);
ob_end_flush();
die();
?>