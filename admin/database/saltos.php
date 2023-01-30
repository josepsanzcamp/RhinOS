<?php
/*
 ____        _ _    ___  ____
/ ___|  __ _| | |_ / _ \/ ___|
\___ \ / _` | | __| | | \___ \
 ___) | (_| | | |_| |_| |___) |
|____/ \__,_|_|\__|\___/|____/

SaltOS: Framework to develop Rich Internet Applications
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
function capture_next_error() {
	global $_ERROR_HANDLER;
	if(!isset($_ERROR_HANDLER["level"])) show_php_error(array("phperror"=>"error_handler without levels availables"));
	$_ERROR_HANDLER["level"]++;
	array_push($_ERROR_HANDLER["msg"],"");
}

function get_clear_error() {
	global $_ERROR_HANDLER;
	if($_ERROR_HANDLER["level"]<=0) show_php_error(array("phperror"=>"error_handler without levels availables"));
	$_ERROR_HANDLER["level"]--;
	return array_pop($_ERROR_HANDLER["msg"]);
}

function do_message_error($array,$format) {
	static $dict=array(
		"html"=>array(array("<h3>","</h3>"),array("<pre>","</pre>"),"<br/>"),
		"text"=>array(array("***** "," *****\n"),array("","\n"),"\n")
	);
	if(!isset($dict[$format])) die("Unknown format $format");
	$msg=array();
	if(isset($array["phperror"])) $msg[]=array("PHP Error",$array["phperror"]);
	if(isset($array["xmlerror"])) $msg[]=array("XML Error",$array["xmlerror"]);
	if(isset($array["dberror"])) $msg[]=array("DB Error",$array["dberror"]);
	if(isset($array["emailerror"])) $msg[]=array("EMAIL Error",$array["emailerror"]);
	if(isset($array["fileerror"])) $msg[]=array("FILE Error",$array["fileerror"]);
	if(isset($array["source"])) $msg[]=array("XML Source",$array["source"]);
	if(isset($array["exception"])) $msg[]=array("Exception",$array["exception"]);
	if(isset($array["details"])) $msg[]=array("Details",$array["details"]);
	if(isset($array["query"])) $msg[]=array("Query",$array["query"]);
	if(isset($array["backtrace"])) {
		$backtrace=$array["backtrace"];
		array_walk($backtrace,"__debug_backtrace_helper");
		$msg[]=array("Backtrace",implode($dict[$format][2],$backtrace));
	}
	array_walk($msg,"__do_message_error_helper",$dict[$format]);
	$msg=implode($msg);
	return $msg;
}

function __debug_backtrace_helper(&$item,$key) {
	$item="${key} => ".$item["function"].(isset($item["class"])?" (in class ".$item["class"].")":"").((isset($item["file"]) && isset($item["line"]))?" (in file ".$item["file"]." at line ".$item["line"].")":"");
}

function __do_message_error_helper(&$item,$key,$dict) {
	$item=$dict[0][0].$item[0].$dict[0][1].$dict[1][0].$item[1].$dict[1][1];
}

function show_php_error($array=null) {
	global $_ERROR_HANDLER;
	static $backup=null;
	if($array===null) $array=$backup;
	if($array===null) return;
	// REFUSE THE DEPRECATED WARNINGS
	if(isset($array["phperror"])) {
		$pos1=stripos($array["phperror"],"deprecated");
		$pos2=stripos($array["phperror"],"function");
		$pos3=stripos($array["phperror"],"method");
		if($pos1!==false && $pos2!==false) return;
		if($pos1!==false && $pos3!==false) return;
	}
	// ADD BACKTRACE IF NOT FOUND
	if(!isset($array["backtrace"])) $array["backtrace"]=debug_backtrace();
	// CREATE THE MESSAGE ERROR USING HTML ENTITIES AND PLAIN TEXT
	$msg_html=do_message_error($array,"html");
	$msg_text=do_message_error($array,"text");
	$msg=getServer("SHELL")?$msg_text:$msg_html;
	// CHECK IF CAPTURE ERROR WAS ACTIVE
	if($_ERROR_HANDLER["level"]>0) {
		$old=array_pop($_ERROR_HANDLER["msg"]);
		array_push($_ERROR_HANDLER["msg"],$old.$msg);
		$backup=$array;
		return;
	}
	// ADD THE MSG_ALT TO THE ERROR LOG FILE
	addlog($msg_text,getDefault("debug/errorfile","error.log"));
	// PREPARE THE FINAL REPORT (ONLY IN NOT SHELL MODE)
	if(checkDebug("DEBUG_ADMIN")) {
		$msg=pretty_html_error($msg);
		if(!headers_sent()) {
			header_powered();
			header_expires(false);
			header("Content-Type: text/html");
			// DUMP TO STDOUT
			while(ob_get_level()) ob_end_clean(); // TRICK TO CLEAR SCREEN
			echo $msg;
		} else {
			// DUMP TO STDOUT
			while(ob_get_level()) ob_end_clean(); // TRICK TO CLEAR SCREEN
			echo $msg;
		}
	}
	die();
}

function pretty_html_error($msg) {
	$html="<html>";
	$html.="<head>";
	$html.="<title>".get_name_version_revision()."</title>";
	$html.="<style>";
	$html.=".phperror { background:#eee; color:#000; padding:20px; font-family:Helvetica,Arial,sans-serif; }";
	$html.=".phperror div { width:80%; margin:0 auto; background:#fff; padding:20px 40px; border:1px solid #aaa; border-radius:5px; text-align:left; }";
	$favicon=getDefault("info/favicon","img/favicon.png");
	if(file_exists($favicon) && memory_get_free()>filesize($favicon)*4/3) $favicon="data:".saltos_content_type($favicon).";base64,".base64_encode(file_get_contents($favicon));
	$html.=".phperror h3 { background:url(${favicon}) top left no-repeat; padding-left: 48px; height:32px; font-size:24px; margin:0; }";
	$html.=".phperror pre { white-space:pre-wrap; font-size:10px; }";
	$html.=".phperror form { display:inline; float:right; }";
	$html.=".phperror a { color:#00c; }";
	$html.=".phperror form a { margin-left:12px; font-size:12px; }";
	$html.="</style>";
	$html.="</head>";
	$html.="<body class='phperror'>";
	$html.="<div>";
	$bug=base64_encode(serialize(array("app"=>get_name_version_revision(),"msg"=>$msg)));
	$html.=__pretty_html_error_helper("http://bugs.saltos.org",array("bug"=>$bug),LANG_LOADED()?LANG("notifybug"):"Notify bug");
	$html.=$msg;
	$html.="</div>";
	$html.="</div>";
	$html.="</body>";
	return $html;
}

function __pretty_html_error_helper($action,$hiddens,$submit) {
	$html="";
	$html.="<form action='${action}' method='post'>";
	foreach($hiddens as $key=>$val) $html.="<input type='hidden' name='${key}' value='${val}'/>";
	$html.="<input type='submit' value='${submit}'/>";
	$html.="</form>";
	return $html;
}

function __error_handler($type,$message,$file,$line) {
	$backtrace=debug_backtrace();
	array_shift($backtrace);
	show_php_error(array("phperror"=>"${message} (code ${type})","details"=>"Error on file '${file}' at line ${line}","backtrace"=>$backtrace));
}

function __exception_handler($e) {
	$backtrace=$e->getTrace();
	show_php_error(array("exception"=>$e->getMessage()." (code ".$e->getCode().")","details"=>"Error on file '".$e->getFile()."' at line ".$e->getLine(),"backtrace"=>$backtrace));
}

function __shutdown_handler() {
	semaphore_shutdown();
	$error=error_get_last();
	$types=array(E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR,E_USER_ERROR,E_RECOVERABLE_ERROR);
	if(is_array($error) && isset($error["type"]) && in_array($error["type"],$types)) {
		global $_ERROR_HANDLER;
		$_ERROR_HANDLER=array("level"=>0,"msg"=>array());
		$backtrace=debug_backtrace();
		show_php_error(array("phperror"=>"${error["message"]}","details"=>"Error on file '${error["file"]}' at line ${error["line"]}","backtrace"=>$backtrace));
	}
}

function program_error_handler() {
	global $_ERROR_HANDLER;
	$_ERROR_HANDLER=array("level"=>0,"msg"=>array());
	//~ error_reporting(0);
	set_error_handler("__error_handler");
	set_exception_handler("__exception_handler");
	register_shutdown_function("__shutdown_handler");
}

function parse_query($query,$type) {
	$pos=__parse_query_strpos($query,"/*");
	$len=strlen($type);
	while($pos!==false) {
		$pos2=__parse_query_strpos($query,"*/",$pos+2);
		if($pos2!==false) {
			$pos3=__parse_query_strpos($query,"/*",$pos+2);
			while($pos3!==false && $pos3<$pos2) {
				$pos=$pos3;
				$pos3=__parse_query_strpos($query,"/*",$pos+2);
			}
			if(substr($query,$pos+2,$len)==$type) {
				$query=substr($query,0,$pos).substr($query,$pos+2+$len,$pos2-$pos-2-$len).substr($query,$pos2+2);
			} else {
				$query=substr($query,0,$pos).substr($query,$pos2+2);
			}
			$pos=__parse_query_strpos($query,"/*",$pos);
		} else {
			$pos=__parse_query_strpos($query,"/*",$pos+2);
		}
	}
	return $query;
}

function __parse_query_strpos($haystack,$needle,$offset=0) {
	$len=strlen($needle);
	$pos=strpos($haystack,$needle,$offset);
	if($pos!==false) {
		$len2=$pos-$offset;
		if($len2>0) {
			$count1=substr_count($haystack,"'",$offset,$len2)-substr_count($haystack,"\\'",$offset,$len2);
			$count2=substr_count($haystack,'"',$offset,$len2)-substr_count($haystack,'\\"',$offset,$len2);
			while($pos!==false && ($count1%2!=0 || $count2%2!=0)) {
				$offset=$pos+$len;
				$pos=strpos($haystack,$needle,$offset);
				if($pos!==false) {
					$len2=$pos-$offset;
					if($len2>0) {
						$count1+=substr_count($haystack,"'",$offset,$len2)-substr_count($haystack,"\\'",$offset,$len2);
						$count2+=substr_count($haystack,'"',$offset,$len2)-substr_count($haystack,'\\"',$offset,$len2);
					}
				}
			}
		}
	}
	return $pos;
}

function eval_bool($arg) {
	static $bools=array(
		"1"=>1, // FOR 1 OR TRUE
		"0"=>0, // FOR 0
		""=>0, // FOR FALSE
		"true"=>1,
		"false"=>0,
		"on"=>1,
		"off"=>0,
		"yes"=>1,
		"no"=>0
	);
	$bool=strtolower($arg);
	if(isset($bools[$bool])) return $bools[$bool];
	xml_error("Unknown boolean value '$arg'");
}

function get_use_cache($query="") {
	static $max=0;
	static $maxs=array();
	$usecache=getDefault("db/usecache");
	if(!$query) return $usecache;
	if(!eval_bool($usecache)) return $usecache;
	$nocaches=getDefault("db/nocaches");
	if(is_array($nocaches)) {
		if(!$max) {
			foreach($nocaches as $key=>$nocache) $maxs[$key]=str_word_count($nocache);
			$max=max($maxs);
		}
		$words=array(strtoupper(strtok($query," ")));
		for($i=1;$i<$max;$i++) $words[]=strtoupper(strtok(" "));
		foreach($nocaches as $key=>$nocache) {
			$word=strtoupper(strtok($nocache," "));
			$pos=0;
			while($pos<$maxs[$key] && $word==$words[$pos]) {
				$word=strtoupper(strtok(" "));
				$pos++;
			}
			if($pos==$maxs[$key]) {
				$usecache="false";
				break;
			}
		}
	}
	return $usecache;
}

function set_use_cache($bool) {
	global $_CONFIG;
	$result=getDefault("db/usecache");
	$_CONFIG["db"]["usecache"]=$bool;
	return $result;
}

function semaphore_acquire($file,$timeout=100000) {
	return __semaphore_helper(__FUNCTION__,$file,$timeout);
}

function semaphore_release($file) {
	return __semaphore_helper(__FUNCTION__,$file,null);
}

function semaphore_shutdown() {
	return __semaphore_helper(__FUNCTION__,null,null);
}

function __semaphore_helper($fn,$file,$timeout) {
	static $stack=array();
	if(stripos($fn,"acquire")!==false) {
		$hash=md5($file);
		if(!isset($stack[$hash])) $stack[$hash]=null;
		if($stack[$hash]) return false;
		init_random();
		while($timeout>=0) {
			capture_next_error();
			$stack[$hash]=fopen($file,"a");
			get_clear_error();
			if($stack[$hash]) break;
			$timeout-=usleep_protected(rand(0,1000));
		}
		if($timeout<0) {
			return false;
		}
		chmod_protected($file,0666);
		touch_protected($file);
		while($timeout>=0) {
			capture_next_error();
			$result=flock($stack[$hash],LOCK_EX|LOCK_NB);
			get_clear_error();
			if($result) break;
			$timeout-=usleep_protected(rand(0,1000));
		}
		if($timeout<0) {
			if($stack[$hash]) {
				capture_next_error();
				fclose($stack[$hash]);
				get_clear_error();
				$stack[$hash]=null;
			}
			return false;
		}
		ftruncate($stack[$hash],0);
		fwrite($stack[$hash],getmypid());
		return true;
	} elseif(stripos($fn,"release")!==false) {
		$hash=md5($file);
		if(!isset($stack[$hash])) $stack[$hash]=null;
		if(!$stack[$hash]) return false;
		capture_next_error();
		flock($stack[$hash],LOCK_UN);
		get_clear_error();
		capture_next_error();
		fclose($stack[$hash]);
		get_clear_error();
		$stack[$hash]=null;
		return true;
	} elseif(stripos($fn,"shutdown")!==false) {
		foreach($stack as $hash=>$val) {
			if($stack[$hash]) {
				capture_next_error();
				flock($stack[$hash],LOCK_UN);
				get_clear_error();
				capture_next_error();
				fclose($stack[$hash]);
				get_clear_error();
				$stack[$hash]=null;
			}
		}
		return true;
	}
	return false;
}

function init_random() {
	static $init=false;
	if($init) return;
	srand(intval(microtime(true)*1000000));
	$init=true;
}

function __addlog_helper($a) {
	return current_datetime_decimals().": ".$a;
}

function addlog($msg,$file="") {
	if(!$file) $file=getDefault("debug/logfile","saltos.log");
	$dir=get_directory("dirs/filesdir",getcwd()."/files");
	$maxlines=intval(getDefault("debug/maxlines",1000));
	if($maxlines>0 && file_exists($dir.$file) && memory_get_free()>filesize($dir.$file)) {
		capture_next_error();
		$numlines=count(file($dir.$file));
		$error=get_clear_error();
		if(!$error && $numlines>$maxlines) {
			$next=1;
			while(file_exists($dir.$file.".".$next)) $next++;
			capture_next_error();
			rename($dir.$file,$dir.$file.".".$next);
			get_clear_error();
		}
	}
	$msg=trim($msg);
	$msg=explode("\n",$msg);
	if(count($msg)==0) $msg=array("");
	$msg=array_map("__addlog_helper",$msg);
	$msg=implode("\n",$msg)."\n";
	file_put_contents($dir.$file,$msg,FILE_APPEND);
	if(memory_get_free()>0) chmod_protected($dir.$file,0666);
}

function current_datetime($offset=0) {
	return current_date($offset)." ".current_time($offset);
}

function current_date($offset=0) {
	return date("Y-m-d",time()+$offset);
}

function current_time($offset=0) {
	return date("H:i:s",time()+$offset);
}

function current_decimals($offset=0) {
	$decimals=explode(".",microtime(true)+$offset);
	return substr((isset($decimals[1])?$decimals[1]:"")."0000",0,4);
}

function current_datetime_decimals($offset=0) {
	return current_datetime($offset).".".current_decimals($offset);
}

function svnversion($dir) {
	$rev=0;
	$dir=realpath($dir);
	for(;;) {
		// FOR STATIC SUBVERSION
		$file="$dir/svnversion";
		if(file_exists($file)) {
			$data=file_get_contents($file);
			$rev=intval($data);
			break;
		}
		// FOR SUBVERSION >= 12
		$file="$dir/.svn/wc.db";
		if(file_exists($file)) {
			$data=file_get_contents($file);
			$pos=strpos($data,"normalfile");
			if($pos!==false) $rev=ord($data[$pos-1])+ord($data[$pos-2])*256;
			break;
		}
		// FOR SUBVERSION <= 11
		$file="$dir/.svn/entries";
		if(file_exists($file)) {
			$data=file($file);
			if(isset($data[3])) $rev=intval($data[3]);
			break;
		}
		if($dir=="/") break;
		$dir=realpath($dir."/..");
	}
	return $rev;
}

function ob_start_protected($param="") {
	capture_next_error();
	if($param=="") ob_start();
	if($param!="") ob_start($param);
	$error=get_clear_error();
	if($error) ob_start();
}

function isphp54() {
	return version_compare(PHP_VERSION,"5.4","ge");
}

function get_name_version_revision($copyright=false) {
	$path=getcwd();
	if(file_exists("baseweb/code")) $path="baseweb/code"; // FOR RHINOS REQUESTS
	if(file_exists("../baseweb/admin")) $path="../baseweb/admin"; // FOR ADMIN REQUESTS
	return "RhinOS"." v"."3.5"." r".intval(svnversion($path)).($copyright?" "."© 2007-2016 by Josep Sanz Campderrós, http://www.saltos.org":"");
}

function header_powered() {
	header("X-Powered-By: ".get_name_version_revision());
}

function header_expires($cache=true) {
	if($cache) {
		header("Pragma: public");
		header("Cache-Control: max-age=604800, no-transform");
		$gmdate=gmdate("D, d M Y H:i:s",time()+604800);
		header("Expires: $gmdate GMT");
		if(is_string($cache)) header("ETag: $cache");
	} else {
		if(!ismsie()) header("Pragma: no-cache");
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, no-transform");
		header("Expires: -1");
	}
}

function ismsie() {
	if(isset($_SERVER["HTTP_USER_AGENT"])) {
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")!==false) {
			return true;
		}
	}
	return false;
}

function memory_get_free() {
	static $memory_limit=0;
	if(!$memory_limit) {
		$memory_limit=ini_get("memory_limit");
		if(strtoupper(substr($memory_limit,-1,1))=="K") $memory_limit=intval(substr($memory_limit,0,-1))*1024;
		if(strtoupper(substr($memory_limit,-1,1))=="M") $memory_limit=intval(substr($memory_limit,0,-1))*1024*1024;
	}
	$memory_usage=memory_get_usage(true);
	return $memory_limit-$memory_usage;
}

function usleep_protected($usec) {
	$socket=socket_create(AF_UNIX,SOCK_STREAM,0);
	$read=null;
	$write=null;
	$except=array($socket);
	capture_next_error();
	$time1=microtime(true);
	socket_select($read,$write,$except,intval($usec/1000000),intval($usec%1000000));
	$time2=microtime(true);
	get_clear_error();
	return ($time2-$time1)*1000000;
}

function chmod_protected($file,$mode) {
	capture_next_error();
	ob_start();
	chmod($file,$mode);
	$error1=ob_get_clean();
	$error2=get_clear_error();
	return $error1.$error2;
}

function touch_protected($file) {
	capture_next_error();
	ob_start();
	touch($file);
	$error1=ob_get_clean();
	$error2=get_clear_error();
	return $error1.$error2;
}

function getDefault($key,$default="") {
	global $_CONFIG;
	$key=explode("/",$key);
	$count=count($key);
	$config=$_CONFIG;
	if($count==1 && isset($config["default"][$key[0]])) {
		$config=$config["default"][$key[0]];
		$count=0;
	}
	while($count) {
		$key2=array_shift($key);
		if(!isset($config[$key2])) return $default;
		$config=$config[$key2];
		$count--;
	}
	if($config==="") return $default;
	return $config;
}

function getServer($index,$default="") {
	return isset($_SERVER[$index])?$_SERVER[$index]:$default;
}

function LANG_LOADED() {
	global $_LANG;
	return isset($_LANG);
}

function LANG($key,$arg="") {
	global $_LANG;
	if(!LANG_LOADED()) return "$key not load";
	if($arg) $arg="$arg,";
	$default=explode(",",$arg.$_LANG["default"]);
	foreach($default as $d) {
		if(isset($_LANG[$d][$key])) {
			return eval_bool(getDefault("debug/langdebug"))?"LANG(".$_LANG[$d][$key].")":$_LANG[$d][$key];
		}
	}
	return "$key (not found)";
}

function saltos_content_type($file) {
	static $mimes=array(
		"css"=>"text/css",
		"js"=>"text/javascript",
		"xml"=>"text/xml",
		"htm"=>"text/html",
		"png"=>"image/png",
		"bmp"=>"image/bmp"
	);
	$ext=strtolower(extension($file));
	if(isset($mimes[$ext])) return $mimes[$ext];
	if(function_exists("mime_content_type")) return mime_content_type($file);
	if(function_exists("finfo_file")) return finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file);
	return "application/octet-stream";
}

function extension($file) {
	return pathinfo($file,PATHINFO_EXTENSION);
}

function get_directory($key,$default="") {
	$default=$default?$default:getcwd()."/cache";
	$dir=getDefault($key,$default);
	$bar=(substr($dir,-1,1)!="/")?"/":"";
	return $dir.$bar;
}
?>