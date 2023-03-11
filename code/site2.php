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
define("__TAG1__","<!--");
define("__TAG2__","-->");
define("__TAG3__","INCLUDE");
define("__TAG4__","CONTENT");
define("__TAG5__","CACHE");

function mime_content_type_rhinos($file) {
	static $mimes=array();
	if(!count($mimes)) {
		$mimes["ez"]="application/andrew-inset";
		$mimes["hqx"]="application/mac-binhex40";
		$mimes["cpt"]="application/mac-compactpro";
		$mimes["doc"]="application/msword";
		$mimes["bin"]="application/octet-stream";
		$mimes["dms"]="application/octet-stream";
		$mimes["lha"]="application/octet-stream";
		$mimes["lzh"]="application/octet-stream";
		$mimes["exe"]="application/octet-stream";
		$mimes["class"]="application/octet-stream";
		$mimes["so"]="application/octet-stream";
		$mimes["dll"]="application/octet-stream";
		$mimes["img"]="application/octet-stream";
		$mimes["iso"]="application/octet-stream";
		$mimes["oda"]="application/oda";
		$mimes["ogg"]="application/ogg";
		$mimes["pdf"]="application/pdf";
		$mimes["ai"]="application/postscript";
		$mimes["eps"]="application/postscript";
		$mimes["ps"]="application/postscript";
		$mimes["rtf"]="application/rtf";
		$mimes["smi"]="application/smil";
		$mimes["smil"]="application/smil";
		$mimes["fm"]="application/vnd.framemaker";
		$mimes["mif"]="application/vnd.mif";
		$mimes["xls"]="application/vnd.ms-excel";
		$mimes["ppt"]="application/vnd.ms-powerpoint";
		$mimes["odc"]="application/vnd.oasis.opendocument.chart";
		$mimes["odb"]="application/vnd.oasis.opendocument.database";
		$mimes["odf"]="application/vnd.oasis.opendocument.formula";
		$mimes["odg"]="application/vnd.oasis.opendocument.graphics";
		$mimes["otg"]="application/vnd.oasis.opendocument.graphics-template";
		$mimes["odi"]="application/vnd.oasis.opendocument.image";
		$mimes["odp"]="application/vnd.oasis.opendocument.presentation";
		$mimes["otp"]="application/vnd.oasis.opendocument.presentation-template";
		$mimes["ods"]="application/vnd.oasis.opendocument.spreadsheet";
		$mimes["ots"]="application/vnd.oasis.opendocument.spreadsheet-template";
		$mimes["odt"]="application/vnd.oasis.opendocument.text";
		$mimes["odm"]="application/vnd.oasis.opendocument.text-master";
		$mimes["ott"]="application/vnd.oasis.opendocument.text-template";
		$mimes["oth"]="application/vnd.oasis.opendocument.text-web";
		$mimes["sxw"]="application/vnd.sun.xml.writer";
		$mimes["stw"]="application/vnd.sun.xml.writer.template";
		$mimes["sxc"]="application/vnd.sun.xml.calc";
		$mimes["stc"]="application/vnd.sun.xml.calc.template";
		$mimes["sxd"]="application/vnd.sun.xml.draw";
		$mimes["std"]="application/vnd.sun.xml.draw.template";
		$mimes["sxi"]="application/vnd.sun.xml.impress";
		$mimes["sti"]="application/vnd.sun.xml.impress.template";
		$mimes["sxg"]="application/vnd.sun.xml.writer.global";
		$mimes["sxm"]="application/vnd.sun.xml.math";
		$mimes["sis"]="application/vnd.symbian.install";
		$mimes["wbxml"]="application/vnd.wap.wbxml";
		$mimes["wmlc"]="application/vnd.wap.wmlc";
		$mimes["wmlsc"]="application/vnd.wap.wmlscriptc";
		$mimes["bcpio"]="application/x-bcpio";
		$mimes["torrent"]="application/x-bittorrent";
		$mimes["bz2"]="application/x-bzip2";
		$mimes["vcd"]="application/x-cdlink";
		$mimes["pgn"]="application/x-chess-pgn";
		$mimes["cpio"]="application/x-cpio";
		$mimes["csh"]="application/x-csh";
		$mimes["dcr"]="application/x-director";
		$mimes["dir"]="application/x-director";
		$mimes["dxr"]="application/x-director";
		$mimes["dvi"]="application/x-dvi";
		$mimes["spl"]="application/x-futuresplash";
		$mimes["gtar"]="application/x-gtar";
		$mimes["gz"]="application/x-gzip";
		$mimes["tgz"]="application/x-gzip";
		$mimes["hdf"]="application/x-hdf";
		$mimes["jar"]="application/x-java-archive";
		$mimes["jnlp"]="application/x-java-jnlp-file";
		$mimes["js"]="application/x-javascript";
		$mimes["kwd"]="application/x-kword";
		$mimes["kwt"]="application/x-kword";
		$mimes["ksp"]="application/x-kspread";
		$mimes["kpr"]="application/x-kpresenter";
		$mimes["kpt"]="application/x-kpresenter";
		$mimes["chrt"]="application/x-kchart";
		$mimes["kil"]="application/x-killustrator";
		$mimes["skp"]="application/x-koan";
		$mimes["skd"]="application/x-koan";
		$mimes["skt"]="application/x-koan";
		$mimes["skm"]="application/x-koan";
		$mimes["latex"]="application/x-latex";
		$mimes["nc"]="application/x-netcdf";
		$mimes["cdf"]="application/x-netcdf";
		$mimes["pl"]="application/x-perl";
		$mimes["rpm"]="application/x-rpm";
		$mimes["sh"]="application/x-sh";
		$mimes["shar"]="application/x-shar";
		$mimes["swf"]="application/x-shockwave-flash";
		$mimes["sit"]="application/x-stuffit";
		$mimes["sv4cpio"]="application/x-sv4cpio";
		$mimes["sv4crc"]="application/x-sv4crc";
		$mimes["tar"]="application/x-tar";
		$mimes["tcl"]="application/x-tcl";
		$mimes["tex"]="application/x-tex";
		$mimes["texinfo"]="application/x-texinfo";
		$mimes["texi"]="application/x-texinfo";
		$mimes["t"]="application/x-troff";
		$mimes["tr"]="application/x-troff";
		$mimes["roff"]="application/x-troff";
		$mimes["man"]="application/x-troff-man";
		$mimes["1"]="application/x-troff-man";
		$mimes["2"]="application/x-troff-man";
		$mimes["3"]="application/x-troff-man";
		$mimes["4"]="application/x-troff-man";
		$mimes["5"]="application/x-troff-man";
		$mimes["6"]="application/x-troff-man";
		$mimes["7"]="application/x-troff-man";
		$mimes["8"]="application/x-troff-man";
		$mimes["me"]="application/x-troff-me";
		$mimes["ms"]="application/x-troff-ms";
		$mimes["ustar"]="application/x-ustar";
		$mimes["src"]="application/x-wais-source";
		$mimes["xhtml"]="application/xhtml+xml";
		$mimes["xht"]="application/xhtml+xml";
		$mimes["zip"]="application/zip";
		$mimes["au"]="audio/basic";
		$mimes["snd"]="audio/basic";
		$mimes["mid"]="audio/midi";
		$mimes["midi"]="audio/midi";
		$mimes["kar"]="audio/midi";
		$mimes["mpga"]="audio/mpeg";
		$mimes["mp2"]="audio/mpeg";
		$mimes["mp3"]="audio/mpeg";
		$mimes["aif"]="audio/x-aiff";
		$mimes["aiff"]="audio/x-aiff";
		$mimes["aifc"]="audio/x-aiff";
		$mimes["m3u"]="audio/x-mpegurl";
		$mimes["ram"]="audio/x-pn-realaudio";
		$mimes["rm"]="audio/x-pn-realaudio";
		$mimes["ra"]="audio/x-realaudio";
		$mimes["wav"]="audio/x-wav";
		$mimes["wma"]="audio/x-ms-wma";
		$mimes["wax"]="audio/x-ms-wax";
		$mimes["pdb"]="chemical/x-pdb";
		$mimes["xyz"]="chemical/x-xyz";
		$mimes["bmp"]="image/bmp";
		$mimes["gif"]="image/gif";
		$mimes["ief"]="image/ief";
		$mimes["jpeg"]="image/jpeg";
		$mimes["jpg"]="image/jpeg";
		$mimes["jpe"]="image/jpeg";
		$mimes["jfif"]="image/jpeg";
		$mimes["png"]="image/png";
		$mimes["tiff"]="image/tiff";
		$mimes["tif"]="image/tiff";
		$mimes["djvu"]="image/vnd.djvu";
		$mimes["djv"]="image/vnd.djvu";
		$mimes["ico"]="image/vnd.microsoft.icon";
		$mimes["wbmp"]="image/vnd.wap.wbmp";
		$mimes["ras"]="image/x-cmu-raster";
		$mimes["fts"]="image/x-fits";
		$mimes["pnm"]="image/x-portable-anymap";
		$mimes["pbm"]="image/x-portable-bitmap";
		$mimes["pgm"]="image/x-portable-graymap";
		$mimes["ppm"]="image/x-portable-pixmap";
		$mimes["rgb"]="image/x-rgb";
		$mimes["tga"]="image/x-targa";
		$mimes["xbm"]="image/x-xbitmap";
		$mimes["xpm"]="image/x-xpixmap";
		$mimes["xwd"]="image/x-xwindowdump";
		$mimes["art"]="message/news";
		$mimes["eml"]="message/rfc822";
		$mimes["mail"]="message/rfc822";
		$mimes["igs"]="model/iges";
		$mimes["iges"]="model/iges";
		$mimes["msh"]="model/mesh";
		$mimes["mesh"]="model/mesh";
		$mimes["silo"]="model/mesh";
		$mimes["wrl"]="model/vrml";
		$mimes["vrml"]="model/vrml";
		$mimes["css"]="text/css";
		$mimes["html"]="text/html";
		$mimes["htm"]="text/html";
		$mimes["asc"]="text/plain";
		$mimes["txt"]="text/plain";
		$mimes["text"]="text/plain";
		$mimes["pm"]="text/plain";
		$mimes["el"]="text/plain";
		$mimes["c"]="text/plain";
		$mimes["h"]="text/plain";
		$mimes["cc"]="text/plain";
		$mimes["hh"]="text/plain";
		$mimes["cxx"]="text/plain";
		$mimes["hxx"]="text/plain";
		$mimes["f90"]="text/plain";
		$mimes["rtx"]="text/richtext";
		$mimes["rtf"]="text/rtf";
		$mimes["sgml"]="text/sgml";
		$mimes["sgm"]="text/sgml";
		$mimes["tsv"]="text/tab-separated-values";
		$mimes["jad"]="text/vnd.sun.j2me.app-descriptor";
		$mimes["wml"]="text/vnd.wap.wml";
		$mimes["wmls"]="text/vnd.wap.wmlscript";
		$mimes["pod"]="text/x-pod";
		$mimes["etx"]="text/x-setext";
		$mimes["vcf"]="text/x-vcard";
		$mimes["xml"]="text/xml";
		$mimes["xsl"]="text/xml";
		$mimes["ent"]="text/xml-external-parsed-entity";
		$mimes["mpeg"]="video/mpeg";
		$mimes["mpg"]="video/mpeg";
		$mimes["mpe"]="video/mpeg";
		$mimes["qt"]="video/quicktime";
		$mimes["mov"]="video/quicktime";
		$mimes["mxu"]="video/vnd.mpegurl";
		$mimes["flv"]="video/x-flv";
		$mimes["asf"]="video/x-ms-asf";
		$mimes["asx"]="video/x-ms-asf";
		$mimes["wm"]="video/x-ms-wm";
		$mimes["wmv"]="video/x-ms-wmv";
		$mimes["wmx"]="video/x-ms-wmx";
		$mimes["wvx"]="video/x-ms-wvx";
		$mimes["avi"]="video/x-msvideo";
		$mimes["movie"]="video/x-sgi-movie";
		$mimes["ice"]="x-conference/x-cooltalk";
		$mimes["json"]="application/json";
		$mimes["webp"]="image/webp";
	}
	$ext=pathinfo($file,PATHINFO_EXTENSION);
	$ext=strtolower($ext);
	if(isset($mimes[$ext])) return $mimes[$ext];
	return "application/octet-stream";
}

function image_type_from_extension($file) {
	static $mimes=array();
	if(!count($mimes)) {
		$mimes["bmp"]="bmp";
		$mimes["gif"]="gif";
		$mimes["jpeg"]="jpeg";
		$mimes["jpg"]="jpeg";
		$mimes["jpe"]="jpeg";
		$mimes["jfif"]="jpeg";
		$mimes["png"]="png";
		$mimes["tiff"]="tiff";
		$mimes["tif"]="tiff";
	}
	$ext=pathinfo($file,PATHINFO_EXTENSION);
	$ext=strtolower($ext);
	if(isset($mimes[$ext])) return $mimes[$ext];
	return "jpeg";
}

function not_found($dir="") {
	global $inicio;
	global $plantillas;
	global $error404;
	global $_SERVER;
	global $content;
	global $argv;
	static $iteration=0;

	if($dir) chdir($dir);
	if($iteration>0) show_php_error(array("phperror"=>"not_found() called more times"));
	$iteration++;
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	if(!isset($error404)) {
		$error404=$inicio;
		$glob=glob($plantillas."*404*");
		if(count($glob)>0) $error404=str_replace($plantillas,"/",$glob[0]);
	}
	$_SERVER["PATH_INFO_OLD"]=$_SERVER["PATH_INFO"];
	$_SERVER["PATH_INFO"]=$error404;
	include("site.php");
	die();
}

function encode($param) {
	static $orig=array(
		"á","à","ä","é","è","ë","í","ì","ï","ó","ò","ö","ú","ù","ü","ñ","ç",
		"Á","À","Ä","É","È","Ë","Í","Ì","Ï","Ó","Ò","Ö","Ú","Ù","Ü","Ñ","Ç");
	static $dest=array(
		"a","a","a","e","e","e","i","i","i","o","o","o","u","u","u","n","c",
		"a","a","a","e","e","e","i","i","i","o","o","o","u","u","u","n","c");
	$cad=mb_strtolower($param,"UTF-8");
	$cad=str_replace($orig,$dest,$cad);
	$new="";
	$len=strlen($cad);
	for($i=0;$i<$len;$i++) {
		$letter=substr($cad,$i,1);
		if($letter>="a" && $letter<="z") $new.=$letter;
		elseif($letter>="0" && $letter<="9") $new.=$letter;
		else $new.=" ";
	}
	$cad="";
	$new=trim($new);
	$new=str_replace(" ","-",$new);
	while($cad!=($new=str_replace("--","-",$new))) $cad=$new;
	return $new;
}

function get_base($param="") {
	global $pathbase;
	static $base=array();
	$param=strtoupper(trim($param));
	$ssl=$_SERVER["SERVER_PORT"]==443;
	// FOR FORWARDED SITES
	if(isset($_SERVER["HTTP_X_FORWARDED_PORT"])) $ssl=$_SERVER["HTTP_X_FORWARDED_PORT"]==443;
	// CONTINUE
	if($param=="SSL") $ssl=1;
	if($param=="NOSSL") $ssl=0;
	if($ssl) $index="https";
	else $index="http";
	if(isset($base[$index])) return $base[$index];
	$host=$_SERVER["SERVER_NAME"];
	// FOR FORWARDED SITES
	if(isset($_SERVER["HTTP_X_FORWARDED_HOST"])) {
		$host=$_SERVER["HTTP_X_FORWARDED_HOST"];
		// TRICK PROVIDED BY SANTI OLIVERAS AT IEEC
		$pos=strrpos($host,",");
		if($pos!==false) $host=substr($host,$pos+1);
	}
	// CONTINUE
	if(!in_array($_SERVER["SERVER_PORT"],array(80,443))) $host.=":".$_SERVER["SERVER_PORT"];
	if(isset($pathbase)) {
		$path="/".$pathbase;
	} else {
		$path=$_SERVER["SCRIPT_NAME"];
		$path=dirname($path)."/".basename($path,".php");
	}
	if(substr($path,0,2)=="//") $path=substr($path,1);
	$base[$index]=$index."://".$host.$path."/";
	return $base[$index];
}

function get_base_parent($param="") {
	return dirname(get_base($param))."/";
}

function get_base_ie9css($param="") {
	if(!ismsie9()) return "";
	return dirname(get_base($param))."/".basename($_SERVER["SCRIPT_NAME"])."/";
}

function get_lang($set="") {
	global $_LANG;
	static $lang="";
	if($set!="") $lang=$set;
	if($lang!="") return $lang;
	$path=$_SERVER["SCRIPT_NAME"];
	$lang=basename($path,".php");
	if(strlen($lang)!=2) $lang=isset($_LANG["lang"])?$_LANG["lang"]:"es";
	return $lang;
}

function set_lang($lang) {
	get_lang($lang);
}

function set_output($param,$extra="default") {
	global $output;
	$output[$extra]=$param;
}

function get_output($extra="") {
	global $output;
	$result=1;
	if($extra=="") {
		foreach($output as $o) $result=$result && $o;
	} else {
		if(!isset($output[$extra])) $output[$extra]=1;
		$result=$result && $output[$extra];
	}
	return $result;
}

function clear_buffer() {
	global $buffer;
	$buffer="";
}

function get_buffer() {
	global $buffer;
	return trim($buffer);
}

function get_argv() {
	global $argv;
	return $argv;
}

function echo_buffer($param) {
	global $buffer;
	if(get_output()) echo $param;
	else $buffer.=$param;
}

function xml2html ($xml, $xsl, $header=false) {
	if(class_exists("XsltProcessor")) {
		$doc = new DomDocument();
		$doc->loadXML($xml);
		$sheet = new DomDocument();
		$sheet->load($xsl);
		$proc = new XsltProcessor();
		$proc->importStylesheet($sheet);
		$html=$proc->transformToXML($doc);
	} else {
		srand(intval(microtime(true)*1000000));
		$input=get_temp_directory().md5(uniqid(rand(),true));
		file_put_contents($input,$xml);
		@chmod($input,0666);
		$output=get_temp_directory().md5(uniqid(rand(),true));
		exec("xsltproc -o $output $xsl $input");
		unlink($input);
		if(file_exists($output)) {
			$html=file_get_contents_cached($output);
			unlink($output);
		} else {
			$html="";
		}
	}
	if(!$header) {
		if(substr($html,0,5)=="<?xml") {
			$html=substr($html,strpos($html,">")+1);
			$html=trim($html);
		}
		if(substr($html,0,9)=="<!DOCTYPE") {
			$html=substr($html,strpos($html,">")+1);
			$html=trim($html);
		}
	}
	return $html;
}

function file_get_contents_cached($file) {
	static $stack=array();
	$cache=md5($file);
	if(!isset($stack[$cache])) {
		if(!file_exists($file)) show_php_error(array("phperror"=>"File not found: $file"));
		$stack[$cache]=file_get_contents($file);
	}
	return $stack[$cache];
}

function has_include_tag($page) {
	return has_parameter_tag($page,__TAG1__,__TAG3__,__TAG2__);
}

function has_cache_tag($page) {
	return has_parameter_tag($page,__TAG1__,__TAG3__." ".__TAG5__,__TAG2__);
}

function has_parameter_tag($page,$open,$tag,$close) {
	global $plantillas;
	static $stack=array();

	$cache=md5($page.$open.$tag.$close);
	if(isset($stack[$cache])) return $stack[$cache];
	if(is_valid_filename($page)) {
		$file=$plantillas.$page;
		if(is_file($file)) $page=file_get_contents_cached($file);
	}
	list($pos1,$param,$pos2)=content_strpos($page,$open,$tag,$close,0);
	$stack[$cache]=($pos1!==false);
	return $stack[$cache];
}

function is_valid_filename($file) {
	$invalids=array("<",">","!","|","&",";",":");
	foreach($invalids as $invalid) {
		if(strpos($file,$invalid)!==false) return 0;
	}
	return 1;
}

function content($file,$isfile=true) {
	global $plantillas;
	static $headers=0;

	if(!$headers && $isfile) {
		set_output(1);
		clear_buffer();
		header_powered();
		header_expires(false);
		$type=mime_content_type_rhinos($file);
		header("Content-Type: $type");
		$headers=1;
	}
	$url=get_base().implode("/",get_argv());
	$cache=get_cache_file($file.$isfile.$url);
	if(cache_exists($cache,array()) && $isfile && !checkDebug("DISABLE_CACHE")) {
		$array=unserialize(file_get_contents_cached($cache));
		$depend=$array["depend"];
		if(cache_exists($cache,$depend)) $page=$array["page"];
	}
	if(!isset($page)) {
		$depend=array();
		$page=content_resolve($file,$isfile,$depend);
		if(has_cache_tag($page)) $page=content_cache($page);
	}
	if(is_array($page)) {
		$write=0;
		foreach($page as $key=>$val) {
			if($val["cache"]) {
				$check=!data2_checktime($val["time"],$val["delay"]);
				if(!isset($val["data2"]) || ($val["delay"]>0 && $check)) {
					ob_start();
					content_include($val["data"]);
					$val["data2"]=ob_get_clean();
					$page[$key]["data2"]=$val["data2"];
					$page[$key]["time"]=time();
					$write=1;
				}
				echo_buffer($val["data2"]);
			} else {
				content_include($val["data"]);
			}
		}
		if((!cache_exists($cache,$depend) || $write) && $isfile) {
			$array=array("depend"=>$depend,"page"=>$page);
			file_put_contents($cache,serialize($array));
			@chmod($cache,0666);
		}
	} else {
		content_include($page);
	}
}

function content_strpos($page,$open,$tag,$close,$ini) {
	if($tag!="") {
		$pos=strpos($page,$tag,$ini);
	} else {
		$pos=strpos($page,$open,$ini);
		if($pos!==false) $pos+=strlen($open);
	}
	if($pos===false) return array(false,"",false);
	$pos1=strrpos($page,$open,-(strlen($page)-$pos));
	$len=strlen($tag);
	if($pos1===false) return content_strpos($page,$open,$tag,$close,$pos+$len);
	if($pos1<$ini) return content_strpos($page,$open,$tag,$close,$pos+$len);
	$len1=strlen($open);
	$void=trim(substr($page,$pos1+$len1,$pos-$pos1-$len1));
	if($void!="") return content_strpos($page,$open,$tag,$close,$pos+$len);
	$pos2=strpos($page,$close,$pos+$len);
	if($pos2===false) content_error("&hellip;".substr($page,$pos1,250)."&hellip;",$open." ".$tag,$close);
	$len2=strlen($close);
	$pos3=strpos($page,$open,$pos+$len);
	if($pos3!==false && $pos3<$pos2) content_error("&hellip;".substr($page,$pos1,250)."&hellip;",$open." ".$tag,$close);
	$param=substr($page,$pos+$len,$pos2-$pos-$len);
	return array($pos1,$param,$pos2+$len2);
}

function content_include($page) {
	if(!has_include_tag($page)) {
		echo_buffer($page);
		return;
	}
	$ini=0;
	list($pos,$tag,$pos2)=content_strpos($page,__TAG1__,__TAG3__,__TAG2__,$ini);
	while($pos!==false) {
		echo_buffer(content_line(substr($page,$ini,$pos-$ini)));
		$module=strtolower(strtok(trim($tag)," "));
		$params=strtok("");
		if(!function_exists($module)) {
			$file="code/{$module}.php";
			if(!file_exists($file)) show_php_error(array("phperror"=>"File not found: $file"));
			include_once($file);
		}
		if(!function_exists($module)) show_php_error(array("phperror"=>"Function not found: $module"));
		$module($params);
		$ini=$pos2;
		list($pos,$tag,$pos2)=content_strpos($page,__TAG1__,__TAG3__,__TAG2__,$ini);
	}
	echo_buffer(content_line(substr($page,$ini)));
}

function content_line($line) {
	if(substr($line,0,1)=="\n") $line=substr($line,1);
	if(substr($line,-1,1)=="\n") $line=substr($line,0,-1);
	return $line;
}

function content_error($source,$tag1,$tag2) {
	$source=htmlentities($source,ENT_COMPAT,"UTF-8");
	$tag1=htmlentities($tag1,ENT_COMPAT,"UTF-8");
	$tag2=htmlentities($tag2,ENT_COMPAT,"UTF-8");
	$xmlerror="Open tag '$tag1' without '$tag2'";
	show_php_error(array("source"=>$source,"xmlerror"=>$xmlerror));
}

function content_cache($page) {
	$ini=0;
	list($pos,$tag,$pos2)=content_strpos($page,__TAG1__,__TAG3__." ".__TAG5__,__TAG2__,$ini);
	$page2=array();
	$cache=0;
	$delay=0;
	$last_delay=0;
	$time=0;
	while($pos!==false) {
		$data=content_line(substr($page,$ini,$pos-$ini));
		if($data) $page2[]=array("cache"=>$cache,"delay"=>$delay,"time"=>$time,"data"=>$data);
		$param=strtok(trim($tag)," ");
		if($param=="BEGIN") $cache=1;
		elseif($param=="END") $cache=0;
		else content_error(substr($page,$pos,250)."&hellip;",__TAG1__." ".__TAG3__." ".__TAG5__,"BEGIN' or 'END");
		$delay=intval(strtok(""));
		if($delay) $last_delay=$delay;
		elseif($cache) $delay=$last_delay;
		$ini=$pos2;
		list($pos,$tag,$pos2)=content_strpos($page,__TAG1__,__TAG3__." ".__TAG5__,__TAG2__,$ini);
	}
	$data=content_line(substr($page,$ini));
	if($data) $page2[]=array("cache"=>$cache,"delay"=>$delay,"time"=>$time,"data"=>$data);
	return $page2;
}

function content_resolve($file,$isfile=true,&$depend=array()) {
	global $plantillas;
	global $content;

	if($isfile) {
		$file=trim($file);
		if(empty($file)) $file=$plantillas.$content;
		else $file=$plantillas.$file;
		$depend[]=$file;
		$page=file_get_contents_cached($file);
	} else {
		$page=$file;
	}
	if(!has_include_tag($page)) {
		return $page;
	}
	$ini=0;
	list($pos,$tag,$pos2)=content_strpos($page,__TAG1__,__TAG3__." ".__TAG4__,__TAG2__,$ini);
	$page2="";
	while($pos!==false) {
		$page2.=content_line(substr($page,$ini,$pos-$ini));
		$page2.=content_resolve($tag,true,$depend);
		$ini=$pos2;
		list($pos,$tag,$pos2)=content_strpos($page,__TAG1__,__TAG3__." ".__TAG4__,__TAG2__,$ini);
	}
	$page2.=content_line(substr($page,$ini));
	return $page2;
}

function cache_exists($cache,$file) {
	if(!file_exists($cache)) return 0;
	if(!is_array($file)) $file=array($file);
	foreach($file as $f) {
		if(!file_exists($f)) return 0;
		if(filemtime($f)>filemtime($cache)) return 0;
	}
	return 1;
}

function cache_checktime($cache,$offset) {
	if(!file_exists($cache)) return 0;
	if(time()-$offset>filemtime($cache)) return 0;
	return 1;
}

function data2_checktime($time,$offset) {
	if(time()-$offset>$time) return 0;
	return 1;
}

function get_cache_file($file,$ext="") {
	if(is_array($file)) $file=json_encode($file);
	if($ext=="") $ext=strtolower(extension($file));
	if($ext=="") $ext=".dat";
	if(substr($ext,0,1)!=".") $ext=".".$ext;
	$temp=get_temp_directory();
	$file=$temp.md5($file).$ext;
	return $file;
}

function base64_encode_url($data) {
	$expr1=array("/","+");
	$expr2=array("_","-");
	return str_replace($expr1,$expr2,base64_encode($data));
}

function base64_decode_url($data) {
	$expr1=array("/","+");
	$expr2=array("_","-");
	return base64_decode(str_replace($expr2,$expr1,$data));
}

function decode_and_encode_htmlentities($text) {
	$cad="";
	$new=$text;
	while($cad!=($new=html_entity_decode($new,ENT_COMPAT,"UTF-8"))) $cad=$new;
	$new=htmlentities($new,ENT_COMPAT,"UTF-8");
	return $new;
}

function htmlentities2unicodeentities ($input) {
	static $htmlEntities=array();
	static $entitiesDecoded=array();
	static $count=0;
	static $utf8Entities=array();

	if($count==0) {
		$htmlEntities=array_values(get_html_translation_table(HTML_ENTITIES,ENT_QUOTES));
		$entitiesDecoded=array_keys(get_html_translation_table(HTML_ENTITIES,ENT_QUOTES));
		$count=count($entitiesDecoded);
		for($i=0;$i<$count;$i++) $utf8Entities[$i]='&#'.ord($entitiesDecoded[$i]).';';
	}
	return str_replace($htmlEntities,$utf8Entities,$input);
}

function check_memory($file="") {
	static $memory_limit=0;
	static $size=0;
	if(!$memory_limit) {
		$memory_limit=ini_get("memory_limit");
		if(strtoupper(substr($memory_limit,-1,1))=="K") $memory_limit=intval(substr($memory_limit,0,-1))*1024;
		if(strtoupper(substr($memory_limit,-1,1))=="M") $memory_limit=intval(substr($memory_limit,0,-1))*1024*1024;
	}
	$memory_usage=memory_get_usage(true);
	if($file) $size=filesize($file);
	return ($memory_limit-$memory_usage)>($size*2) && $size<10*1024*1024;
}

function ismsie6() {
	if(isset($_SERVER["HTTP_USER_AGENT"])) {
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6")!==false) {
			return true;
		}
	}
	return false;
}

function ismsie9() {
	if(isset($_SERVER["HTTP_USER_AGENT"])) {
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 9")!==false) {
			return true;
		}
	}
	return false;
}

function pngfix($file="") {
	static $type="";
	if($file) $type=image_type_from_extension($file);
	if($type=="png") return ismsie6();
	return false;
}

function icofix($file="") {
	static $type="";
	if($file) $type=image_type_from_extension($file);
	if($type=="ico") return true;
	return false;
}

function debug($param) {
	static $debug=0;
	static $pre=0;
	static $die=0;
	static $buffer="";

	$temp=strtok($param," ");
	while($temp!="") {
		if($temp=="PRE") $pre=1;
		elseif($temp=="DIE") $die=1;
		$temp=strtok(" ");
	}
	if(!$debug) {
		ob_clean();
		ob_start();
		if(!defined("DEBUG")) define("DEBUG","DEBUG_ALL");
		$debug=1;
	} else {
		if($pre) {
			$output=ob_get_clean();
			ob_start();
			$buffer.="<pre>";
			$buffer.=htmlentities($output,ENT_COMPAT,"UTF-8");
			$buffer.="</pre>";
		} else {
			$output=ob_get_clean();
			ob_start();
			$buffer.=$output;
		}
		$debug=0;
		$pre=0;
	}
	if($die) {
		ob_clean();
		echo $buffer;
		die();
	}
}

function google_referer($buffer) {
	if(!isset($_SERVER["HTTP_REFERER"])) {
		$_SERVER["HTTP_REFERER"]="?".$_SERVER["QUERY_STRING"];
	}
	if(isset($_SERVER["HTTP_REFERER"])) {
		// BUSCAR PARAMETRO Q= DEL QUERY_STRING DEL REFERER
		$referer=$_SERVER["HTTP_REFERER"];
		$referer=rawurldecode($referer);
		$referer=explode("?",$referer,2);
		if(isset($referer[1])) {
			$keys_vars=explode("&",$referer[1]);
			foreach($keys_vars as $key_var) {
				$key_var=explode("=",$key_var,2);
				if(isset($key_var[1])) {
					if($key_var[0]=="q") $google=$key_var[1];
					if($key_var[0]=="p") $google=$key_var[1];
				}
			}
			// EXISTE PARAMETRO
			if(isset($google)) {
				// SEPARAR POR PALABRAS (MAXIMO TOTAL PALABRAS DE LEN>=3, LAS DEMAS SE BORRAN)
				$google=str_replace("+"," ",$google);
				$google=explode(" ",$google);
				$count=0;
				foreach($google as $key=>$val) {
					if(strlen($val)<3) {
						unset($google[$key]);
					} elseif($count>=6) {
						unset($google[$key]);
					} else {
						$count++;
					}
				}
				$buffer=google_highlight($buffer,$google);
			}
		}
	}
	return $buffer;
}

function google_replace($buffer,$g,$pre,$post,$ga,$gb) {
	$len3=strlen($pre.$post);
	$len1=strlen($g);
	$pos=stripos($buffer,$g);
	while($pos!==false) {
		$ok1=0;
		foreach($ga as $ga0) {
			$len0=strlen($ga0);
			if(substr($buffer,$pos-$len0,$len0)==$ga0) $ok1=1;
		}
		$ok2=0;
		foreach($gb as $gb0) {
			$len2=strlen($gb0);
			if(substr($buffer,$pos+$len1,$len2)==$gb0) $ok2=1;
		}
		$ok3=1;
		for($i=$pos-1;$i>0;$i--) {
			if($buffer[$i]==">") { $ok3=1; break; }
			if($buffer[$i]=="<") { $ok3=0; break; }
		}
		if($ok1 && $ok2 && $ok3) {
			$pre1=substr($buffer,0,$pos);
			$word=substr($buffer,$pos,$len1);
			$post1=substr($buffer,$pos+$len1);
			$buffer=$pre1.$pre.$word.$post.$post1;
			$pos=stripos($buffer,$g,$pos+$len1+$len3);
		} else {
			$pos=stripos($buffer,$g,$pos+$len1);
		}
	}
	return $buffer;
}

function get_body($buffer) {
	$pre="";
	$post="";
	$pos=stripos($buffer,"<body");
	if($pos!==false) {
		$pos=strpos($buffer,">",$pos+1);
		if($pos!==false) {
			$pre=substr($buffer,0,$pos);
			$buffer=substr($buffer,$pos);
		}
		$pos=stripos($buffer,"</body>");
		if($pos!==false) {
			$post=substr($buffer,$pos);
			$buffer=substr($buffer,0,$pos);
		}
	}
	return array($pre,$buffer,$post);
}

function google_highlight($buffer,$google) {
	// DEFINIR PALETA DE COLORES
	$paleta=array("ffff66","66ffff","ff66ff","ff9999","99ff99","9999ff");
	$total=count($paleta);
	$index=0;
	// QUEDARSE SOLO CON EL BODY
	list($pre0,$buffer,$post0)=get_body($buffer);
	// DEFINIR DICCIONARIOS PARA BUSQUEDAS
	$ga=array("\n","\r","\t"," ",">","(",";","¿");
	$gb=array("\n","\r","\t"," ",", ",". ",",<",".<","<",",\n",",\r",",\t",".\n",".\r",".\t",")",":","&","?");
	foreach($ga as $key=>$val) {
		if(strpos($buffer,$val)===false) unset($ga[$key]);
	}
	foreach($gb as $key=>$val) {
		if(strpos($buffer,$val)===false) unset($gb[$key]);
	}
	// DEFINIR DICCIONARIOS PARA ACENTOS
	$ca=array("á","é","í","ó","ú","à","è","ì","ò","ù","ä","ë","ï","ö","ü","ç","ñ");
	$sa=array("a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","c","n");
	// ITERAR PARA CADA PALABRA DEL ARRAY
	foreach($google as $g) {
		// PASAR A MINUSCULAS SIN ACENTOS
		$g=strtolower($g);
		$g=str_replace($ca,$sa,$g);
		// PREPARAR RESALTADO
		$color=$paleta[$index];
		$pre1="<b style=\"background:#{$color};color:black;font-weight:bold\">";
		$post1="</b>";
		// RESALTAR PALABRAS SIN ACENTOS
		$buffer=google_replace($buffer,$g,$pre1,$post1,$ga,$gb);
		// RESALTAR PALABRAS CON ACENTOS
		$len=strlen($g);
		for($i=0;$i<$len;$i++) {
			// SI ES VOCAL (DEFINIDA EN LA VARIABLE SAM)
			if(in_array($g[$i],$sa)) {
				// SUSTITUIR ACENTOS CERRADOS
				$gg=$g;
				$gg=substr($gg,0,$i).str_replace($sa,$ca,$gg[$i]).substr($gg,$i+1);
				$buffer=google_replace($buffer,$gg,$pre1,$post1,$ga,$gb);
				// IDEM CON ENTIDADES HTML
				$gg=htmlentities($gg,ENT_COMPAT,"UTF-8");
				$buffer=google_replace($buffer,$gg,$pre1,$post1,$ga,$gb);
			}
		}
		$index=($index+1)%$total;
	}
	$buffer=$pre0.$buffer.$post0;
	return $buffer;
}

function check_referer() {
	if(defined("__CHECK_REFERER__")) return __CHECK_REFERER__;
	global $argv;
	if(!check_devel()) {
		$checks=array("img","swf");
		if(in_array($argv[0],$checks)) {
			if(!isset($_SERVER["HTTP_REFERER"])) return 0;
			$referer=$_SERVER["HTTP_REFERER"];
			strtok($referer,"://");
			$referer=strtok("/");
			$base=get_base("AUTO");
			strtok($base,"://");
			$base=strtok("/");
			if($referer!=$base) return 0;
		}
	}
	return 1;
}

function check_devel() {
	if(defined("__CHECK_DEVEL__")) return __CHECK_DEVEL__;
	$host=$_SERVER["SERVER_NAME"];
	if(substr($host,0,3)=="10.") return 1;
	if(substr($host,0,8)=="192.168.") return 1;
	if($host=="127.0.0.1") return 1;
	if($host=="localhost") return 1;
	if($host=="localhost.localdomain") return 1;
	return 0;
}

function mincache($files) {
	global $plantillas;
	$files=explode(",",$files);
	foreach($files as $key=>$val) {
		$file=$plantillas.trim($val);
		$ext=strtolower(extension($file));
		if(in_array($ext,array("js","css")) && file_exists($file)) {
			$files[$key]=$file;
		} else {
			unset($files[$key]);
		}
	}
	if(!isset($ext)) die();
	$cache=get_cache_file(array("files",$plantillas,$files),$ext);
	if(!cache_exists($cache,$files)) {
		$buffer="";
		foreach($files as $file) {
			$ext=strtolower(extension($file));
			if($ext=="css") {
				$buffer.=file_get_contents($file);
			} elseif($ext=="js") {
				$buffer.=file_get_contents($file);
				if(substr(trim($buffer),-1,1)!=";") $buffer.=";";
			}
		}
		file_put_contents($cache,$buffer);
		@chmod($cache,0666);
	}
	return $cache;
}

function table_exists($table,$usecache=true) {
	return column_exists($table,"id",$usecache);
}

function column_exists($table,$column,$usecache=true) {
	static $exists=array();
	$hash=md5(serialize(array($table,$column)));
	if(!$usecache) unset($exists[$hash]);
	if(isset($exists[$hash])) return $exists[$hash];
	$query="SELECT `$column` FROM `$table` LIMIT 1";
	capture_next_error();
	dbQuery($query);
	$error=get_clear_error();
	$result=$error?0:1;
	if($usecache) $exists[$hash]=$result;
	return $result;
}

function set_db_cache($new) {
	global $_CONFIG;

	$old=$_CONFIG["db"]["usecache"];
	$_CONFIG["db"]["usecache"]=$new;
	return $old;
}

function send_request($url,$type="GET",$array=array(),$referer="",$cookie="") {
	// PREPARE DATA
	if(is_array($array)) {
		$data=array();
		foreach($array as $key=>$val) $data[]=$key."=".rawurlencode($val);
		$data=implode("&",$data);
	} else {
		$data=$array;
	}
	// PREPARE URL
	$url=parse_url($url);
	if($url["scheme"]=="http") $port=80;
	if($url["scheme"]=="https") $port=443;
	if(isset($url["port"])) $port=$url["port"];
	if(!isset($port)) die("Unknown port");
	$host1=$url["host"];
	if($url["scheme"]=="https") $host1="ssl://".$host1;
	$host2=$url["host"];
	if($port!=80 && $port!=443) $host2=$host2.":".$port;
	$path=$url["path"];
	$type=strtoupper($type);
	if(!in_array($type,array("GET","POST"))) die("Unknown type");
	if($type=="GET") $path.="?".$data;
	// OPEN THE SOCKET
	$fp=fsockopen($host1,$port);
	if(!$fp) die("Could not open the socket");
	// SEND REQUEST
	fputs($fp,"$type $path HTTP/1.1\r\n");
	fputs($fp,"Host: $host2\r\n");
	if($referer) fputs($fp,"Referer: $referer\r\n");
	if($type=="POST") fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
	if($type=="POST") fputs($fp,"Content-length: ".strlen($data)."\r\n");
	fputs($fp,"User-Agent: ".get_name_version_revision()."\r\n");
	if($cookie) fputs($fp,"Cookie: $cookie\r\n");
	fputs($fp,"Connection: close\r\n\r\n");
	fputs($fp,$data);
	// READ RESPONSE
	$result="";
	while(!feof($fp)) $result.=fgets($fp,128);
	// CLOSE SOCKET
	fclose($fp);
	// PREPARE RESPONSE
	$result=explode("\r\n\r\n",$result,2);
	$header=isset($result[0])?$result[0]:"";
	$header=explode("\r\n",$header);
	$content=isset($result[1])?$result[1]:"";
	// RETURN RESPONSE
	return array("header"=>$header,"content"=>$content);
}

function getdbtype() {
	global $dbtype;
	return strtoupper(str_replace("pdo_","",$dbtype));
}

function header_etag($hash) {
	static $key="HTTP_IF_NONE_MATCH";
	$etag=isset($_SERVER[$key])?$_SERVER[$key]:"";
	if($etag==$hash) {
		ob_start_protected("ob_gzhandler");
		header_powered();
		header_expires();
		header("HTTP/1.1 304 Not Modified");
		ob_end_flush();
		die();
	}
}

// PUBLIC COMMANDS
function print_today_google($param) {
	echo_buffer(date("Y-m-d",time()));
}

function get_title() {
	$argv=get_argv();
	$argc=count($argv);
	$orig=array(".htm",".xml","_","-");
	$dest=array("",""," "," ");
	$nada=array("nada","null","void","img","ico","css","swf","fla","rss","xml","htm","js","open","down","xsl","json","print","flv","content");
	$result=array();
	for($i=$argc-1;$i>=0;$i--) {
		$temp=str_replace($orig,$dest,$argv[$i]);
		if($temp=="") $temp=$_SERVER["SERVER_NAME"];
		$temp=strtolower($temp);
		if(!is_numeric($temp) && !in_array($temp,$nada)) {
			$result[]=ucwords($temp);
		}
	}
	return implode(" - ",$result);
}

function print_title($param) {
	echo_buffer(get_title());
}

function get_pathinfo() {
	return substr(isset($_SERVER["PATH_INFO_OLD"])?$_SERVER["PATH_INFO_OLD"]:$_SERVER["PATH_INFO"],1);
}

function print_pathinfo($param) {
	echo_buffer(get_pathinfo());
}

function print_tag_base($param) {
	$base=get_base("AUTO");
	echo_buffer("<base href='$base' />");
}

function print_tag_rss_firefox($param) {
	$base=get_base();
	$rss=strtok($param," ");
	$title=trim(strtok(""));
	echo_buffer("<link rel='alternate' type='application/rss+xml' href='$base$rss' title='$title' />");
}

function print_tag_favicon($param) {
	$base=get_base("AUTO");
	$img=trim($param);
	echo_buffer("<link rel='shortcut icon' href='$base$img' />");
}

function print_base($param) {
	echo_buffer(get_base($param));
}

function print_base_parent($param) {
	echo_buffer(get_base_parent($param));
}

function print_base_ie9css($param) {
	echo_buffer(get_base_ie9css($param));
}

function print_lang($param) {
	echo_buffer(get_lang());
}

function print_timestamp ($param) {
	echo_buffer(time());
}

function print_argv_param($param) {
	global $argv;
	$index=strtok($param, " ");
	if(isset($argv[$index])) echo_buffer($argv[$index]);
}

function print_random($param) {
	srand(intval(microtime(true)*1000000));
	echo_buffer(md5(uniqid(rand(),true)));
}

function set_global($param) {
	$key=strtok($param," ");
	$val=trim(strtok(""));
	$temp=preg_split("/[\[\]]/",$key);
	$count=count($temp);
	if($count==1) {
		global $$key;
		$$key=$val;
	} elseif($count==3) {
		$key=$temp[0];
		$vars=array("GET"=>"_GET","POST"=>"_POST","SESSION"=>"_SESSION");
		if(isset($vars[$key])) $key=$vars[$key];
		global $$key;
		$temp2=$$key;
		$temp2[$temp[1]]=$val;
		$$key=$temp2;
	}
}

function parser($param) {
	global $argv;
	eval(trim($param));
}

function ifoutput($param) {
	if(get_output()) content(__TAG1__." ".__TAG3__." $param ".__TAG2__,false);
}

function is_disabled_function($fn="") {
	static $disableds_string=null;
	static $disableds_array=array();
	if($disableds_string===null) {
		$disableds_string=ini_get("disable_functions").",".ini_get("suhosin.executor.func.blacklist");
		$disableds_array=$disableds_string?explode(",",$disableds_string):array();
		foreach($disableds_array as $key=>$val) {
			$val=strtolower(trim($val));
			if($val=="") unset($disableds_array[$key]);
			if($val!="") $disableds_array[$key]=$val;
		}
	}
	return in_array($fn,$disableds_array);
}
