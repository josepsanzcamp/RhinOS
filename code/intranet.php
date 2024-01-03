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
function intranet($param) {
	static $msgok="";
	static $msgko="";
	static $msgcaptcha="";
	static $msgout="";
	static $retok="";
	static $retko="";
	static $retout="";
	static $query="";
	static $captcha="";
	static $row=array();

	include_once("dbapp2.php");
	$temp=strtok($param," ");
	if($temp=="MSG_OK") {
		$msgok=trim(strtok(""));
		$msgok=_intranet_label_config($msgok);
	} elseif($temp=="MSG_KO") {
		$msgko=trim(strtok(""));
		$msgko=_intranet_label_config($msgko);
	} elseif($temp=="MSG_CAPTCHA") {
		$msgcaptcha=trim(strtok(""));
		$msgcaptcha=_intranet_label_config($msgcaptcha);
	} elseif($temp=="MSG_OUT") {
		$msgout=trim(strtok(""));
		$msgout=_intranet_label_config($msgout);
	} elseif($temp=="RET_OK") {
		$retok=trim(strtok(""));
		$retok=get_base()._intranet_label_config($retok);
	} elseif($temp=="RET_KO") {
		$retko=trim(strtok(""));
		$retko=get_base()._intranet_label_config($retko);
	} elseif($temp=="RET_OUT") {
		$retout=trim(strtok(""));
		$retout=get_base()._intranet_label_config($retout);
	} elseif($temp=="QUERY") {
		$query=trim(strtok(""));
	} elseif($temp=="LOGIN") {
		$has_error=sessions("GET has_error");
		if(!$has_error) {
			$valid=1;
			if($captcha!="") {
				// CHECK VALUE
				$captcha1=sessions("GET {$captcha}_value");
				sessions("SET {$captcha}_value NULL");
				$captcha2=getParam($captcha);
				if(strlen($captcha1)==0) $valid=0;
				if(strlen($captcha2)==0) $valid=0;
				if($captcha1=="NULL") $valid=0;
				if($captcha2=="NULL") $valid=0;
				if($captcha1!=$captcha2) $valid=0;
				// CHECK IPADDR
				$captcha1=sessions("GET {$captcha}_ipaddr");
				sessions("SET {$captcha}_ipaddr NULL");
				$captcha2=isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"NULL";
				if(strlen($captcha1)==0) $valid=0;
				if(strlen($captcha2)==0) $valid=0;
				if($captcha1=="NULL") $valid=0;
				if($captcha2=="NULL") $valid=0;
				if($captcha1!=$captcha2) $valid=0;
			}
			if($valid) {
				$row["__STATIC__"]=_intranet_check_user($query);
				if($row["__STATIC__"]!=0) {
					sessions("SET error $msgok");
					sessions("SET has_error 0");
					sessions("SET uid ".$row["__STATIC__"]);
				} else {
					sessions("SET error $msgko");
					sessions("SET has_error 1");
					sessions("SET uid 0");
				}
			} else {
				$row["__STATIC__"]=0;
				sessions("SET error $msgcaptcha");
				sessions("SET has_error 1");
				sessions("SET uid 0");
			}
			$captcha="";
		}
	} elseif($temp=="LOGOUT") {
		$has_error=sessions("GET has_error");
		if(!$has_error) {
			$row["__STATIC__"]=0;
			sessions("SET error $msgout");
			sessions("SET has_error 0");
			sessions("SET uid 0");
		}
	} elseif($temp=="OK") {
		if(isset($row["__FILTER_IF__"])) _dbapp2_parser($row,__TAG1__." ENDIF ".__TAG2__);
		if(!isset($row["__STATIC__"])) $row["__STATIC__"]=_intranet_check_user($query);
		_dbapp2_parser($row,__TAG1__." IF __STATIC__ ".__TAG2__);
		$row["__FILTER_IF__"]=1;
	} elseif($temp=="KO") {
		if(isset($row["__FILTER_IF__"])) _dbapp2_parser($row,__TAG1__." ENDIF ".__TAG2__);
		if(!isset($row["__STATIC__"])) $row["__STATIC__"]=_intranet_check_user($query);
		_dbapp2_parser($row,__TAG1__." IF NOT __STATIC__ ".__TAG2__);
		$row["__FILTER_IF__"]=1;
	} elseif($temp=="ALL") {
		if(isset($row["__FILTER_IF__"])) _dbapp2_parser($row,__TAG1__." ENDIF ".__TAG2__);
		unset($row["__FILTER_IF__"]);
	} elseif($temp=="RETURN") {
		$temp=strtok(" ");
		$error=sessions("GET error");
		$has_error=sessions("GET has_error");
		$uid=sessions("GET uid");
		if($temp=="REFERER") {
			if(!isset($_SERVER["HTTP_REFERER"])) die();
			$return=$_SERVER["HTTP_REFERER"];
		} else {
			if($uid!=0 && $has_error==0) $return=$retok;
			if($uid!=0 && $has_error==1) die();
			if($uid==0 && $has_error==1) $return=$retko;
			if($uid==0 && $has_error==0) $return=$retout;
		}
		if($return=="") {
			if(!isset($_SERVER["HTTP_REFERER"])) die();
			$return=$_SERVER["HTTP_REFERER"];
		}
		_intranet_new_location($return,$has_error,$error);
	} elseif($temp=="CAPTCHA") {
		$captcha=strtok(" ");
	}
}

function _intranet_check_user($query) {
	$query=_dbapp2_replace($query);
	$result=dbQuery($query);
	$numrows=dbNumRows($result);
	$row=dbFetchRow($result);
	dbFree($result);
	$uid=($numrows==1 && isset($row["id"]))?$row["id"]:0;
	return $uid;
}

function _intranet_label_config($cad) {
	$clave=strtok($cad," ");
	if(in_array($clave,array("LABELS","LABEL"))) {
		include_once("labels.php");
		$cad=labels("GET ".strtok(""));
	} elseif($clave=="CONFIG") {
		include_once("config.php");
		$cad=config("GET ".strtok(""));
	}
	return $cad;
}

function _intranet_new_location($param,$has_error="",$error="") {
	header_powered();
	header_expires(false);
	header("Content-Type: text/html");
	echo_buffer("<script type='text/javascript'>\n");
	echo_buffer("if(typeof(_intranet_new_location2)=='function') _intranet_new_location2('$param','$has_error',\"$error\");\n");
	echo_buffer("else if(typeof(_intranet_new_location)=='function') _intranet_new_location('$param');\n");
	echo_buffer("else window.location.href='$param';\n");
	echo_buffer("</script>\n");
	die();
}
