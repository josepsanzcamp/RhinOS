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
function query($param) {
	static $msgneeded="";
	static $msgunique="";
	static $msgcaptcha="";
	static $msgnotfound="";
	static $msgok="";
	static $table="";
	static $fields=array();
	static $types=array();
	static $neededs=array();
	static $uniques=array();
	static $key="";
	static $value="";
	static $captcha="";
	static $row=array();
	// OTHER VARIABLES
	include_once("dbapp2.php");
	$temp=strtok($param," ");
	if($temp=="MSG_NEEDED") {
		$msgneeded=trim(strtok(""));
		$msgneeded=_query_label_config($msgneeded);
	} elseif($temp=="MSG_UNIQUE") {
		$msgunique=trim(strtok(""));
		$msgunique=_query_label_config($msgunique);
	} elseif($temp=="MSG_CAPTCHA") {
		$msgcaptcha=trim(strtok(""));
		$msgcaptcha=_query_label_config($msgcaptcha);
	} elseif($temp=="MSG_NOTFOUND") {
		$msgnotfound=trim(strtok(""));
		$msgnotfound=_query_label_config($msgnotfound);
	} elseif($temp=="MSG_OK") {
		$msgok=trim(strtok(""));
		$msgok=_query_label_config($msgok);
	} elseif($temp=="TABLE") {
		$table=strtok(" ");
	} elseif($temp=="FIELDS") {
		$fields=explode(",",trim(strtok("")));
		foreach($fields as $key=>$val) $fields[$key]=trim($val);
	} elseif($temp=="TYPES") {
		$types=explode(",",trim(strtok("")));
		foreach($types as $key=>$val) $types[$key]=strtolower(trim($val));
	} elseif($temp=="NEEDEDS") {
		$neededs=explode(",",trim(strtok("")));
		foreach($neededs as $key=>$val) $neededs[$key]=trim($val);
	} elseif($temp=="UNIQUES") {
		$uniques=explode(",",trim(strtok("")));
		foreach($uniques as $key=>$val) $uniques[$key]=trim($val);
	} elseif($temp=="KEY") {
		$key=strtok(" ");
	} elseif($temp=="VALUE") {
		$value=trim(strtok(""));
	} elseif($temp=="SELECT") {
		$temp=strtok(" ");
		$has_error=sessions("GET has_error");
		if(!$has_error) {
			if(_query_captcha($captcha)) {
				$v2=_dbapp2_replace($value,array(),$row,"ROW");
				$row=_query_select($table,$fields,$types,$key,$value,$row,$temp=="PRINT");
				$error="";
				foreach($row as $k=>$v) if($k==$key && $v!=$v2) $error="notfound";
				if($error=="notfound") {
					sessions("SET error $msgnotfound");
					sessions("SET has_error 1");
				} else {
					sessions("SET error $msgok");
					sessions("SET has_error 0");
				}
			} else {
				sessions("SET error $msgcaptcha");
				sessions("SET has_error 1");
			}
			$table="";
			$fields=array();
			$types=array();
			$uniques=array();
			$neededs=array();
			$key="";
			$value="";
			$captcha="";
		}
	} elseif($temp=="INSERT") {
		$temp=strtok(" ");
		$has_error=sessions("GET has_error");
		if(!$has_error) {
			if(_query_captcha($captcha)) {
				$error=_query_insert($table,$fields,$types,$uniques,$neededs,$row,$temp=="PRINT");
				if($error=="unique") {
					sessions("SET error $msgunique");
					sessions("SET has_error 1");
				} elseif($error=="needed") {
					sessions("SET error $msgneeded");
					sessions("SET has_error 1");
				} else {
					$row=array_merge($row,_query_select($table,array("MAX(id) LAST_INSERT_ID"),array("integer"),"","",array(),$temp=="PRINT"));
					sessions("SET error $msgok");
					sessions("SET has_error 0");
				}
			} else {
				sessions("SET error $msgcaptcha");
				sessions("SET has_error 1");
			}
			$table="";
			$fields=array();
			$types=array();
			$uniques=array();
			$neededs=array();
			$key="";
			$value="";
			$captcha="";
		}
	} elseif($temp=="UPDATE") {
		$temp=strtok(" ");
		$has_error=sessions("GET has_error");
		if(!$has_error) {
			if(_query_captcha($captcha)) {
				$error=_query_update($table,$fields,$types,$uniques,$neededs,$key,$value,$row,$temp=="PRINT");
				if($error=="unique") {
					sessions("SET error $msgunique");
					sessions("SET has_error 1");
				} elseif($error=="needed") {
					sessions("SET error $msgneeded");
					sessions("SET has_error 1");
				} else {
					sessions("SET error $msgok");
					sessions("SET has_error 0");
				}
			} else {
				sessions("SET error $msgcaptcha");
				sessions("SET has_error 1");
			}
			$table="";
			$fields=array();
			$types=array();
			$uniques=array();
			$neededs=array();
			$key="";
			$value="";
			$captcha="";
		}
	} elseif($temp=="DELETE") {
		$temp=strtok(" ");
		$has_error=sessions("GET has_error");
		if(!$has_error) {
			if(_query_captcha($captcha)) {
				$error=_query_delete($table,$key,$value,$row,$temp=="PRINT");
				if($error=="notfound") {
					sessions("SET error $msgnotfound");
					sessions("SET has_error 1");
				} else {
					sessions("SET error $msgok");
					sessions("SET has_error 0");
				}
			} else {
				sessions("SET error $msgcaptcha");
				sessions("SET has_error 1");
			}
			$table="";
			$fields=array();
			$types=array();
			$uniques=array();
			$neededs=array();
			$key="";
			$value="";
			$captcha="";
		}
	} elseif($temp=="PRINT") {
		$param=trim(strtok(""));
		_dbapp2_parser($row,__TAG1__." PRINT $param ".__TAG2__);
	} elseif($temp=="CAPTCHA") {
		$captcha=strtok(" ");
	} elseif($temp=="GET") {
		$temp=strtok("");
		return _dbapp2_replace($temp,array(),$row,"ROW");
	} elseif($temp=="SET_GLOBAL") {
		$temp=strtok(" ");
		$temp2=trim(strtok(""));
		$temp2=_dbapp2_replace($temp2,array(),$row,"ROW");
		set_global($temp." ".$temp2);
	} elseif($temp=="RETURN") {
		$temp=strtok(" ");
		if($temp=="REFERER") {
			if(!isset($_SERVER["HTTP_REFERER"])) die();
			$temp=$_SERVER["HTTP_REFERER"];
		} else {
			$temp=get_base().$temp;
		}
		_query_new_location($temp);
	} else {
		if(checkDebug("DEBUG_QUERY")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	}
}

function _query_select($table,$fields,$types,$key,$value,$row,$print=0) {
	$value=_dbapp2_replace($value,array(),$row,"ROW");
	$list=array();
	foreach($fields as $field) {
		$list[]=$field;
	}
	$list=implode(",",$list);
	$query="SELECT $list FROM $table";
	if($key!="") $query.=" WHERE $key='$value'";
	if($print) echo_buffer($query);
	$oldcache=set_db_cache(false);
	$result=dbQuery($query);
	set_db_cache($oldcache);
	if(dbNumRows($result)>0) {
		$row=dbFetchRow($result);
		foreach($fields as $key2=>$field) {
			$temp=explode(" ",$field);
			$field=$temp[count($temp)-1];
			$type=isset($types[$key2])?$types[$key2]:"text";
			$row[$field]=_query_typecast($row[$field],$type);
		}
	} else {
		$row=array();
		foreach($fields as $key2=>$field) {
			$temp=explode(" ",$field);
			$field=$temp[count($temp)-1];
			$type=isset($types[$key2])?$types[$key2]:"text";
			$row[$field]=_query_typecast("",$type);
		}
	}
	dbFree($result);
	return $row;
}

function _query_insert($table,$fields,$types,$uniques,$neededs,$row,$print=0) {
	$list1=array();
	$list2=array();
	foreach($fields as $key2=>$field) {
		$data=getParam($field);
		$data=_dbapp2_replace($data,array(),$row,"ROW");
		$type=isset($types[$key2])?$types[$key2]:"text";
		$data=_query_typecast($data,$type);
		if(in_array($field,$neededs)) {
			if($data=="") return "needed";
		}
		if(in_array($field,$uniques)) {
			$query="SELECT $field FROM $table WHERE $field='$data'";
			$oldcache=set_db_cache(false);
			$result=dbQuery($query);
			set_db_cache($oldcache);
			$numrows=dbNumRows($result);
			dbFree($result);
			if($numrows>0) return "unique";
		}
		$list1[]=$field;
		$list2[]="'".addslashes($data)."'";
	}
	$list1=implode(",",$list1);
	$list2=implode(",",$list2);
	$query="INSERT INTO $table($list1) VALUES($list2)";
	if(!$print) dbQuery($query);
	if($print) echo_buffer($query);
	return "";
}

function _query_update($table,$fields,$types,$uniques,$neededs,$key,$value,$row,$print=0) {
	$value=_dbapp2_replace($value,array(),$row,"ROW");
	$list=array();
	foreach($fields as $key2=>$field) {
		if($field!=$key) {
			$data=getParam($field);
			$data=_dbapp2_replace($data,array(),$row,"ROW");
			$type=isset($types[$key2])?$types[$key2]:"text";
			$data=_query_typecast($data,$type);
			if(in_array($field,$neededs)) {
				if($data=="") return "needed";
			}
			if(in_array($field,$uniques)) {
				$query="SELECT $field FROM $table WHERE $field='$data'";
				if($key!="") $query.=" AND $key!='$value'";
				$oldcache=set_db_cache(false);
				$result=dbQuery($query);
				set_db_cache($oldcache);
				$numrows=dbNumRows($result);
				dbFree($result);
				if($numrows>0) return "unique";
			}
			$list[]=$field."='".addslashes($data)."'";
		}
	}
	$list=implode(",",$list);
	$query="UPDATE $table SET $list";
	if($key!="") $query.=" WHERE $key='$value'";
	if(!$print) dbQuery($query);
	if($print) echo_buffer($query);
	return "";
}

function _query_delete($table,$key,$value,$row,$print=0) {
	$value=_dbapp2_replace($value,array(),$row,"ROW");
	if($key!="") {
		$query="SELECT $key FROM $table WHERE $key='$value'";
		if($print) echo_buffer($query);
		$oldcache=set_db_cache(false);
		$result=dbQuery($query);
		set_db_cache($oldcache);
		$numrows=dbNumRows($result);
		dbFree($result);
		if($numrows==0) return "notfound";
	}
	$query="DELETE FROM $table";
	if($key!="") $query.=" WHERE $key='$value'";
	if(!$print) dbQuery($query);
	if($print) echo_buffer($query);
	return "";
}

function _query_label_config($cad) {
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

function _query_captcha($captcha) {
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
	return $valid;
}

function _query_typecast($value,$type="text") {
	if($type=="integer") $value=intval($value);
	elseif($type=="float") $value=floatval($value);
	elseif($type=="text") $value=strval($value);
	elseif(checkDebug("DEBUG_QUERY")) echo_buffer(__TAG1__." UNKNOWN TYPE: $type ".__TAG2__);
	return $value;
}

function _query_new_location($param) {
	header_powered();
	header_expires(false);
	header("Content-Type: text/html");
	echo_buffer("<script type='text/javascript'>\n");
	echo_buffer("if(typeof(_query_new_location)=='function') _query_new_location('$param');\n");
	echo_buffer("else window.location.href='$param';\n");
	echo_buffer("</script>\n");
	die();
}
