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
include_once("site2.php");
error_reporting(0);
ini_set("session.bug_compat_42","Off");
if(!isset($timezone)) $timezone="Europe/Madrid";
ini_set("date.timezone",$timezone);
if(checkDebug("DEBUG_SITE")) {
	if(!isset($_SERVER["PATH_INFO"])) $_SERVER["PATH_INFO"]="/";
	addlog("PATH_INFO=".$_SERVER["PATH_INFO"]);
	if(!isset($_SERVER["HTTP_REFERER"])) $_SERVER["HTTP_REFERER"]="/";
	addlog("HTTP_REFERER=".$_SERVER["HTTP_REFERER"]);
	error_reporting(defined("E_DEPRECATED")?(E_ALL & ~E_DEPRECATED):(E_ALL));
}
$argv=array();
// FOR CONVERT REWRITE ENGINE TO MULTIVIEWS
if(!isset($_SERVER["PATH_INFO"])) {
	$temp=explode("/",$_SERVER["REQUEST_URI"]);
	$temp2=explode("/",$_SERVER["SCRIPT_NAME"]);
	while(array_pop($temp2)) array_shift($temp);
	$temp=implode("/",$temp);
	$pos=strpos($temp,"?");
	if($pos!==false) $temp=substr($temp,0,$pos);
	$_SERVER["PATH_INFO"]=$temp;
	unset($_SERVER["REDIRECT_URL"]);
}
// FOR MULTIVIEWS
if(isset($_SERVER["PATH_INFO"])) {
	$path=explode("/",$_SERVER["PATH_INFO"]);
	$count=count($path);
	for($i=1;$i<$count;$i++) $argv[]=$path[$i];
} else {
	$base=get_base("AUTO");
	header("Location: $base");
	die();
}
if(!isset($argv[0])) $argv[0]="";
if(!isset($plantillas)) $plantillas="templates";
if(substr($plantillas,-1,1)!="/") $plantillas.="/";
if(!isset($inicio)) $inicio="inicio.htm";
if(!isset($secundaria)) $secundaria="secundaria.htm";
if(!isset($carga)) $carga=$secundaria;
if(!check_referer()) not_found();
if($argv[0]=="cache") {
	$files=isset($_SERVER["QUERY_STRING"])?$_SERVER["QUERY_STRING"]:"";
	$cache=mincache($files);
	if($cache) {
		$hash=md5(file_get_contents($cache));
		header_etag($hash);
	} else {
		$hash=true;
	}
	if(!ismsie6()) ob_start_protected("ob_gzhandler");
	header_powered();
	header_expires($hash);
	if($cache) {
		$type=mime_content_type_rhinos($cache);
		header("Content-type: $type");
		readfile($cache);
	}
	if(!ismsie6()) ob_end_flush();
	disconnect();
	die();
}
$direct=array("img","ico","css","swf","rss","xml","htm","js","open","down","xsl","json","print","flv","files","fonts");
if(in_array($argv[0],$direct)) {
	if(!isset($argv[1])) not_found();
	if(empty($argv[1])) not_found();
	$carga=$argv[0]."/".$argv[1];
	if(!file_exists($plantillas.$carga)) $carga=$argv[0]."/".str_replace("|","/",$argv[1]);
	if(!file_exists($plantillas.$carga)) $carga=$argv[0]."/".$argv[1].".htm";
	if(!file_exists($plantillas.$carga)) $carga=$argv[0]."/".$argv[1].".xml";
	if(!file_exists($plantillas.$carga)) $carga=$argv[1];
	if(!file_exists($plantillas.$carga)) $carga=str_replace("|","/",$argv[1]);
	if(!file_exists($plantillas.$carga)) $carga=$argv[1].".htm";
	if(!file_exists($plantillas.$carga)) $carga=$argv[1].".xml";
	if(!file_exists($plantillas.$carga)) $carga="../admin/files/".$argv[1];
	if(!file_exists($plantillas.$carga)) $carga="../admin/files/".str_replace("|","/",$argv[1]);
	if(!file_exists($plantillas.$carga)) not_found();
	if(in_array($argv[0],array("img","ico")) && isset($argv[2])) {
		$tmpdir=get_temp_directory();
		if(!isset($_SERVER["HTTP_REFERER"])) $_SERVER["HTTP_REFERER"]="";
		$_SERVER["PHP_SELF"]=dirname($_SERVER["SCRIPT_NAME"])."admin/lib/phpthumb/phpThumb.php";
		require_once("admin/lib/phpthumb/phpthumb.class.php");
		$phpThumb = new phpThumb();
		$phpThumb->src=realpath(getcwd()."/".$plantillas.$carga);
		$phpThumb->config_temp_directory=$tmpdir;
		$phpThumb->config_cache_directory=$tmpdir;
		$phpThumb->config_cache_maxage=86400*30;
		$phpThumb->config_cache_maxsize=10*1024*1024;
		$phpThumb->config_cache_maxfiles=200;
		$phpThumb->config_cache_force_passthru=false;
		if($argv[2]!="original") {
			$size=explode("x",$argv[2]);
			if(count($size)!=2) not_found();
			$size[0]=intval($size[0]);
			$size[1]=intval($size[1]);
			if($size[0]<=0 || $size[1]<=0) not_found();
			if($size[0]>2000 || $size[1]>2000) not_found();
			$phpThumb->w=$size[0];
			$phpThumb->h=$size[1];
		}
		$phpThumb->q=100;
		$argc=count($argv);
		$phpThumb->config_output_format=image_type_from_extension($argv[$argc-1]);
		$phpThumb->config_allow_src_above_docroot=true;
		$usm=defined("__PHPTHUMB_USM__")?__PHPTHUMB_USM__:1;
		if($usm) $phpThumb->fltr[]="usm|80|0.5|3";
		for($i=3;$i<$argc-1;$i++) {
			if($argv[$i]=="ric") {
				$i++;
				if(!isset($argv[$i])) not_found();
				$radio=intval($argv[$i]);
				if($radio<=0) not_found();
				if(isset($size[0])) if($radio>$size[0]) not_found();
				if(isset($size[1])) if($radio>$size[1]) not_found();
				$phpThumb->fltr[]="ric|$radio|$radio";
			} elseif($argv[$i]=="over") {
				$i++;
				if(!isset($argv[$i])) not_found();
				$over=$argv[$i];
				$over2=realpath(getcwd()."/".$plantillas."img/".$over);
				if(file_exists($over2)) $over=$over2;
				if(!isset($margin)) $margin=10;
				$phpThumb->fltr[]="over|$over|1|$margin|100";
			} elseif($argv[$i]=="far") {
				$phpThumb->far=1;
			} elseif($argv[$i]=="iar") {
				$phpThumb->iar=1;
			} elseif($argv[$i]=="zc") {
				$phpThumb->zc=1;
			} elseif(in_array($argv[$i],array("backgroundcolor","background","bgcolor","bg"))) {
				$i++;
				if(!isset($argv[$i])) not_found();
				$color=$argv[$i];
				$phpThumb->bg=$color;
			} elseif(in_array($argv[$i],array("foregroundcolor","foreground","fgcolor","fg"))) {
				$i++;
				if(!isset($argv[$i])) not_found();
				$fg=$argv[$i];
			} elseif(in_array($argv[$i],array("font","fn"))) {
				$i++;
				if(!isset($argv[$i])) not_found();
				$font=$argv[$i].".ttf";
				$font2=realpath(getcwd()."/".$plantillas."fonts/".$font);
				if(file_exists($font2)) $font=$font2;
			} elseif(in_array($argv[$i],array("size","sz"))) {
				$i++;
				if(!isset($argv[$i])) not_found();
				$size2=floatval($argv[$i]);
			} elseif($argv[$i]=="align") {
				$i++;
				if(!isset($argv[$i])) not_found();
				$align=$argv[$i];
			} elseif($argv[$i]=="opacity") {
				$i++;
				if(!isset($argv[$i])) not_found();
				$opacity=floatval($argv[$i]);
			} elseif($argv[$i]=="margin") {
				$i++;
				if(!isset($argv[$i])) not_found();
				$margin=floatval($argv[$i]);
			} elseif(in_array($argv[$i],array("rotate","rot"))) {
				$i++;
				if(!isset($argv[$i])) not_found();
				$rot=floatval($argv[$i]);
			} elseif(in_array($argv[$i],array("text","txt","label","caption"))) {
				$i++;
				if(!isset($argv[$i])) not_found();
				$text=base64_decode_url($argv[$i]);
				$text=decode_and_encode_htmlentities($text);
				$text=htmlentities2unicodeentities($text);
			} elseif($argv[$i]=="gray") {
				$phpThumb->fltr[]="gray";
			} elseif($argv[$i]=="sep") {
				$phpThumb->fltr[]="sep";
			} elseif($argv[$i]=="clr") {
				$i++;
				if(!isset($argv[$i])) not_found();
				$color=$argv[$i];
				$phpThumb->fltr[]="clr|50|$color";
			} else {
				not_found();
			}
		}
		if(isset($text)) {
			if(!isset($size2)) $size2="12";
			if(!isset($align)) $align="C";
			if(!isset($fg)) $fg="000000";
			if(!isset($font)) $font="DejaVuSans.ttf";
			if(!isset($rot)) $rot="0";
			if(!isset($opacity)) $opacity="100";
			if(!isset($margin)) $margin="0";
			$phpThumb->fltr[]="wmt|$text|$size2|$align|$fg|$font|$opacity|$margin|$rot|0|0|";
		}
		$phpThumb->SetCacheFilename();
		$cache=$phpThumb->cache_filename;
		$cache=pathinfo($cache,PATHINFO_DIRNAME)."/".md5(pathinfo($cache,PATHINFO_FILENAME)).".".pathinfo($cache,PATHINFO_EXTENSION);
		//if(file_exists($cache)) unlink($cache); // FOR TEST PURPOSES
		if(isset($over) && cache_exists($cache,$over)) unlink($cache);
		if(isset($font) && cache_exists($cache,$font)) unlink($cache);
		// TRICK FOR GIF
		if(!cache_exists($cache,$phpThumb->src) && pathinfo($phpThumb->src,PATHINFO_EXTENSION)=="gif" && pathinfo($argv[$argc-1],PATHINFO_EXTENSION)=="gif" && !is_disabled_function("passthru")) {
			if(!$phpThumb->h || !$phpThumb->w) {
				copy($phpThumb->src,$cache);
			} elseif($phpThumb->far) {
				$im=imagecreatefromgif($phpThumb->src);
				$w=imagesx($im);
				$h=imagesy($im);
				imagedestroy($im);
				$sw=$w/$phpThumb->w;
				$sh=$h/$phpThumb->h;
				if($sw<$sh) {
					$resize=$phpThumb->w."x".$phpThumb->h;
					$x=(($w/$sh)-$phpThumb->w)/2;
					$y=0;
					$extent=$phpThumb->w."x".$phpThumb->h."+".round($x)."+".round($y);
					$extent=str_replace("+-","-",$extent);
				} else {
					$resize=$phpThumb->w."x".$phpThumb->h;
					$x=0;
					$y=(($h/$sw)-$phpThumb->h)/2;
					$extent=$phpThumb->w."x".$phpThumb->h."+".round($x)."+".round($y);
					$extent=str_replace("+-","-",$extent);
				}
				$background="ffffff";
				if($phpThumb->bg!="") $background=$phpThumb->bg;
				capture_next_error();
				passthru("convert ".$phpThumb->src." -resize $resize -background '#$background' -extent $extent ".$cache);
				get_clear_error();
			} elseif($phpThumb->iar) {
				$im=imagecreatefromgif($phpThumb->src);
				$w=imagesx($im);
				$h=imagesy($im);
				imagedestroy($im);
				$density=$w."x".$h;
				$resample=$phpThumb->w."x".$phpThumb->h;
				capture_next_error();
				passthru("convert ".$phpThumb->src." -density $density -resample $resample ".$cache);
				get_clear_error();
			} elseif($phpThumb->zc) {
				$im=imagecreatefromgif($phpThumb->src);
				$w=imagesx($im);
				$h=imagesy($im);
				imagedestroy($im);
				$sw=$w/$phpThumb->w;
				$sh=$h/$phpThumb->h;
				if($sw<$sh) {
					$resize=$phpThumb->w."x".round($phpThumb->w*$h/$w);
					$x=0;
					$y=(($h/$sw)-$phpThumb->h)/2;
					$crop=$phpThumb->w."x".$phpThumb->h."+".round($x)."+".round($y);
				} else {
					$resize=round($phpThumb->h*$w/$h)."x".$phpThumb->h;
					$x=(($w/$sh)-$phpThumb->w)/2;
					$y=0;
					$crop=$phpThumb->w."x".$phpThumb->h."+".round($x)."+".round($y);
				}
				capture_next_error();
				passthru("convert ".$phpThumb->src." -resize $resize -crop $crop +repage ".$cache);
				get_clear_error();
			} else {
				$resize=$phpThumb->w."x".$phpThumb->h;
				capture_next_error();
				passthru("convert ".$phpThumb->src." -resize $resize ".$cache);
				get_clear_error();
			}
			if(file_exists($cache)) @chmod($cache,0666);
		}
		// NORMAL CODE
		if(!cache_exists($cache,$phpThumb->src)) {
			capture_next_error();
			if(!$phpThumb->GenerateThumbnail()) not_found();
			get_clear_error();
			if(!$phpThumb->RenderToFile($cache)) not_found();
			@chmod($cache,0666);
		}
		// TRICK FOR WEBM
		if(pathinfo($argv[$argc-1],PATHINFO_EXTENSION)=="webp" && !is_disabled_function("passthru")) {
			$cache2=pathinfo($cache,PATHINFO_DIRNAME)."/".pathinfo($cache,PATHINFO_FILENAME).".webp";
			if(!cache_exists($cache2,$cache)) {
				capture_next_error();
				passthru("cwebp -quiet ".$cache." -o ".$cache2);
				get_clear_error();
				if(file_exists($cache2)) @chmod($cache2,0666);
			}
			if(file_exists($cache2)) $cache=$cache2;
		}
		// CONTINUE
		$hash=md5(file_get_contents($cache));
		header_etag($hash);
		if(check_memory($cache) && !pngfix($cache) && !icofix($cache)) ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires($hash);
		$type=mime_content_type_rhinos($cache);
		header("Content-Type: $type");
		readfile($cache);
		if(check_memory() && !pngfix() && !icofix()) ob_end_flush();
		disconnect();
		die();
	} elseif(in_array($argv[0],array("img","ico"))) {
		$hash=md5(file_get_contents($plantillas.$carga));
		header_etag($hash);
		if(check_memory($plantillas.$carga) && !pngfix($plantillas.$carga) && !icofix($plantillas.$carga)) ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires($hash);
		$type=mime_content_type_rhinos($plantillas.$carga);
		header("Content-Type: $type");
		readfile($plantillas.$carga);
		if(check_memory() && !pngfix() && !icofix()) ob_end_flush();
		disconnect();
		die();
	} elseif(in_array($argv[0],array("swf","flv"))) {
		$hash=md5(file_get_contents($plantillas.$carga));
		header_etag($hash);
		if(check_memory($plantillas.$carga)) ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires($hash);
		$type=mime_content_type_rhinos($plantillas.$carga);
		header("Content-Type: $type");
		readfile($plantillas.$carga);
		if(check_memory()) ob_end_flush();
		disconnect();
		die();
	} elseif(in_array($argv[0],array("rss","xml","xsl"))) {
		if(has_include_tag($carga)) initsession();
		ob_start_protected("ob_gzhandler");
		content($carga);
		ob_end_flush();
		if(has_include_tag($carga)) closesession();
		disconnect();
		die();
	} elseif($argv[0]=="htm") {
		if(substr($carga,0,strlen($argv[1]))==$argv[1]) {
			$argc=count($argv);
			for($i=1;$i<$argc;$i++) $argv[$i-1]=$argv[$i];
			unset($argv[$argc-1]);
		}
		if(has_include_tag($carga)) initsession();
		ob_start_protected("ob_gzhandler");
		content($carga);
		ob_end_flush();
		if(has_include_tag($carga)) closesession();
		disconnect();
		die();
	} elseif($argv[0]=="print") {
		if(substr($carga,0,strlen($argv[1]))==$argv[1]) {
			$argv0=$argv[0];
			$argc=count($argv);
			for($i=1;$i<$argc;$i++) $argv[$i-1]=$argv[$i];
			unset($argv[$argc-1]);
			if(file_exists($plantillas.$argv0."/".$secundaria)) {
				$content=$carga;
				$carga=$argv0."/".$secundaria;
			}
		}
		if(has_include_tag($carga)) initsession();
		ob_start_protected("ob_gzhandler");
		content($carga);
		ob_end_flush();
		if(has_include_tag($carga)) closesession();
		disconnect();
		die();
	} elseif(in_array($argv[0],array("open","down"))) {
		if(check_memory($plantillas.$carga) && !ismsie()) ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires(false);
		$type=mime_content_type_rhinos($plantillas.$carga);
		if($argv[0]=="open") {
			header("Content-Type: $type");
			$disposition="inline";
			if(ismsie()) $disposition="attachment";
			header("Content-Disposition: $disposition; filename=\"$argv[2]\"");
		}
		if($argv[0]=="down") {
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"$argv[2]\"");
		}
		header("Content-Length: ".filesize($plantillas.$carga));
		header("Content-Transfer-Encoding: binary");
		readfile($plantillas.$carga);
		if(check_memory() && !ismsie()) ob_end_flush();
		disconnect();
		die();
	} elseif(in_array($argv[0],array("js","css","json"))) {
		if(has_include_tag($carga)) initsession();
		if(!ismsie6()) ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires(false);
		content($carga);
		if(!ismsie6()) ob_end_flush();
		if(has_include_tag($carga)) closesession();
		disconnect();
		die();
	} elseif(in_array($argv[0],array("files","fonts"))) {
		$carga=implode("/",$argv);
		if(strpos($carga,"..")!==false) not_found();
		if(!file_exists($plantillas.$carga)) not_found();
		$hash=md5(file_get_contents($plantillas.$carga));
		header_etag($hash);
		if(!ismsie6()) ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires($hash);
		$type=mime_content_type_rhinos($plantillas.$carga);
		header("Content-Type: $type");
		readfile($plantillas.$carga);
		if(!ismsie6()) ob_end_flush();
		disconnect();
		die();
	} else {
		not_found();
	}
}
cache_gc();
if(empty($argv[0])) $argv[0]=$inicio;
$content=$argv[0];
if(!file_exists($plantillas.$content)) $content=$argv[0].".htm";
if(!file_exists($plantillas.$content)) $content=$argv[0].".xml";
if(!file_exists($plantillas.$content)) not_found();
$secundaria2=pathinfo($secundaria,PATHINFO_FILENAME);
if(in_array($content,array($secundaria,$secundaria2))) not_found();
if(has_include_tag($carga)) initsession();
ob_start();
content($carga);
$buffer=ob_get_clean();
$buffer=google_referer($buffer);
ob_start_protected("ob_gzhandler");
echo $buffer;
ob_end_flush();
if(has_include_tag($carga)) closesession();
disconnect();
?>