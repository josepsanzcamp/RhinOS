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
function send($param) {
	static $msgok="";
	static $msgko="";
	static $msgcaptcha="";
	static $msgneeded="";
	static $from="";
	static $fromname="";
	static $replyto="";
	static $replytoname="";
	static $to=array();
	static $subject="";
	static $fields=array();
	static $names=array();
	static $types=array();
	static $neededs=array();
	static $retok="";
	static $retko="";
	static $captcha="";
	static $host="";
	static $user="";
	static $pass="";

	include_once("mailer.php");
	include_once("dbapp2.php");
	$temp=strtok($param," ");
	if($temp=="MSG_OK") {
		$msgok=trim(strtok(""));
		$msgok=_send_label_config($msgok);
	} elseif($temp=="MSG_KO") {
		$msgko=trim(strtok(""));
		$msgko=_send_label_config($msgko);
	} elseif($temp=="MSG_CAPTCHA") {
		$msgcaptcha=trim(strtok(""));
		$msgcaptcha=_send_label_config($msgcaptcha);
	} elseif($temp=="MSG_NEEDED") {
		$msgneeded=trim(strtok(""));
		$msgneeded=_send_label_config($msgneeded);
	} elseif($temp=="RET_OK") {
		$retok=trim(strtok(""));
		$retok=get_base()._send_label_config($retok);
	} elseif($temp=="RET_KO") {
		$retko=trim(strtok(""));
		$retko=get_base()._send_label_config($retko);
	} elseif($temp=="FROM") {
		$from=trim(strtok(""));
		$from=_send_tags($from);
		$from=_send_label_config($from);
	} elseif($temp=="FROMNAME") {
		$fromname=trim(strtok(""));
		$fromname=_send_label_config($fromname);
	} elseif($temp=="REPLYTO") {
		$replyto=trim(strtok(""));
		$replyto=_send_tags($replyto);
		$replyto=_send_label_config($replyto);
	} elseif($temp=="REPLYTONAME") {
		$replytoname=trim(strtok(""));
		$replytoname=_send_tags($replytoname);
		$replytoname=_send_label_config($replytoname);
	} elseif($temp=="TO") {
		$temp2=trim(strtok(""));
		$temp2=_send_tags($temp2);
		$temp2=_send_label_config($temp2);
		$temp2=_dbapp2_replace($temp2);
		if($temp2 && !in_array($temp2,$to)) $to[]=$temp2;
	} elseif($temp=="SUBJECT") {
		$subject=trim(strtok(""));
		$subject=_send_label_config($subject);
	} elseif($temp=="FIELDS") {
		$fields=explode(",",trim(strtok("")));
		foreach($fields as $key=>$val) $fields[$key]=trim($val);
	} elseif($temp=="NAMES") {
		$names=explode(",",trim(strtok("")));
		foreach($names as $key=>$val) $names[$key]=trim($val);
	} elseif($temp=="TYPES") {
		$types=explode(",",trim(strtok("")));
		foreach($types as $key=>$val) $types[$key]=strtolower(trim($val));
	} elseif($temp=="NEEDEDS") {
		$neededs=explode(",",trim(strtok("")));
		foreach($neededs as $key=>$val) $neededs[$key]=trim($val);
	} elseif($temp=="BACKUP") {
		$temp=strtok(" ");
		if($temp=="SET") {
			sessions("SET backup",$_POST);
		} elseif($temp=="RESTORE") {
			$backup=sessions("GET backup");
			if(is_array($backup)) foreach($backup as $key=>$val) $_POST[$key]=$val;
			unset($backup);
		}
	} elseif($temp=="RETURN") {
		$temp=strtok(" ");
		$error=sessions("GET error");
		$has_error=sessions("GET has_error");
		if($temp=="REFERER") {
			if(!isset($_SERVER["HTTP_REFERER"])) die();
			$return=$_SERVER["HTTP_REFERER"];
		} else {
			if($has_error==0) $return=$retok;
			if($has_error==1) $return=$retko;
		}
		if($return=="") {
			if(!isset($_SERVER["HTTP_REFERER"])) die();
			$return=$_SERVER["HTTP_REFERER"];
		}
		_send_new_location($return,$has_error,$error);
	} elseif($temp=="CAPTCHA") {
		$captcha=strtok(" ");
	} elseif($temp=="HOST") {
		$host=trim(strtok(""));
		$host=_send_label_config($host);
	} elseif($temp=="USER") {
		$user=trim(strtok(""));
		$user=_send_label_config($user);
	} elseif($temp=="PASS") {
		$pass=trim(strtok(""));
		$pass=_send_label_config($pass);
	} elseif($temp=="COLOR") {
		$temp=strtolower(strtok(" "));
		$color=strtok(" ");
		colorreport(array($temp=>$color));
	} elseif($temp=="PRINT") {
		$body=_send_body($subject,$fields,$names,$types);
		if(!headers_sent()) header("Content-Type: text/html");
		echo $body;
	} elseif($temp=="") {
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
				// CONTINUE
				if(!$valid) $error=$msgcaptcha;
			}
			if($valid) {
				$count=count($fields);
				for($i=0;$i<$count;$i++) {
					$field=$fields[$i];
					$valor=getParam($field);
					if(in_array($field,$neededs)) {
						if($valor=="") $valid=0;
					}
				}
				if(!$valid) $error=$msgneeded;
			}
			if($valid) {
				$body=_send_body($subject,$fields,$names,$types);
				$extra=array();
				$count=count($fields);
				for($i=0;$i<$count;$i++) {
					$field=$fields[$i];
					$tipo=$types[$i];
					if($tipo=="file") {
						$path=$_FILES[$field]["tmp_name"];
						$name2=$_FILES[$field]["name"];
						$mime=$_FILES[$field]["type"];
						if($path!="" && $name2!="" && $mime!="") {
							$extra[]=$path; $extra[]=$name2; $extra[]=$mime;
						}
					}
				}
				$result="";
				foreach($to as $a) {
					$result.=sendmail($from,$fromname,$replyto,$replytoname,$a,$subject,$body,$extra,$host,$user,$pass);
				}
				if($result=="") {
					sessions("SET error $msgok");
					sessions("SET has_error 0");
					$count=count($fields);
					for($i=0;$i<$count;$i++) {
						$field=$fields[$i];
						sessions("SET $field NULL");
					}
				} else {
					sessions("SET error $msgko");
					sessions("SET has_error 1");
					$count=count($fields);
					for($i=0;$i<$count;$i++) {
						$field=$fields[$i];
						$valor=getParam($field);
						sessions("SET $field $valor");
					}
				}
			} else {
				sessions("SET error $error");
				sessions("SET has_error 1");
				$count=count($fields);
				for($i=0;$i<$count;$i++) {
					$field=$fields[$i];
					$valor=getParam($field);
					sessions("SET $field $valor");
				}
			}
			$to=array();
			$captcha="";
		}
	} else {
		if(checkDebug("DEBUG_SEND")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	}
}

function _send_body($subject,$fields,$names,$types) {
	$body=beginreport($subject);
	$count=count($fields);
	for($i=0;$i<$count;$i++) {
		$field=$fields[$i];
		$name=$names[$i];
		$tipo=$types[$i];
		$valor=getParam($field);
		if(isset($_GET[$field])) $valor=$_GET[$field];
		if(isset($_POST[$field])) $valor=$_POST[$field];
		if($tipo=="text") {
			$body.=textreport($name,$valor);
		} elseif($tipo=="textarea") {
			$body.=textareareport($name,$valor);
		} elseif($tipo=="mail") {
			$body.=mailreport($name,$valor);
		} elseif($tipo=="link") {
			$body.=linkreport($name,$valor);
		} elseif($tipo=="file") {
			$path=$_FILES[$field]["tmp_name"];
			$name2=$_FILES[$field]["name"];
			$mime=$_FILES[$field]["type"];
			if($path!="" && $name2!="" && $mime!="") {
				$body.=textreport($name,$name2);
			}
		}
	}
	$body.=endreport();
	return $body;
}

function _send_tags($cad) {
	$cad=str_replace("[at]","@",$cad);
	$cad=str_replace("[dot]",".",$cad);
	return $cad;
}

function _send_label_config($cad) {
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

function _send_new_location($param,$has_error="",$error="") {
	header_powered();
	header_expires(false);
	header("Content-Type: text/html");
	echo_buffer("<script type='text/javascript'>\n");
	echo_buffer("if(typeof(_send_new_location2)=='function') _send_new_location2('$param','$has_error',\"$error\");\n");
	echo_buffer("else if(typeof(_send_new_location)=='function') _send_new_location('$param');\n");
	echo_buffer("else window.location.href='$param';\n");
	echo_buffer("</script>\n");
	die();
}
