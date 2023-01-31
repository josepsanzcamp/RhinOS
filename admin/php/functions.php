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
function convert_date($date) {
	$year=substr($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);
	$newdate=$day."/".$month."/".$year;
	return $newdate;
}

function invert_date($date) {
	$day=substr($date,0,2);
	$month=substr($date,3,2);
	$year=substr($date,6,4);
	$newdate="$year-$month-$day";
	return $newdate;
}

function get_date() {
	return date("d/m/Y",time());
}

function get_time() {
	return date("H:i:s",time());
}

function opentable($param="") {
	$width="";
	if(strpos($param,"width=")===false) $width="width='100%'";
	echo "<table cellspacing='0' cellpadding='0' $width border='0' $param>\n";
}

function opentr($tropt="") {
	echo "<tr $tropt>\n";
}

function escribetd($texto="&nbsp;",$tdopt="") {
	echo "<td $tdopt nowrap>$texto</td>\n";
}

function closetr() {
	echo "</tr>\n";
}

function opentd($tdopt="") {
	echo "<td $tdopt>\n";
}

function closetd() {
	echo "</td>\n";
}

function closetable() {
	echo "</table>\n";
}

function openform($width="900",$id="",$div="",$iframe="",$tblopt="",$tdopt="") {
	global $_div,$_iframe;
	if($id!="") $id="id='$id'";
	if($width!="") {
		if(is_numeric($width)) $width.="px";
		$width="width='$width'";
	}
	$class="";
	if(strpos($tblopt,"class")===false) $class="class='tables'";
	echo "<tr>\n";
	echo "<td align='center' $tdopt>\n";
	$_div=$div;
	$_iframe=$iframe;
	if($div!="") echo "<div $div>\n";
	echo "<table $id $width $class $tblopt>\n";
}

function openrow($param="") {
	echo "<tr $param>\n";
}

function closerow() {
	echo "</tr>\n";
}

function putcolumn($texto="&nbsp;",$align="center",$width="",$colspan="",$class="",$javascript="") {
	global $_tds;
	global $_withtd;
	if(!isset($_tds)) $_tds="tdsh";
	if(!isset($_withtd)) $_withtd=1;
	if($width!="") {
		if(is_numeric($width)) $width.="px";
		$width="width='$width'";
	}
	if($colspan!="") $colspan="colspan='$colspan'";
	if($align!="") $align="align='$align'";
	if($class=="") $class="texts";
	if($_withtd) echo "<td $width $colspan class='$_tds $class' nowrap $javascript $align>";
	echo $texto;
	if($_withtd) echo "</td>\n";
}

function closeform($div="") {
	global $_div,$_iframe;
	echo "</table>";
	if(!isset($_div)) $_div="";
	if(!isset($_iframe)) $_iframe="";
	if($_div!="") echo "</div><iframe $_iframe></iframe>\n";
	echo "</td>\n";
	echo "</tr>\n";
}

function linea() {
	echo "<tr>\n";
	echo "<td valign='top' class='linea'></td>\n";
	echo "</tr>\n";
}

function escribe($texto="&nbsp;",$class="texts",$tdopt="",$align="center") {
	echo "<tr>\n";
	echo "<td $tdopt nowrap align='$align' class='$class'>$texto</td>\n";
	echo "</tr>\n";
}

function putiframe($page,$width="800",$height="400",$name="main",$scroll="auto",$style="") {
	echo "<tr>\n";
	if(is_numeric($width)) $width.="px";
	if(is_numeric($height)) $height.="px";
	echo "<td height='$height'>\n";
	echo "<iframe src='$page' name='$name' width='$width' height='$height' scrolling='$scroll' frameborder='0' style='$style'></iframe>\n";
	echo "</td>\n";
	echo "</tr>\n";
}

function openbody($title="",$body="") {
	global $table,$style;
	global $favicon;

	if(isset($favicon)) {
		$favicon="files/".$favicon;
		if(!file_exists($favicon)) $favicon=substr($favicon,6);
		if(!file_exists($favicon)) $favicon="img/favicon.ico";
	} else {
		$favicon="img/favicon.ico";
	}
	//echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 3.2 Final//EN'>\n";
	//echo "<html>\n";
	//echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
	//echo "<html xmlns='http://www.w3.org/1999/xhtml'>\n";
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
	echo "<html xmlns='http://www.w3.org/1999/xhtml'>\n";
	echo "<head>\n";
	echo "<title>".get_current_app()." - $title</title>\n";
	echo "<link rel='shortcut icon' href='$favicon' />\n";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>\n";
	echo "<meta name='copyright' content='".get_name_version_revision(true)."'/>\n";
	echo "<link href='css/default.css' rel='stylesheet' type='text/css'>\n";
	echo "<script language='javascript' type='text/javascript' src='js/functions.js'></script>\n";
	if(usefloatmenu()) echo "<script language='javascript' type='text/javascript' src='js/menu.js'></script>\n";
	echo "<script type='text/javascript' language='javascript' src='lib/jquery/jquery.min.js'></script>\n";
	echo "<script type='text/javascript' language='javascript' src='lib/jquery/jquery-ui.min.js'></script>\n";
	$file="lib/jquery/jquery-ui.$style/jquery-ui.min.css";
	if(file_exists("jquery-ui.$style/jquery-ui.min.css")) $file="jquery-ui.$style/jquery-ui.min.css";
	echo "<link href='{$file}' rel='stylesheet' type='text/css'>\n";
	echo "<script type='text/javascript' language='javascript' src='lib/phpjs/php.default.min.js'></script>\n";
	echo "<script language='javascript' type='text/javascript' src='js/dinamics.js'></script>\n";
	echo "<script type='text/javascript' language='javascript' src='js/default.js'></script>\n";
	echo "<script src='lib/jquery/jquery.ui.datepicker-"._LANG("lang").".js' type='text/javascript' language='javascript'></script>\n";
	echo "<link rel='stylesheet' href='lib/jquery/jquery.colorpicker.min.css' type='text/css' media='screen'>\n";
	echo "<script src='lib/jquery/jquery.colorpicker.min.js' type='text/javascript' language='javascript'></script>\n";
	echo "<script src='lib/jquery/jquery.autogrow-textarea.min.js' type='text/javascript' language='javascript'></script>\n";
	put_javascript_msgbox();
	put_javascript_var("table",$table);
	echo "<script>window.CKEDITOR_BASEPATH='lib/ckeditor/';</script>\n";
	echo "<script type='text/javascript' src='lib/ckeditor/ckeditor.js'></script>\n";
	echo "<script type='text/javascript' src='lib/ckeditor/adapters/jquery.js'></script>\n";
	echo "<script type='text/javascript'>";
	echo "$(document).ready(function() {";
	echo "  CKEDITOR.config.title='';";
	echo "  CKEDITOR.config.skin='moono-lisa';";
	echo "  CKEDITOR.config.extraPlugins='autogrow';";
	echo "  CKEDITOR.config.removePlugins='elementspath';";
	echo "  CKEDITOR.config.enterMode=CKEDITOR.ENTER_BR;";
	echo "  CKEDITOR.config.shiftEnterMode=CKEDITOR.ENTER_BR;";
	echo "  CKEDITOR.config.toolbar=[['Bold', 'Italic', 'Underline', 'Strike'], ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Link', 'Unlink'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'], ['Undo', 'Redo'], ['SelectAll', 'RemoveFormat'], ['Maximize', 'Source', 'HorizontalRule', 'Table'],['TextColor', 'BGColor']];";
	echo "  CKEDITOR.config.language='"._LANG("lang")."';";
	echo "  CKEDITOR.config.autoGrow_onStartup=true;";
	echo "  CKEDITOR.config.autoGrow_minHeight=150;";
	echo "  CKEDITOR.config.disableNativeSpellChecker=false;";
	echo "  CKEDITOR.config.resize_enabled=false;";
	//~ echo "  CKEDITOR.config.width=612;";
	//~ echo "  CKEDITOR.config.height=150;";
	//~ echo "  $('body').append('<div id=\"ui-color-ckeditor\" class=\"ui-widget-header\"></div>');";
	//~ echo "  var background=$('#ui-color-ckeditor').css('background-color');";
	//~ echo "  $('#ui-color-ckeditor').remove();";
	//~ echo "  CKEDITOR.config.uiColor=background;";
	//~ echo "  CKEDITOR.config.uiColor='transparent';";
	//~ echo "  CKEDITOR.config.forcePasteAsPlainText=true;";
	//~ echo "  CKEDITOR.config.allowedContent=true;";
	echo "})";
	echo "</script>";
	echo "</head>\n";
	echo "<body $body>\n";
	echo "<div id='loading' style='position:absolute;display:none'>".get_loading(5)."</div>\n";
	echo "<div id='dialog' style='display:none;' title=''><p></p></div>\n";
	echo "<form name='f2' method='get' action='inicio.php' style='display:none'></form>\n";
}

function put_javascript_var($key,$val) {
	echo "<script type='text/javascript'>\n";
	echo "var $key='$val';\n";
	echo "</script>\n";
}

function put_javascript_code($code) {
	echo "<script type='text/javascript'>\n";
	echo "$code\n";
	echo "</script>\n";
}

function closebody() {
	if(ismsie()) {
		initsession();
		$first=useSession("ismsie");
		useSession("ismsie","1");
		closesession();
		if(!$first) {
			$msg=_LANG("functions_closebody_ismsie");
			$msg.="<br/><br/>";
			$msg.="<a href='http://www.getfirefox.com' target='_blank'><img src='img/get_firefox.png' style='width:80px;height:15px;' /></a>";
			$msg.="&nbsp;<a href='http://www.getfirefox.com' target='_blank'>http://www.getfirefox.com</a>";
			$msg.="<br/><br/>";
			$msg.="<a href='http://www.google.com/chrome?hl=es' target='_blank'><img src='img/get_chrome.gif' style='width:80px;height:15px;' /></a>";
			$msg.="&nbsp;<a href='http://www.google.com/chrome?hl=es' target='_blank'>http://www.google.com/chrome</a>";
			msgbox($msg);
		}
	}
	echo "</body>\n";
	echo "</html>\n";
}

function puttextarea($variable="",$texto="",$form="",$ckeditor=true) {
	global $width_obj;

	if($form!="show") {
		$class=$ckeditor?"":"edits ui-state-default ui-corner-all";
		$style=$ckeditor?"":"width:600px;height:150px";
		$ckeditor=$ckeditor?"true":"false";
		putcolumn("<textarea name='{$variable}' style='$style' ckeditor='$ckeditor' class='$class'>{$texto}</textarea>","left",$width_obj,"","bigfield fix20181218");
	} else {
		if(!$ckeditor) {
			$texto=htmlentities($texto,ENT_COMPAT,"UTF-8");
			$texto=str_replace("\n","<br/>",$texto);
		}
		putcolumn("<table class='tables2' style='width:600px;height:150px;'><tr><td valign='top'><div class='texts siwrap' style='width:600px;height:150px;overflow:auto;'>".$texto."</div></td></tr></table>","left",$width_obj,"","bigfield");
	}
}

function putinput($variable,$texto,$form,$options="",$style="",$js="") {
	global $width_obj;

	$type="type='text'";
	if($style=="") $style="width:600px";
	if(substr($options,0,5)=="type=") $type="";
	if($form!="show") {
		$texto=quot_htmlentities($texto);
		putcolumn("<input style='$style' $type name='$variable' id='$variable' class='inputs ui-state-default ui-corner-all' value=\"$texto\" $options />$js","left",$width_obj);
	} else {
		putcolumn("<table class='tables2' style='$style'><tr><td><div class='texts' style='$style;overflow:hidden;'>$texto</div></td></tr></table>","left",$width_obj);
	}
}

function protectselect($query,$limit=0) {
	static $stack=array();

	$hash=md5($query);
	if(!isset($stack[$hash])) {
		$query="SELECT COUNT(*) count FROM ($query) __tbl_count__";
		$result=dbQuery($query);
		$row=dbFetchRow($result);
		$stack[$hash]=$row["count"];
		dbFree($result);
	}
	return ($limit>0)?$stack[$hash]>$limit:$stack[$hash];
}

function putselect($table,$variable,$form,$default="",$filter="1",$extra="") {
	global $width_obj;

	if($table=="") {
		if($form!="show") {
			$list_vals=explode(",",$filter);
			if($list_vals[0]=="") unset($list_vals[0]);
			$temp="<select style='width:612px;' class='inputs ui-state-default ui-corner-all' name='$variable' id='$variable'>\n";
			$sel="";
			if($default=="") $sel="selected";
			$temp.="<option value=\"\" $sel>"._LANG("functions_putselect_default_option")."</option>\n";
			$default=encode_bad_chars($default);
			foreach($list_vals as $list_val) {
				if(strpos($list_val,":")!==false) {
					$list_val=explode(":",$list_val,2);
					$buscar=encode_bad_chars($list_val[0]);
					$valor=$list_val[0];
					$texto=$list_val[1];
				} else {
					$buscar=encode_bad_chars($list_val);
					$valor=$list_val;
					$texto=$list_val;
				}
				$sel="";
				if($default==$buscar) $sel="selected";
				$valor=quot_htmlentities($valor);
				$temp.="<option value=\"$valor\" $sel>$texto</option>\n";
			}
			$temp.="</select>\n";
			putcolumn($temp,"left",$width_obj);
		} else {
			$list_vals=explode(",",$filter);
			if($list_vals[0]=="") unset($list_vals[0]);
			$default=encode_bad_chars($default);
			$valor="";
			foreach($list_vals as $list_val) {
				if(strpos($list_val,":")!==false) {
					$list_val=explode(":",$list_val,2);
					$buscar=encode_bad_chars($list_val[0]);
					$texto=$list_val[1];
				} else {
					$buscar=encode_bad_chars($list_val);
					$texto=$list_val;
				}
				if($default==$buscar) $valor=$texto;
			}
			if($valor=="") $valor=_LANG("functions_putselect_not_options");
			putinput($variable,$valor,$form,"","","");
		}
		return;
	}
	$temp=explode(".",$variable);
	if(count($temp)==3) {
		$variable=$temp[0];
		$table=$temp[1];
		$j=$temp[2];
		$postvar=".$table.$j";
	} else {
		$postvar="";
		$j="";
	}
	$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$variable'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$text_ref=$row["text_ref"];
	$table_ref=$row["table_ref"];
	$value_ref=$row["value_ref"];
	dbFree($result);
	$query="SELECT * FROM db_forms WHERE tbl='$table_ref' AND row='".addslashes($text_ref)."'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$is_photo=(isset($row["type"]) && $row["type"]=="photo");
	$is_file=(isset($row["type"]) && $row["type"]=="file");
	dbFree($result);
	$temp=explode(":",$text_ref);
	if($temp[0]=="concat") {
		unset($temp[0]);
		$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
		$text_ref=parseQuery($text_ref,getdbtype());
	}
	if($form!="show") {
		$variable2="{$variable}{$postvar}";
		$variable5=str_replace(".","_",$variable2);
		$_withtd=isset($_withtd)?$_withtd:1;
		$maximo=100;
		$filter2=is_array($filter)?$filter["filter"].$filter["extra"]:$filter;
		$query="SELECT id FROM $table_ref WHERE $filter2";
		if(protectselect($query,$maximo) && $_withtd) {
			echo "<script type='text/javascript'>\n";
			echo "function retardo1_$variable5() {\n";
			echo "    count_$variable5=1;\n";
			echo "}\n";
			echo "function retardo2_$variable5() {\n";
			echo "    count_$variable5=0;\n";
			echo "}\n";
			echo "var count_$variable5=0;\n";
			echo "function reload_$variable5() {\n";
			echo "    var temp=document.getElementById('$variable2');\n";
			echo "    var def='';\n";
			echo "    if(!isNull(temp)) def=temp.value;\n";
			echo "    var temp=document.getElementById('extra_$variable2');\n";
			echo "    var extra='';\n";
			echo "    if(!isNull(temp)) extra=trim(temp.value);\n";
			echo "    if(extra_$variable5==extra || count_$variable5!=0) {\n";
			echo "        setTimeout('reload_$variable5();',500);\n";
			echo "    } else {\n";
			echo "        extra_$variable5=extra;\n";
			echo "        var mytd=document.getElementById('td_$variable2');\n";
			echo "        mytd.innerHTML='".get_loading()."';\n";
			echo "        var arg='table=$table&variable=$variable2&default='+def+'&filter=*&extra='+extra;\n";
			echo "        $.ajax({\n";
			echo "            url:'inicio.php?include=php/getfilter.php',\n";
			echo "            data:arg,\n";
			echo "            type:'post',\n";
			echo "            dataType:'html',\n";
			echo "            success:function(data) {\n";
			echo "                var mytd=document.getElementById('td_$variable2');\n";
			echo "                mytd.innerHTML=data;\n";
			echo "                hover_events();\n";
			echo "                make_tables();\n";
			echo "                fix_max_height();\n";
			echo "                var temp=document.getElementById('extra_$variable2');\n";
			echo "                if(!isNull(temp)) temp.focus();\n";
			echo "                setTimeout('reload_$variable5();',500);\n";
			echo "            },\n";
			echo "            error:function(XMLHttpRequest,textStatus,errorThrown){\n";
			echo "                alert('Error '+XMLHttpRequest.status+': '+XMLHttpRequest.statusText);\n";
			echo "            }\n";
			echo "        });\n";
			echo "    }\n";
			echo "}\n";
			echo "var extra_$variable5='';\n";
			echo "$(document).ready(function() { reload_$variable5(); });\n";
			echo "</script>\n";
		}
		$temp="";
		if(protectselect($query,$maximo) || $extra!="") {
			if(protectselect($query,$maximo)) {
				$temp2=_LANG("functions_putselect_big_options_description");
				$temp2=str_replace("#numero#",protectselect($query),$temp2);
				list($title,$text)=make_title_text("alert",_LANG("functions_putselect_big_options_title"),$temp2,"","48");
			} elseif(protectselect($query)) {
				$temp2=_LANG("functions_putselect_ok_options_description");
				$temp2=str_replace("#numero#",protectselect($query),$temp2);
				$temp2=str_replace("#maximo#",$maximo,$temp2);
				list($title,$text)=make_title_text("button_ok",_LANG("functions_putselect_ok_options_title"),$temp2,"","48");
			} else {
				$temp2=_LANG("functions_putselect_not_options_description");
				list($title,$text)=make_title_text("button_cancel",_LANG("functions_putselect_not_options_title"),$temp2,"","48");
			}
			$js="onkeydown='retardo1_$variable5(); if(event.keyCode==13) return false;' title='$title'";
			$temp.="&nbsp;<span title='$title'>"._LANG("functions_putselect_big_options")."</span>";
			$temp.=str_replace("inputs","inputs fix20181123",getinput($table,"extra_".$variable,$extra,15,$j,0,$js));
			list($title2,$text2)=make_title_text("search",_LANG("functions_putselect_button_search"),$temp2,_LANG("functions_putselect_button_search"),"48");
			$url2="retardo2_$variable5();";
			$temp.=get_button($title2,$url2,"","","",$text2);
			if(protectselect($query,$maximo)) {
				$temp.="&nbsp;<img src='lib/crystal/16x16/alert.png' width='16px' height='16px' title='$title' />";
			} elseif(protectselect($query)) {
				$temp.="&nbsp;<img src='lib/crystal/16x16/button_ok.png' width='16px' height='16px' title='$title' />";
			} else {
				$temp.="&nbsp;<img src='lib/crystal/16x16/button_cancel.png' width='16px' height='16px' title='$title' />";
			}
			$temp.="&nbsp;<span title='$title'>(".protectselect($query)._LANG("functions_putselect_big_options_number").")</span><br/>";
		}
		if(protectselect($query,$maximo)) {
			$temp.="<select style='width:612px;' class='inputs ui-state-default ui-corner-all' name='$variable$postvar' id='$variable$postvar'>\n";
			$campos="$value_ref valor,$text_ref texto";
			$filter2=is_array($filter)?$filter["filter"]:$filter;
			$query="SELECT $campos FROM $table_ref WHERE $filter2 AND `$value_ref`='$default' AND $value_ref!='' AND $text_ref!=''";
			$result=dbQuery($query);
			if($row=dbFetchRow($result)) {
				$temp.="<option value=''>"._LANG("functions_putselect_default_option")."</option>\n";
				$row["valor"]=quot_htmlentities($row["valor"]);
				$temp.="<option value=\"".$row["valor"]."\" selected>".$row["texto"]."</option>\n";
			} else {
				$temp.="<option value=''>"._LANG("functions_putselect_big_options_message")."</option>\n";
			}
			dbFree($result);
			$temp.="</select>\n";
			putcolumn($temp,"left",$width_obj,"","","id='td_$variable2'");
			return;
		}
		$campos="$value_ref valor,$text_ref texto";
		$filter2=is_array($filter)?$filter["filter"].$filter["extra"]:$filter;
		$query="SELECT $campos FROM $table_ref WHERE $filter2 AND $value_ref!='' AND $text_ref!='' ORDER BY `texto`";
		$result=dbQuery($query);
		$temp.="<select style='width:612px;' class='inputs ui-state-default ui-corner-all' name='$variable$postvar' id='$variable$postvar'>\n";
		$temp.="<option value=''>"._LANG("functions_putselect_default_option")."</option>\n";
		while($row=dbFetchRow($result)) {
			$sel="";
			if($default==$row["valor"]) $sel="selected";
			$row["valor"]=quot_htmlentities($row["valor"]);
			$temp.="<option value=\"".$row["valor"]."\" $sel>".$row["texto"]."</option>\n";
		}
		dbFree($result);
		$temp.="</select>\n";
		putcolumn($temp,"left",$width_obj,"","","id='td_$variable2'");
	} else {
		$campos="$value_ref valor,$text_ref texto";
		if($is_photo || $is_file) $campos.=",$text_ref"."_file fichero,$text_ref"."_size tamano,$text_ref"."_type tipo";
		$query="SELECT $campos FROM $table_ref WHERE `$value_ref`='$default'";
		$result=dbQuery($query);
		if($row=dbFetchRow($result)) {
			if($is_photo) {
				$text=$row["texto"];
				$file=$row["fichero"];
				$size=$row["tamano"];
				$type=$row["tipo"];
				$preview1="inicio.php?include=php/phpthumb.php&src=$file&w=16&h=16&far=1&bg=ffffff&f=jpg";
				$preview2="inicio.php?include=php/phpthumb.php&src=$file&w=100&h=100&f=jpg";
				$valor="<a href='javascript:void(0);' title='<img src=\"$preview2\" />'><img src='$preview1' width='16px' height='16px' /></a>&nbsp;".getlink($file,$text,$size,$type);
			} elseif($is_file) {
				$text=$row["texto"];
				$file=$row["fichero"];
				$size=$row["tamano"];
				$type=$row["tipo"];
				$valor=getlink($file,$text,$size,$type);
			} else {
				$valor=$row["texto"];
			}
		} else {
			$valor=_LANG("functions_putselect_not_options");
		}
		dbFree($result);
		putcolumn("<table class='tables2' style='width:600px;height:15px'><tr><td><div class='texts'>".$valor."</div></td></tr></table>","left",$width_obj);
	}
}

function putmultiselect($table,$variable,$form,$default="",$filter="1",$extra="") {
	global $width_obj;

	// FOR FIX AN UNKNOWN BUG THAT GENERATE A LIST WITH VOID VALUES BETWEEN THE COMAS
	$default=explode(",",$default);
	foreach($default as $key=>$val) if(trim($val)=="") unset($default[$key]);
	$default=implode(",",$default);
	// CONTINUE THE NORMAL OPERATION
	if($table=="") {
		if($form!="show") {
			$list_vals=explode(",",$filter);
			if($list_vals[0]=="") unset($list_vals[0]);
			$count_vals=count($list_vals);
			$list_def=explode(",",$default);
			if($list_def[0]=="") unset($list_def[0]);
			$count_def=count($list_def);
			for($j=0;$j<$count_def;$j++) $list_def[$j]=encode_bad_chars($list_def[$j]);
			$temp1="<select multiple='multiple' size='5' style='width:284px;height:80px;overflow:auto' class='inputs ui-state-default ui-corner-all' name='all_$variable' id='all_$variable'>\n";
			$temp2="<select multiple='multiple' size='5' style='width:284px;height:80px;overflow:auto' class='inputs ui-state-default ui-corner-all' name='sel_$variable' id='sel_$variable'>\n";
			for($i=0;$i<$count_vals;$i++) {
				if(strpos($list_vals[$i],":")!==false) {
					$list_vals[$i]=explode(":",$list_vals[$i],2);
					$buscar=encode_bad_chars($list_vals[$i][0]);
					$valor=$list_vals[$i][0];
					$texto=$list_vals[$i][1];
				} else {
					$buscar=encode_bad_chars($list_vals[$i]);
					$valor=$list_vals[$i];
					$texto=$list_vals[$i];
				}
				for($j=0;$j<$count_def;$j++) if($list_def[$j]==$buscar) break;
				$valor=quot_htmlentities($valor);
				if($j==$count_def) $temp1.="<option value=\"$valor\" title=\"$texto\">$texto</option>\n";
				else $temp2.="<option value=\"$valor\" title=\"$texto\">$texto</option>\n";
			}
			$temp1.="</select>\n";
			$temp2.="</select>\n";
			$default=quot_htmlentities($default);
			$temp="<input type='hidden' name='$variable' id='$variable' value=\"$default\" />";
			list($title,$text)=make_title_text("forward",_LANG("functions_putmultiselect_button_add"),_LANG("functions_putmultiselect_button_add_title"),"","48");
			$boton1=get_button($title,"seleccionar(\"all\",\"sel\",\"$variable\");","","","buttons ui-state-default ui-corner-all",$text);
			list($title,$text)=make_title_text("back",_LANG("functions_putmultiselect_button_del"),_LANG("functions_putmultiselect_button_del_title"),"","48");
			$boton2=get_button($title,"seleccionar(\"sel\",\"all\",\"$variable\");","","","buttons ui-state-default ui-corner-all",$text);
			putcolumn("$temp<table class='tables2' align='left'><tr><td rowspan='2'>$temp1</td><td style='height:40px'>$boton1</td><td rowspan='2'>$temp2</td></tr><tr><td>$boton2</td></tr></table>","left",$width_obj);
		} else {
			$list_vals=explode(",",$filter);
			if($list_vals[0]=="") unset($list_vals[0]);
			$count_vals=count($list_vals);
			$list_def=explode(",",$default);
			if($list_def[0]=="") unset($list_def[0]);
			$count_def=count($list_def);
			for($j=0;$j<$count_def;$j++) $list_def[$j]=encode_bad_chars($list_def[$j]);
			$valor="";
			for($i=0;$i<$count_vals;$i++) {
				if(strpos($list_vals[$i],":")!==false) {
					$list_vals[$i]=explode(":",$list_vals[$i],2);
					$buscar=encode_bad_chars($list_vals[$i][0]);
					$texto=$list_vals[$i][1];
				} else {
					$buscar=encode_bad_chars($list_vals[$i]);
					$texto=$list_vals[$i];
				}
				for($j=0;$j<$count_def;$j++) if($list_def[$j]==$buscar) break;
				if($j<$count_def) {
					if($valor!="") $valor.="<br/>\n";
					$valor.=$texto."\n";
				}
			}
			if($valor=="") $valor=_LANG("functions_putmultiselect_not_options");
			putcolumn("<table class='tables2' style='width:600px;height:85px;'><tr><td valign='top'><div class='texts' style='width:600px;height:85px;overflow:auto;'>".$valor."</div></td></tr></table>","left",$width_obj);
		}
		return;
	}
	$temp=explode(".",$variable);
	if(count($temp)==3) {
		$variable=$temp[0];
		$table=$temp[1];
		$j=$temp[2];
		$postvar=".$table.$j";
	} else {
		$postvar="";
		$j="";
	}
	$list_def=explode(",",$default);
	$count_def=count($list_def);
	$exists_def=array();
	for($i=0;$i<$count_def;$i++) $exists_def[]=0;
	$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$variable'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$text_ref=$row["text_ref"];
	$table_ref=$row["table_ref"];
	$value_ref=$row["value_ref"];
	dbFree($result);
	$query="SELECT * FROM db_forms WHERE tbl='$table_ref' AND row='".addslashes($text_ref)."'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$is_photo=is_array($row) && isset($row["type"]) && ($row["type"]=="photo");
	$is_file=is_array($row) && isset($row["type"]) && ($row["type"]=="file");
	dbFree($result);
	$temp=explode(":",$text_ref);
	if($temp[0]=="concat") {
		unset($temp[0]);
		$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
		$text_ref=parseQuery($text_ref,getdbtype());
	}
	if($form!="show") {
		$variable2="{$variable}{$postvar}";
		$variable5=str_replace(".","_",$variable2);
		$_withtd=isset($_withtd)?$_withtd:1;
		$maximo=100;
		$filter2=is_array($filter)?$filter["filter"].$filter["extra"]:$filter;
		$query="SELECT id FROM $table_ref WHERE $filter2";
		if(protectselect($query,$maximo) && $_withtd) {
			echo "<script type='text/javascript'>\n";
			echo "function retardo1_$variable5() {\n";
			echo "    count_$variable5=1;\n";
			echo "}\n";
			echo "function retardo2_$variable5() {\n";
			echo "    count_$variable5=0;\n";
			echo "}\n";
			echo "var count_$variable5=0;\n";
			echo "function reload_$variable5() {\n";
			echo "    var temp=document.getElementById('$variable2');\n";
			echo "    var def='';\n";
			echo "    if(!isNull(temp)) def=temp.value;\n";
			echo "    var temp=document.getElementById('extra_$variable2');\n";
			echo "    var extra='';\n";
			echo "    if(!isNull(temp)) extra=trim(temp.value);\n";
			echo "    if(extra_$variable5==extra || count_$variable5!=0) {\n";
			echo "        setTimeout('reload_$variable5();',500);\n";
			echo "    } else {\n";
			echo "        extra_$variable5=extra;\n";
			echo "        var mytd=document.getElementById('td_$variable2');\n";
			echo "        mytd.innerHTML='".get_loading()."';\n";
			echo "        var arg='table=$table&variable=$variable2&default='+def+'&filter=*&extra='+extra;\n";
			echo "        $.ajax({\n";
			echo "            url:'inicio.php?include=php/getfilter.php',\n";
			echo "            data:arg,\n";
			echo "            type:'post',\n";
			echo "            dataType:'html',\n";
			echo "            success:function(data) {\n";
			echo "                var mytd=document.getElementById('td_$variable2');\n";
			echo "                mytd.innerHTML=data;\n";
			echo "                hover_events();\n";
			echo "                make_tables();\n";
			echo "                fix_max_height();\n";
			echo "                var temp=document.getElementById('extra_$variable2');\n";
			echo "                if(!isNull(temp)) temp.focus();\n";
			echo "                setTimeout('reload_$variable5();',500);\n";
			echo "            },\n";
			echo "            error:function(XMLHttpRequest,textStatus,errorThrown){\n";
			echo "                alert('Error '+XMLHttpRequest.status+': '+XMLHttpRequest.statusText);\n";
			echo "            }\n";
			echo "        });\n";
			echo "    }\n";
			echo "}\n";
			echo "var extra_$variable5='';\n";
			echo "$(document).ready(function() { reload_$variable5(); });\n";
			echo "</script>\n";
		}
		$temp="";
		if(protectselect($query,$maximo) || $extra!="") {
			if(protectselect($query,$maximo)) {
				$temp2=_LANG("functions_putmultiselect_big_options_description");
				$temp2=str_replace("#numero#",protectselect($query),$temp2);
				list($title,$text)=make_title_text("alert",_LANG("functions_putmultiselect_big_options_title"),$temp2,"","48");
			} elseif(protectselect($query)) {
				$temp2=_LANG("functions_putmultiselect_ok_options_description");
				$temp2=str_replace("#numero#",protectselect($query),$temp2);
				$temp2=str_replace("#maximo#",$maximo,$temp2);
				list($title,$text)=make_title_text("button_ok",_LANG("functions_putmultiselect_ok_options_title"),$temp2,"","48");
			} else {
				$temp2=_LANG("functions_putmultiselect_not_options_description");
				list($title,$text)=make_title_text("button_cancel",_LANG("functions_putmultiselect_not_options_title"),$temp2,"","48");
			}
			$js="onkeydown='retardo1_$variable5(); if(event.keyCode==13) return false;' title='$title'";
			$temp.="&nbsp;<span title='$title'>"._LANG("functions_putmultiselect_big_options")."</span>";
			$temp.=str_replace("inputs","inputs fix20181123",getinput($table,"extra_".$variable,$extra,15,$j,0,$js));
			list($title2,$text2)=make_title_text("search",_LANG("functions_putmultiselect_button_search"),$temp2,_LANG("functions_putmultiselect_button_search"),"48");
			$url2="retardo2_$variable5();";
			$temp.=get_button($title2,$url2,"","","",$text2);
			if(protectselect($query,$maximo)) {
				$temp.="&nbsp;<img src='lib/crystal/16x16/alert.png' width='16px' height='16px' title='$title' />";
			} elseif(protectselect($query)) {
				$temp.="&nbsp;<img src='lib/crystal/16x16/button_ok.png' width='16px' height='16px' title='$title' />";
			} else {
				$temp.="&nbsp;<img src='lib/crystal/16x16/button_cancel.png' width='16px' height='16px' title='$title' />";
			}
			$temp.="&nbsp;<span title='$title'>(".protectselect($query)._LANG("functions_putmultiselect_big_options_number").")</span><br/>";
		}
		if(protectselect($query,$maximo)) {
			$temp1="<select multiple='multiple' size='5' style='width:284px;height:80px;overflow:auto' class='inputs ui-state-default ui-corner-all' name='all_$variable$postvar' id='all_$variable$postvar'>\n";
			$temp2="<select multiple='multiple' size='5' style='width:284px;height:80px;overflow:auto' class='inputs ui-state-default ui-corner-all' name='sel_$variable$postvar' id='sel_$variable$postvar'>\n";
			$campos="$value_ref valor,$text_ref texto";
			if($default=="") $default="-1";
			$filter2=is_array($filter)?$filter["filter"]:$filter;
			$query="SELECT $campos FROM $table_ref WHERE $filter2 AND $value_ref IN ($default) AND $value_ref!='' AND $text_ref!=''";
			$result=dbQuery($query);
			$default=array();
			while($row=dbFetchRow($result)) {
				$row["valor"]=quot_htmlentities($row["valor"]);
				$temp2.="<option value=\"".$row["valor"]."\" title=\"".$row["texto"]."\">".$row["texto"]."</option>\n";
				$default[]=$row["valor"];
			}
			$default=implode(",",$default);
			dbFree($result);
			$temp1.="</select>\n";
			$temp2.="</select>\n";
			$default=quot_htmlentities($default);
			$temp.="<input type='hidden' name='$variable$postvar' id='$variable$postvar' value=\"$default\" />";
			list($title,$text)=make_title_text("forward",_LANG("functions_putmultiselect_button_add"),_LANG("functions_putmultiselect_button_add_title"),"","48");
			$boton1=get_button($title,"seleccionar(\"all\",\"sel\",\"$variable$postvar\");","","","buttons ui-state-default ui-corner-all",$text);
			list($title,$text)=make_title_text("back",_LANG("functions_putmultiselect_button_del"),_LANG("functions_putmultiselect_button_del_title"),"","48");
			$boton2=get_button($title,"seleccionar(\"sel\",\"all\",\"$variable$postvar\");","","","buttons ui-state-default ui-corner-all",$text);
			putcolumn("$temp<table class='tables2' align='left'><tr><td rowspan='2'>$temp1</td><td style='height:40px'>$boton1</td><td rowspan='2'>$temp2</td></tr><tr><td>$boton2</td></tr></table>","left",$width_obj,"","","id='td_$variable2'");
			return;
		}
		$campos="$value_ref valor,$text_ref texto";
		if($default=="") $default="-1";
		$filter2=is_array($filter)?$filter["filter"]:$filter;
		$extra2=is_array($filter)?"AND ((1 {$filter["extra"]}) OR $value_ref IN ($default))":"";
		$query="SELECT $campos FROM $table_ref WHERE $filter2 $extra2 AND $value_ref!='' AND $text_ref!='' ORDER BY `texto`";
		$result=dbQuery($query);
		$temp1="<select multiple='multiple' size='5' style='width:284px;height:80px;overflow:auto' class='inputs ui-state-default ui-corner-all' name='all_$variable$postvar' id='all_$variable$postvar'>\n";
		$temp2="<select multiple='multiple' size='5' style='width:284px;height:80px;overflow:auto' class='inputs ui-state-default ui-corner-all' name='sel_$variable$postvar' id='sel_$variable$postvar'>\n";
		while($row=dbFetchRow($result)) {
			for($i=0;$i<$count_def;$i++) {
				if($list_def[$i]==$row["valor"]) {
					$exists_def[$i]=1;
					break;
				}
			}
			$row["valor"]=quot_htmlentities($row["valor"]);
			if($i==$count_def) $temp1.="<option value=\"".$row["valor"]."\" title=\"".$row["texto"]."\">".$row["texto"]."</option>\n";
			else $temp2.="<option value=\"".$row["valor"]."\" title=\"".$row["texto"]."\">".$row["texto"]."</option>\n";
		}
		dbFree($result);
		$temp1.="</select>\n";
		$temp2.="</select>\n";
		for($i=0;$i<$count_def;$i++) if(!$exists_def[$i]) unset($list_def[$i]);
		$default=implode(",",$list_def);
		$default=quot_htmlentities($default);
		$temp.="<input type='hidden' name='$variable$postvar' id='$variable$postvar' value=\"$default\" />";
		list($title,$text)=make_title_text("forward",_LANG("functions_putmultiselect_button_add"),_LANG("functions_putmultiselect_button_add_title"),"","48");
		$boton1=get_button($title,"seleccionar(\"all\",\"sel\",\"$variable$postvar\");","","","buttons ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("back",_LANG("functions_putmultiselect_button_del"),_LANG("functions_putmultiselect_button_del_title"),"","48");
		$boton2=get_button($title,"seleccionar(\"sel\",\"all\",\"$variable$postvar\");","","","buttons ui-state-default ui-corner-all",$text);
		putcolumn("$temp<table class='tables2' align='left'><tr><td rowspan='2'>$temp1</td><td style='height:40px'>$boton1</td><td rowspan='2'>$temp2</td></tr><tr><td>$boton2</td></tr></table>","left",$width_obj);
	} else {
		$valor="";
		if($default!="") {
			$campos="$value_ref valor,$text_ref texto";
			if($is_photo || $is_file) $campos.=",$text_ref"."_file fichero,$text_ref"."_size tamano,$text_ref"."_type tipo";
			$query="SELECT $campos FROM $table_ref WHERE $value_ref IN ($default) AND $value_ref!='' AND $text_ref!=''";
			$result=dbQuery($query);
			while($row=dbFetchRow($result)) {
				if($valor!="") $valor.="<br/>\n";
				if($is_photo) {
					$text=$row["texto"];
					$file=$row["fichero"];
					$size=$row["tamano"];
					$type=$row["tipo"];
					$preview1="inicio.php?include=php/phpthumb.php&src=$file&w=16&h=16&far=1&bg=ffffff&f=jpg";
					$preview2="inicio.php?include=php/phpthumb.php&src=$file&w=100&h=100&f=jpg";
					$valor.="<a href='javascript:void(0);' title='<img src=\"$preview2\" />'><img src='$preview1' width='16px' height='16px' /></a>&nbsp;".getlink($file,$text,$size,$type);
				} elseif($is_file) {
					$text=$row["texto"];
					$file=$row["fichero"];
					$size=$row["tamano"];
					$type=$row["tipo"];
					$valor.=getlink($file,$text,$size,$type);
				} else {
					$valor.=$row["texto"];
				}
			}
			dbFree($result);
		}
		if($valor=="") $valor=_LANG("functions_putmultiselect_not_options");
		putcolumn("<table class='tables2' style='width:600px;height:85px;'><tr><td valign='top'><div class='texts' style='width:600px;height:85px;overflow:auto;'>".$valor."</div></td></tr></table>","left",$width_obj);
	}
}

function putajaxselect($table,$variable,$form,$default) {
	global $width_obj;

	$variable2=str_replace(":","_",$variable);
	$variable2=str_replace(".","_",$variable2);
	$temp=explode(":",$variable);
	$temp2=explode(".",$temp[1]);
	$temp2[0]=$temp[0];
	$variable3=implode(".",$temp2);
	putcolumn("&nbsp;","left",$width_obj,"","","id='td_$variable2'");
	if($form!="show") {
		echo "<script type='text/javascript'>\n";
		echo "function reload_$variable2() {\n";
		echo "    var id=document.getElementById('$variable3').value;\n";
		echo "    if(last_$variable2==id) {\n";
		echo "        setTimeout('reload_$variable2();',500);\n";
		echo "    } else {\n";
		echo "        last_$variable2=id;\n";
		echo "        var mytd=document.getElementById('td_$variable2');\n";
		echo "        mytd.innerHTML='".get_loading()."';\n";
		echo "        var arg='table=$table&variable=$variable&default='+id;\n";
		echo "        $.ajax({\n";
		echo "            url:'inicio.php?include=php/getselect.php',\n";
		echo "            data:arg,\n";
		echo "            type:'post',\n";
		echo "            dataType:'html',\n";
		echo "            success:function(data) {\n";
		echo "                var mytd=document.getElementById('td_$variable2');\n";
		echo "                mytd.innerHTML=data;\n";
		echo "                hover_events();\n";
		echo "                make_tables();\n";
		echo "                fix_max_height();\n";
		echo "                setTimeout('reload_$variable2();',500);\n";
		echo "            },\n";
		echo "            error:function(XMLHttpRequest,textStatus,errorThrown){\n";
		echo "                alert('Error '+XMLHttpRequest.status+': '+XMLHttpRequest.statusText);\n";
		echo "            }\n";
		echo "        });\n";
		echo "    }\n";
		echo "}\n";
		echo "var last_$variable2=-1;\n";
		echo "$(document).ready(function() { reload_$variable2(); });\n";
		echo "</script>\n";
	} else {
		echo "<script type='text/javascript'>\n";
		echo "$(document).ready(function() {\n";
		echo "    var arg='table=$table&variable=$variable&default=$default';\n";
		echo "    $.ajax({\n";
		echo "        url:'inicio.php?include=php/getselect.php',\n";
		echo "        data:arg,\n";
		echo "        type:'post',\n";
		echo "        dataType:'html',\n";
		echo "        success:function(data) {\n";
		echo "            var mytd=document.getElementById('td_$variable2');\n";
		echo "            mytd.innerHTML=data;\n";
		echo "            hover_events();\n";
		echo "            make_tables();\n";
		echo "            fix_max_height();\n";
		echo "        },\n";
		echo "        error:function(XMLHttpRequest,textStatus,errorThrown){\n";
		echo "            alert('Error '+XMLHttpRequest.status+': '+XMLHttpRequest.statusText);\n";
		echo "        }\n";
		echo "    });\n";
		echo "});\n";
		echo "</script>\n";
	}
}

function putajaxfilter($table,$variable,$form,$default) {
	global $width_obj;

	$variable2=str_replace(":","_",$variable);
	$variable2=str_replace(".","_",$variable2);
	$temp=explode(":",$variable);
	$temp2=explode(".",$temp[1]);
	$temp2[0]=$temp[0];
	$variable4=implode(".",$temp2);
	$variable3=$temp[1];
	$variable5=str_replace(".","_",$variable4);
	putcolumn("&nbsp;","left",$width_obj,"","","id='td_$variable2'");
	if($form!="show") {
		echo "<script type='text/javascript'>\n";
		echo "function retardo1_$variable5() {\n";
		echo "    count_$variable2=1;\n";
		echo "}\n";
		echo "function retardo2_$variable5() {\n";
		echo "    count_$variable2=0;\n";
		echo "}\n";
		echo "var count_$variable2=0;\n";
		echo "function reload_$variable2() {\n";
		echo "    var temp=document.getElementById('$variable3');\n";
		echo "    if(isNull(temp)) {\n";
		echo "        setTimeout('reload_$variable2()',500);\n";
		echo "        return;\n";
		echo "    }\n";
		echo "    var id=temp.value;\n";
		echo "    var temp=document.getElementById('$variable4');\n";
		echo "    var id2='';\n";
		echo "    if(!isNull(temp)) id2=temp.value;\n";
		echo "    if(ini_$variable2!='') {\n";
		echo "        id2=ini_$variable2;\n";
		echo "        ini_$variable2='';\n";
		echo "    }\n";
		echo "    var temp=document.getElementById('extra_$variable4');\n";
		echo "    var extra='';\n";
		echo "    if(!isNull(temp)) extra=trim(temp.value);\n";
		echo "    if(last_$variable2==id && (extra_$variable2==extra || count_$variable2!=0)) {\n";
		echo "        setTimeout('reload_$variable2();',500);\n";
		echo "    } else {\n";
		echo "        if(last_$variable2!=id) extra='';\n";
		echo "        extra_$variable2=extra;\n";
		echo "        last_$variable2=id;\n";
		echo "        var mytd=document.getElementById('td_$variable2');\n";
		echo "        mytd.innerHTML='".get_loading()."';\n";
		echo "        var arg='table=$table&variable=$variable&default='+id2+'&filter='+id+'&extra='+extra;\n";
		echo "        $.ajax({\n";
		echo "            url:'inicio.php?include=php/getfilter.php',\n";
		echo "            data:arg,\n";
		echo "            type:'post',\n";
		echo "            dataType:'html',\n";
		echo "            success:function(data) {\n";
		echo "                var mytd=document.getElementById('td_$variable2');\n";
		echo "                mytd.innerHTML=data;\n";
		echo "                hover_events();\n";
		echo "                make_tables();\n";
		echo "                fix_max_height();\n";
		echo "                var temp=document.getElementById('extra_$variable4');\n";
		echo "                if(!isNull(temp) && !first_$variable2) temp.focus();\n";
		echo "                first_$variable2=0;\n";
		echo "                setTimeout('reload_$variable2();',500);\n";
		echo "            },\n";
		echo "            error:function(XMLHttpRequest,textStatus,errorThrown){\n";
		echo "                alert('Error '+XMLHttpRequest.status+': '+XMLHttpRequest.statusText);\n";
		echo "            }\n";
		echo "        });\n";
		echo "    }\n";
		echo "}\n";
		echo "var ini_$variable2='$default'\n";
		echo "var last_$variable2=-1;\n";
		echo "var extra_$variable2='';\n";
		echo "var first_$variable2=1;\n";
		echo "$(document).ready(function() { reload_$variable2(); });\n";
		echo "</script>\n";
	}
}

function putfile($variable,$valor,$file,$size,$type,$form) {
	$temp=explode(".",$variable);
	if(count($temp)==3) {
		$variable=$temp[0];
		$table=$temp[1];
		$j=$temp[2];
		$postvar=".$table.$j";
	} else {
		$postvar="";
	}
	$temp1="<table class='tables2' style='margin-left:0px'>";
	$temp2="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putfile_name")."</div></td><td nowrap><div class='texts'><a class='links' href='javascript:redir2(\"inicio.php?include=php/download.php&name=$valor&file=$file&size=$size&type=$type\");' title='"._LANG("functions_putfile_download")."'>".$valor."</a></div></td><td width='99%'></td></tr>";
	$temp3="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putfile_size")."</div></td><td nowrap><div class='texts'>".$size." bytes</div></td></tr>";
	$temp4="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putfile_type")."</div></td><td nowrap><div class='texts'>".$type."</div></td></tr>";
	$temp5="<tr><td colspan='2'><div class='texts' align='left'><table class='texts' cellspading=0 cellspacing=0 border=0><tr><td nowrap>"._LANG("functions_putfile_delete")."&nbsp;</td><td><input type=checkbox name='$variable"."_del$postvar' class='inputs ui-state-default ui-corner-all' value='1' /></td></tr></table></div></td></tr>";
	if(check_demo("user")) $temp5="";
	$temp6="</table>";
	$valor=quot_htmlentities($valor);
	$temp7="<input type=file name='$variable"."_new$postvar' class='inputs ui-state-default ui-corner-all' style='width:600px' size='45' /><input type=hidden name='$variable$postvar' value=\"$valor\"><input type=hidden name='$variable"."_file$postvar' value=\"$file\" /><input type=hidden name='$variable"."_size$postvar' value='$size' /><input type=hidden name='$variable"."_type$postvar' value='$type' />";
	if(check_demo("user")) {
		$temp7="<span class='texts ui-state-error' style='border:0;background:none'>"._LANG("functions_putfile_demo_disabled")."</span><input type=hidden name='$variable$postvar' value=\"$valor\" /><input type=hidden name='$variable"."_file$postvar' value=\"$file\" /><input type=hidden name='$variable"."_size$postvar' value='$size' /><input type=hidden name='$variable"."_type$postvar' value='$type' />";
	}
	$temp8="<tr><td><div class='texts'>"._LANG("functions_putfile_not_file")."</div></td></tr>";
	if($file!="" && $form!="show") $total=$temp1.$temp2.$temp3.$temp4.$temp5.$temp6.$temp7;
	if($file!="" && $form=="show") $total=$temp1.$temp2.$temp3.$temp4.$temp6;
	if($file=="" && $form!="show") $total=$temp1.$temp8.$temp6.$temp7;
	if($file=="" && $form=="show") $total=$temp1.$temp8.$temp6;
	if($form=="new") $total=$temp7;
	if($file!="" && $form=="over") $total=$temp1.$temp2.$temp3.$temp4.$temp6.$temp7;
	if($file=="" && $form=="over") $total=$temp1.$temp8.$temp6.$temp7;
	putcolumn($total,"left","600","","bigfield");
}

function putphoto($variable,$valor,$file,$size,$type,$form) {
	$temp=explode(".",$variable);
	if(count($temp)==3) {
		$variable=$temp[0];
		$table=$temp[1];
		$j=$temp[2];
		$postvar=".$table.$j";
	} else {
		$postvar="";
	}
	$temp1="<table class='tables2' style='margin-left:0px'>";
	$temp2="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putphoto_name")."</div></td><td nowrap><div class='texts'><a class='links' href='javascript:redir2(\"inicio.php?include=php/download.php&name=$valor&file=$file&size=$size&type=$type\");' title='"._LANG("functions_putphoto_download")."'>".$valor."</a></div></td><td width='99%'></td></tr>";
	$temp3="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putphoto_size")."</div></td><td nowrap><div class='texts'>".$size." bytes</div></td></tr>";
	$temp4="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putphoto_type")."</div></td><td nowrap><div class='texts'>".$type."</div></td></tr>";
	$preview1="inicio.php?include=php/phpthumb.php&src=$file&w=100&h=100&far=1&bg=ffffff&f=jpg";
	$preview2="inicio.php?include=php/phpthumb.php&src=$file&w=300&h=300&f=jpg";
	$temp5="<tr><td><div class='texts' align='right'>&nbsp;"._LANG("functions_putphoto_preview")."</div></td><td><img title='<img src=\"$preview2\" />' src='$preview1' width='100px' height='100px' class='ui-corner-all' /></td></tr>";
	$temp6="<tr><td colspan='2'><div class='texts' align='left'><table class='texts' cellspading=0 cellspacing=0 border=0><tr><td nowrap>"._LANG("functions_putphoto_delete")."&nbsp;</td><td><input type=checkbox name='$variable"."_del$postvar' class='inputs ui-state-default ui-corner-all' value='1' /></td></tr></table></div></td></tr>";
	if(check_demo("user")) $temp6="";
	$temp7="</table>";
	$valor=quot_htmlentities($valor);
	$temp8="<input type=file name='$variable"."_new$postvar' class='inputs ui-state-default ui-corner-all' style='width:600px;' size='45' /><input type=hidden name='$variable$postvar' value=\"$valor\" /><input type=hidden name='$variable"."_file$postvar' value=\"$file\" /><input type=hidden name='$variable"."_size$postvar' value='$size' /><input type=hidden name='$variable"."_type$postvar' value='$type' />";
	if(check_demo("user")) {
		$temp8="<span class='texts ui-state-error' style='border:0;background:none'>"._LANG("functions_putphoto_demo_disabled")."</span><input type=hidden name='$variable$postvar' value=\"$valor\" /><input type=hidden name='$variable"."_file$postvar' value=\"$file\" /><input type=hidden name='$variable"."_size$postvar' value='$size' /><input type=hidden name='$variable"."_type$postvar' value='$type' />";
	}
	$temp9="<tr><td><div class='texts'>"._LANG("functions_putphoto_not_photo")."</div></td></tr>";
	if($file!="" && $form!="show") $total=$temp1.$temp2.$temp3.$temp4.$temp5.$temp6.$temp7.$temp8;
	if($file!="" && $form=="show") $total=$temp1.$temp2.$temp3.$temp4.$temp5.$temp7;
	if($file=="" && $form!="show") $total=$temp1.$temp9.$temp7.$temp8;
	if($file=="" && $form=="show") $total=$temp1.$temp9.$temp7;
	if($form=="new") $total=$temp8;
	if($file!="" && $form=="over") $total=$temp1.$temp2.$temp3.$temp4.$temp5.$temp7.$temp8;
	if($file=="" && $form=="over") $total=$temp1.$temp9.$temp7.$temp8;
	putcolumn($total,"left","600","","bigfield");
}

function putboolean($campo,$valor,$form) {
	global $width_obj;

	if($form!="show") {
		$checked_si="";
		$checked_no="";
		switch(strtoupper($valor)) {
			case "1": $checked_si="checked"; break;
			case "SI": $checked_si="checked"; break;
			default: $checked_no="checked"; break;
		}
		$value_si="1";
		$value_no="0";
		$style_width="600px";
		if($width_obj=="33%") $style_width="250px";
		$campo2=str_replace(".","_",$campo);
		putcolumn("<table class='texts' border='0' cellspacing='0' cellpadding='0' style='width:$style_width' align='left'><tr><td style='vertical-align:bottom'><input type='radio' id='{$campo2}_1' name='$campo' value='$value_si' $checked_si style='margin-bottom:3px' /></td><td><div><a href='javascript:void(0)' onclick='document.getElementById(\"{$campo2}_1\").click()' onkeypress='if(event.keyCode==13) document.getElementById(\"{$campo2}_1\").click()' style='text-decoration:none' >&nbsp;"._LANG("functions_putboolean_yes")."</a></div></td><td style='vertical-align:bottom'>&nbsp;</td><td style='vertical-align:bottom'><input type='radio' id='{$campo2}_0' name='$campo' value='$value_no' $checked_no style='margin-bottom:3px' /></td><td><div><a href='javascript:void(0)' onclick='document.getElementById(\"{$campo2}_0\").click()' onkeypress='if(event.keyCode==13) document.getElementById(\"{$campo2}_0\").click()' style='text-decoration:none'>&nbsp;"._LANG("functions_putboolean_not")."</a></div></td><td width='99%' style='vertical-align:bottom'>&nbsp;</td></tr></table>","left",$width_obj);
	} else {
		putcolumn(getboolean($valor),"left",$width_obj);
	}
}

function getboolean($valor,$withlinks=0,$yesno=1,$campo="",$table="",$id="") {
	$checked_si="0";
	$checked_no="0";
	switch(strtoupper($valor)) {
		case "1": $checked_si="1"; break;
		case "SI": $checked_si="1"; break;
		default: $checked_no="1"; break;
	}
	$checked="";
	if($valor) $checked="checked";
	$alt="";
	if($withlinks) $alt=_LANG("functions_getboolean_alt");
	$temp1="<a href=javascript:msgset('inicio.php?page=process&action=setboolean&table=$table&id=$id&campo=$campo&value=1'); class='links' title='$alt'>";
	$temp2="<img src='lib/crystal/16x16/checkedbox_$checked_si.png' width='16px' height='16px' />";
	$temp3="</a>";
	$temp4="<a href=javascript:msgset('inicio.php?page=process&action=setboolean&table=$table&id=$id&campo=$campo&value=0'); class='links' title='$alt'>";
	$temp5="<img src='lib/crystal/16x16/checkedbox_$checked_no.png' width='16px' height='16px' />";
	$temp6="</a>";
	$temp7="<input type='checkbox' name='$campo.$table.$id' value='1' class='inputs ui-state-default ui-corner-all' $checked />";
	if($yesno && !$withlinks) $total=$temp2."&nbsp;"._LANG("functions_getboolean_yes")."&nbsp;"."&nbsp;"."&nbsp;".$temp5."&nbsp;"._LANG("functions_getboolean_not");
	if($yesno && $withlinks) $total=$temp1.$temp2._LANG("functions_getboolean_yes").$temp3."&nbsp;".$temp4.$temp5._LANG("functions_getboolean_not").$temp6;
	if(!$yesno && !$withlinks) $total=$temp2;
	//if(!$yesno && $withlinks & $valor==0) $total=$temp1.$temp2.$temp3;
	//if(!$yesno && $withlinks & $valor==1) $total=$temp4.$temp2.$temp6;
	if(!$yesno && $withlinks) $total=$temp7;
	return $total;
}

function getFilesDir() {
	static $filesdir="";
	if(!$filesdir) $filesdir=getcwd()."/files";
	return $filesdir;
}

function getpreview($file,$valor,$size,$type,$size2=0) {
	$preview1="inicio.php?include=php/phpthumb.php&src=$file&w=20&h=20&far=1&bg=ffffff&f=jpg";
	$preview2="inicio.php?include=php/phpthumb.php&src=$file&w=100&h=100&f=jpg";
	return "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='22px' align='center'><img title='<img src=\"$preview2\" />' src='$preview1' width='20px' height='20px' class='ui-corner-all' /></td><td nowrap><div class='texts' align='center'>".getlink($file,$valor,$size,$type,$size2)."</div></td><td width='22px' align='center'>&nbsp;</td></tr></table>";
}

function getlink($file,$valor,$size,$type,$size2=0) {
	$temp=_LANG("functions_getlink_not_file");
	if($file!="") {
		$temp=_LANG("functions_getlink_name").$valor."<br/>";
		$temp.=_LANG("functions_getlink_size").$size." bytes<br/>";
		$temp.=_LANG("functions_getlink_type").$type;
	}
	$valor2=rawurlencode($valor);
	$temp2=intval($size2);
	if($temp2!=0) if(mb_strlen($valor,"UTF-8")>$temp2) $valor=mb_substr($valor,0,$temp2-4,"UTF-8")."&hellip;";
	return "<a class='links' href='javascript:redir2(\"inicio.php?include=php/download.php&name=$valor2&file=$file&size=$size&type=$type\");' title=\"$temp\">$valor</a>";
}

function getinput($table,$campo,$valor,$size,$num,$bad=0,$js="") {
	$class="inputs ui-state-default ui-corner-all";
	if($bad) $class="inputs2 ui-state-error ui-corner-all";
	$size=intval($size)*6;
	$style=($size>0)?"width:{$size}px;":"";
	$theid=$campo;
	$num=strval($num);
	if($table!="" && $num!="") $theid.=".$table.$num";
	$valor=quot_htmlentities($valor);
	return "<input type='text' name='$theid' id='$theid' class='$class' value=\"$valor\" style='$style' $js />";
}

function resolveselect($table,$campo) {
	static $stack=array();

	$hash=md5($table.$campo);
	if(!isset($stack[$hash])) {
		$query="SELECT text_ref,table_ref FROM db_selects WHERE tbl='$table' AND row='$campo'";
		$result=dbQuery($query);
		$row=dbFetchRow($result);
		$text_ref=isset($row["text_ref"])?$row["text_ref"]:"";
		$table_ref=isset($row["table_ref"])?$row["table_ref"]:"";
		dbFree($result);
		$stack[$hash]="{$table_ref}_{$text_ref}";
	}
	return $stack[$hash];
}

function getselect($table,$campo,$valor,$size,$edit,$num,$bad=0) {
	$query="SELECT text_ref,table_ref,value_ref FROM db_selects WHERE tbl='$table' AND row='$campo'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$text_ref=$row["text_ref"];
	$table_ref=$row["table_ref"];
	$value_ref=$row["value_ref"];
	dbFree($result);
	if(!$edit) {
		$temp=explode(":",$text_ref);
		if($temp[0]=="concat") {
			unset($temp[0]);
			$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
			$text_ref=parseQuery($text_ref,getdbtype());
		}
		$query="SELECT $text_ref texto FROM $table_ref WHERE $value_ref='$valor'";
		$result=dbQuery($query);
		if($row=dbFetchRow($result)) {
			$valor=$row["texto"];
			dbFree($result);
			$temp=intval($size);
			if($temp!=0) if(mb_strlen($valor,"UTF-8")>$temp) $valor=mb_substr($valor,0,$temp-4,"UTF-8")."&hellip;";
		} else {
			$valor=_LANG("functions_getselect_not_options");
		}
	} else {
		$size=intval($size)*6;
		$class="inputs ui-state-default ui-corner-all";
		if($bad) $class="inputs2 ui-state-error ui-corner-all";
		$temp=explode(":",$text_ref);
		if($temp[0]=="concat") {
			unset($temp[0]);
			$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
			$text_ref=parseQuery($text_ref,getdbtype());
		}
		$query="SELECT $value_ref valor,$text_ref texto FROM $table_ref WHERE $value_ref!='' AND $text_ref!='' ORDER BY `texto`";
		$result=dbQuery($query);
		$newvalor="<select class='$class' name='$campo.$table.$num' id='$table.$campo.$num' style='width:$size"."px;'>\n";
		$newvalor.="<option value=\"\">"._LANG("functions_getselect_default_option")."</option>\n";
		while($row=dbFetchRow($result)) {
			$selected="";
			if($valor==$row["valor"]) $selected="selected";
			$row["valor"]=quot_htmlentities($row["valor"]);
			$newvalor.="<option value=\"".$row["valor"]."\" $selected>".$row["texto"]."</option>\n";
		}
		$newvalor.="</select>\n";
		$valor=$newvalor;
	}
	return $valor;
}

function getmultiselect($table,$campo,$valor,$size,$num,$bad=0) {
	$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$campo'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$text_ref=$row["text_ref"];
	$table_ref=$row["table_ref"];
	$value_ref=$row["value_ref"];
	dbFree($result);
	$temp=explode(":",$text_ref);
	if($temp[0]=="concat") {
		unset($temp[0]);
		$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
		$text_ref=parseQuery($text_ref,getdbtype());
	}
	$query="SELECT $text_ref texto FROM $table_ref WHERE ";
	$temp=explode(",",$valor);
	$first=1;
	foreach($temp as $temp2) {
		if(!$first) $query.=" OR ";
		$query.="/*MYSQL FIND_IN_SET('".$temp2."',`$value_ref`) *//*SQLITE (`$value_ref` LIKE '$temp2' OR `$value_ref` LIKE '%,$temp2' OR `$value_ref` LIKE '%,$temp2,%' OR `$value_ref` LIKE '$temp2,%') */";
		$first=0;
	}
	$result=dbQuery($query);
	if(dbNumRows($result)) {
		$valor="";
		$first=1;
		while($row=dbFetchRow($result)) {
			if(!$first) $valor.=", ";
			$valor.=$row["texto"];
			$first=0;
		}
		$temp=intval($size);
		if($temp!=0) if(mb_strlen($valor,"UTF-8")>$temp) $valor=mb_substr($valor,0,$temp-4,"UTF-8")."&hellip;";
	} else {
		$valor=_LANG("functions_getmultiselect_not_options");
	}
	dbFree($result);
	return $valor;
}

function puterrores($width="900") {
	global $error;
	global $info;

	if(!isset($error)) $error=array();
	if(!isset($info)) $info=array();
	$error=array_unique($error);
	$info=array_unique($info);
	if(count($error)+count($info)>0) {
		openform($width,"","","","class='tabla'","colspan='3'");
		settds("tdsh thead ui-state-error");
		foreach($error as $e) {
			openrow();
			$e=explode(":",$e);
			if($e[0]=="WARNING") {
				unset($e[0]);
				$texto="&nbsp;"._LANG("functions_puterrores_warning")."&nbsp;";
			} elseif($e[0]=="ERROR") {
				unset($e[0]);
				$texto="&nbsp;"._LANG("functions_puterrores_error")."&nbsp;";
			} else {
				$texto="&nbsp;";
			}
			$e=implode(":",$e);
			putcolumn("{$texto}{$e}","left","100%","","errors");
			closerow();
			settds("tdsh thead ui-state-error nofirst");
		}
		settds("tdsh thead ui-state-highlight");
		foreach($info as $i) {
			openrow();
			putcolumn("&nbsp;{$i}","left","100%","","errors");
			closerow();
			settds("tdsh thead ui-state-highlight nofirst");
		}
		closeform();
		escribe();
	}
}

function getnametable($table) {
	static $stack=array();
	$hash=md5($table);
	if(!isset($stack[$hash])) {
		$query="SELECT name FROM db_tables WHERE tbl='$table'";
		$result=dbQuery($query);
		$row=dbFetchRow($result);
		$stack[$hash]=isset($row["name"])?$row["name"]:"";
		dbFree($result);
	}
	return $stack[$hash];
}

function getnamegroup($num) {
	static $stack=array();
	$hash=md5($num);
	if(!isset($stack[$hash])) {
		$query="SELECT name,description,icon FROM db_tables WHERE tbl='' AND position='$num'";
		$result=dbQuery($query);
		$row=dbFetchRow($result);
		$stack[$hash]="";
		if(isset($row["name"])) {
			if($row["icon"]=="") $row["icon"]="kblackbox.png";
			if(!file_exists("lib/crystal/16x16/".$row["icon"])) $row["icon"]="kblackbox.png";
			if(!file_exists("lib/crystal/32x32/".$row["icon"])) $row["icon"]="kblackbox.png";
			if(!file_exists("lib/crystal/48x48/".$row["icon"])) $row["icon"]="kblackbox.png";
			$stack[$hash]="<table width='100%' height='100%'><tr><td rowspan=2 width=1><img src=\"lib/crystal/48x48/{$row["icon"]}\" /></td><td height=1 valign=top class=\"titulos ui-state-default\" style=\"background:none;border:none;font-weight:bold;font-size:24px;padding-left:10px\">{$row["name"]}</td></tr><tr><td valign=top class=\"titulos ui-state-default\" style=\"background:none;border:none;font-weight:normal;font-size:12px;padding-left:10px\">{$row["description"]}</td></tr></table>";
		}
		dbFree($result);
	}
	return $stack[$hash];
}

function getlistconfig($table) {
	global $campos,$textos,$types,$sizes,$edits,$neededs,$uniques;

	$query="SELECT * FROM db_lists WHERE tbl='$table' ORDER BY position";
	$result=dbQuery($query);
	if(dbNumRows($result)>0) {
		getformconfig($table);
		$campos_form=$campos;
		$neededs_form=$neededs;
		$uniques_form=$uniques;
		$campos=array();
		$textos=array();
		$types=array();
		$sizes=array();
		$edits=array();
		$neededs=array();
		$uniques=array();
		while($row=dbFetchRow($result)) {
			$campos[]=$row["row"];
			$textos[]=$row["name"];
			$types[]=$row["type"];
			$sizes[]=$row["size"];
			$edits[]=$row["edit"];
			$count_campos_form=count($campos_form);
			$finded=0;
			for($i=0;$i<$count_campos_form;$i++) {
				$temp=explode(":",$campos_form[$i]);
				$campo_form=$temp[0];
				if($row["row"]==$campo_form) {
					$neededs[]=$neededs_form[$i];
					$uniques[]=$uniques_form[$i];
					$finded=1;
				}
			}
			if(!$finded) {
				$neededs[]="0";
				$uniques[]="0";
			}
		}
	}
	if(is_array($sizes)) {
		$valid=1;
		$total=0;
		foreach($sizes as $key=>$val) {
			if(substr($val,-1,1)!="%") $valid=0;
			$total+=intval($val);
		}
		if($valid && $total!=100) {
			$total2=0;
			foreach($sizes as $key=>$val) {
				$sizes[$key]=round(intval($val)*100/$total,0)."%";
				$total2+=intval($sizes[$key]);
			}
			$count=count($sizes);
			if($count>0) $sizes[$count-1]=intval(intval($sizes[$count-1])+100-$total2)."%";
		}
	}
	dbFree($result);
}

function getformconfig($table) {
	global $campos,$textos,$types,$neededs,$uniques,$noedits;

	$query="SELECT * FROM db_forms WHERE tbl='$table' ORDER BY position";
	$result=dbQuery($query);
	if(dbNumRows($result)>0) {
		$campos=array();
		$textos=array();
		$types=array();
		$neededs=array();
		$uniques=array();
		$noedits=array();
		while($row=dbFetchRow($result)) {
			$campos[]=$row["row"];
			$textos[]=$row["name"];
			$types[]=$row["type"];
			$neededs[]=$row["needed"];
			$uniques[]=$row["unique"];
			$noedits[]=isset($row["noedit"])?$row["noedit"]:0;
		}
	}
	dbFree($result);
}

function getdefaults($table) {
	global $campos,$defaults;

	$query="/*MYSQL SHOW COLUMNS FROM $table *//*SQLITE PRAGMA TABLE_INFO($table) */";
	$result=dbQuery($query);
	if(dbNumRows($result)>0) {
		$temp=array();
		$dbtemp=getdbtype();
		while($row=dbFetchRow($result)) {
			if($dbtemp=="MYSQL") $temp[$row["Field"]]=$row["Default"];
			if($dbtemp=="SQLITE") $temp[$row["name"]]=$row["dflt_value"];
		}
		if(is_array($campos)) {
			$defaults=array();
			foreach($campos as $campo) {
				if(isset($temp[$campo])) {
					$default=$temp[$campo];
					//~ if($dbtemp=="SQLITE") $default=substr($default,1,-1);
					if(substr($default,0,1)=="'" && substr($default,-1,1)=="'") $default=substr($default,1,-1);
					if(substr($default,0,1)=='"' && substr($default,-1,1)=='"') $default=substr($default,1,-1);
					$defaults[]=$default;
				} else {
					$defaults[]="";
				}
			}
		}
	}
	dbFree($result);
}

function getdinamicsconfig() {
	global $dinamics;

	$query="SELECT * FROM db_dinamics ORDER BY dinamic";
	$result=dbQuery($query);
	if(dbNumRows($result)>0) {
		$dinamics=array();
		while($row=dbFetchRow($result)) {
			$dinamics[$row["dinamic"]]=array("type"=>$row["type"],"text"=>$row["text"]);
		}
	}
	dbFree($result);
}

function reparaformconfig() {
	global $campos,$types,$table;

	$count_campos=count($campos);
	for($i=0;$i<$count_campos;$i++) {
		$campo=$campos[$i];
		$type=$types[$i];
		if($type=="ajaxfilter") {
			$temp=explode(":",$campo);
			$campo=$temp[0];
			$campos[$i]=$campo;
			$padre=$temp[1];
			$counter=100;
			while($counter>0) {
				$query="SELECT * FROM db_forms WHERE tbl='$table' AND row='".addslashes($padre)."'";
				$result=dbQuery($query);
				$row=dbFetchRow($result);
				$type=$row["type"];
				dbFree($result);
				if($type!="") break;
				$query="SELECT * FROM db_selects WHERE tbl='$table' AND row LIKE '$padre:%'";
				$result=dbQuery($query);
				$row=dbFetchRow($result);
				$temp=explode(":",$row["row"]);
				if(isset($temp[1])) $padre=$temp[1];
				else $counter=0;
				$counter--;
			}
			if($counter<=0) die("&nbsp;"._LANG("functions_reparaformconfig_error"));
			$types[$i]=$type;
		}
	}
}

function find_dependencies($table,$id) {
	$deps=array();
	$query="SELECT * FROM db_selects WHERE table_ref='$table' AND row NOT LIKE '%:%' AND SUBSTR(tbl,-4,4)!='.php'";
	$result=dbQuery($query);
	while($row=dbFetchRow($result)) {
		$table_ref=$row["tbl"];
		$value_ref=$row["row"];
		$query2="SELECT * FROM $table WHERE id IN ($id)";
		$result2=dbQuery($query2);
		while($row2=dbFetchRow($result2)) {
			$value=$row2[$row["value_ref"]];
			$temp=explode(":",$value_ref);
			$value_ref=$temp[0];
			$query3="SELECT * FROM $table_ref WHERE $value_ref='$value'";
			$result3=dbQuery($query3);
			if(dbNumRows($result3)>0) {
				$deps[]="ERROR:"._LANG("functions_finddependencies_error").getnametable($table_ref);
			}
			dbFree($result3);
		}
		dbFree($result2);
	}
	dbFree($result);
	return $deps;
}

function check_permissions($role,$table) {
	static $stack=array();
	$hash=md5("role=$role&table=$table");
	if(!isset($stack[$hash])) $stack[$hash]=check_permissions_uncached($role,$table);
	return $stack[$hash];
}

function check_permissions_uncached($role,$table) {
	global $admuser;

	$user=$_SESSION["user"];
	$query="SELECT * FROM db_perms WHERE (user='$user' OR (user='$admuser' AND allow='deny')) AND '$table' LIKE tbl AND (role='$role' OR role='all')";
	$result=dbQuery($query);
	$allow=0;
	$deny=0;
	while($row=dbFetchRow($result)) {
		if($row["allow"]=="allow") $allow=1;
		if($row["allow"]=="deny") $deny=1;
	}
	dbFree($result);
	if($deny) return 0;
	if($allow) return 1;
	return 0;
}

function putnotes() {
	$user=$_SESSION["user"];
	$query="SELECT * FROM db_notes WHERE user='$user' AND enabled='1'";
	$result=dbQuery($query);
	$num=dbNumRows($result);
	if($num>0) {
		while($row=dbFetchRow($result)) {
			$id=$row["id"];
			$note=$row["note"];
			$alltime=$row["alltime"];
			msgbox($note);
			if(!$alltime) {
				$query="UPDATE db_notes SET enabled='0' WHERE id='$id'";
				dbQuery($query);
			}
		}
	}
	dbFree($result);
}

function msgbox($msg,$url="") {
	echo "<script language=javascript type=text/javascript>\n";
	echo "$(document).ready(function() {\n";
	echo "    msgbox(\"$msg\",\"$url\")\n";
	echo "});\n";
	echo "</script>\n";
}

function put_javascript_msgbox() {
	echo "<script language=javascript type=text/javascript>\n";
	echo "function msgbox(msg,url) {\n";
	echo "    if(typeof(url)=='undefined') url='';\n";
	echo "    var title=\""._LANG("functions_msgbox_title")."\";\n";
	echo "    var buttons={\""._LANG("functions_msgbox_button_close")."\":function() { $('#dialog').dialog('close'); }};\n";
	echo "    var fnclose=function() { fix_max_height(); };\n";
	echo "    var fnopen=function() { $(this).parents('.ui-dialog-buttonpane button:eq(0)').focus(); };\n";
	echo "    if(typeof(url)=='object') {\n";
	echo "        buttons=url;\n";
	echo "    } else if(url=='back') {\n";
	echo "        buttons={\""._LANG("functions_msgbox_button_return")."\":function() { $('#dialog').dialog('close'); }};\n";
	echo "        fnclose=function() { history.back(); };\n";
	echo "    } else if(url!='') {\n";
	echo "        buttons={\""._LANG("functions_msgbox_button_continue")."\":function() { $('#dialog').dialog('close'); }};\n";
	echo "        fnclose=function() { redir(url); };\n";
	echo "    }\n";
	echo "    $('#dialog p').html(msg);\n";
	echo "    $('#dialog').dialog({\n";
	echo "        'closeOnEscape':true,\n";
	echo "        'modal':true,\n";
	echo "        'autoOpen':true,\n";
	echo "        'position':{ my:'center',at:'center',of:window },\n";
	echo "        'resizable':false,\n";
	echo "        'title':title,\n";
	echo "        'buttons':buttons,\n";
	echo "        'close':fnclose,\n";
	echo "        'open':fnopen\n";
	echo "    })\n";
	echo "    $('#dialog').trigger('resize');\n";
	echo "};\n";
	echo "</script>\n";
}

function location($url) {
	echo "<script language=javascript type=text/javascript>\n";
	echo "$(document).ready(function() {\n";
	echo "    redir(\"$url\")\n";
	echo "});\n";
	echo "</script>\n";
}

function back() {
	echo "<script language=javascript type=text/javascript>\n";
	echo "$(document).ready(function() {\n";
	echo "    history.back();\n";
	echo "});\n";
	echo "</script>\n";
}

function settds($tds) {
	global $_tds;
	$_tds=$tds;
}

function getmenu() {
	global $table,$func,$page;

	$result_name=array();
	$result_desc=array();
	$result_tbl=array();
	$result_sel=array();
	$result_icon=array();
	$result_pos=array();
	$query="SELECT * FROM db_tables WHERE tbl!='' ORDER BY position";
	$result=dbQuery($query);
	while($row=dbFetchRow($result)) {
		$name=$row["name"];
		$desc=$row["description"];
		$tbl=$row["tbl"];
		$icon=$row["icon"];
		$pos=$row["position"];
		if($icon=="random") $icon=getrandomicon($tbl);
		if($icon=="") $icon="kblackbox.png";
		if(!file_exists("lib/crystal/16x16/$icon")) $icon="kblackbox.png";
		if(!file_exists("lib/crystal/32x32/$icon")) $icon="kblackbox.png";
		if(!file_exists("lib/crystal/48x48/$icon")) $icon="kblackbox.png";
		if(check_permissions("list",$tbl)) {
			$selected="";
			if($table==$tbl) $selected="selected";
			if($func==$tbl) $selected="selected";
			if("$func.adm"==$tbl) $selected="selected";
			if($page==$tbl) $selected="selected";
			$result_name[]=$name;
			$result_desc[]=$desc;
			$result_tbl[]=$tbl;
			$result_sel[]=$selected;
			$result_icon[]=$icon;
			$result_pos[]=$pos;
		}
	}
	dbFree($result);
	$result=array("name"=>$result_name,"desc"=>$result_desc,"tbl"=>$result_tbl,"sel"=>$result_sel,"icon"=>$result_icon,"pos"=>$result_pos);
	return $result;
}

function getrandomicon($key) {
	$count=getcount("def_icons");
	if($key!="") {
		$hash=crc32($key);
		$index=$hash%$count;
	} else {
		srand(intval(microtime(true))*10000000);
		$index=rand(0,$count-1);
	}
	$icon=getvalue("def_icons","icon",$index);
	return $icon;
}

function getvalue($table,$key,$id) {
	$query="SELECT $key FROM $table WHERE id='$id'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	$value=$row[$key];
	dbFree($result);
	return $value;
}

function putfloatmenu() {
	global $table;
	global $page;
	global $func;
	global $error;

	if(!check_user()) return;
	$menu=getmenu();
	openform("100%","fmt","id='fmd' style='position:absolute;display:none;z-index:100;' onmouseover='__menu_show();' onmouseout='__menu_hide();'","id='fmi' src='javascript:false;' scrolling='no' frameborder='0' style='position:absolute; top:0px; left:0px; display:none;'","cellspacing='0' cellpadding='0' border='0' class='menusleft0'");
	$total=count($menu["name"]);
	$mymax=10;
	$ncols=((int)(($total-1)/$mymax))+1;
	$nrows=((int)(($total-1)/$ncols))+1;
	openrow();
	settds("");
	putcolumn(_LANG("functions_putfloatmenu_title"),"center","","","menusleft1 ui-widget-header","colspan='$ncols' style='height:25px;border-right:none!important'");
	$num=$total+1;
	if($total==1) {
		$nrows="2";
		$num++;
	}
	if($ncols>1) $num=((int)(($total-1)/$ncols))+2;
	$menutitle=_LANG("functions_putfloatmenu_title");
	$menulabel=array();
	for($i=0;$i<mb_strlen($menutitle,"UTF-8");$i++) $menulabel[]=mb_substr($menutitle,$i,1,"UTF-8");
	$menulabel=implode("<br/>",$menulabel);
	putcolumn($menulabel,"center","27","","menusleft2 ui-widget-header ui-corner-right","rowspan='$num'");
	closerow();
	for($i=0;$i<$nrows;$i++) {
		openrow();
		for($j=0;$j<$ncols;$j++) {
			$num=$j*$nrows+$i;
			if($num<$total) {
				$name=$menu["name"][$num];
				$desc=$menu["desc"][$num];
				$tbl=$menu["tbl"][$num];
				$sel=$menu["sel"][$num];
				$icon=$menu["icon"][$num];
				$name=str_replace("'","",$name);
				$desc=str_replace("'","",$desc);
			} else {
				$name="";
				$desc="";
				$tbl="";
				$sel="";
				$icon="";
			}
			if($name!="") {
				$name=str_replace("'","",$name);
				$name=str_replace(array("&lt;","&gt;"),array("<",">"),$name);
				$desc=str_replace("'","",$desc);
				$desc=str_replace(array("&lt;","&gt;"),array("<",">"),$desc);
				$url="inicio.php?page=list&table=$tbl";
				if(substr($tbl,-4)==".php") $url="inicio.php?page=$tbl";
				if(substr($tbl,-4)==".adm") $url="inicio.php?func=$tbl";
				$action="redir(\"$url\");";
				$direct=array("download_db_spec.adm","download_backup.adm");
				if(in_array($tbl,$direct)) $action="document.location=\"inicio.php?include=php/db_spec.php&func=$tbl\"";
				$title="<table><tr><td rowspan=2><img src=\"lib/crystal/48x48/$icon\" width=\"48px\" height=\"48px\" /></td><td height=\"1\" valign=\"top\"><em>$name</em></td></tr><tr><td valign=top>$desc</td></tr></table>";
				if(strlen($name)>15) $name=mb_substr($name,0,13,"UTF-8")."&hellip;";
				$text="<img src='lib/crystal/16x16/$icon' width='16px' height='16px' />&nbsp;$name";
				$temp=get_button($title,$action,"150","27","menusleft3 ui-state-default",$text);
				$temp=str_replace("style='","style='border-right:none!important;border-top:none!important;",$temp);
				putcolumn($temp,"left","");
			} else {
				putcolumn("&nbsp;","center","","","menusleft3 ui-state-default","height='25' style='border-right:none!important;border-top:none!important'");
			}
		}
		closerow();
	}
	closeform();
	echo "<script type='text/javascript'>\n";
	echo "$(document).ready(function() {\n";
	echo "    menu_init();\n";
	echo "});\n";
	echo "</script>\n";
}

function usefloatmenu() {
	global $floatmenu;
	if(!isset($floatmenu)) return 0;
	return $floatmenu;
}

function getcount($table) {
	static $stack=array();

	$hash=md5($table);
	if(!isset($stack[$hash])) {
		$query="SELECT count(id) counter FROM $table";
		$result=dbQuery($query);
		$row=dbFetchRow($result);
		$stack[$hash]=$row["counter"];
		dbFree($result);
	}
	return $stack[$hash];
}

function show_data_dinamic($campo,$table,$j,$valor,&$row) {
	global $campos,$types,$textos;
	global $dinamics;

	if(!isset($dinamics)) getdinamicsconfig();
	if($valor=="") {
		$query="SELECT $campo FROM $table WHERE id='$j'";
		$result=dbQuery($query);
		$row2=dbFetchRow($result);
		dbFree($result);
		$valor=$row2[$campo];
	}
	if($valor=="") $valor="|";
	$temp=explode("|",$valor);
	$names=$temp[0];
	$tipos=$temp[1];
	if($names!="" && $tipos!="") {
		$names=explode(",",$names);
		$tipos=explode(",",$tipos);
		$select=array();
		foreach($names as $key=>$val) {
			$campos[]=$val;
			$tipo=$tipos[$key];
			if(!isset($dinamics[$tipo])) die(_LANG("functions_showdatadinamic_unknown_type").$tipo);
			$types[]=$dinamics[$tipo]["type"];
			$textos[]=$dinamics[$tipo]["text"];
			$select[]=$val;
			if($dinamics[$tipo]["type"]=="photo" || $dinamics[$tipo]["type"]=="file") {
				$select[]=$val."_file";
				$select[]=$val."_type";
				$select[]=$val."_size";
			}
		}
		$select=implode(",",$select);
		$query="SELECT $select FROM $table WHERE id='$j'";
		$result=dbQuery($query);
		$row2=dbFetchRow($result);
		dbFree($result);
		foreach($row2 as $key=>$val) $row["$key.$table.$j"]=$val;
	}
}

function getmuestra($table,$campo,$valor,$myid,$edit) {
	$temp="<input type='text' readonly='readonly' id='muestra_{$campo}.{$table}.{$myid}' class='inputs ui-state-default ui-corner-all' style='width:22px;border:0px;background:#$valor' />\n";
	if($edit) {
		$temp.="<script type='text/javascript'>\n";
		$temp.="$(document).ready(function() {\n";
		$temp.="    $(\"input[name='{$campo}.{$table}.{$myid}']\").bind('keyup',function() {\n";
		$temp.="        hexadecimal(this,6);\n";
		$temp.="        $(\"input[id='muestra_\"+$(this).attr('name')+\"']\").css('background',($(this).val()?'#'+$(this).val():''));\n";
		$temp.="    });\n";
		$temp.="});\n";
		$temp.="</script>\n";
	}
	return $temp;
}

function putcolor($campo,$table,$valor,$j,$myform) {
	list($title,$text)=make_title_text("colors",_LANG("functions_putcolor_button_open"),_LANG("functions_putcolor_button_open_title"),"");
	$temp="&nbsp;<a href='javascript:void(0)'><img src='lib/crystal/16x16/colors.png' title='$title' width='16px' height='16px' id='color_{$campo}.{$table}.{$j}' /></a>";
	$temp.="&nbsp;".getmuestra($table,$campo,$valor,$j,1);
	$temp.="<script type='text/javascript'>\n";
	$temp.="$(document).ready(function() {\n";
	$temp.="    $(\"img[id='color_{$campo}.{$table}.{$j}']\").ColorPicker({\n";
	$temp.="    	onBeforeShow:function() {\n";
	$temp.="    		var caja=$(\"input[name='\"+$(this).attr('id').substr(6)+\"']\");\n";
	$temp.="    		$(this).ColorPickerSetColor($(caja).val());\n";
	$temp.="    	},\n";
	$temp.="    	onShow:function(colpkr) {\n";
	$temp.="    		$(colpkr).fadeIn(500);\n";
	$temp.="    		return false;\n";
	$temp.="    	},\n";
	$temp.="    	onHide:function(colpkr) {\n";
	$temp.="    		$(colpkr).fadeOut(500);\n";
	$temp.="    		return false;\n";
	$temp.="    	},\n";
	$temp.="    	onSubmit:function(hsb, hex, rgb, el) {\n";
	$temp.="    		var caja=$(\"input[name='\"+$(el).attr('id').substr(6)+\"']\");\n";
	$temp.="    		$(caja).val(strtoupper(hex));\n";
	$temp.="    		var muestra=$(\"input[id='muestra_\"+$(el).attr('id').substr(6)+\"']\");\n";
	$temp.="    		$(muestra).css('background','#'+hex);\n";
	$temp.="    	}\n";
	$temp.="    });\n";
	$temp.="});\n";
	$temp.="</script>\n";
	$mywidth=($myform!="show")?"width:100px":"";
	$extra=($myform=="show" && $valor)?"&nbsp;".getmuestra($table,$campo,$valor,$j,0):"";
	putinput("$campo.$table.$j",$valor.$extra,$myform,"",$mywidth,$temp);
}

function putdate($campo,$table,$valor,$j,$myform) {
	list($title,$text)=make_title_text("date",_LANG("functions_putdate_button_open"),_LANG("functions_putdate_button_open_title"),"");
	$temp="&nbsp;<a href='javascript:void(0)' onclick='show_{$campo}_{$table}_{$j}()'><img src='lib/crystal/16x16/date.png' title='$title' width='16px' height='16px' id='{$campo}.{$table}.{$j}_img' /></a>\n";
	$temp.="<script type='text/javascript'>\n";
	$temp.="function show_{$campo}_{$table}_{$j}() {\n";
	$temp.="	$(\"input[name='{$campo}.{$table}.{$j}']\").datepicker(\"show\")\n";
	$temp.="}\n";
	$temp.="$(document).ready(function() {\n";
	$temp.="    $(\"input[name='{$campo}.{$table}.{$j}']\").bind('keyup',function() {\n";
	$temp.="        mascara(this,'/',new Array(2,2,4),true);\n";
	$temp.="    });\n";
	$temp.="    $(\"input[name='{$campo}.{$table}.{$j}']\").datepicker({\n";
	$temp.="        dateFormat:'dd/mm/yy',\n";
	$temp.="        firstDay:1,\n";
	$temp.="        numberOfMonths:3,\n";
	$temp.="        showCurrentAtPos:1,\n";
	$temp.="        stepMonths:3,\n";
	$temp.="        showOn:'none'\n";
	$temp.="    });\n";
	$temp.="});\n";
	$temp.="</script>\n";
	$mywidth=($myform!="show")?"width:100px":"";
	putinput("$campo.$table.$j",substr($valor,0,10),$myform,"",$mywidth,$temp);
}

function puttime($campo,$table,$valor,$j,$myform) {
	list($title,$text)=make_title_text("clock",_LANG("functions_putform_time_button"),_LANG("functions_putform_time_button_title"),"");
	$temp="&nbsp;<img src='lib/crystal/16x16/clock.png' title='$title' width='16px' height='16px' />";
	$mywidth=($myform!="show")?"width:100px":"";
	putinput("$campo.$table.$j",substr($valor,0,5),$myform,"onkeyup=\"javascript:mascara(this,':',new Array(2,2),true)\"",$mywidth,$temp);
}

function putdatetime($campo,$table,$valor,$j,$myform) {
	global $width_obj;
	if($myform!="show") {
		$onchange="$('input[name=\'{$campo}.{$table}.{$j}\']').val($('input[name=\'{$campo}_date.{$table}.{$j}\']').val()+' '+$('input[name=\'{$campo}_time.{$table}.{$j}\']').val());";
		$temp="";
		$temp.=getinput($table,$campo."_date",substr($valor,0,10),16,$j,0,"onchange=\"{$onchange}\"");
		list($title,$text)=make_title_text("date",_LANG("functions_putdate_button_open"),_LANG("functions_putdate_button_open_title"),"");
		$temp.="&nbsp;<a href='javascript:void(0)' onclick='show_{$campo}_date_{$table}_{$j}()'><img src='lib/crystal/16x16/date.png' title='$title' width='16px' height='16px' id='{$campo}_date.{$table}.{$j}_img' /></a>\n";
		$temp.="<script type='text/javascript'>\n";
		$temp.="function show_{$campo}_date_{$table}_{$j}() {\n";
		$temp.="	$(\"input[name='{$campo}_date.{$table}.{$j}']\").datepicker(\"show\")\n";
		$temp.="}\n";
		$temp.="$(document).ready(function() {\n";
		$temp.="    $(\"input[name='{$campo}_date.{$table}.{$j}']\").bind('keyup',function() {\n";
		$temp.="        mascara(this,'/',new Array(2,2,4),true);\n";
		$temp.="    });\n";
		$temp.="    $(\"input[name='{$campo}_date.{$table}.{$j}']\").datepicker({\n";
		$temp.="        dateFormat:'dd/mm/yy',\n";
		$temp.="        firstDay:1,\n";
		$temp.="        numberOfMonths:3,\n";
		$temp.="        showCurrentAtPos:1,\n";
		$temp.="        stepMonths:3,\n";
		$temp.="        showOn:'none'\n";
		$temp.="    });\n";
		$temp.="});\n";
		$temp.="</script>\n";
		$temp.=getinput($table,$campo."_time",substr($valor,11,5),16,$j,0,"onkeyup=\"javascript:mascara(this,':',new Array(2,2),true)\" onchange=\"{$onchange}\"");
		list($title,$text)=make_title_text("clock",_LANG("functions_putform_time_button"),_LANG("functions_putform_time_button_title"),"");
		$temp.="&nbsp;<img src='lib/crystal/16x16/clock.png' title='$title' width='16px' height='16px' />";
		$temp.="<input type='hidden' name='{$campo}.{$table}.{$j}' id='{$campo}.{$table}.{$j}' value='{$valor}' />";
	} else {
		$temp="<table class='tables2' style='width:600px'><tr><td><div class='texts' style='width:600px;overflow:hidden;'>$valor</div></td></tr></table>";
	}
	putcolumn($temp,"left",$width_obj);
}

function put_form($table,$j,$row,$id) {
	global $campos,$textos,$types,$neededs,$uniques,$noedits;
	global $form;
	global $marked;
	global $width_obj;

	$first=0;
	if($id!="") {
		$temp=explode(",",$id);
		if($temp[0]==$j) $first=1;
	} else {
		if($j==0) $first=1;
	}
	openform("900","","","","class='tabla'");
	$count_campos=count($campos);
	if(!$count_campos) $first=0;
	if($first) put_buttons($table,$id,$form);
	$existsneeded=0;
	$existsunique=0;
	$existsoptional=0;
	$existsreadonly=0;
	for($i=0;$i<$count_campos;$i++) {
		$campo=$campos[$i];
		$temp=explode(":",$campo);
		$campo_real=$temp[0];
		$texto=$textos[$i];
		$tipo=$types[$i];
		$needed=isset($neededs[$i])?$neededs[$i]:0;
		$unique=isset($uniques[$i])?$uniques[$i]:0;
		$noedit=isset($noedits[$i])?$noedits[$i]:0;
		if(!isset($row["$campo_real.$table.$j"])) $valor="";
		else $valor=$row["$campo_real.$table.$j"];
		if(!isset($row[$campo."_file.$table.$j"])) $file="";
		else $file=$row[$campo."_file.$table.$j"];
		if(!isset($row[$campo."_size.$table.$j"])) $size="";
		else $size=$row[$campo."_size.$table.$j"];
		if(!isset($row[$campo."_type.$table.$j"])) $type="";
		else $type=$row[$campo."_type.$table.$j"];
		$textneeded="";
		$textunique="";
		$textoptional="";
		$textreadonly="";
		if($form!="show") {
			if($needed) $textneeded="<span class='errors'>(*)</span> ";
			if($unique) $textunique="<span class='errors'>(**)</span> ";
			if($tipo=="password" || $tipo=="md5password" || $tipo=="sha1password") $textoptional="<span class='errors'>(***)</span> ";
			if($tipo=="ajaxselect") $textreadonly="<span class='errors'>(****)</span> ";
			if($needed) $existsneeded=1;
			if($unique) $existsunique=1;
			if($tipo=="password" || $tipo=="md5password" || $tipo=="sha1password") $existsoptional=1;
			if($tipo=="ajaxselect") $existsreadonly=1;
		}
		$bad=ismarked($campo,$table,$j);
		settds("thead");
		$direct=array("ajaxdinamic","serialize");
		if(!in_array($tipo,$direct)) openrow();
		//~ if($bad) settds("thead ui-state-error");
		$bigfields=array("textarea","textareaold","file","photo");
		$bigfield=in_array($tipo,$bigfields)?$bigfield=" bigfield":"";
		if(!in_array($tipo,$direct)) putcolumn($textneeded.$textunique.$textoptional.$textreadonly.$texto.":","right","33%","","texts2".$bigfield);
		settds("tbody");
		if($bad) settds("tbody ui-state-error");
		$myform=$noedit?"show":$form;
		switch($tipo) {
			case "text":
				putinput("$campo.$table.$j",$valor,$myform,"","","");
				break;
			case "textarea":
				puttextarea("$campo.$table.$j",$valor,$myform);
				break;
			case "textareaold":
				puttextarea("$campo.$table.$j",$valor,$myform,false);
				break;
			case "select":
				putselect($table,"$campo.$table.$j",$myform,$valor);
				break;
			case "multiselect":
				putmultiselect($table,"$campo.$table.$j",$myform,$valor);
				break;
			case "file":
				putfile("$campo.$table.$j",$valor,$file,$size,$type,$myform);
				break;
			case "photo":
				putphoto("$campo.$table.$j",$valor,$file,$size,$type,$myform);
				break;
			case "password":
			case "md5password":
			case "sha1password":
				if(($tipo=="md5password" || $tipo=="sha1password") && $myform!="show") $valor="";
				putinput("$campo.$table.$j",$valor,$myform,"type='password' autocomplete='off'","","");
				if($myform!="show") {
					closerow();
					openrow();
					settds("thead");
					//~ if($bad) settds("thead ui-state-error");
					putcolumn($textneeded.$textunique.$textoptional._LANG("functions_putform_retype").mb_strtolower($texto,"UTF-8").": ","right","","","texts2");
					settds("tbody");
					if($bad) settds("tbody ui-state-error");
					putinput($campo."_retype.$table.$j",$valor,$myform,"type='password' autocomplete='off'","","");
				}
				break;
			case "boolean":
				putboolean("$campo.$table.$j",$valor,$myform);
				break;
			case "date":
				putdate($campo,$table,$valor,$j,$myform);
				break;
			case "time":
				puttime($campo,$table,$valor,$j,$myform);
				break;
			case "unixtime":
			case "timestamp":
				putdatetime($campo,$table,$valor,$j,$myform);
				break;
			case "datetime":
				putdatetime($campo,$table,$valor,$j,$myform);
				break;
			case "color":
				if($valor) $valor=hexadecimal($valor,6,true);
				putcolor($campo,$table,$valor,$j,$myform);
				break;
			case "integer":
				putinput("$campo.$table.$j",intval($valor),$myform,"onkeyup=\"javascript:mascara_num(this,1)\"","","");
				break;
			case "real":
			case "decimal":
			case "float":
			case "double":
				putinput("$campo.$table.$j",floatval($valor),$myform,"onkeyup=\"javascript:mascara_num(this,0)\"","","");
				break;
			case "ajaxselect":
				putajaxselect($table,"$campo.$table.$j",$myform,$valor);
				break;
			case "ajaxfilter":
				putajaxfilter($table,"$campo.$table.$j",$myform,$valor);
				break;
			case "ajaxdinamic":
				openrow();
				settds("tdsh thead ui-widget-header");
				putcolumn($texto,"center","","2","texts2");
				closerow();
				$myform=$form;
				if($form=="" && $id=="") $myform="insert";
				if($form=="" && $id!="") $myform="update";
				putajaxdinamic($table,"$campo.$table.$j",$myform,$valor);
				break;
			case "serialize":
				openrow();
				settds("tdsh thead ui-widget-header");
				putcolumn($texto,"center","","2","texts2");
				closerow();
				capture_next_error();
				$valor1=base64_decode($valor);
				$error1=get_clear_error();
				capture_next_error();
				$valor2=unserialize($valor1);
				$error2=get_clear_error();
				if($error2!="") {
					capture_next_error();
					$valor2=unserialize(fix_serialized_string($valor1));
					if(get_clear_error()=="") $error2="";
				}
				if($error1!="") {
					openrow();
					settds("thead");
					putcolumn("","right","33%","","texts2".$bigfield);
					settds("tbody");
					puttextarea("$campo.$table.$j",$error1,$myform);
					closerow();
					openrow();
					settds("thead");
					putcolumn("","right","33%","","texts2".$bigfield);
					settds("tbody");
					puttextarea("$campo.$table.$j",$valor,$myform);
					closerow();
				} elseif($error2!="") {
					openrow();
					settds("thead");
					putcolumn("","right","33%","","texts2".$bigfield);
					settds("tbody");
					puttextarea("$campo.$table.$j",$error2,$myform);
					closerow();
					openrow();
					settds("thead");
					putcolumn("","right","33%","","texts2".$bigfield);
					settds("tbody");
					puttextarea("$campo.$table.$j",$valor,$myform);
					closerow();
					openrow();
					settds("thead");
					putcolumn("","right","33%","","texts2".$bigfield);
					settds("tbody");
					puttextarea("$campo.$table.$j",$valor1,$myform);
					closerow();
				} elseif(is_array($valor2)) {
					foreach($valor2 as $key=>$val) {
						openrow();
						settds("thead");
						putcolumn($val["name"].":","right","33%","","texts2".$bigfield);
						settds("tbody");
						switch($val["type"]) {
							case "text":
								putinput("$campo.$table.$j",$val["value"],$myform,"","","");
								break;
							case "textarea":
								puttextarea("$campo.$table.$j",$val["value"],$myform);
								break;
							default:
								putinput("$campo.$table.$j",$val["value"],$myform,"","","");
								break;
						}
						closerow();
					}
				}
				break;
			default:
				putinput("$campo.$table.$j",$valor,$myform,"","","");
				break;
		}
		if(!in_array($tipo,$direct)) closerow();
	}
	if($form!="show") {
		$text="";
		if($existsneeded) $text=myconcattxt("(*) "._LANG("functions_putform_field_needed"),$text);
		if($existsunique) $text=myconcattxt("(**) "._LANG("functions_putform_field_unique"),$text);
		if($existsoptional) $text=myconcattxt("(***) "._LANG("functions_putform_field_optional"),$text);
		if($existsreadonly) $text=myconcattxt("(****) "._LANG("functions_putform_field_readonly"),$text);
		if($text!="") {
			openrow();
			settds("thead");
			putcolumn("<span class='errors'>$text</span>","center","","2");
			closerow();
		}
	}
	put_buttons($table,$id,$form);
	closeform();
}

function fix_serialized_string($buffer) {
	$buffer=explode(";",$buffer);
	foreach($buffer as $key=>$val) {
		$val=explode(":",$val);
		if($val[0]=="s") $val[1]=strlen($val[2])-2;
		elseif($val[0]=="a" && $val[2]=="{s") $val[3]=strlen($val[4])-2;
		$val=implode(":",$val);
		$buffer[$key]=$val;
	}
	$buffer=implode(";",$buffer);
	return $buffer;
}

function getcolumns($table,$prefix="") {
	$query="/*MYSQL SHOW COLUMNS FROM $table *//*SQLITE PRAGMA TABLE_INFO($table) */";
	$result=dbQuery($query);
	$campos=array();
	$len=strlen($prefix);
	if(dbNumRows($result)>0) {
		$dbtemp=getdbtype();
		while($row=dbFetchRow($result)) {
			if($dbtemp=="MYSQL") $campo=$row["Field"];
			if($dbtemp=="SQLITE") $campo=$row["name"];
			if($len>0) {
				if(substr($campo,0,$len)==$prefix) $campos[]=$campo;
			} else {
				$campos[]=$campo;
			}
		}
	}
	dbFree($result);
	return $campos;
}

function has_ajaxdinamic() {
	global $types;

	if(is_array($types)) foreach($types as $type) if($type=="ajaxdinamic") return 1;
	return 0;
}

function prepare_buttons($tipos,$variable) {
	global $dinamics;

	if(!isset($dinamics)) getdinamicsconfig();
	// MONTAR BOTONERA OPCIONES ADD
	$buttons="&nbsp;";
	$buttons.="<select class='inputs ui-state-default ui-corner-all'>";
	$buttons.="<option value=''>"._LANG("functions_preparebuttons_default_option")."</option>";
	foreach($tipos as $tipo) {
		$temp=str_replace("_","+",$tipo);
		if(isset($dinamics[$tipo])) {
			$texto=$dinamics[$tipo]["text"];
			$buttons.="<option value='$tipo'>$texto</option>";
		} elseif(isset($dinamics[$temp])) {
			$texto=$dinamics[$temp]["text"];
			$buttons.="<option value='$temp'>$texto</option>";
		}
	}
	$buttons.="</select>";
	$buttons.="&nbsp;";
	list($title,$text)=make_title_text("new_window",_LANG("functions_preparebuttons_button_add"),_LANG("functions_preparebuttons_button_add_title"),_LANG("functions_preparebuttons_button_add"));
	$url="add_data(this,\"{$variable}\",\"\");";
	$buttons.=get_button($title,$url,"","22","",$text);
	$buttons.="&nbsp;";
	// MONTAR BOTON SUBIR
	list($title,$text)=make_title_text("1downarrow",_LANG("functions_preparebuttons_button_moveup"),_LANG("functions_preparebuttons_button_moveup_title"),"");
	$url="up_data(this,\"{$variable}\");";
	$borrar="&nbsp;";
	$borrar.=get_button($title,$url,"","22","",$text);
	// MONTAR BOTON BAJAR
	list($title,$text)=make_title_text("1uparrow",_LANG("functions_preparebuttons_button_movedown"),_LANG("functions_preparebuttons_button_movedown_title"),"");
	$url="down_data(this,\"{$variable}\");";
	$borrar.=get_button($title,$url,"","22","",$text);
	// MONTAR BOTON BORRAR
	list($title,$text)=make_title_text("button_cancel",_LANG("functions_preparebuttons_button_delete"),_LANG("functions_preparebuttons_button_delete_title"),"");
	$url="del_data(this,\"{$variable}\");";
	$borrar.=get_button($title,$url,"","22","",$text);
	$borrar.="&nbsp;";
	return array($buttons,$borrar);
}

function putajaxdinamic($table,$variable,$form,$default) {
	global $dinamics;
	global $row;
	global $id_real;

	if(!isset($dinamics)) getdinamicsconfig();
	// PREPARAR DATOS
	$temp=explode(".",$variable);
	$patron=$temp[0]."_count_";
	$campos=getcolumns($table,$patron);
	$len=strlen($patron);
	$tipos=array();
	foreach($campos as $campo) $tipos[]=substr($campo,$len);
	$patron=$temp[0]."_data_";
	$campos=getcolumns($table,$patron);
	$len=strlen($patron);
	$maxdata=0;
	foreach($campos as $campo) {
		$campo=substr($campo,$len);
		if(strpos($campo,"_")===false) {
			$campo=intval($campo);
			if($campo>$maxdata) $maxdata=$campo+1;
		}
	}
	// CONTINUAR
	if($default=="") $default="||";
	if($default=="|") $default="||";
	$temp2=explode("|",$default);
	if(!isset($temp2[2])) {
		$temp2[0]=explode(",",$temp2[0]);
		$temp2[2]=array();
		for($i=0;$i<count($temp2[0]);$i++) $temp2[2][]="1";
		$temp2[0]=implode(",",$temp2[0]);
		$temp2[2]=implode(",",$temp2[2]);
		$default=implode("|",$temp2);
	}
	if($form!="show") {
		list($buttons,$borrar)=prepare_buttons($tipos,$variable);
		$buttons2=base64_encode($buttons);
		$borrar2=base64_encode($borrar);
		puthidden($variable."_add",$buttons2);
		puthidden($variable."_del",$borrar2);
		puthidden($variable."_max",$maxdata);
		puthidden($variable."_msg",_LANG("functions_putajaxdinamic_message_max"));
		puthidden($variable."_name",$temp2[0]);
		puthidden($variable."_type",$temp2[1]);
		puthidden($variable."_group",$temp2[2]);
		puthidden($variable,$default);
		openrow();
		settds("tnada");
		putcolumn($buttons,"center","","2","","style='height:33px'");
		closerow();
	}
	if($default!="" && $default!="|" && $default!="||") {
		$temp2[0]=explode(",",$temp2[0]);
		$temp2[1]=explode(",",$temp2[1]);
		$temp2[2]=explode(",",$temp2[2]);
		$temp2_2=$temp2[2];
		$select=array();
		foreach($temp2[0] as $key=>$val) {
			$tipo=$temp2[1][$key];
			$select[]=$val;
			if($dinamics[$tipo]["type"]=="photo" || $dinamics[$tipo]["type"]=="file") {
				$select[]=$val."_file";
				$select[]=$val."_type";
				$select[]=$val."_size";
			}
		}
		$select=implode(",",$select);
		$myid=($form=="copy")?$id_real[$temp[2]]:$temp[2];
		$query="SELECT $select FROM $table WHERE id='$myid'";
		$result=dbQuery($query);
		$row2=dbFetchRow($result);
		dbFree($result);
		$grupo=0;
		foreach($temp2[0] as $key=>$val) {
			openrow(($temp2[2][$grupo]!=$temp2_2[$grupo])?"extra='true'":"movable='true'");
			$type=$temp2[1][$key];
			if(!isset($dinamics[$type])) die(_LANG("functions_putajaxdinamic_unknown_type").$type);
			$tipo=$dinamics[$type]["type"];
			$texto=$dinamics[$type]["text"];
			$campo="$val.{$temp[1]}.{$temp[2]}";
			$campo_file="{$val}_file.{$temp[1]}.{$temp[2]}";
			$campo_size="{$val}_size.{$temp[1]}.{$temp[2]}";
			$campo_type="{$val}_type.{$temp[1]}.{$temp[2]}";
			settds("thead");
			$borrar2=isset($borrar)?$borrar:"";
			if(($tipo=="file" || $tipo=="photo") && check_demo("user")) $borrar2="";
			if($temp2[2][$grupo]!=$temp2_2[$grupo]) $borrar2="";
			$bigfields=array("textarea","textareaold","file","photo");
			$bigfield=in_array($tipo,$bigfields)?$bigfield=" bigfield":"";
			putcolumn($borrar2.$texto.":","right","33%","","texts2".$bigfield);
			$valor=isset($row[$campo])?$row[$campo]:$row2[$val];
			if($dinamics[$type]["type"]=="photo" || $dinamics[$type]["type"]=="file") {
				$valor_file=isset($row[$campo_file])?$row[$campo_file]:$row2[$val."_file"];
				$valor_size=isset($row[$campo_size])?$row[$campo_size]:$row2[$val."_size"];
				$valor_type=isset($row[$campo_type])?$row[$campo_type]:$row2[$val."_type"];
			}
			settds("tbody");
			switch($tipo) {
				case "text":
					putinput($campo,$valor,$form,"","","");
					break;
				case "textarea":
					puttextarea($campo,$valor,$form);
					break;
				case "textareaold":
					puttextarea($campo,$valor,$form,false);
					break;
				case "file":
					$myform=($form=="show")?"show":"over";
					putfile($campo,$valor,$valor_file,$valor_size,$valor_type,$myform);
					break;
				case "photo":
					$myform=($form=="show")?"show":"over";
					putphoto($campo,$valor,$valor_file,$valor_size,$valor_type,$myform);
					break;
				default:
					die();
					break;
			}
			echo "<script type='text/javascript'>\n";
			echo "$(document).ready(function() {\n";
			echo "    $(\"*[name='$campo']\").attr('tipo','$type');\n";
			echo "});\n";
			echo "</script>\n";
			closerow();
			if($form!="show") {
				$temp2[2][$grupo]--;
				if($temp2[2][$grupo]<1) {
					openrow();
					settds("tnada");
					putcolumn($buttons,"center","","2","","style='height:33px'");
					closerow();
					$grupo++;
				}
			}
		}
	}
}

function put_buttons($table,$id,$form) {
	if($form!="show") {
		openrow();
		settds("");
		if($id=="") {
			list($title,$text)=make_title_text("button_ok",_LANG("functions_putbuttons_button_create"),_LANG("functions_putbuttons_button_create_title"),_LANG("functions_putbuttons_button_create"));
		} else {
			list($title,$text)=make_title_text("button_ok",_LANG("functions_putbuttons_button_update"),_LANG("functions_putbuttons_button_update_title"),_LANG("functions_putbuttons_button_update"));
		}
		$url="myreturnhere(0);mysubmit();";
		$temp=get_button($title,$url,"","22","",$text);
		if($id=="") {
			list($title,$text)=make_title_text("quick_restart",_LANG("functions_putbuttons_button_create2"),_LANG("functions_putbuttons_button_create2_title"),_LANG("functions_putbuttons_button_create2"));
		} else {
			list($title,$text)=make_title_text("quick_restart",_LANG("functions_putbuttons_button_update2"),_LANG("functions_putbuttons_button_update2_title"),_LANG("functions_putbuttons_button_update2"));
		}
		$url="myreturnhere(1);mysubmit();";
		$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
		if($id=="") {
			list($title,$text)=make_title_text("button_cancel",_LANG("functions_putbuttons_button_cancel"),_LANG("functions_putbuttons_button_cancel_create"),_LANG("functions_putbuttons_button_cancel"));
		} else {
			list($title,$text)=make_title_text("button_cancel",_LANG("functions_putbuttons_button_cancel"),_LANG("functions_putbuttons_button_cancel_update"),_LANG("functions_putbuttons_button_cancel"));
		}
		$url="redir(\"inicio.php?page=list&table=$table&id=$id\");";
		$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
		putcolumn($temp,"center","","2","","style='height:33px'");
		closerow();
	} else {
		openrow();
		settds("");
		list($title,$text)=make_title_text("button_ok",_LANG("functions_putbuttons_button_return"),_LANG("functions_putbuttons_button_return_title"),_LANG("functions_putbuttons_button_return"));
		$url="redir(\"inicio.php?page=list&table=$table&id=$id\");";
		$temp=get_button($title,$url,"","22","",$text);
		$updates=check_permissions("update",$table);
		if($updates) {
			list($title,$text)=make_title_text("quick_restart",_LANG("functions_putbuttons_button_update3"),_LANG("functions_putbuttons_button_update3_title"),_LANG("functions_putbuttons_button_update3"));
			$url="redir(\"inicio.php?page=form&form=edit&table=$table&id=$id\");";
			$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
		}
		putcolumn($temp,"center","","2","","style='height:33px'");
		closerow();
	}
}

function ismarked($campo,$table,$j) {
	global $marked;

	$num=count($marked);
	for($i=0;$i<$num;$i++) if("$campo.$table.$j"==$marked[$i] || "$campo"."_$table"."_$j"==$marked[$i]) return 1;
	return 0;
}

function checkMultipleParameters($table,$j,$id) {
	global $campos,$textos,$types,$neededs,$uniques,$noedits;
	global $error,$row,$marked;

	$count_campos=count($campos);
	for($i=0;$i<$count_campos;$i++) {
		$campo=$campos[$i];
		$texto=$textos[$i];
		$type=$types[$i];
		$unique=$uniques[$i];
		$needed=$neededs[$i];
		$noedit=isset($noedits[$i])?$noedits[$i]:0;
		$value=getParam("$campo"."_$table"."_$j");
		$row["$campo.$table.$j"]=$value;
		$check=1;
		if($type=="password" || $type=="md5password" || $type=="sha1password") {
			$value_retype=getParam($campo."_retype_$table"."_$j");
			if($value!=$value_retype) {
				$temp=_LANG("functions_checkmultipleparameters_message_diff_error");
				$temp=str_replace("#campo1#",$texto,$temp);
				$temp=str_replace("#campo2#",mb_strtolower($texto,"UTF-8"),$temp);
				$error[]="ERROR:".$temp;
				$marked[]="$campo"."_$table"."_$j";
			}
		} elseif($type=="file" || $type=="photo") {
			$file=getParam($campo."_file_$table"."_$j");
			$size=getParam($campo."_size_$table"."_$j");
			$type=getParam($campo."_type_$table"."_$j");
			$row[$campo."_file.$table.$j"]=$file;
			$row[$campo."_size.$table.$j"]=$size;
			$row[$campo."_type.$table.$j"]=$type;
			$check=0;
		} elseif($noedit) {
			$check=0;
		}
		if($unique && $check) {
			if($id=="") {
				$query="SELECT $campo FROM $table WHERE $campo='$value'";
				$result=dbQuery($query);
				if(dbNumRows($result)>0) {
					$error[]="ERROR:"._LANG("functions_checkmultipleparameters_message_unique_error").$texto;
					$marked[]="$campo"."_$table"."_$j";
				}
				dbFree($result);
			} else {
				$query="SELECT $campo FROM $table WHERE $campo='$value' AND id<>'$j'";
				$result=dbQuery($query);
				if(dbNumRows($result)>0) {
					$error[]="ERROR:"._LANG("functions_checkmultipleparameters_message_unique_error").$texto;
					$marked[]="$campo"."_$table"."_$j";
				}
				dbFree($result);
			}
		}
		if($needed && $check) {
			if($value=="") {
				$temp=_LANG("functions_checkmultipleparameters_message_needed_error");
				$temp=str_replace("#campo#",$texto,$temp);
				$error[]="ERROR:".$temp;
				$marked[]="$campo"."_$table"."_$j";
			}
		}
	}
}

function check_data_dinamic($campo,$table,$j) {
	global $campos,$types;
	global $dinamics;
	global $row;

	if(!isset($dinamics)) getdinamicsconfig();
	$names=getParam("{$campo}_{$table}_{$j}_name");
	$tipos=getParam("{$campo}_{$table}_{$j}_type");
	$grupos=getParam("{$campo}_{$table}_{$j}_group");
	$row["$campo.$table.$j"]="$names|$tipos|$grupos";
	if($names!="" && $tipos!="") {
		$names=explode(",",$names);
		$tipos=explode(",",$tipos);
		foreach($names as $key=>$name) {
			$tipo=$tipos[$key];
			$row["$name.$table.$j"]=getParam("{$name}_{$table}_{$j}");
			if($dinamics[$tipo]["type"]=="photo" || $dinamics[$tipo]["type"]=="file") {
				$row["{$name}_file.$table.$j"]=getParam("{$name}_file_{$table}_{$j}");
				$row["{$name}_size.$table.$j"]=getParam("{$name}_size_{$table}_{$j}");
				$row["{$name}_type.$table.$j"]=getParam("{$name}_type_{$table}_{$j}");
			}
		}
	}
}

function update_data_dinamic($campo,$table,$j) {
	global $campos,$types;
	global $dinamics;

	if(!isset($dinamics)) getdinamicsconfig();
	foreach($campos as $key=>$val) {
		if($campo==$val) {
			$types[$key]="text";
			break;
		}
	}
	$oldvalue=getParam("{$campo}_{$table}_{$j}");
	$tocheck=array();
	if($oldvalue!="" && $oldvalue!="|" && $oldvalue!="||") {
		$temp=explode("|",$oldvalue);
		$temp[0]=explode(",",$temp[0]);
		$temp[1]=explode(",",$temp[1]);
		foreach($temp[0] as $key=>$val) {
			$tipo=$temp[1][$key];
			if($dinamics[$tipo]["type"]=="photo" || $dinamics[$tipo]["type"]=="file") {
				$query="SELECT {$val}_file FROM $table WHERE id='$j'";
				$result=dbQuery($query);
				$row=dbFetchRow($result);
				dbFree($result);
				if(!isset($row["{$val}_file"])) $row["{$val}_file"]="";
				$tocheck[$val]=$row["{$val}_file"];
				$key2="{$val}_file_{$table}_{$j}";
				if(getParam($key2)=="") $_POST[$key2]=$row["{$val}_file"];
			}
		}
	}
	$names=getParam("{$campo}_{$table}_{$j}_name");
	$tipos=getParam("{$campo}_{$table}_{$j}_type");
	$grupos=getParam("{$campo}_{$table}_{$j}_group");
	$_POST["{$campo}_{$table}_{$j}"]="$names|$tipos|$grupos";
	$patron=$campo."_count_";
	$temps=getcolumns($table,$patron);
	$counters=array();
	foreach($temps as $temp) {
		$temp=str_replace($patron,"",$temp);
		$counters[$temp]=0;
	}
	$counter=0;
	if($names!="" && $tipos!="" && $grupos!="") {
		$names=explode(",",$names);
		$tipos=explode(",",$tipos);
		$grupos=explode(",",$grupos);
		$grupo=0;
		$tipo2="";
		foreach($names as $key=>$val) {
			$campos[]=$val;
			$tipo=$tipos[$key];
			if(!isset($dinamics[$tipo])) die(_LANG("functions_updatedatadinamic_message_error_type").$tipo);
			$types[]=$dinamics[$tipo]["type"];
			$grupos[$grupo]--;
			if($grupos[$grupo]<1) {
				if(!isset($counters[$tipo2.$tipo])) die(_LANG("functions_updatedatadinamic_message_error_counter").$tipo);
				$counters[$tipo2.$tipo]++;
				$counter++;
				$grupo++;
				$tipo2="";
			} else {
				$tipo2.=$tipo."_";
			}
		}
	}
	$_POST["{$campo}_count_{$table}_{$j}"]=$counter;
	$campos[]="{$campo}_count";
	$types[]="text";
	foreach($counters as $key=>$val) {
		$campo2=$patron.$key;
		$_POST["{$campo2}_{$table}_{$j}"]=$val;
		$campos[]=$campo2;
		$types[]="text";
	}
	foreach($tocheck as $key=>$val) {
		$borrar=0;
		if(!in_array($key,$campos)) {
			$borrar=1;
		} else {
			foreach($campos as $key2=>$val2) {
				if($key==$val2 && $types[$key2]!="photo" && $types[$key2]!="file") $borrar=1;
			}
		}
		if($borrar && file_exists("files/".$val) && is_file("files/".$val)) unlink("files/".$val);
	}
	if(column_exists($table,$campo."_search")) {
		$prefijo="{$campo}_search";
		$sufijo="_{$table}_{$j}";
		$_POST[$prefijo.$sufijo]=array();
		$campos[]=$prefijo;
		$types[]="text";
		if(is_array($names) && is_array($tipos)) {
			foreach($names as $key=>$val) {
				$tipo=$dinamics[$tipos[$key]]["type"];
				if($tipo=="photo" || $tipo=="file") {
					if(isset($_FILES[$val."_new".$sufijo]) && $_FILES[$val."_new".$sufijo]["name"]!="") {
						$_POST[$prefijo.$sufijo][]=$_FILES[$val."_new".$sufijo]["name"];
					} else {
						$_POST[$prefijo.$sufijo][]=$_POST[$val.$sufijo];
					}
				} else {
					$_POST[$prefijo.$sufijo][]=$_POST[$val.$sufijo];
				}
			}
		}
		$_POST[$prefijo.$sufijo]=implode("\n",$_POST[$prefijo.$sufijo]);
	}
	if(0) {
		echo "<pre style='text-align:left'>";
		print_r($tocheck);
		print_r($campos);
		print_r($types);
		print_r($names);
		print_r($tipos);
		print_r($_POST);
		print_r($_FILES);
		echo "</pre>";
		die();
	}
}

function processMultipleParameters($table,$j,$id) {
	global $campos,$textos,$types,$neededs,$uniques,$noedits,$defaults;
	$qcampos="";
	$qvalues="";
	$qsets="";
	$count_campos=count($campos);
	if(!$count_campos) return;
	for($i=0;$i<$count_campos;$i++) {
		$campo=$campos[$i];
		$type=$types[$i];
		$value=getParam("$campo"."_$table"."_$j");
		$default=isset($defaults[$i])?$defaults[$i]:"";
		$noedit=isset($noedits[$i])?$noedits[$i]:0;
		if($type=="date") {
			$value=invert_date($value);
		} elseif($type=="unixtime" || $type=="timestamp") {
			$value=strtotime(invert_date(substr($value,0,10))." ".substr($value,11,5));
		} elseif($type=="datetime") {
			$value=invert_date(substr($value,0,10))." ".substr($value,11,5);
		} elseif($type=="md5password") {
			$value=md5($value);
		} elseif($type=="sha1password") {
			$value=sha1($value);
		} elseif($type=="file" || $type=="photo") {
			$file=getParam($campo."_file_$table"."_$j");
			$size=getParam($campo."_size_$table"."_$j");
			$type=getParam($campo."_type_$table"."_$j");
			$del=getParam($campo."_del_$table"."_$j");
			if(isset($_FILES[$campo."_new_$table"."_$j"])) {
				$file_new=$_FILES[$campo."_new_$table"."_$j"]["name"];
				$size_new=$_FILES[$campo."_new_$table"."_$j"]["size"];
				$type_new=$_FILES[$campo."_new_$table"."_$j"]["type"];
				$tmp_new=$_FILES[$campo."_new_$table"."_$j"]["tmp_name"];
			} else {
				$file_new="";
			}
			if($del=="1" || ($file!="" && $file_new!="")) {
				if($id!="" && file_exists("files/".$file)) unlink("files/".$file);
				$value="";
				$file="";
				$size="";
				$type="";
			}
			if($id=="" && $file!="") {
				for($k=0;;$k++) {
					$temp=explode(".",$file);
					$temp[0]=time()+$k;
					$temp=implode(".",$temp);
					if(!file_exists("files/".$temp)) break;
				}
				copy("files/".$file,"files/".$temp);
				$file=$temp;
			}
			if($file_new!="") {
				$value=$file_new;
				$temp=explode(".",$file_new);
				$count_temp=count($temp);
				$ext="dat";
				if($count_temp>0) $ext=$temp[$count_temp-1];
				$ext=mb_strtolower($ext,"UTF-8");
				if($ext=="php") $ext="dat"; // FOR SECURITY REASONS
				$file=time().".".md5("$campo.$table.$j").".".$ext;
				$size=$size_new;
				$type=$type_new;
				move_uploaded_file($tmp_new,"files/".$file);
				if(file_exists($tmp_new) && !file_exists("files/".$file)) {
					rename($tmp_new,"files/".$file);
					@chmod("files/".$file,0666);
				}
			}
			if($qcampos!="") $qcampos=$qcampos.",";
			$qcampos=$qcampos."`".$campo."_file`";
			if($qvalues!="") $qvalues=$qvalues.",";
			$qvalues=$qvalues."'$file'";
			if($qsets!="") $qsets=$qsets.",";
			$qsets=$qsets."`".$campo."_file`='$file'";
			$qcampos=$qcampos.",";
			$qcampos=$qcampos."`".$campo."_size`";
			$qvalues=$qvalues.",";
			$qvalues=$qvalues."'$size'";
			$qsets=$qsets.",";
			$qsets=$qsets."`".$campo."_size`='$size'";
			$qcampos=$qcampos.",";
			$qcampos=$qcampos."`".$campo."_type`";
			$qvalues=$qvalues.",";
			$qvalues=$qvalues."'$type'";
			$qsets=$qsets.",";
			$qsets=$qsets."`".$campo."_type`='$type'";
		} elseif($type=="text") {
			$value=some_htmlentities($value);
		}
		$addtoquery=1;
		if($type=="ajaxselect") $addtoquery=0;
		if($type=="password" && $value=="") $addtoquery=0;
		if($type=="md5password" && $value==md5("")) $addtoquery=0;
		if($type=="sha1password" && $value==sha1("")) $addtoquery=0;
		if($noedit) $addtoquery=0;
		if($addtoquery) {
			if($value=="") $value=$default;
			if($qcampos!="") $qcampos=$qcampos.",";
			$qcampos=$qcampos."`$campo`";
			if($qvalues!="") $qvalues=$qvalues.",";
			$qvalues=$qvalues."'$value'";
			if($qsets!="") $qsets=$qsets.",";
			$qsets=$qsets."`$campo`='$value'";
		}
	}
	if(column_exists($table,"_modified")) {
		$campo="_modified";
		$value=time();
		if($qcampos!="") $qcampos=$qcampos.",";
		$qcampos=$qcampos."`$campo`";
		if($qvalues!="") $qvalues=$qvalues.",";
		$qvalues=$qvalues."'$value'";
		if($qsets!="") $qsets=$qsets.",";
		$qsets=$qsets."`$campo`='$value'";
	}
	if($id=="") {
		$query="INSERT INTO $table ($qcampos) VALUES ($qvalues)";
	} else {
		$query="UPDATE $table SET $qsets WHERE id='$j'";
	}
	//die("<pre>".htmlentities($query,ENT_COMPAT,"UTF-8")."</pre>");
	dbQuery($query);
}

function some_htmlentities($value,$type="encode") {
	static $orig=array("&","<",">");
	static $dest=array("&amp;","&lt;","&gt;");
	if($type=="encode") $value=str_replace($orig,$dest,$value);
	elseif($type=="decode") $value=str_replace($orig,$dest,$value);
	else die("Unknown type: $type");
	return $value;
}

function quot_htmlentities($value,$type="encode") {
	if($type=="encode") $value=str_replace("\"","&quot;",$value);
	elseif($type=="decode") $value=str_replace("&quot;","\"",$value);
	else die("Unknown type: $type");
	return $value;
}

function is_selected($id,$lista) {
	$temp=explode(",",$lista);
	for($i=0;$i<count($temp);$i++) if($temp[$i]==$id) return true;
	return false;
}

function is_needed($table,$id) {
	static $notexists=array();
	if(isset($notexists[$table])) return 0;
	$query="SELECT _needed FROM $table WHERE id IN ($id)";
	capture_next_error();
	$result=dbQuery($query);
	$error=get_clear_error();
	$needed=0;
	if($error) {
		$notexists[$table]=1;
	} else {
		while($row=dbFetchRow($result)) $needed=$needed+intval($row["_needed"]);
		dbFree($result);
	}
	return $needed;
}

function intro() {
	location("inicio.php?page=intro");
	die();
}

function getdbtype() {
	global $dbtype;
	static $result=null;
	if(is_null($result)) {
		switch($dbtype) {
			case "mysql":
			case "mysqli":
			case "pdo_mysql":
				$result="MYSQL";
				break;
			case "pdo_sqlite":
			case "sqlite3":
			case "bin_sqlite":
				$result="SQLITE";
				break;
			default:
				show_php_error(array("phperror"=>_LANG("getdbtype: unknown dbtype='$dbtype'")));
				break;
		}
	}
	return $result;
}

function process_query($usepag=1,$onlycount=0) {
	global $campos,$types,$textos;
	global $table;
	global $search;
	global $order,$limit,$offset;

	$count_campos=count($campos);
	$order_array=$order?explode(",",$order):array();
	foreach($order_array as $key=>$val) {
		$val=explode(".",$val);
		$val[1]=explode(" ",$val[1]);
		$order_array[$key]=$val;
	}
	if(count($order_array)==2) {
		if($order_array[0][0]==$order_array[1][0] && $order_array[0][1][0]==$order_array[1][1][0]) {
			unset($order_array[1]);
		}
	}
	for($i=0;$i<$count_campos;$i++) {
		$texto=$textos[$i];
		$campo=$campos[$i];
		$type=$types[$i];
		if($type=="select" || $type=="multiselect") {
			$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$campo'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$table_ref=$row["table_ref"];
			$value_ref=$row["value_ref"];
			$text_ref=$row["text_ref"];
			dbFree($result);
			$temp=explode(":",$text_ref);
			if($temp[0]=="concat") {
				unset($temp[0]);
				$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
				$text_ref=parseQuery($text_ref,getdbtype());
			}
			foreach($order_array as $key=>$val) {
				$val[1][0]=str_replace("+"," ",$val[1][0]);
/*
				if($val[0]==$table_ref && $val[1][0]==$text_ref) {
					$order_array[$key][0]=$table_ref."_$i";
				}
*/
				if($val[0]==$table && $val[1][0]==$campo) {
					if($table!=$table_ref) $order_array[$key][0]=$table_ref."_$i";
					else $order_array[$key][0]=$table_ref."_filter_$i";
					$order_array[$key][1][0]=$text_ref;
				}
			}
		}
	}
	$count_campos=count($campos);
	foreach($order_array as $key=>$val) {
		$temp=explode(":",$val[1][0]);
		if($temp[0]=="concat") {
			unset($temp[0]);
			$val[1][0]="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
			$val[1][0]=parseQuery($val[1][0],getdbtype());
			$val[1][0]=str_replace("+"," ",$val[1][0]);
			unset($val[0]);
		}
		$val[1]=implode(" ",$val[1]);
		$val=implode(".",$val);
		$order_array[$key]=$val;
	}
	$order_new=implode(",",$order_array);
	if($usepag) {
		if($order_new=="") $order_new="$table.id asc";
		$limit_query="LIMIT $limit OFFSET $offset";
		if($limit=="inf") $limit_query="";
		$post_query="ORDER BY $order_new $limit_query";
	} else {
		if($order_new=="") $order_new="$table.id asc";
		$post_query="ORDER BY $order_new";
	}
	$lista_tables=$table;
	$lista_wheres="1";
	$lista_search="0";
	$lista_campos="`$table`.`id` as `$table"."_id`";
	$expr1=array(" ","*");
	$expr2=array("%","%");
	$temp=explode("=",$search);
	if(count($temp)==2) {
		$field=$temp[0];
		$value=$temp[1];
		for($i=0;$i<$count_campos;$i++) {
			$texto=$textos[$i];
			$campo=$campos[$i];
			$type=$types[$i];
			if($field==$texto) {
				$field="$table.$campo";
				if($type=="date") {
					$value=invert_date($value);
				} elseif($type=="unixtime" || $type=="timestamp") {
					$value=strtotime(invert_date(substr($value,0,10))." ".substr($value,11,5));
				} elseif($type=="datetime") {
					$value=invert_date(substr($value,0,10))." ".substr($value,11,5);
				} elseif($type=="select") {
					$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$campo'";
					$result=dbQuery($query);
					$row=dbFetchRow($result);
					$table_ref=$row["table_ref"];
					$value_ref=$row["value_ref"];
					$text_ref=$row["text_ref"];
					dbFree($result);
					$temp=explode(":",$text_ref);
					if($temp[0]=="concat") {
						unset($temp[0]);
						$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
						$text_ref=parseQuery($text_ref,getdbtype());
					}
					$query="SELECT $value_ref from $table_ref WHERE $text_ref='$value'";
					$result=dbQuery($query);
					$row=dbFetchRow($result);
					$value=$row[$value_ref];
					if(!$value && $value_ref=="id") $value="0";
					dbFree($result);
				} elseif($type=="boolean") {
					if(strtoupper($value)=="SI") $value="1";
					else $value="0";
				}
				$lista_search.=" OR $field='$value'";
				break;
			}
		}
		if($lista_search=="0") {
			$search_string=str_replace($expr1,$expr2,$search);
		} else {
			$search_string="";
		}
	} else {
		$search_string=str_replace($expr1,$expr2,$search);
	}
	$search_array=explode("|",$search_string);
	$count_array=count($search_array);
	for($i=0;$i<$count_campos;$i++) {
		$campo=$campos[$i];
		$temp=explode(":",$campo);
		$campo_real=$temp[0];
		$tipo=$types[$i];
		if($tipo=="select" || $tipo=="multiselect") {
			$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$campo'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$table_ref=$row["table_ref"];
			$value_ref=$row["value_ref"];
			$text_ref=$row["text_ref"];
			dbFree($result);
			$temp=explode(":",$text_ref);
			if($temp[0]=="concat") {
				unset($temp[0]);
				if($table!=$table_ref) foreach($temp as $key=>$val) if(strpos($val,"'")===false) $temp[$key]=$table_ref."_".$i.".".$val;
				$text_ref="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
				$text_ref=parseQuery($text_ref,getdbtype());
				$lista_campos=myconcat($text_ref,",",$lista_campos);
			} elseif($table==$table_ref) {
				$lista_tables=myconcat("LEFT JOIN `{$table_ref}` as `{$table_ref}_filter_{$i}` ON `{$table}`.`{$campo_real}`=`{$table_ref}_filter_{$i}`.`{$value_ref}`"," ",$lista_tables);
				$lista_campos=myconcat("`{$table_ref}_filter_{$i}`.`{$text_ref}` as `{$table_ref}_filter_{$i}_{$text_ref}`",",",$lista_campos);
			} else {
				$lista_campos=myconcat("`{$table_ref}_{$i}`.`{$text_ref}` as `{$table_ref}_{$i}_{$text_ref}`",",",$lista_campos);
			}
			if($table!=$table_ref) {
				if($tipo=="select") {
					$lista_tables=myconcat("LEFT JOIN `{$table_ref}` as `{$table_ref}_{$i}` ON `{$table}`.`{$campo_real}`=`{$table_ref}_{$i}`.`{$value_ref}`"," ",$lista_tables);
				} else {
					$temp2="LEFT JOIN `{$table_ref}` as `{$table_ref}_{$i}` ON /*MYSQL FIND_IN_SET(`{$table_ref}_{$i}`.`{$value_ref}`,`{$table}`.`{$campo_real}`) *//*SQLITE (`{$table}`.`{$campo_real}` LIKE `{$table_ref}_{$i}`.`{$value_ref}` OR `{$table}`.`{$campo_real}` LIKE '%,' || `{$table_ref}_{$i}`.`{$value_ref}` OR `{$table}`.`{$campo_real}` LIKE '%,' || `{$table_ref}_{$i}`.`{$value_ref}` || ',%' OR `{$table}`.`{$campo_real}` LIKE `{$table_ref}_{$i}`.`{$value_ref}` || ',%') */";
					$temp2=parseQuery($temp2,getdbtype());
					$lista_tables=myconcat($temp2," ",$lista_tables);
				}
			}
			$lista_campos=myconcat("`{$table}`.`{$campo_real}` as `{$table}_{$campo_real}`",",",$lista_campos);
			if($table!=$table_ref) $lista_campos=myconcat("`{$table_ref}_{$i}`.`{$value_ref}` as `{$table_ref}_{$i}_{$value_ref}`",",",$lista_campos);
			if($search_string!="") {
				if($table==$table_ref) {
					$lista_search=myconcatsearch($search_array,$count_array,$lista_search,"{$table_ref}_filter_{$i}.{$text_ref}");
				} else {
					$lista_search=myconcatsearch($search_array,$count_array,$lista_search,"{$table_ref}_{$i}.{$text_ref}");
				}
			}
		} elseif($tipo=="file" || $tipo=="photo") {
			$lista_campos=myconcat("`{$table}`.`{$campo_real}` as `{$table}_{$campo_real}`",",",$lista_campos);
			$lista_campos=myconcat("`{$table}`.`{$campo_real}_file` as `{$table}_{$campo_real}_file`",",",$lista_campos);
			$lista_campos=myconcat("`{$table}`.`{$campo_real}_size` as `{$table}_{$campo_real}_size`",",",$lista_campos);
			$lista_campos=myconcat("`{$table}`.`{$campo_real}_type` as `{$table}_{$campo_real}_type`",",",$lista_campos);
			if($search_string!="") $lista_search=myconcatsearch($search_array,$count_array,$lista_search,"{$table}.{$campo_real}");
		} elseif($tipo=="date") {
			$lista_campos=myconcat("`{$table}`.`{$campo_real}` as `{$table}_{$campo_real}`",",",$lista_campos);
			if($search_string!="") $lista_search=myconcatsearch($search_array,$count_array,$lista_search,"/*MYSQL CONCAT(SUBSTRING({$table}.{$campo_real},9,2),'/',SUBSTRING({$table}.{$campo_real},6,2),'/',SUBSTRING({$table}.{$campo_real},1,4)) *//*SQLITE SUBSTR({$table}.{$campo_real},9,2) || '/' || SUBSTR({$table}.{$campo_real},6,2) || '/' || SUBSTR({$table}.{$campo_real},1,4) */");
		} elseif($tipo=="unixtime" || $tipo=="timestamp") {
			$lista_campos=myconcat("`{$table}`.`{$campo_real}` as `{$table}_{$campo_real}`",",",$lista_campos);
			if($search_string!="") $lista_search=myconcatsearch($search_array,$count_array,$lista_search,"/*MYSQL FROM_UNIXTIME({$table}.{$campo_real},'%d/%m/%Y %H:%i') *//*SQLITE STRFTIME('%d/%m/%Y %H:%M',{$table}.{$campo_real},'UNIXEPOCH','LOCALTIME') */");
		} elseif($tipo=="datetime") {
			$lista_campos=myconcat("`{$table}`.`{$campo_real}` as `{$table}_{$campo_real}`",",",$lista_campos);
			if($search_string!="") $lista_search=myconcatsearch($search_array,$count_array,$lista_search,"/*MYSQL CONCAT(SUBSTRING({$table}.{$campo_real},9,2),'/',SUBSTRING({$table}.{$campo_real},6,2),'/',SUBSTRING({$table}.{$campo_real},1,4),' ',SUBSTRING({$table}.{$campo_real},12,5)) *//*SQLITE SUBSTR({$table}.{$campo_real},9,2) || '/' || SUBSTR({$table}.{$campo_real},6,2) || '/' || SUBSTR({$table}.{$campo_real},1,4) || ' ' || SUBSTR({$table}.{$campo_real},12,5) */");
		} elseif($temp[0]=="concat") {
			unset($temp[0]);
			$alias_real=encode_bad_chars(implode("_",$temp));
			foreach($temp as $key=>$val) if(strpos($val,"'")===false) $temp[$key]="`{$table}`.`{$val}`";
			$campo_real="/*MYSQL CONCAT(".implode(",",$temp).") *//*SQLITE ".implode(" || ",$temp)." */";
			$lista_campos=myconcat("{$campo_real} as `{$table}_{$alias_real}`",",",$lista_campos);
			if($search_string!="") $lista_search=myconcatsearch($search_array,$count_array,$lista_search,$campo_real);
		} else {
			$lista_campos=myconcat("`{$table}`.`{$campo_real}` as `{$table}_{$campo_real}`",",",$lista_campos);
			if($search_string!="") $lista_search=myconcatsearch($search_array,$count_array,$lista_search,"{$table}.{$campo_real}");
		}
	}
	if($lista_search=="0") $lista_search="1";
	$query="SELECT {$lista_campos} FROM {$lista_tables} WHERE {$lista_wheres} AND ({$lista_search}) GROUP BY `{$table}`.`id` {$post_query}";
	if($onlycount) $query="SELECT count(`{$table}_id`) as `count` FROM ({$query}) count";
	//echo "<br/>$query<br/>";
	return $query;
}

function myconcatsearch($search_array,$count_array,$lista_search,$campo) {
	if(substr($campo,0,2)!="/*") {
		if(strpos($campo,"CONCAT")!==false || strpos($campo,"||")!==false) {
			$campo=explode(".",$campo);
			unset($campo[0]);
			$campo=implode(".",$campo);
		} else {
			$campo=explode(".",$campo);
			foreach($campo as $key=>$val) $campo[$key]="`".$val."`";
			$campo=implode(".",$campo);
		}
	}
	if($count_array>0) {
		for($i=0;$i<$count_array;$i++) {
			$search_string=$search_array[$i];
			$lista_search.=" OR $campo LIKE '%$search_string%'";
		}
	}
	return $lista_search;
}

function myconcat($txt1,$txt2,$txt3) {
	if(strpos($txt3,$txt1)!==false) return $txt3;
	$temp=explode($txt2,$txt3);
	$count_temp=count($temp);
	for($i=0;$i<$count_temp;$i++) if($temp[$i]==$txt1) break;
	if($i==$count_temp) $txt3.="$txt2$txt1";
	return $txt3;
}

function myconcattxt($txt1,$txt2) {
	if($txt2!="") $txt2.=", ";
	$txt2.=$txt1;
	return $txt2;
}

function import_config() {
	$query="SELECT * FROM db_config";
	$result=dbQuery($query);
	while($row=dbFetchRow($result)) {
		$param=$row["param"];
		$value=$row["value"];
		global $$param;
		$$param=$value;
	}
	dbFree($result);
}

function set_db_config($param,$value) {
	$cache=set_db_cache("false");
	$query="SELECT id FROM db_config WHERE `param`='$param'";
	$result=dbQuery($query);
	if(dbNumRows($result)==0) {
		$query="INSERT INTO db_config(`id`,`param`,`value`) VALUES(NULL,'$param','$value')";
	} else {
		$query="UPDATE db_config SET `value`='$value' WHERE `param`='$param'";
	}
	dbQuery($query);
	dbFree($result);
	set_db_cache($cache);
}

function get_migas() {
	global $page,$table,$func,$form,$id,$iter;

	if(check_user()) {
		list($title,$text)=make_title_text("folder_home",_LANG("functions_getmigas_button_menu"),_LANG("functions_getmigas_button_menu_title"),_LANG("functions_getmigas_button_menu"),"48:32");
		$url="redir(\"inicio.php\");";
		$temp0=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("database",_LANG("functions_getmigas_button_access_to").mb_strtolower(getnametable($page),"UTF-8"),_LANG("functions_getmigas_button_access_to_title").mb_strtolower(getnametable($page),"UTF-8"),_LANG("functions_getmigas_button_access_to").mb_strtolower(getnametable($page),"UTF-8"),"48:32");
		$url="redir(\"inicio.php?page=$page\");";
		$temp1=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("list",_LANG("functions_getmigas_button_list").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_list_title").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_list").mb_strtolower(getnametable($table),"UTF-8"),"48:32");
		$url="redir(\"inicio.php?page=list&table=$table&id=$id\");";
		$temp2=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		$tempfunc=$func;
		if(substr($tempfunc,-4,4)!=".adm") $tempfunc.=".adm";
		if($tempfunc=="save_db_spec.adm") $tempfunc="edit_db_spec.adm";
		list($title,$text)=make_title_text("database",_LANG("functions_getmigas_button_access_to").mb_strtolower(getnametable($tempfunc),"UTF-8"),_LANG("functions_getmigas_button_access_to_title").mb_strtolower(getnametable($tempfunc),"UTF-8"),_LANG("functions_getmigas_button_access_to").mb_strtolower(getnametable($tempfunc),"UTF-8"),"48:32");
		$url="redir(\"inicio.php?func=$tempfunc\");";
		$temp3=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("disksfilesystems",_LANG("functions_getmigas_button_show").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_show_title").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_show").mb_strtolower(getnametable($table),"UTF-8"),"48:32");
		$url="redir(\"inicio.php?page=form&form=$form&table=$table&id=$id&iter=$iter\");";
		$temp4=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("edit",_LANG("functions_getmigas_button_update").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_update_title").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_update").mb_strtolower(getnametable($table),"UTF-8"),"48:32");
		$url="redir(\"inicio.php?page=form&form=$form&table=$table&id=$id&iter=$iter\");";
		$temp5=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("new_window",_LANG("functions_getmigas_button_create").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_create_title").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_create").mb_strtolower(getnametable($table),"UTF-8"),"48:32");
		$myid=($form=="copy")?getParam("id"):$id;
		$url="redir(\"inicio.php?page=form&form=$form&table=$table&id=$myid&iter=$iter\");";
		$temp6=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		list($title,$text)=make_title_text("3floppy_unmount",_LANG("functions_getmigas_button_export").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_export_title").mb_strtolower(getnametable($table),"UTF-8"),_LANG("functions_getmigas_button_export").mb_strtolower(getnametable($table),"UTF-8"),"48:32");
		$url="redir(\"inicio.php?page=export&table=$table\");";
		$temp7=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
		$total=$temp0;
		if(substr($page,-4)==".php") $total.="&nbsp;".$temp1;
		elseif($table!="") $total.="&nbsp;".$temp2;
		elseif($func!="") $total.="&nbsp;".$temp3;
		$mypage=$page;
		if(substr($page,4,1)=="_") $mypage=substr($page,0,4);
		if($mypage=="form" && $form=="show") $total.="&nbsp;".$temp4;
		elseif($mypage=="form" && $id!="") $total.="&nbsp;".$temp5;
		elseif($mypage=="form" && $id=="") $total.="&nbsp;".$temp6;
		elseif($page=="export") $total.="&nbsp;".$temp7;
	} else {
		list($title,$text)=make_title_text("password",_LANG("functions_getmigas_button_login"),_LANG("functions_getmigas_button_login_title"),_LANG("functions_getmigas_button_login"),"48:32");
		$url="redir(\"inicio.php\");";
		$total=get_button($title,$url,"","44","migas ui-state-default ui-corner-all",$text);
	}
	return $total;
}

function get_current_app() {
	global $page,$table,$func,$form,$id,$iter;

	if(check_user()) {
		$temp0=_LANG("functions_getmigas_button_menu");
		$temp1=_LANG("functions_getmigas_button_access_to").mb_strtolower(getnametable($page),"UTF-8");
		$temp2=_LANG("functions_getmigas_button_list").mb_strtolower(getnametable($table),"UTF-8");
		$tempfunc=$func;
		if(substr($tempfunc,-4,4)!=".adm") $tempfunc.=".adm";
		if($tempfunc=="save_db_spec.adm") $tempfunc="edit_db_spec.adm";
		$temp3=_LANG("functions_getmigas_button_access_to").mb_strtolower(getnametable($tempfunc),"UTF-8");
		$temp4=_LANG("functions_getmigas_button_show").mb_strtolower(getnametable($table),"UTF-8");
		$temp5=_LANG("functions_getmigas_button_update").mb_strtolower(getnametable($table),"UTF-8");
		$temp6=_LANG("functions_getmigas_button_create").mb_strtolower(getnametable($table),"UTF-8");
		$temp7=_LANG("functions_getmigas_button_export").mb_strtolower(getnametable($table),"UTF-8");
		$total=$temp0;
		if(substr($page,-4)==".php") $total=$temp1;
		elseif($table!="") $total=$temp2;
		elseif($func!="") $total=$temp3;
		if($page=="form" && $form=="show") $total=$temp4;
		elseif($page=="form" && $id!="") $total=$temp5;
		elseif($page=="form" && $id=="") $total=$temp6;
		elseif($page=="export") $total=$temp7;
	} else {
		$total=_LANG("functions_getmigas_button_login");
	}
	return $total;
}

function get_button($title="",$action="",$width="",$height="",$class="",$text="") {
	if($class=="") $class="buttons ui-state-default ui-corner-all";
	if($text=="") $text=$title;
	$style="text-align:left;";
	if($width!="") {
		if(is_numeric($width)) $width.="px";
		$style.="width:$width;";
	}
	if($height!="") {
		if(is_numeric($height)) $height.="px";
		$style.="height:$height;";
	}
	if($style!="") $style="style='$style'";
	$temp="<button type='button' class='$class' $style onclick='$action' title='$title'>$text</button>";
	return $temp;
}

function debug($txt) {
	echo "<!-- ";
	echo $txt;
	echo " -->";
}

function make_title_text($icon,$title1,$title2,$text1,$size="48") {
	$size=explode(":",$size);
	if(!isset($size[1])) $size[1]="16";
	$icon_a=file_exists("lib/crystal/{$size[0]}x{$size[0]}/$icon.png")?$icon:"kblackbox";
	$title="<table><tr><td rowspan=\"2\" valign=\"top\"><img src=\"lib/crystal/{$size[0]}x{$size[0]}/$icon_a.png\" align=\"top\" /></td><td height=\"1\" valign=\"top\"><em>$title1</em></td></tr><tr><td valign=\"top\">$title2</td></tr></table>";
	$icon_b=file_exists("lib/crystal/{$size[1]}x{$size[1]}/$icon.png")?$icon:"kblackbox";
	$text="<img src='lib/crystal/{$size[1]}x{$size[1]}/$icon_b.png' width='{$size[1]}px' height='{$size[1]}px' />";
	if($text1!="") $text.="&nbsp;$text1";
	return array($title,$text);
}

function make_title_icon($icon,$title1,$title2,$url,$size="48") {
	$size=explode(":",$size);
	if(!isset($size[1])) $size[1]="16";
	$icon_a=file_exists("lib/crystal/{$size[0]}x{$size[0]}/$icon.png")?$icon:"kblackbox";
	$title="<table><tr><td rowspan=2 valign=\"top\"><img src=\"lib/crystal/{$size[0]}x{$size[0]}/$icon_a.png\" align=\"top\" /></td><td height=\"1\" valign=\"top\"><em>$title1</em></td></tr><tr><td valign=\"top\">$title2</td></tr></table>";
	$icon_b=file_exists("lib/crystal/{$size[1]}x{$size[1]}/$icon.png")?$icon:"kblackbox";
	$text="<a href='javascript:$url'><img title='$title' src='lib/crystal/{$size[1]}x{$size[1]}/$icon_b.png' width='{$size[1]}px' height='{$size[1]}px' /></a>";
	return $text;
}

function get_partners() {
	$partners=array();
	$partners[]=array(_LANG("functions_getpartners_2_title"),_LANG("functions_getpartners_2_description"),"http://www.saltos.org","saltos_org.png");
	$partners[]=array(_LANG("functions_getpartners_3_title"),_LANG("functions_getpartners_3_description"),"http://www.gnu.org/licenses/gpl-3.0.txt","gpl_licensed.png");
	$partners[]=array(_LANG("functions_getpartners_4_title"),_LANG("functions_getpartners_4_description"),"http://www.gnu.org","gnu.jpg");
	$partners[]=array(_LANG("functions_getpartners_5_title"),_LANG("functions_getpartners_5_description"),"http://www.apache.org","apache_powered.jpg");
	$partners[]=array(_LANG("functions_getpartners_6_title"),_LANG("functions_getpartners_6_description"),"http://www.php.net","php_powered.png");
	$partners[]=array(_LANG("functions_getpartners_7_title"),_LANG("functions_getpartners_7_description"),"http://www.mysql.com","mysql_powered.jpg");
	$partners[]=array(_LANG("functions_getpartners_8_title"),_LANG("functions_getpartners_8_description"),"http://www.sqlite.org","sqlite.png");
	$partners[]=array(_LANG("functions_getpartners_9_title"),_LANG("functions_getpartners_9_description"),"http://www.jquery.com","powered_by_jquery.gif");
	$partners[]=array(_LANG("functions_getpartners_10_title"),_LANG("functions_getpartners_10_description"),"http://www.phpjs.org","phpjs_powered.gif");
	$partners[]=array(_LANG("functions_getpartners_11_title"),_LANG("functions_getpartners_11_description"),"http://ckeditor.com/","ckeditor.png");
	$partners[]=array(_LANG("functions_getpartners_12_title"),_LANG("functions_getpartners_12_description"),"http://www.getfirefox.com","get_firefox.png");
	$partners[]=array(_LANG("functions_getpartners_13_title"),_LANG("functions_getpartners_13_description"),"http://www.google.com/chrome?hl=es","get_chrome.gif");
	$buttons=array();
	foreach($partners as $partner) {
		if($partner[0]!="") {
			$title="<table><tr><td><em>".$partner[0]."</em></td></tr><tr><td>".$partner[1]."</td></tr></table>";
			$img="<img src='img/".$partner[3]."' style='width:80px;height:15px;' title='$title' />";
		} else {
			$img="<img src='img/".$partner[3]."' style='width:80px;height:15px;' />";
		}
		if($partner[2]!="") {
			$button="<a href='".$partner[2]."' target='_blank'>$img</a>";
		} else {
			$button=$img;
		}
		$buttons[]=$button;
	}
	return $buttons;
}

function encode_bad_chars($param) {
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
	$new=str_replace(" ","_",$new);
	while($cad!=($new=str_replace("__","_",$new))) $cad=$new;
	return $new;
}

function get_loading($padding=0) {
	$temp="<table cellpadding=\"1\" cellspacing=\"1\" border=\"0\" class=\"ui-state-highlight ui-corner-all\" style=\"padding:{$padding}px\"><tr><td><img src=\"img/spinner.gif\" width=\"16px\" height=\"16px\" /></td><td class=\"texts2\">&nbsp;"._LANG("functions_getloading_title")."&nbsp;</td></tr></table>";
	return $temp;
}

function set_db_cache($new) {
	global $_CONFIG;

	$old=$_CONFIG["db"]["usecache"];
	$_CONFIG["db"]["usecache"]=$new;
	return $old;
}

function ismsie6() {
	if(isset($_SERVER["HTTP_USER_AGENT"])) {
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6")!==false) {
			return true;
		}
	}
	return false;
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
	$oldusecache=setUseCache(false);
	capture_next_error();
	dbQuery($query);
	$error=get_clear_error();
	setUseCache($oldusecache);
	$result=$error?0:1;
	if($usecache) $exists[$hash]=$result;
	return $result;
}

function value_exists($table,$field,$value) {
	$query="SELECT `$field` FROM `$table` WHERE `$field`='$value'";
	$result=dbQuery($query);
	$row=dbFetchRow($result);
	dbFree($result);
	return isset($row[$field]);
}

function hexadecimal($valor,$maximo,$fill=false) {
	$viejo=strtoupper($valor);
	$len=strlen($viejo);
	$nuevo="";
	for($i=0;$i<$len && $i<$maximo;$i++) {
		$letra=$viejo[$i];
		$ishex=false;
		if($letra>="0" && $letra<="9") $ishex=true;
		if($letra>="A" && $letra<="F") $ishex=true;
		if($ishex) $nuevo.=$letra;
	}
	if($fill) {
		$nuevo=substr($nuevo."000000",0,6);
	}
	return $nuevo;
}

function color2dec($color,$component) {
	$offset=array("R"=>1,"G"=>3,"B"=>5);
	if(!isset($offset[$component])) show_php_error(array("phperror"=>_LANG("functions_color2dec_error")));
	return base_convert(substr($color,$offset[$component],2),16,10);
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

function openf1form($action="inicio.php") {
	echo "<tr>\n";
	echo "<td>\n";
	echo "<form name='f1' action='$action' method='post' encType='multipart/form-data'>\n";
	echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>\n";
}

function closef1form() {
	echo "</table>\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "</tr>\n";
}

function puthidden($name,$value) {
	$value=quot_htmlentities($value);
	echo "<input type='hidden' id=\"$name\" name=\"$name\" value=\"$value\" />\n";
}

function openmain() {
	opentr();
	opentd("class='main' valign='top'");
	opentable("width='90%'");
	escribe();
}

function closemain() {
	escribe("&nbsp;","","id='fixmax'");
	closetable();
	closetd();
	closetr();
}

function my_mime_content_type($file) {
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
	}
	$ext=pathinfo($file,PATHINFO_EXTENSION);
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

function get_base() {
	$ssl=$_SERVER["SERVER_PORT"]==443;
	$proto="http://";
	if($ssl) $proto="https://";
	$host=$_SERVER["SERVER_NAME"];
	$path=$_SERVER["SCRIPT_NAME"];
	$path=dirname($path);
	return $proto.$host.$path."/";
}

function get_body($buffer) {
	$pos=stripos($buffer,"<body");
	if($pos!==false) {
		$pos=strpos($buffer,">",$pos+1);
		if($pos!==false) {
			$buffer=substr($buffer,$pos);
		}
		$pos=stripos($buffer,"</body>");
		if($pos!==false) {
			$buffer=substr($buffer,0,$pos);
		}
	}
	return $buffer;
}

function text_cutter(&$valor,$size) {
	$title="";
	$temp=intval($size);
	$valor=strip_tags($valor);
	$len=mb_strlen($valor,"UTF-8");
	if($len>$temp) {
		$temp2=0;
		$salto=0;
		while($temp2<$len) {
			$letra=mb_substr($valor,$temp2,1,"UTF-8");
			if($letra==" " && $salto) {
				$title.="<br/>"; $salto=0;
			} else {
				$title.=$letra;
			}
			$temp2++;
			if(($temp2)%50==0) $salto=1;
		}
		$title=str_replace('"',"''",$title);
		while($temp<$len && mb_substr($valor,$temp,1,"UTF-8")!=" ") $temp++;
		if($temp<$len) $valor=mb_substr($valor,0,$temp,"UTF-8")."&hellip;";
		else $title="";
	}
	return $title;
}
