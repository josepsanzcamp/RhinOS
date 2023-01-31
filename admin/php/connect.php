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
function check_user() {
	$user=isset($_SESSION["user"])?$_SESSION["user"]:"";
	$pass=isset($_SESSION["pass"])?$_SESSION["pass"]:"";
	$query="SELECT * FROM db_users WHERE login='$user' AND password='$pass'";
	$result=dbQuery($query);
	if(dbNumRows($result)==1) {
		$row=dbFetchRow($result);
		dbFree($result);
		if($user==$row["login"]) return true;
		return false;
	}
	return false;
}

function getUID() {
	$user=isset($_SESSION["user"])?$_SESSION["user"]:"";
	$pass=isset($_SESSION["pass"])?$_SESSION["pass"]:"";
	$id=-1;
	$query="SELECT * FROM db_users WHERE login='$user' AND password='$pass'";
	$result=dbQuery($query);
	if(dbNumRows($result)==1) {
		$row=dbFetchRow($result);
		dbFree($result);
		if($user==$row["login"]) $id=$row["id"];
	}
	return $id;
}

function check_demo($type="") {
	if($type=="admin") return defined("DEMO")?DEMO:0;
	if($type=="user") return check_demo("admin") && ($_SESSION["user"]=="demo");
	die(_LANG("connect_checkdemo_unknown_type").$type);
}

function connect () {
	dbConnect();
}

function disconnect() {
	dbDisconnect();
}

function getString($string) {
	$string=trim($string);
	if(ini_get("magic_quotes_gpc")!=1) $string=addslashes($string);
	return $string;
}

function getParam($index,$default="") {
	if(isset($_POST[$index])) return getString($_POST[$index]);
	if(isset($_GET[$index])) return getString($_GET[$index]);
	return $default;
}

function checkTable($value) {
	$query="SELECT * FROM db_tables WHERE tbl='".$value."'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$value=$row["tbl"];
	dbFree($result);
	return $value;
}

function checkOrder($value,$table,$order) {
	$order=str_replace("."," ",$order);
	$order=str_replace(","," ",$order);
	$temp=explode(" ",$order);
	$count_temp=count($temp);
	if($count_temp==3 || $count_temp==6) $order=",".$temp[0].".".$temp[1]." ".$temp[2];
	else $order="";
	$value=str_replace(","," ",$value);
	$value=str_replace("."," ",$value);
	$temp=explode(" ",$value);
	if(count($temp)!=3) return "";
	$temp[1]=str_replace("|","\\'",$temp[1]);
	$temp[1]=str_replace("+"," ",$temp[1]);
	$campo=0;
	$sentido=0;
	$query="SELECT tbl,row FROM db_lists WHERE tbl='$temp[0]' AND row='$temp[1]'";
	$result=dbQuery($query);
	if(dbNumRows($result)>0) $campo=1;
	dbFree($result);
	if(!$campo) {
		$query="SELECT table_ref,text_ref FROM db_selects WHERE table_ref='$temp[0]' AND text_ref='$temp[1]'";
		$result=dbQuery($query);
		if(dbNumRows($result)>0) $campo=1;
		dbFree($result);
	}
	if($temp[2]=="asc" || $temp[2]=="desc") $sentido=1;
	$temp[1]=str_replace("\\'","'",$temp[1]);
	$temp[1]=str_replace(" ","+",$temp[1]);
	$value=$temp[0].".".$temp[1]." ".$temp[2];
	if($campo && $sentido) return $value.$order;
	return "";
}

function checkNumber($value) {
	if($value=="") return "";
	return intval($value);
}

function checkNumbers($value) {
	if($value=="") return "";
	$temp=explode(",",$value);
	$count_temp=count($temp);
	for($i=0;$i<$count_temp;$i++) {
		$temp[$i]=checkNumber($temp[$i]);
		if($temp[$i]=="") unset($temp[$i]);
	}
	$value=implode(",",$temp);
	return $value;
}

function checkNumberInf($value) {
	if($value=="") return $value;
	if($value=="inf") return $value;
	return intval($value);
}

function checkPage($value) {
	if($value=="form") return $value;
	if($value=="list") return $value;
	if($value=="process") return $value;
	if($value=="export") return $value;
	$query="SELECT * FROM db_tables WHERE `tbl`='$value'";
	$result=dbQuery($query);
	$num=dbNumRows($result);
	dbFree($result);
	if($num==1) return $value;
	return "intro";
}

function checkAction($value) {
	if($value=="setboolean") return $value;
	if($value=="delete") return $value;
	if($value=="update") return $value;
	if($value=="save") return $value;
	return "";
}

function useSession($name,$value="",$default="") {
	if($value!="") $_SESSION[$name]=$value;
	elseif(isset($_SESSION[$name]) && $_SESSION[$name]!="") $value=$_SESSION[$name];
	else $value=$default;
	return $value;
}

function useCookie($name,$value="",$default="") {
	if($value!="") setcookie($name,$value,time()+86400*30);
	elseif(isset($_COOKIE[$name]) && $_COOKIE[$name]!="") $value=$_COOKIE[$name];
	else $value=$default;
	return $value;
}

function checkRegisterGlobals() {
	return ini_get("register_globals")==1;
}

function checkZlibOutputCompression() {
	return ini_get("zlib.output_compression")==1;
}

function block_admin($yes) {
	$file="files/blocked";
	if($yes) touch($file);
	if(!$yes && file_exists($file)) unlink($file);
}

function check_block_admin() {
	$file="files/blocked";
	return ($_SESSION["user"]!="" && file_exists($file));
}

function swap_block_admin() {
	global $head,$main,$tail,$void;
	block_admin(!check_block_admin());
	location("inicio.php");
	closemain();
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}

function check_admin() {
	global $admuser;
	if(!isset($admuser)) $admuser="";
	if($admuser=="") $admuser="admin";

	return $_SESSION["user"]==$admuser;
}

function checkOffset($offset,$limit) {
	if($offset==0) return $offset;
	$offset=((int)(($offset+1)/$limit))*$limit;
	return $offset;
}

function checkDependencies() {
	global $error;

	$deps=array(
		"writable:files",
		"writable:cache",
		"dir:php",
		"dir:css",
		"dir:database",
		"dir:img",
		"dir:install",
		"dir:js",
		"dir:lib",
		"file:config.php",
		"file:index.php",
		"file:inicio.php",
		"file:php/about.php",
		"file:php/catalog.php",
		"file:php/connect.php",
		"file:php/contact.php",
		"file:php/dbmailer.php",
		"file:php/db_spec.php",
		"file:php/download.php",
		"file:php/export.php",
		"file:php/form.php",
		"file:php/functions.php",
		"file:php/getdinamic.php",
		"file:php/getfilter.php",
		"file:php/getselect.php",
		"file:php/head.php",
		"file:php/intro.php",
		"file:php/lang_ca.php",
		"file:php/lang_en.php",
		"file:php/lang_es.php",
		"file:php/list.php",
		"file:php/login.php",
		"file:php/mailer.php",
		"file:php/main.php",
		"file:php/password.php",
		"file:php/phpthumb.php",
		"file:php/process.php",
		"file:php/sessions.php",
		"file:php/style.php",
		"file:php/tail.php",
		"file:php/tbl_config.php",
		"file:php/tbl_files.php",
		"file:php/tbl_google.php",
		"file:php/tbl_labels.php",
		"file:php/tbl_mailing.php",
		"file:php/tbl_users.php");
	foreach($deps as $dep) {
		$dep=explode(":",$dep);
		if($dep[0]=="file") {
			if(!file_exists($dep[1]) || !is_file($dep[1])) $error[]="ERROR:"._LANG("connect_message_file_not_found").$dep[1];
		} elseif($dep[0]=="dir") {
			if(!file_exists($dep[1]) || !is_dir($dep[1])) $error[]="ERROR:"._LANG("connect_message_dir_not_found").$dep[1];
		} elseif($dep[0]=="writable") {
			if(!file_exists($dep[1]) || !is_dir($dep[1])) $error[]="ERROR:"._LANG("connect_message_dir_not_found").$dep[1];
			if(!file_exists($dep[1]) || !is_dir($dep[1]) || !is_writable($dep[1])) $error[]="ERROR:"._LANG("connect_message_dir_not_writable").$dep[1];
		} else {
			$error[]="ERROR:"._LANG("connect_message_unknown_type")."{$dep[0]} for {$dep[1]}";
		}
	}
}

function get_order($num,$order) {
	$temp=explode(",",$order);
	if(count($temp)>$num) return $temp[$num];
	return "";
}

function global_vars() {
	global $querystring,$page,$action,$id,$iter,$form,$table;
	global $last_order,$order,$limit,$offset,$func,$search;
	global $style,$pagestyle,$format;

	if(isset($_SERVER["QUERY_STRING"])) $querystring=$_SERVER["QUERY_STRING"];
	if(!isset($querystring)) $querystring="";
	$page=checkPage(getParam("page"));
	$action=checkAction(getParam("action"));
	$id=checkNumbers(getParam("id"));
	$iter=checkNumber(getParam("iter"));
	$form=getParam("form");
	$table=checkTable(getParam("table"));
	$last_order=$_SESSION["order"];
	$order=checkOrder(getParam("order"),$table,useSession("order","","$table.id desc"));
	$limit=checkNumberInf(getParam("limit"));
	$offset=checkNumber(getParam("offset"));
	$search=checkSearch(getParam("search"));
	$func=getParam("func");
	$style=checkStyle(useCookie("style",getParam("style"),$pagestyle),"custom.blue");
	$format=getParam("format");
}

function checkStyle($param,$default="") {
	global $pagestyle;
	if($param=="min.js") return $default;
	if($default=="") $default=$pagestyle;
	$file="jquery-ui.$param/jquery-ui.min.css";
	if(file_exists($file) && is_file($file)) return $param;
	$file="lib/jquery/jquery-ui.$param/jquery-ui.min.css";
	if(file_exists($file) && is_file($file)) return $param;
	return $default;
}

function checkSearch($param) {
	global $table;

	$temp=explode(".",$param);
	if(count($temp)==4) {
		if($temp[0]=="field" && $temp[2]=="id") {
			$field=$temp[1];
			$id=$temp[3];
			$query="SELECT name,type from db_lists WHERE tbl='$table' AND row='$field'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$name=$row["name"];
			$type=$row["type"];
			dbFree($result);
			$query="SELECT $field from $table WHERE id='$id'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$value=$row[$field];
			dbFree($result);
			if($type=="date") {
				$value=convert_date($value);
			} elseif($type=="unixtime" || $type=="timestamp") {
				$valor=date("d/m/Y H:i",$valor);
			} elseif($type=="datetime") {
				$valor=convert_date(substr($valor,0,10))." ".substr($valor,11,5);
			} elseif($type=="select") {
				$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$field'";
				$result=dbQuery($query);
				$row=dbFetchRow($result);
				$table_ref=$row["table_ref"];
				$value_ref=$row["value_ref"];
				$text_ref=$row["text_ref"];
				$temp=explode(":",$text_ref);
				dbFree($result);
				if($temp[0]=="concat") {
					unset($temp[0]);
					$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
					$text_ref=parseQuery($text_ref,getdbtype());
				}
				$query="SELECT $text_ref texto from $table_ref WHERE $value_ref='$value'";
				$result=dbQuery($query);
				$row=dbFetchRow($result);
				$value=$row["texto"];
				dbFree($result);
			} elseif($type=="boolean") {
				static $dict=array("No","Si");
				if(isset($dict[$value])) $value=$dict[$value];
			}
			$value=str_replace("'","\\'",$value);
			$param="$name=$value";
		}
	}
	return $param;
}

function control() {
	global $table,$last_order,$order,$limit,$offset;
	global $search;
	global $pagelimit;
	global $width_obj;
	global $page;

	if($page=="list") {
		if(($search!="" && $_SESSION["search"]!=$search) || ($order!="" && $order!=$last_order)) {
			$offset="0";
			if($_SESSION["limit"]=="inf") $limit=$pagelimit;
		}
		if($table!="" && $_SESSION["table"]!=$table) {
			$offset="0";
			if($_SESSION["limit"]=="inf") $limit=$pagelimit;
			$order="$table.id desc";
			$_SESSION["search"]="";
		}
		$_SESSION["table"]=$table;
		$order=useSession("order",$order,"$table.id desc");
		$limit=useSession("limit",$limit,$pagelimit);
		$offset=checkOffset(useSession("offset",strval($offset),"0"),$limit);
		$search=useSession("search",$search,"null");
	} else {
		$order=useSession("order",$order,"$table.id desc");
		$limit="inf";
		$offset=0;
		$search=useSession("search",$search,"null");
	}
	if($search=="null") $search="";
	$width_obj="66%";
}

function get_and_clear_login() {
	initsession();
	$login=$_SESSION["login"];
	$_SESSION["login"]=0;
	closesession();
	return $login;
}

function check_remember() {
	if(isset($_COOKIE["remember"]) && !$_COOKIE["remember"]) return 0;
	if($_SESSION["user"]!="") return 0;
	if($_SESSION["pass"]!="") return 0;
	if(isset($_COOKIE["user"])) $_SESSION["user"]=$_COOKIE["user"];
	if(isset($_COOKIE["pass"])) $_SESSION["pass"]=$_COOKIE["pass"];
	return 1;
}

function get_temp_directory() {
	static $temp="";
	if($temp!="") return $temp;
	$temp=getcwd()."/cache";
	if(isset($_ENV["TEMP"])) $temp=$_ENV["TEMP"];
	if(substr($temp,-1,1)!="/") $temp.="/";
	return $temp;
}

function cache_gc() {
	init_random();
	if(rand(0,100)>1) return;
	$files=glob(get_temp_directory()."*");
	$unix=time()-(86400*30);
	foreach($files as $file) {
		capture_next_error();
		$mtime=filemtime($file);
		$error=get_clear_error();
		if(!$error && $unix>$mtime) {
			capture_next_error();
			unlink($file);
			get_clear_error();
		}
	}
}

function check_db_schema() {
	global $error;

	if(!column_exists("db_forms","noedit")) $error[]="WARNING:"._LANG("connect_message_db_schema_noedit");
	if(!value_exists("db_tables","tbl","about.php")) $error[]="WARNING:"._LANG("connect_message_db_schema_about");
}

function check_install() {
	global $dbname;

	$rehash=0;
	$files=glob("install/*.sql");
	foreach($files as $file) {
		$table=str_replace(array("install/",".sql"),"",$file);
		if(!table_exists($table,false)) {
			$create=file_get_contents($file);
			dbQuery($create);
			$file=str_replace(".sql",".txt",$file);
			if(!file_exists($file)) $file.="."._LANG("lang");
			if(file_exists($file)) {
				$dbspec="DATABASE $dbname\n";
				$dbspec.=file_get_contents($file);
				include_once("db_spec.php");
				set_db_spec($dbspec);
			}
			$rehash=1;
		}
	}
	include_once("db_spec.php");
	if($rehash) set_db_config("spechash",md5(get_db_spec()));
}

ini_set("session.bug_compat_42","Off");
ini_set("date.timezone","Europe/Madrid");
connect();
check_install();
initsession();
check_remember();
import_config();
global_vars();
control();
closesession();
cache_gc();
