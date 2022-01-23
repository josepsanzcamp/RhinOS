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
define("__BUF_TR_DATA1__",0);
define("__BUF_TD_DATA__",1);
define("__BUF_TR_DATA2__",2);
define("__BUF_TD_NULL__",3);
define("__BUF_TR_NULL__",4);
define("__BUF_PAG_PREV__",5);
define("__BUF_PAG_CURRENT__",6);
define("__BUF_PAG_NEXT__",7);
define("__BUF_DINAMICS__",8);

function dbapp2($param,$newrow2=null) {
	static $params=array();
	static $buffers=array();
	static $row0=array();
	static $row1=array();
	static $row2=array();
	static $old_output=1;
	static $backups=array();
	static $include_if=0;
	// FOR COMPATIBILITY WITH DB AND XML
	global $_dbapp2_dbNumRows;
	global $_dbapp2_dbFree;
	global $_dbapp2_dbFetchRow;
	global $_dbapp2_dbNumFields;
	global $_dbapp2_dbFieldName;
	global $_dbapp2_dbFieldType;
	// OTHER VARIABLES
	$proc="NOTHING";
	$argv=get_argv();
	$counter=_dbapp2_if_counter();
	$process=(!isset($params["__BEGIN_END__"]) && get_output()) || (isset($params["__BEGIN_END__"]) && $old_output);
	// FOR ALLOW INCLUDES
	$temp=strtok($param," ");
	$temp2=strtok(" ");
	if($temp2=="INCLUDE") {
		if($temp=="BEGIN") $include_if++;
		elseif($temp=="END") $include_if=-$include_if;
		if(abs($include_if)==1) $forcetag=$temp.$temp2;
	}
	// BEGIN PARSER OPERATION
	$temp=strtok($param," ");
	if($include_if!=0) {
		if($process) {
			if($include_if==1 && !isset($params["__BEGIN_END__"])) {
				$old_output=get_output("dbapp2_buffer");
				set_output(0,"dbapp2_buffer");
				clear_buffer();
				$params["__BEGIN_END__"]=1;
				$params["__INCLUDE__"]=1;
			}
			if(isset($forcetag)) echo_buffer(__TAG1__." $forcetag ".__TAG2__);
			else echo_buffer(__TAG1__." INCLUDE DBAPP2 $param ".__TAG2__);
			if($include_if==-1 && isset($params["__INCLUDE__"])) {
				$include_buffer=get_buffer();
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				unset($params["__BEGIN_END__"]);
				unset($params["__INCLUDE__"]);
			}
		}
		if($include_if<0) $include_if=-$include_if-1;
		if(isset($include_buffer)) _dbapp2_parser($params,$include_buffer,$row0,$row2);
	} elseif($temp=="__BACKUP__") {
		array_push($backups,$params);
		array_push($backups,$buffers);
		array_push($backups,$row0);
		array_push($backups,$row1);
		array_push($backups,$row2);
		$params=array();
		//~ $buffers=array(); // TO ALLOW TO RECICLE DINAMICS WITH BEGIN INCLUDE
		$row0=array();
		$row1=array();
		$row2=$newrow2;
	} elseif($temp=="__RESTORE__") {
		$row2=array_pop($backups);
		$row1=array_pop($backups);
		$row0=array_pop($backups);
		$buffers=array_pop($backups);
		$params=array_pop($backups);
	} elseif($temp=="SET") {
		if($process) {
			$key=strtok(" \n\t");
			$value=trim(strtok(""));
			$params[$key]=_dbapp2_replace($value,array_merge($row0,$params),$row2,"ROW");
		}
	} elseif($temp=="RESET") {
		if($process) {
			$key=trim(strtok(""));
			if($key=="ALL"){
				$params=array();
				$buffers=array();
				$row0=array();
				$row1=array();
			} else {
				unset($params[$key]);
			}
		}
	} elseif($temp=="INIT") {
		if($process) {
			$proc=$temp;
		}
	} elseif($temp=="BEGIN") {
		if($process) {
			$temp=strtok(" ");
			if($temp=="TR_DATA") {
				$old_output=get_output("dbapp2_buffer");
				set_output(0,"dbapp2_buffer");
				clear_buffer();
				$params["__BEGIN_END__"]=1;
			} elseif($temp=="TD_DATA") {
				$buffers[__BUF_TR_DATA1__]=get_buffer();
				clear_buffer();
			} elseif($temp=="TR_NULL") {
				$old_output=get_output("dbapp2_buffer");
				set_output(0,"dbapp2_buffer");
				clear_buffer();
				$params["__BEGIN_END__"]=1;
			} elseif($temp=="TD_NULL") {
				$old_output=get_output("dbapp2_buffer");
				set_output(0,"dbapp2_buffer");
				clear_buffer();
				$params["__BEGIN_END__"]=1;
			} elseif($temp=="PAG_PREV") {
				$old_output=get_output("dbapp2_buffer");
				set_output(0,"dbapp2_buffer");
				clear_buffer();
				$params["__BEGIN_END__"]=1;
			} elseif($temp=="PAG_CURRENT") {
				$buffers[__BUF_PAG_PREV__]=get_buffer();
				clear_buffer();
			} elseif($temp=="PAG_NEXT") {
				$buffers[__BUF_PAG_CURRENT__]=get_buffer();
				clear_buffer();
			} elseif($temp=="DINAMICS") {
				$temp=trim(strtok(""));
				if($temp!="") {
					$old_output=get_output("dbapp2_buffer");
					set_output(0,"dbapp2_buffer");
					clear_buffer();
					$params["__BEGIN_END__"]=1;
					$params["__DINAMICS__"]=$temp;
				} else {
					if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." WARNING: INCLUDE DBAPP2 BEGIN DINAMICS WITHOUT argument ".__TAG2__);
				}
			} elseif($temp=="DIV_DATA") {
				$old_output=get_output("dbapp2_buffer");
				set_output(0,"dbapp2_buffer");
				clear_buffer();
				$params["__BEGIN_END__"]=1;
			}
		}
	} elseif($temp=="END") {
		if($process) {
			$temp=strtok(" ");
			if($temp=="TD_DATA") {
				$buffers[__BUF_TD_DATA__]=get_buffer();
				clear_buffer();
			} elseif($temp=="TR_DATA") {
				$buffers[__BUF_TR_DATA2__]=get_buffer();
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				$proc="QUERY";
				unset($params["__BEGIN_END__"]);
			} elseif($temp=="TR_NULL") {
				$buffers[__BUF_TR_NULL__]=get_buffer();
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				unset($params["__BEGIN_END__"]);
			} elseif($temp=="TD_NULL") {
				$buffers[__BUF_TD_NULL__]=get_buffer();
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				unset($params["__BEGIN_END__"]);
			} elseif($temp=="PAG") {
				$buffers[__BUF_PAG_NEXT__]=get_buffer();
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				$proc="PAG";
				unset($params["__BEGIN_END__"]);
			} elseif($temp=="DINAMICS") {
				$temp=trim(strtok(""));
				$buffers[__BUF_DINAMICS__][$params["__DINAMICS__"]]=get_buffer();
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				unset($params["__BEGIN_END__"]);
				if($temp!="" && $params["__DINAMICS__"]!=$temp && checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." WARNING: INCLUDE DBAPP2 BEGIN DINAMICS ".$params["__DINAMICS__"]." CAN NOT BE CLOSED WITH INCLUDE DBAPP2 END DINAMICS $temp ".__TAG2__);
				unset($params["__DINAMICS__"]);
			} elseif($temp=="DIV_DATA") {
				$buffers[__BUF_TR_DATA1__]="";
				$buffers[__BUF_TD_DATA__]=get_buffer();
				$buffers[__BUF_TR_DATA2__]="";
				clear_buffer();
				set_output($old_output,"dbapp2_buffer");
				$proc="QUERY";
				unset($params["__BEGIN_END__"]);
			}
		}
	} elseif($temp=="GET") {
		$temp=strtok("");
		return _dbapp2_replace($temp,$params,$row0,"ROW");
	} elseif($temp=="SET_GLOBAL") {
		if($process) {
			$temp=strtok(" ");
			$temp2=trim(strtok(""));
			$temp2=_dbapp2_replace($temp2,$params,$row0,"ROW");
			set_global($temp." ".$temp2);
		}
	} elseif(in_array($temp,array("IF","ELIF","ELSEIF","ELSE","ENDIF"))) {
		if(isset($params["__BEGIN_END__"])) echo_buffer(__TAG1__." $param ".__TAG2__);
		else _dbapp2_parser($params,__TAG1__." $param ".__TAG2__,$row0,$row2);
	} else {
		if($process) {
			if(isset($params["__BEGIN_END__"])) echo_buffer(__TAG1__." $param ".__TAG2__);
			else _dbapp2_parser($params,__TAG1__." $param ".__TAG2__,$row0,$row2);
		}
	}
	// BEGIN THE PROC OPERATION
	if($proc=="INIT") {
		if(isset($params["XML"])) {
			$params["XML"]=_dbapp2_replace($params["XML"],array_merge($row0,$params),$row2,"ROW");
			$params["QUERY"]=_dbapp2_replace($params["QUERY"],array_merge($row0,$params),$row2,"ROW");
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".$params["XML"]." ".__TAG2__);
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".$params["QUERY"]." ".__TAG2__);
			include_once("admin/database/xml.php");
			$valids=array("http://","https://");
			$exists=0;
			foreach($valids as $valid) if(substr($params["XML"],0,strlen($valid))==$valid) $exists=1;
			if(!$exists) $params["XML"]=get_base().$params["XML"];
			$result=dbQuery_xml($params["XML"]."|".$params["QUERY"]);
			_dbapp2_set_xml_db();
		} else {
			$params["QUERY"]=_dbapp2_replace($params["QUERY"],array_merge($row0,$params),$row2,"ROW");
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".$params["QUERY"]." ".__TAG2__);
			$result=dbQuery($params["QUERY"]);
			_dbapp2_set_normal_db();
		}
		$params["NUMROWS"]=$_dbapp2_dbNumRows($result);
		if($params["NUMROWS"]>0) {
			$row0=$_dbapp2_dbFetchRow($result);
			$row0=_dbapp2_remove_void_dinamics($row0);
		}
		$_dbapp2_dbFree($result);
		if(isset($params["LIMIT"])) {
			$params["NUMPAGS"]=intval(($params["NUMROWS"]+$params["LIMIT"]-1)/$params["LIMIT"]);
			$params["USEPAG"]=$params["NUMPAGS"]>1?1:0;
			if(!isset($params["ARGV_PAG"])) $params["ARGV_PAG"]=1;
			if(!isset($argv[$params["ARGV_PAG"]])) $params["NUMPAG"]=0;
			else $params["NUMPAG"]=max(intval(sprintf("%d",$argv[$params["ARGV_PAG"]]))-1,0);
			if(!isset($params["OFFSET"])) {
				$params["OFFSET"]=$params["NUMPAG"]*$params["LIMIT"];
			} elseif($params["OFFSET"]=="SEQ") {
				if($params["NUMROWS"]>1) {
					$key=md5($params["QUERY"]);
					$seq=intval(sessions("GET $key"));
					$seq=($seq+1)%$params["NUMROWS"];
					sessions("SET $key $seq");
				} else {
					$seq=0;
				}
				$params["OFFSET"]=$seq;
			} elseif(in_array($params["OFFSET"],array("RND","RAND","RANDOM"))) {
				if($params["NUMROWS"]>$params["LIMIT"]) {
					$key=md5($params["QUERY"]);
					$old=intval(sessions("GET $key"));
					$rnd=$old;
					srand((float)microtime(true)*10000000);
					while($rnd==$old) $rnd=rand(0,$params["NUMROWS"]-$params["LIMIT"]);
					sessions("SET $key $rnd");
				} else {
					$rnd=0;
				}
				$params["OFFSET"]=$rnd;
			}
			$params["NUM_REG_BEGIN"]=$params["OFFSET"]+1;
			$params["NUM_REG_END"]=min($params["OFFSET"]+$params["LIMIT"],$params["NUMROWS"]);
			$params["NUM_PAG_CURRENT"]=intval($params["OFFSET"]/$params["LIMIT"])+1;
			$params["NUM_PAG_FIRST"]=1;
			$params["NUM_PAG_PREV"]=max($params["NUM_PAG_CURRENT"]-1,1);
			$params["NUM_PAG_LAST"]=intval(($params["NUMROWS"]+$params["LIMIT"]-1)/$params["LIMIT"]);
			$params["NUM_PAG_NEXT"]=min($params["NUM_PAG_CURRENT"]+1,$params["NUM_PAG_LAST"]);
			$params["USE_PAG"]=$params["USEPAG"];
			$params["USE_PAG_PREV"]=$params["NUM_PAG_CURRENT"]>1?1:0;
			$params["USE_PAG_NEXT"]=$params["NUM_PAG_CURRENT"]<$params["NUM_PAG_LAST"]?1:0;
		}
	} elseif($proc=="QUERY") {
		if(isset($params["XML"])) {
			$temp="";
			if(isset($params["LIMIT"]) && isset($params["OFFSET"])) $temp.="[position()<=".($params["LIMIT"]+$params["OFFSET"])."]";
			if(isset($params["LIMIT"]) && !isset($params["OFFSET"])) $temp.="[position()<=".$params["LIMIT"]."]";
			if(isset($params["OFFSET"])) $temp.="[position()>".$params["OFFSET"]."]";
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".$params["XML"]." ".__TAG2__);
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".$params["QUERY"].$temp." ".__TAG2__);
			include_once("admin/database/xml.php");
			$params["__QUERY__"]=$params["XML"]."|".$params["QUERY"].$temp;
			$result=dbQuery_xml($params["__QUERY__"]);
			_dbapp2_set_xml_db();
		} else {
			$temp="";
			if(isset($params["LIMIT"])) $temp.=" LIMIT ".$params["LIMIT"];
			if(isset($params["LIMIT"]) && isset($params["OFFSET"])) $temp.=" OFFSET ".$params["OFFSET"];
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".$params["QUERY"].$temp." ".__TAG2__);
			$params["__QUERY__"]=$params["QUERY"].$temp;
			$result=dbQuery($params["__QUERY__"]);
			_dbapp2_set_normal_db();
			$params["__HASH__"]=md5($params["__QUERY__"]);
			$hash=$params["__HASH__"];
			$numfields=$_dbapp2_dbNumFields($result);
			for($j=0;$j<$numfields;$j++) {
				$val=$_dbapp2_dbFieldType($result,$j);
				if(in_array($val,array("time","date"))) {
					$key=$_dbapp2_dbFieldName($result,$j);
					$params["__".$hash."_".$key."__"]=$val;
				}
			}
		}
		if(has_parameter_tag($buffers[__BUF_TD_DATA__],__TAG1__,"DINAMICS",__TAG2__)) {
			$params["__DINAMICS__"]=$buffers[__BUF_DINAMICS__];
		}
		if(has_parameter_tag($buffers[__BUF_TD_DATA__],__TAG1__,"NEXT_ROW",__TAG2__)) {
			$buffer=$buffers[__BUF_TD_DATA__];
			$buffers[__BUF_TD_DATA__]=array();
			$ini=0;
			list($pos,$tag,$pos2)=content_strpos($buffer,__TAG1__,"NEXT_ROW",__TAG2__,$ini);
			while($pos!==false) {
				$buffers[__BUF_TD_DATA__][]=content_line(substr($buffer,$ini,$pos-$ini));
				$ini=$pos2;
				list($pos,$tag,$pos2)=content_strpos($buffer,__TAG1__,"NEXT_ROW",__TAG2__,$ini);
			}
			$buffers[__BUF_TD_DATA__][]=content_line(substr($buffer,$ini));
		}
		$num_trs=0;
		$num_tds=0;
		while($row1=$_dbapp2_dbFetchRow($result)) {
			$row1=_dbapp2_remove_void_dinamics($row1);
			if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." ".print_r($row1,true)." ".__TAG2__);
			if($num_tds==0) {
				_dbapp2_parser($params,$buffers[__BUF_TR_DATA1__],$row1,$row2);
				$num_trs++;
			}
			if(is_array($buffers[__BUF_TD_DATA__])) {
				$cur_div=$num_tds%count($buffers[__BUF_TD_DATA__]);
				_dbapp2_parser($params,$buffers[__BUF_TD_DATA__][$cur_div],$row1,$row2);
			} else {
				_dbapp2_parser($params,$buffers[__BUF_TD_DATA__],$row1,$row2);
			}
			$num_tds=($num_tds+1)%(isset($params["NUM_TD"])?$params["NUM_TD"]:1);
			if($num_tds==0) {
				_dbapp2_parser($params,$buffers[__BUF_TR_DATA2__],$row1,$row2);
			}
		}
		$_dbapp2_dbFree($result);
		if($num_tds!=0) {
			while($num_tds!=0) {
				if(isset($buffers[__BUF_TD_NULL__])) {
					echo_buffer($buffers[__BUF_TD_NULL__]);
				}
				$num_tds=($num_tds+1)%(isset($params["NUM_TD"])?$params["NUM_TD"]:1);
			}
			_dbapp2_parser($params,$buffers[__BUF_TR_DATA2__],$row1,$row2);
		}
		if(isset($buffers[__BUF_TR_NULL__])) {
			while($num_trs<(isset($params["NUM_TR"])?$params["NUM_TR"]:1)) {
				echo_buffer($buffers[__BUF_TR_NULL__]);
				$num_trs++;
			}
		}
		unset($params["__DINAMICS__"]);
	} elseif($proc=="PAG") {
		$cache=5;
		for($j=0;$j<$params["NUMPAGS"];$j++) {
			$print1=($j>=$params["NUMPAG"]-$cache+1 && $j<=$params["NUMPAG"]+$cache-1);
			$print2=($params["NUMPAG"]<$cache-1 && $j<$cache*2-1);
			$print3=($params["NUMPAG"]>$params["NUMPAGS"]-$cache && $j>$params["NUMPAGS"]-$cache*2);
			if($print1 || $print2 || $print3) {
				if($j+1<$params["NUM_PAG_CURRENT"]) $temp=__BUF_PAG_PREV__;
				elseif($j+1==$params["NUM_PAG_CURRENT"]) $temp=__BUF_PAG_CURRENT__;
				else $temp=__BUF_PAG_NEXT__;
				if(!isset($buffers[$temp])) $temp=__BUF_PAG_CURRENT__;
				if(empty($buffers[$temp])) $temp=__BUF_PAG_CURRENT__;
				$params["NUM_PAG"]=$j+1;
				_dbapp2_parser($params,$buffers[$temp],$row0,$row2);
			}
		}
		unset($params["NUM_PAG"]);
	}
}

function _dbapp2_parser(&$params,$buffer,$row=array(),$row2=array()) {
	global $_dbapp2_dbNumRows;
	global $_dbapp2_dbFree;
	global $_dbapp2_dbFetchRow;
	global $_dbapp2_dbNumFields;
	global $_dbapp2_dbFieldName;
	global $_dbapp2_dbFieldType;

	$counter=_dbapp2_if_counter();
	$include_if=0;
	$include_buffer="";
	$ini=0;
	list($pos,$tag,$pos2)=content_strpos($buffer,__TAG1__,"",__TAG2__,$ini);
	while($pos!==false) {
		$temp=content_line(substr($buffer,$ini,$pos-$ini));
		if(!$include_if) echo_buffer($temp);
		else $include_buffer.=$temp;
		$cmd=strtok(trim($tag)," ");
		if($cmd=="BEGININCLUDE") {
			$include_if=1;
		} elseif($cmd=="ENDINCLUDE") {
			dbapp2("__BACKUP__",$row);
			content_include($include_buffer);
			dbapp2("__RESTORE__");
			$include_if=0;
			$include_buffer="";
		} elseif($include_if) {
			$include_buffer.=__TAG1__." ".$tag." ".__TAG2__;
		} elseif($cmd=="INCLUDE") {
			content_include(__TAG1__." ".$tag." ".__TAG2__);
		} elseif($cmd=="PRINT") {
			$param=explode(" ",trim(strtok("")));
			if(isset($params["__ALIAS__"])) foreach($params["__ALIAS__"] as $alias) $param[0]=str_replace($alias[0],$alias[1],$param[0]);
			$temp=(string)_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
			$temp=str_replace("\\'","'",$temp);
			$no_convert_date=0;
			$no_convert_time=0;
			$cut_options=array("delim"=>"","field"=>"");
			$count_param=count($param);
			for($j=1;$j<$count_param;$j++) {
				if($param[$j]=="PREVIEW") {
					$j++;
					$temp=html_entity_decode($temp,ENT_COMPAT,"UTF-8");
					$temp=strip_tags($temp);
					$len=strlen($temp);
					if($len>$param[$j]) {
						while($param[$j]<$len && $temp[$param[$j]]!=" ") $param[$j]++;
						if($param[$j]<$len) $temp=substr($temp,0,$param[$j])."&hellip;";
					}
				} elseif($param[$j]=="CUT") {
					$j++;
					$temp=html_entity_decode($temp,ENT_COMPAT,"UTF-8");
					$temp=strip_tags($temp);
					$len=mb_strlen($temp,"UTF-8");
					if($len>$param[$j]) $temp=mb_substr($temp,0,$param[$j],"UTF-8")."&hellip;";
				} elseif($param[$j]=="EVAL") {
					$j++;
					$myfunction=strtolower($param[$j]);
					if($myfunction=="content") $temp=$myfunction($temp,false);
					if($myfunction!="content") $temp=$myfunction($temp);
				} elseif($param[$j]=="ENCODE") {
					$temp=encode($temp);
				} elseif($param[$j]=="ADD_SLASHES") {
					$temp=addslashes($temp);
				} elseif($param[$j]=="STRIP_SLASHES") {
					$temp=stripslashes($temp);
				} elseif($param[$j]=="STRTOLOWER") {
					$temp=mb_strtolower($temp,"UTF-8");
				} elseif($param[$j]=="STRTOUPPER") {
					$temp=mb_strtoupper($temp,"UTF-8");
				} elseif($param[$j]=="UCFIRST") {
					$temp=ucfirst($temp);
				} elseif($param[$j]=="UCWORDS") {
					$temp=ucwords($temp);
				} elseif($param[$j]=="REMOVE_EXT") {
					$temp=explode(".",$temp);
					$count=count($temp);
					if($count>1) unset($temp[$count-1]);
					$temp=implode(".",$temp);
				} elseif($param[$j]=="HTML_ENTITY_ENCODE") {
					$temp=htmlentities($temp,ENT_COMPAT,"UTF-8");
				} elseif($param[$j]=="HTML_ENTITY_DECODE") {
					$temp=html_entity_decode($temp,ENT_COMPAT,"UTF-8");
				} elseif($param[$j]=="UTF8_ENCODE") {
					$temp=utf8_encode($temp);
				} elseif($param[$j]=="UTF8_DECODE") {
					$temp=utf8_decode($temp);
				} elseif($param[$j]=="BASE64_ENCODE") {
					$temp=base64_encode($temp);
				} elseif($param[$j]=="BASE64_DECODE") {
					$temp=base64_decode($temp);
				} elseif($param[$j]=="BASE64_ENCODE_URL") {
					$temp=base64_encode_url($temp);
				} elseif($param[$j]=="BASE64_DECODE_URL") {
					$temp=base64_decode_url($temp);
				} elseif($param[$j]=="RAWURLENCODE") {
					$temp=rawurlencode($temp);
				} elseif($param[$j]=="RAWURLDECODE") {
					$temp=rawurldecode($temp);
				} elseif($param[$j]=="XML_ENCODE") {
					$temp=html_entity_decode($temp,ENT_COMPAT,"UTF-8");
					$temp=strip_tags($temp);
					$temp=str_replace("&","&amp;",$temp);
				} elseif($param[$j]=="NO_CONVERT_DATE") {
					$no_convert_date=1;
				} elseif($param[$j]=="NO_CONVERT_TIME") {
					$no_convert_time=1;
				} elseif($param[$j]=="CONVERT_DATE_XML") {
					$temp=_dbapp2_convert_date_xml($temp);
					$no_convert_date=1;
				} elseif($param[$j]=="CONVERT_DATE_TXT") {
					$temp=_dbapp2_convert_date_txt($temp,get_lang());
					$no_convert_date=1;
				} elseif($param[$j]=="CONVERT_DATE_MONTH_YEAR") {
					$temp=_dbapp2_convert_date_month_year($temp,get_lang());
					$no_convert_date=1;
				} elseif($param[$j]=="CONVERT_DATE_GOOGLE") {
					$temp=_dbapp2_convert_date_google($temp);
					$no_convert_date=1;
				} elseif($param[$j]=="CONVERT_DATE") {
					$temp=_dbapp2_convert_date($temp);
					$no_convert_date=1;
				} elseif($param[$j]=="CONVERT_TIME") {
					$temp=substr($temp,0,5);
					$no_convert_time=1;
				} elseif($param[$j]=="REPARE_GOOGLE_MAPS") {
					$temp=str_replace(array("\r","\n"),"",$temp);
					$temp=str_replace("\"","\\\"",$temp);
					$temp=str_replace("'","\\'",$temp);
				} elseif($param[$j]=="REPARE_W3C_BUG") {
					// regular expresion
					$expr1="[a-zA-Z0-9\"'-=:\(\)#_ ]*";
					$expr2="[\r\n\t ]*";
					$expr3="[a-zA-Z0-9-=:\(\)#_ ]*";
					// for p tags
					$temp=preg_replace("/<p>|<p $expr1>/","",$temp);
					$temp=preg_replace("/<\/p>/","<br/>",$temp);
					// for html comments
					$temp=preg_replace("/<!--(.|\s)*?-->/","",$temp);
					// for ul tags
					$temp=preg_replace("/<ul>$expr2<\/ul>/","",$temp);
					$temp=preg_replace("/<\/li>$expr2<ul>/","<ul>",$temp);
					$temp=preg_replace("/<\/ul>$expr2<li>/","</ul></li><li>",$temp);
					// for ol tags
					$temp=preg_replace("/<ol>$expr2<\/ol>/",'',$temp);
					$temp=preg_replace("/<\/li>$expr2<ol>/","<ol>",$temp);
					$temp=preg_replace("/<\/ol>$expr2<li>/","</ol></li><li>",$temp);
					// for target tags
					$temp=preg_replace("/target=\"_blank\"/","onclick=\"window.open(this);return false;\"",$temp);
					$temp=preg_replace("/target=\"$expr3\"/","",$temp);
					// for classname, class and name tags
					$temp=preg_replace("/classname=\"$expr3\"/","",$temp);
					$temp=preg_replace("/class=\"$expr3\"/","",$temp);
					$temp=preg_replace("/name=\"$expr3\"/","",$temp);
					$temp=preg_replace("/type=\"$expr3\"/","",$temp);
					// for old br tags
					$temp=preg_replace("/<br>/i","<br/>",$temp);
				} elseif($param[$j]=="DELIM") {
					$cut_options["delim"]=$param[++$j];
				} elseif($param[$j]=="FIELD") {
					$cut_options["field"]=$param[++$j];
				}
			}
			if(isset($params["__HASH__"])) {
				$hash=$params["__HASH__"];
				if(isset($params["__".$hash."_".$param[0]."__"])) {
					if($params["__".$hash."_".$param[0]."__"]=="date") {
						if(!$no_convert_date) $temp=_dbapp2_convert_date($temp);
					}
					if($params["__".$hash."_".$param[0]."__"]=="time") {
						if(!$no_convert_time) $temp=substr($temp,0,5);
					}
				}
			}
			if($cut_options["delim"]!="" && $cut_options["field"]!="") {
				$temp2=explode($cut_options["delim"],$temp);
				if(isset($temp2[$cut_options["field"]-1])) $temp=$temp2[$cut_options["field"]-1];
			}
			echo_buffer($temp);
		} elseif($cmd=="IMAGE") {
			$param=explode(" ",trim(strtok("")));
			if(isset($params["__ALIAS__"])) foreach($params["__ALIAS__"] as $alias) $param[0]=str_replace($alias[0],$alias[1],$param[0]);
			$name=_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
			$width="";
			$height="";
			$id="";
			$class="";
			$effects="";
			$format="jpeg";
			$title="";
			$print=0;
			$text="";
			$alt="";
			$dir="img";
			$count_param=count($param);
			for($j=1;$j<$count_param;$j++) {
				if($param[$j]=="WIDTH") $width=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="HEIGHT") $height=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="ID") $id=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="CLASS") $class.=(($class!="")?" ":"")._dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="FORMAT") $format=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="FAR") $effects.="far/";
				elseif($param[$j]=="IAR") $effects.="iar/";
				elseif($param[$j]=="ZC") $effects.="zc/";
				elseif($param[$j]=="GRAY") $effects.="gray/";
				elseif(in_array($param[$j],array("SEP","SEPIA"))) $effects.="sep/";
				elseif($param[$j]=="TITLE") $title=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="ALT") $alt=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="DIR") $dir=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="PRINT") $print=1;
				elseif($param[$j]=="RIC"
					|| $param[$j]=="OVER"
					|| in_array($param[$j],array("BACKGROUNDCOLOR","BACKGROUND","BGCOLOR","BG"))
					|| in_array($param[$j],array("FOREGROUNDCOLOR","FOREGROUND","FGCOLOR","FG"))
					|| $param[$j]=="CLR"
					|| in_array($param[$j],array("FONT","FN"))
					|| in_array($param[$j],array("SIZE","SZ"))
					|| $param[$j]=="ALIGN"
					|| $param[$j]=="OPACITY"
					|| $param[$j]=="MARGIN"
					|| in_array($param[$j],array("ROTATE","ROT"))) {
					$param[$j+1]=_dbapp2_replace($param[$j+1],array_merge($row,$params),$row2,"ROW");
					$effects.=_dbapp2_effects($param[$j],$param[$j+1]);
					$j++;
				}
				elseif(in_array($param[$j],array("TEXT","TXT","LABEL","CAPTION"))) {
					$text=_dbapp2_replace($param[$j+1],array_merge($row,$params),$row2,"ROW");
					$effects.=_dbapp2_effects($param[$j],$text,true);
					$j++;
				}
				elseif($param[$j]=="EVAL") {
					$j++;
					$myfunction=strtolower($param[$j]);
					$name=$myfunction($name);
				}
			}
			$temp="";
			if($name!="") {
				$name=str_replace(" ","+",$name);
				$name=explode(".",$name);
				$name[count($name)-1]=$format;
				$name=implode(".",$name);
				if($title!="") $real=encode($title).".".$format;
				elseif($alt!="") $real=encode($alt).".".$format;
				elseif($text!="") $real=encode($text).".".$format;
				else $real=$name;
				if(substr($param[0],-1,1)=="]") {
					$fixfile=$param[0];
					$param[0]=substr($param[0],0,-1)."_file]";
				} else {
					$param[0].="_file";
				}
				$file=_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
				if(isset($fixfile)) {
					global $plantillas;
					if(!file_exists("${plantillas}/${dir}/${file}") && !file_exists("admin/files/${file}")) {
						$param[0]=$fixfile."_file";
						$file=_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
					}
				}
				$size="original";
				if($width && $height) $size=$width."x".$height;
				// TRICK FOR GIF
				if(pathinfo($file,PATHINFO_EXTENSION)=="gif") {
					$real=pathinfo($real,PATHINFO_FILENAME).".gif";
				}
				// TRICK FOR WEBP
				if(pathinfo($real,PATHINFO_EXTENSION)=="webp") {
					if(!isset($_SERVER["HTTP_ACCEPT"]) || strpos($_SERVER["HTTP_ACCEPT"],"image/webp")===false) {
						$real=pathinfo($real,PATHINFO_FILENAME).".jpeg";
					}
				}
				// CONTINUE
				if($print) {
					$temp="${dir}/${file}/${size}/${effects}${real}";
				} else {
					if($id!="") $id="id='${id}'";
					if($class!="") $class="class='${class}'";
					$temp="<img src='${dir}/${file}/${size}/${effects}${real}' alt=\"${alt}\" title=\"${title}\" ${id} ${class} />";
				}
			} else {
				if(checkDebug("DEBUG_DBAPP2")) $temp=__TAG1__." WARNING: INCLUDE DBAPP2 IMAGE ".implode(" ",$param).": NOT FOUND ".__TAG2__;
			}
			echo_buffer($temp);
		} elseif($cmd=="FILE") {
			$param=explode(" ",trim(strtok("")));
			if(isset($params["__ALIAS__"])) foreach($params["__ALIAS__"] as $alias) $param[0]=str_replace($alias[0],$alias[1],$param[0]);
			$name=_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
			$icon=0;
			$opdw="open";
			$print=0;
			$id="";
			$class="";
			$image="";
			$dir="img";
			$count_param=count($param);
			for($j=1;$j<$count_param;$j++) {
				if($param[$j]=="ICON") $icon=1;
				elseif($param[$j]=="TEXT") $icon=0;
				elseif($param[$j]=="OPEN") $opdw="open";
				elseif($param[$j]=="DOWN") $opdw="down";
				elseif($param[$j]=="PRINT") $print=1;
				elseif($param[$j]=="ID") $id=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="CLASS") $class.=(($class!="")?" ":"")._dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="IMAGE") $image=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
				elseif($param[$j]=="DIR") $dir=_dbapp2_replace($param[++$j],array_merge($row,$params),$row2,"ROW");
			}
			$temp="";
			if($name!="") {
				$name2=str_replace(" ","+",$name);
				if(substr($param[0],-1,1)=="]") {
					$fixfile=$param[0];
					$param[0]=substr($param[0],0,-1)."_file]";
				} else {
					$param[0].="_file";
				}
				$file=_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
				if(isset($fixfile)) {
					global $plantillas;
					if(!file_exists("${plantillas}/${dir}/${file}") && !file_exists("admin/files/${file}")) {
						$param[0]=$fixfile."_file";
						$file=_dbapp2_replace($param[0],array_merge($row,$params),$row2,"ROW");
					}
				}
				if($print) {
					$temp="${opdw}/${file}/${name2}";
				} else {
					if($icon) {
						$icim=($image!="")?$image:_dbapp2_icon($name);
						$temp2="<img src='${dir}/${icim}' alt=\"${name}\"/>";
					} else {
						$temp2=$name;
					}
					$temp="<a href='${opdw}/${file}/${name2}' id='${id}' class='${class}'>${temp2}</a>";
				}
			} else {
				if(checkDebug("DEBUG_DBAPP2")) $temp=__TAG1__." WARNING: INCLUDE DBAPP2 FILE ".implode(" ",$param).": NOT FOUND ".__TAG2__;
			}
			echo_buffer($temp);
		} elseif($cmd=="DINAMICS") {
			$param=trim(strtok(""));
			$datos=_dbapp2_replace($param,array_merge($row,$params),$row2,"ROW");
			if($datos!="" && $datos!="|" && $datos!="||") {
				$datos=explode("|",$datos);
				if(!isset($datos[2])) {
					$datos[0]=explode(",",$datos[0]);
					$datos[2]=array();
					for($i=0;$i<count($datos[0]);$i++) $datos[2][]="1";
					$datos[0]=implode(",",$datos[0]);
					$datos[2]=implode(",",$datos[2]);
					$default=implode("|",$datos);
				}
				$datos[0]=explode(",",$datos[0]);
				$datos[1]=explode(",",$datos[1]);
				$datos[2]=explode(",",$datos[2]);
				$grupo=0;
				$count=count($datos[0]);
				for($i=0;$i<$count;$i++) {
					$key=array();
					for($j=0;$j<$datos[2][$grupo];$j++) $key[]=$datos[1][$i+$j];
					$key=implode("+",$key);
					if(isset($params["__DINAMICS__"][$key])) {
						$buffer2=$params["__DINAMICS__"][$key];
						$params["__ALIAS__"]=array();
						for($j=0;$j<$datos[2][$grupo];$j++) {
							$params["__ALIAS__"][]=array($datos[1][$i+$j],$datos[0][$i+$j]);
							if(!isset($row[$datos[0][$i+$j]])) $row[$datos[0][$i+$j]]="";
						}
						$rowid=isset($row["id"])?__TAG1__." INCLUDE SET_GLOBAL GET[ROWID] ".$row["id"]." ".__TAG2__:"";
						_dbapp2_parser($params,$rowid.$buffer2,$row,$row2);
						unset($params["__ALIAS__"]);
					} else {
						if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." WARNING: INCLUDE DBAPP2 DINAMICS $param: BUFFER $key NOT FOUND ".__TAG2__);
					}
					$i+=$datos[2][$grupo]-1;
					$grupo++;
				}
			} else {
				if(checkDebug("DEBUG_DBAPP2")) echo_buffer(__TAG1__." WARNING: INCLUDE DBAPP2 DINAMICS $param: NOT FOUND ".__TAG2__);
			}
		} elseif(in_array($cmd,array("IF","ELIF","ELSEIF"))) {
			if($cmd=="IF") {
				$counter=_dbapp2_if_counter(1);
				$params["__ACCUMULATOR_IF_".$counter."__"]=1;
			}
			$param=strtok(" ");
			if($param=="EVAL") {
				$param=strtok("");
				$param=_dbapp2_replace($param,array_merge($row,$params),$row2,"ROW");
				capture_next_error();
				ob_start();
				$params["__VALUE_IF_".$counter."__"]=eval("if($param) return true; else return false;");
				$error1=ob_get_clean();
				$error2=get_clear_error();
				$params["__VALUE_IF_".$counter."__"]=$params["__ACCUMULATOR_IF_".$counter."__"] && $params["__VALUE_IF_".$counter."__"];
				if($params["__VALUE_IF_".$counter."__"]) $params["__ACCUMULATOR_IF_".$counter."__"]=0;
				set_output($params["__VALUE_IF_".$counter."__"],"dbapp2_if_".$counter);
			} elseif($param=="NOT") {
				$param=strtok(" ");
				if($param=="EVAL") {
					$param=strtok("");
					$param=_dbapp2_replace($param,array_merge($row,$params),$row2,"ROW");
					capture_next_error();
					ob_start();
					$params["__VALUE_IF_".$counter."__"]=eval("if($param) return false; else return true;");
					$error1=ob_get_clean();
					$error2=get_clear_error();
					$params["__VALUE_IF_".$counter."__"]=$params["__ACCUMULATOR_IF_".$counter."__"] && $params["__VALUE_IF_".$counter."__"];
					if($params["__VALUE_IF_".$counter."__"]) $params["__ACCUMULATOR_IF_".$counter."__"]=0;
					set_output($params["__VALUE_IF_".$counter."__"],"dbapp2_if_".$counter);
				} else {
					$param2=_dbapp2_replace($param,array_merge($row,$params),$row2,"ROW");
					$param=($param==$param2 && !isset($row[$param]))?"":$param2;
					$params["__VALUE_IF_".$counter."__"]=!_dbapp2_if_bool($param);
					$params["__VALUE_IF_".$counter."__"]=$params["__ACCUMULATOR_IF_".$counter."__"] && $params["__VALUE_IF_".$counter."__"];
					if($params["__VALUE_IF_".$counter."__"]) $params["__ACCUMULATOR_IF_".$counter."__"]=0;
					set_output($params["__VALUE_IF_".$counter."__"],"dbapp2_if_".$counter);
				}
			} else {
				$param2=_dbapp2_replace($param,array_merge($row,$params),$row2,"ROW");
				$param=($param==$param2 && !isset($row[$param]))?"":$param2;
				$params["__VALUE_IF_".$counter."__"]=_dbapp2_if_bool($param);
				$params["__VALUE_IF_".$counter."__"]=$params["__ACCUMULATOR_IF_".$counter."__"] && $params["__VALUE_IF_".$counter."__"];
				if($params["__VALUE_IF_".$counter."__"]) $params["__ACCUMULATOR_IF_".$counter."__"]=0;
				set_output($params["__VALUE_IF_".$counter."__"],"dbapp2_if_".$counter);
			}
		} elseif($cmd=="ELSE") {
			set_output($params["__ACCUMULATOR_IF_".$counter."__"],"dbapp2_if_".$counter);
		} elseif($cmd=="ENDIF") {
			set_output(1,"dbapp2_if_".$counter);
			$counter=_dbapp2_if_counter(-1);
		} else {
			echo_buffer(__TAG1__." ".$tag." ".__TAG2__);
		}
		$ini=$pos2;
		list($pos,$tag,$pos2)=content_strpos($buffer,__TAG1__,"",__TAG2__,$ini);
	}
	echo_buffer(content_line(substr($buffer,$ini)));
}

function _dbapp2_effects($key,$val,$base64=false) {
	$result="";
	if($val!="") {
		$val=str_replace("\\'","'",$val);
		if($base64) $val=base64_encode_url($val);
		$result=strtolower($key)."/".$val."/";
	}
	return $result;
}

function _dbapp2_if_bool($param) {
	if($param===false) return 0;
	$param=strtolower(trim($param));
	if($param=="") return 0;
	if($param=="false") return 0;
	if($param=="0") return 0;
	if($param=="0%") return 0;
	if($param=="0&euro;") return 0;
	if($param=="0.00") return 0;
	if($param=="0.00%") return 0;
	if($param=="0.00&euro;") return 0;
	if($param=="0,00") return 0;
	if($param=="0,00&euro;") return 0;
	if($param=="00:00") return 0;
	if($param=="00:00:00") return 0;
	if($param=="00/00/0000") return 0;
	if($param=="0000-00-00") return 0;
	return 1;
}

function _dbapp2_replace($cad1,$row1=array(),$row2=array(),$alias="") {
	static $stack=array();
	static $config=null;
	// CHECK IF IS NEEDED TO PARSE IT
	if(strpos($cad1,"[")!==false && strpos($cad1,"]")!==false) {
		// CHECK IF EXIST THE CONFIG
		if($config===null) {
			$config=array();
			if(table_exists("tbl_config")) {
				$query="SELECT * FROM tbl_config";
				$result=dbQuery($query);
				while($row=dbFetchRow($result)) $config[$row["param"]]=$row["value"];
				dbFree($result);
			}
		}
		// COMPUTE THE CACHE HASH
		$argv=get_argv();
		$cache_argv=md5("argv".serialize($argv));
		$cache_config=md5("config".serialize($config));
		$cache_session=md5("session".serialize($_SESSION));
		$cache_get=md5("get".serialize($_GET));
		$cache_post=md5("post".serialize($_POST));
		$cache_row2=md5("row2".serialize($row2).$alias);
		$cache=md5($cache_argv.$cache_config.$cache_session.$cache_get.$cache_post.$cache_row2);
		// CHECK IF EXIST OR CREATE IT
		if(!isset($stack[$cache])) {
			if(!isset($stack[$cache_argv])) {
				$expr1_argv=array();
				$expr2_argv=array();
				foreach($argv as $key=>$val) {
					$expr1_argv[]="ARGV[$key]";
					$expr2_argv[]=getString($val);
				}
				$stack[$cache_argv]["expr1"]=$expr1_argv;
				$stack[$cache_argv]["expr2"]=$expr2_argv;
			}
			if(!isset($stack[$cache_config])) {
				$expr1_config=array();
				$expr2_config=array();
				foreach($config as $key=>$val) {
					$expr1_config[]="CONFIG[$key]";
					$expr2_config[]=$val;
				}
				$stack[$cache_config]["expr1"]=$expr1_config;
				$stack[$cache_config]["expr2"]=$expr2_config;
			}
			if(!isset($stack[$cache_session])) {
				$expr1_session=array();
				$expr2_session=array();
				foreach($_SESSION as $key=>$val) {
					if(!is_array($val)) {
						$expr1_session[]="SESSION[$key]";
						$expr2_session[]=$val;
					}
				}
				$stack[$cache_session]["expr1"]=$expr1_session;
				$stack[$cache_session]["expr2"]=$expr2_session;
			}
			if(!isset($stack[$cache_post])) {
				$expr1_post=array();
				$expr2_post=array();
				foreach($_POST as $key=>$val) {
					if(!is_array($val)) {
						$expr1_post[]="POST[$key]";
						$expr2_post[]=getString($val);
					}
				}
				$stack[$cache_post]["expr1"]=$expr1_post;
				$stack[$cache_post]["expr2"]=$expr2_post;
			}
			if(!isset($stack[$cache_get])) {
				$expr1_get=array();
				$expr2_get=array();
				foreach($_GET as $key=>$val) {
					if(!is_array($val)) {
						$expr1_get[]="GET[$key]";
						$expr2_get[]=getString($val);
					}
				}
				$stack[$cache_get]["expr1"]=$expr1_get;
				$stack[$cache_get]["expr2"]=$expr2_get;
			}
			if(!isset($stack[$cache_row2])) {
				$expr1_row2=array();
				$expr2_row2=array();
				if($alias!="") {
					foreach($row2 as $key=>$val) {
						if(!is_array($val)) {
							$expr1_row2[]="${alias}[${key}]";
							$expr2_row2[]=$val;
						}
					}
				} else {
					foreach($row2 as $key=>$val) {
						if(!is_array($val) && strpos($key,"[")!==false && strpos($key,"]")!==false) {
							$expr1_row2[]=$key;
							$expr2_row2[]=$val;
						}
					}
				}
				$stack[$cache_row2]["expr1"]=$expr1_row2;
				$stack[$cache_row2]["expr2"]=$expr2_row2;
			}
			$expr1=array_merge($stack[$cache_argv]["expr1"],$stack[$cache_config]["expr1"],$stack[$cache_session]["expr1"],$stack[$cache_get]["expr1"],$stack[$cache_post]["expr1"],$stack[$cache_row2]["expr1"]);
			$expr2=array_merge($stack[$cache_argv]["expr2"],$stack[$cache_config]["expr2"],$stack[$cache_session]["expr2"],$stack[$cache_get]["expr2"],$stack[$cache_post]["expr2"],$stack[$cache_row2]["expr2"]);
			$stack[$cache]["expr1"]=$expr1;
			$stack[$cache]["expr2"]=$expr2;
		} else {
			$expr1=$stack[$cache]["expr1"];
			$expr2=$stack[$cache]["expr2"];
		}
		// NORMAL PROCESS
		$cad2=$cad1;
		$cad3="";
		while($cad3!=($cad2=str_replace($expr1,$expr2,$cad2))) $cad3=$cad2;
		// LIMPIAR RESULTADO
		$expr3="[a-zA-Z0-9#_ -]*";
		$cad3=preg_replace("/ARGV\[$expr3\]/","",$cad3);
		$cad3=preg_replace("/CONFIG\[$expr3\]/","",$cad3);
		$cad3=preg_replace("/SESSION\[$expr3\]/","",$cad3);
		$cad3=preg_replace("/POST\[$expr3\]/","",$cad3);
		$cad3=preg_replace("/GET\[$expr3\]/","",$cad3);
		if($alias!="") $cad3=preg_replace("/$alias\[$expr3\]/","",$cad3);
	} else {
		$cad3=$cad1;
	}
	// RESOLVE THE CONTENT IF EXISTS AND RETURN
	if(array_key_exists($cad3,$row1)) $cad3=$row1[$cad3];
	return $cad3;
}

function _dbapp2_icon($file) {
	$sufix=strtolower(substr($file,-3,3));
	if($sufix=="xls") return "dbapp2-icon-xls.png";
	if($sufix=="ppt") return "dbapp2-icon-ppt.png";
	if($sufix=="doc") return "dbapp2-icon-doc.png";
	if($sufix=="odt") return "dbapp2-icon-doc.png";
	if($sufix=="pdf") return "dbapp2-icon-pdf.png";
	if($sufix=="bmp") return "dbapp2-icon-img.png";
	if($sufix=="gif") return "dbapp2-icon-img.png";
	if($sufix=="jpg") return "dbapp2-icon-img.png";
	if($sufix=="tif") return "dbapp2-icon-img.png";
	if($sufix=="png") return "dbapp2-icon-img.png";
	if($sufix=="mdb") return "dbapp2-icon-mdb.png";
	if($sufix=="zip") return "dbapp2-icon-zip.png";
	if($sufix=="tar") return "dbapp2-icon-zip.png";
	if($sufix=="tgz") return "dbapp2-icon-zip.png";
	if($sufix==".gz") return "dbapp2-icon-zip.png";
	$sufix=strtolower(substr($file,-4,4));
	if($sufix=="tiff") return "dbapp2-icon-img.png";
	if($sufix=="jpeg") return "dbapp2-icon-img.png";
	$prefix=strtolower(substr($file,0,6));
	if($prefix=="ftp://") return "dbapp2-icon-ftp.png";
	$prefix=strtolower(substr($file,0,7));
	if($prefix=="http://") return "dbapp2-icon-url.png";
	$prefix=strtolower(substr($file,0,8));
	if($prefix=="https://") return "dbapp2-icon-url.png";
	return "dbapp2-icon-unk.png";
}

function _dbapp2_set_xml_db() {
	global $_dbapp2_dbNumRows;
	global $_dbapp2_dbFree;
	global $_dbapp2_dbFetchRow;
	global $_dbapp2_dbNumFields;
	global $_dbapp2_dbFieldName;
	global $_dbapp2_dbFieldType;
	$_dbapp2_dbNumRows="dbNumRows_xml";
	$_dbapp2_dbFree="dbFree_xml";
	$_dbapp2_dbFetchRow="dbFetchRow_xml";
	$_dbapp2_dbNumFields="dbNumFields_xml";
	$_dbapp2_dbFieldName="dbFieldName_xml";
	$_dbapp2_dbFieldType="dbFieldType";
}

function _dbapp2_set_normal_db() {
	global $_dbapp2_dbNumRows;
	global $_dbapp2_dbFree;
	global $_dbapp2_dbFetchRow;
	global $_dbapp2_dbNumFields;
	global $_dbapp2_dbFieldName;
	global $_dbapp2_dbFieldType;
	$_dbapp2_dbNumRows="dbNumRows";
	$_dbapp2_dbFree="dbFree";
	$_dbapp2_dbFetchRow="dbFetchRow";
	$_dbapp2_dbNumFields="dbNumFields";
	$_dbapp2_dbFieldName="dbFieldName";
	$_dbapp2_dbFieldType="dbFieldType";
}

function _dbapp2_if_counter($incr=0) {
	static $counter=0;
	$counter+=$incr;
	return $counter;
}

function _dbapp2_remove_void_dinamics($row) {
	$dinamics=array();
	foreach($row as $key=>$val) {
		if(isset($row[$key."_count"]) && isset($row[$key."_data_0"])) $dinamics[]=$key;
	}
	foreach($dinamics as $dinamic) {
		$len=strlen($dinamic);
		foreach($row as $key=>$val) {
			if(substr($key,0,$len+6)==$dinamic."_count") {
				unset($row[$key]);
			} elseif(substr($key,0,$len+6)==$dinamic."_data_") {
				if(substr($key,-5,5)=="_size") {
					if($val=="0") unset($row[$key]);
				} else {
					if($val=="") unset($row[$key]);
				}
			}
		}
	}
	return $row;
}

function _dbapp2_convert_date($date) {
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);
	$newdate=$day."/".$month."/".$year;
	return $newdate;
}

function _dbapp2_convert_date_xml($date) {
	$str_format="D, d M Y H:i:s";
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);
	$timestamp=mktime(12,0,0,$month,$day,$year);
	$result=date($str_format,$timestamp)." GMT";
	return $result;
}

function _dbapp2_convert_date_google($date) {
	$str_format="Y-m-d";
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);
	$timestamp=mktime(12,0,0,$month,$day,$year);
	$result=date($str_format,$timestamp);
	return $result;
}

function _dbapp2_convert_date_txt($date,$lang="es") {
	$d["es"]=array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
	$m["es"]=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$d["ca"]=array("Diumenge","Dilluns","Dimarts","Dimecres","Dijous","Divendres","Dissabte");
	$m["ca"]=array("Gener","Febrer","Mar&ccedil;","Abril","Maig","Juny","Juliol","Agost","Setembre","Octubre","Novembre","Desembre");
	$d["en"]=array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
	$m["en"]=array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$d["fr"]=array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
	$m["fr"]=array("Janvier", "F&eacute;vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Ao&ucirc;t", "Septembre", "Octobre", "Novembre", "D&eacute;cembre");
	if(!isset($d[$lang])) $lang="es";
	$dias=$d[$lang];
	$meses=$m[$lang];
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);
	$timestamp=mktime(12,0,0,$month,$day,$year);
	$dia=date("w",$timestamp);
	$valor=$dias[$dia]." ".intval($day)." de ".$meses[$month-1]." ".$year;
	return $valor;
}

function _dbapp2_convert_date_month_year($date,$lang="es") {
	$m["es"]=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$m["ca"]=array("Gener","Febrer","Mar&ccedil;","Abril","Maig","Juny","Juliol","Agost","Setembre","Octubre","Novembre","Desembre");
	$m["en"]=array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$m["fr"]=array("Janvier", "F&eacute;vrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Ao&ucirc;t", "Septembre", "Octobre", "Novembre", "D&eacute;cembre");
	if(!isset($m[$lang])) $lang="es";
	$meses=$m[$lang];
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$valor=$meses[$month-1]." ".$year;
	return $valor;
}
?>