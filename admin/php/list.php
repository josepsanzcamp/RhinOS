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
if(!check_user()) intro();
if($table=="") intro();
if(!check_permissions("list",$table)) intro();
$inserts=check_permissions("insert",$table);
$selects=check_permissions("select",$table);
$updates=check_permissions("update",$table);
$deletes=check_permissions("delete",$table);
openform("100%");
openrow("height=22");
settds("trans");
putcolumn(get_migas(),"left","100%");
$temp="&nbsp;";
if($inserts) {
	list($title,$text)=make_title_text("new_window",_LANG("list_button_create"),_LANG("list_button_create_title"),_LANG("list_button_create"));
	$select="<select name='iter' id='iter' style='width:50px' class='inputs ui-state-default ui-corner-all' title='$title'>";
	for($i=1;$i<=10;$i++) $select.="<option value='$i'>$i</option>";
	$select.="</select>";
	$url="anadir(13);";
	$button=get_button($title,$url,"","22","",$text);
	$temp=_LANG("list_button_create_prefix")."$select&nbsp;$button";
}
putcolumn($temp,"right","","","texts2","valign='bottom'");
closeform();
escribe();
puterrores();
getlistconfig($table);
if(!count($campos)) {
	$error[]="WARNING:"._LANG("list_config_not_found");
	puterrores();
	unset($error[count($error)-1]);
}
$count_campos=count($campos);
$query=process_query($usepag=0,$onlycount=1);
$result=dbQuery($query);
$row=dbFetchRow($result);
$total=$row["count"];
dbFree($result);
$oldlimit=$limit;
if($oldlimit=="inf") $oldlimit=$total;
if($oldlimit<1) $oldlimit=1;
$num1=$offset+1;
$num2=$offset+$oldlimit;
if($num2>$total) $num2=$total;
$pmin=0;
$pant=$offset-$oldlimit;
if($pant<$pmin) $pant=$pmin;
$pmax=((int)(($total-1)/$oldlimit))*$oldlimit;
$psig=$offset+$oldlimit;
if($psig>$pmax) $psig=$pmax;
openform("100%");
openrow("height=22");
settds("trans");
if($offset>0) {
	list($title,$text)=make_title_text("2leftarrow",_LANG("list_button_first_title"),_LANG("list_button_first_description"),_LANG("list_button_first"),"48");
	$url="redir(\"inicio.php?page=list&table=$table&offset=$pmin\");";
	$temp=get_button($title,$url,"","22","",$text);
	$temp.="&nbsp;";
	list($title,$text)=make_title_text("1leftarrow",_LANG("list_button_previous_title"),_LANG("list_button_previous_description"),_LANG("list_button_previous"),"48");
	$url="redir(\"inicio.php?page=list&table=$table&offset=$pant\");";
	$temp.=get_button($title,$url,"","22","",$text);
} else {
	list($title,$text)=make_title_text("2leftarrow",_LANG("list_button_first_title"),_LANG("list_button_first_description"),_LANG("list_button_first"),"48");
	$url="void(0);";
	$temp=get_button("",$url,"","22","buttons2 ui-state-default ui-corner-all ui-state-disabled",$text);
	$temp.="&nbsp;";
	list($title,$text)=make_title_text("1leftarrow",_LANG("list_button_previous_title"),_LANG("list_button_previous_description"),_LANG("list_button_previous"),"48");
	$url="void(0);";
	$temp.=get_button("",$url,"","22","buttons2 ui-state-default ui-corner-all ui-state-disabled",$text);
}
putcolumn($temp);
$search2=quot_htmlentities(stripslashes($search));
$input="<input type='text' name=\"search\" id=\"search\" value=\"$search2\" class='inputs ui-state-default ui-corner-all' style='width:75px' onkeyup='buscador(event.keyCode)'>";
list($title,$text)=make_title_text("search",_LANG("list_button_search"),_LANG("list_button_search_title"),_LANG("list_button_search"));
$url="buscador(13);";
$button=get_button($title,$url,"","22","",$text);
if($search!="") {
	list($title,$text)=make_title_text("quick_restart",_LANG("list_button_clear_title"),_LANG("list_button_clear_description"),_LANG("list_button_clear"));
	$url="redir(\"inicio.php?page=list&table=$table&search=null\");";
	$button2=get_button($title,$url,"","22","",$text);
} else {
	list($title,$text)=make_title_text("quick_restart",_LANG("list_button_clear_title"),_LANG("list_button_clear_description"),_LANG("list_button_clear"));
	$url="void(0);";
	$button2=get_button("",$url,"","22","buttons2 ui-state-default ui-corner-all ui-state-disabled",$text);
}
$temp=_LANG("list_button_clear_prefix")."$input&nbsp;$button&nbsp;$button2";
putcolumn("&nbsp;","center","50%");
putcolumn($temp,"center","","","texts2");
putcolumn("&nbsp;","center","22");
if($limit!="inf") {
	$mylabel=_LANG("list_button_fullpage_title");
	$mylabel=str_replace("#numero#",$total,$mylabel);
	list($title,$text)=make_title_text("list",_LANG("list_button_fullpage"),$mylabel,_LANG("list_button_fullpage"));
	$url="mostrar(\"inicio.php?page=list&table=$table&offset=0&limit=inf\",$total);";
	$temp=get_button($title,$url,"","22","",$text);
} else {
	$mylabel=_LANG("list_button_onlypage_title");
	$mylabel=str_replace("#numero#",$pagelimit,$mylabel);
	list($title,$text)=make_title_text("list",_LANG("list_button_onlypage"),$mylabel,_LANG("list_button_onlypage"));
	$url="redir(\"inicio.php?page=list&table=$table&offset=0&limit=$pagelimit\");";
	$temp=get_button($title,$url,"","22","",$text);
}
putcolumn($temp,"center","","","texts2");
putcolumn("&nbsp;","center","50%");
if($offset<$pmax) {
	list($title,$text)=make_title_text("1rightarrow",_LANG("list_button_next_title"),_LANG("list_button_next_description"),_LANG("list_button_next"),"48");
	$url="redir(\"inicio.php?page=list&table=$table&offset=$psig\");";
	$temp=get_button($title,$url,"","22","",$text);
	$temp.="&nbsp;";
	list($title,$text)=make_title_text("2rightarrow",_LANG("list_button_last_title"),_LANG("list_button_last_description"),_LANG("list_button_last"),"48");
	$url="redir(\"inicio.php?page=list&table=$table&offset=$pmax\");";
	$temp.=get_button($title,$url,"","22","",$text);
} else {
	list($title,$text)=make_title_text("1rightarrow",_LANG("list_button_next_title"),_LANG("list_button_next_description"),_LANG("list_button_next"),"48");
	$url="void(0);";
	$temp=get_button("",$url,"","22","buttons2 ui-state-default ui-corner-all ui-state-disabled",$text);
	$temp.="&nbsp;";
	list($title,$text)=make_title_text("2rightarrow",_LANG("list_button_last_title"),_LANG("list_button_last_description"),_LANG("list_button_last"),"48");
	$url="void(0);";
	$temp.=get_button("",$url,"","22","buttons2 ui-state-default ui-corner-all ui-state-disabled",$text);
}
putcolumn($temp);
closerow();
closeform();
escribe("","","height=2");
$temp="";
for($i=0;$i<$count_campos;$i++) {
	$edit=$edits[$i];
	$type=$types[$i];
	if($edit && $type!="file" && $type!="photo") {
		if(strlen($temp)>0) $temp.=",";
		$temp.=$i;
	}
}
openf1form();
puthidden("page","process");
puthidden("id","");
puthidden("table",$table);
puthidden("row",$temp);
puthidden("action","save");
openform("100%","","","","class='tabla'");
openrow("height='22'");
settds("thead");
putcolumn("&nbsp;","center","22");
$temp=explode(",",$order);
$first_order="";
if(isset($temp[0])) $first_order=$temp[0];
for($i=0;$i<$count_campos;$i++) {
	$campo=$campos[$i];
	$temp=explode(":",$campo);
	$campo_real=$temp[0];
	$texto=$textos[$i];
	$size=$sizes[$i];
	$type=$types[$i];
	$myorder="$table.$campo";
/*
	if($type=="select" || $type=="multiselect") {
		$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$campo'";
		$result=dbQuery($query);
		$row=dbFetchRow($result);
		$table_ref=$row["table_ref"];
		$value_ref=$row["value_ref"];
		$text_ref=$row["text_ref"];
		dbFree($result);
		$myorder="$table_ref.$text_ref";
	}
*/
	$myorder=str_replace(" ","+",$myorder);
	$asc="$myorder asc";
	$desc="$myorder desc";
	$myorder=str_replace("'","|",$myorder);
	$jsasc="redir(\"inicio.php?page=list&table=$table&order=$myorder asc\");";
	$jsdesc="redir(\"inicio.php?page=list&table=$table&order=$myorder desc\");";
	$iconasc="&nbsp;<img src='lib/crystal/16x16/sort_incr.png' width='16' height='16' />";
	$icondesc="&nbsp;<img src='lib/crystal/16x16/sort_decrease.png' width='16' height='16' />";
	$icontitleasc="sort_incr";
	$icontitledesc="sort_decrease";
	$textotitleasc=_LANG("list_label_sort_asc");
	$textotitledesc=_LANG("list_label_sort_desc");
	$neworder=$jsasc;
	if($first_order==$asc) $neworder=$jsdesc;
	if($first_order==$desc) $neworder=$jsasc;
	$newicon="";
	if($first_order==$asc) $newicon=$iconasc;
	if($first_order==$desc) $newicon=$icondesc;
	$newicontitle=$icontitleasc;
	if($first_order==$asc) $newicontitle=$icontitledesc;
	if($first_order==$desc) $newicontitle=$icontitleasc;
	$newtextotitle=$textotitleasc;
	if($first_order==$asc) $newtextotitle=$textotitledesc;
	if($first_order==$desc) $newtextotitle=$textotitleasc;
	list($title,$nada)=make_title_text($newicontitle,_LANG("list_button_sort"),_LANG("list_button_sort_title").mb_strtolower($texto,"UTF-8")." ".$newtextotitle,_LANG("list_button_sort"),"48");
	putcolumn($texto.$newicon,"center",$size,"","texts2","onclick='$neworder' title='$title' style='cursor:pointer'");
}
if(!$count_campos) putcolumn("&nbsp;","center","100%");
putcolumn("&nbsp;","center","110","5");
closerow();
$query=process_query();
$result=dbQuery($query);
if(dbNumRows($result)>0) {
	$num=0;
	while($row=dbFetchRow($result)) {
		$myid=$row["$table"."_id"];
		settds("tbody");
		openrow();
		$checked="";
		if(is_selected($myid,$id)) $checked="checked";
		$temp="<input type='checkbox' name='chk$num' id='chk$num' class='inputs ui-state-default ui-corner-all' value='".$myid."' $checked>";
		putcolumn($temp,"center","22");
		for($i=0;$i<$count_campos;$i++) {
			$campo=$campos[$i];
			$temp=explode(":",$campo);
			if($temp[0]=="concat") {
				unset($temp[0]);
				$campo_real=encode_bad_chars(implode("_",$temp));
			} else {
				$campo_real=$temp[0];
			}
			$texto=$textos[$i];
			$size=$sizes[$i];
			$type=$types[$i];
			$edit=$edits[$i];
			if(isset($row["$table"."_$campo_real"])) $valor=$row["$table"."_$campo_real"];
			if(isset($rowdata)) if(isset($rowdata["$campo.$table.$myid"])) $valor=$rowdata["$campo.$table.$myid"];
			$bad=ismarked($campo,$table,$myid);
			if(!isset($row["$table"."_$campo"."_file"])) $file="";
			else $file=$row["$table"."_$campo"."_file"];
			if(!isset($row["$table"."_$campo"."_size"])) $_size="";
			else $_size=$row["$table"."_$campo"."_size"];
			if(!isset($row["$table"."_$campo"."_type"])) $_type="";
			else $_type=$row["$table"."_$campo"."_type"];
			$title="";
			if($type=="select") {
				$size2=$edit?$size:0;
				$valor=getselect($table,$campo,$valor,$size2,$edit,$myid,$bad);
				if(!$edit) $title=text_cutter($valor,$size);
			} elseif($type=="multiselect") {
				$size2=$edit?$size:0;
				$valor=getmultiselect($table,$campo,$valor,$size2,$myid,$bad);
				$title=text_cutter($valor,$size);
			} elseif($type=="file") {
				if($valor=="") $valor=_LANG("list_label_not_file");
				else $valor=getlink($file,$valor,$_size,$_type,$size);
			} elseif($type=="photo") {
				if($valor=="") $valor=_LANG("list_label_not_photo");
				else $valor=getpreview($file,$valor,$_size,$_type,$size);
			} elseif($type=="boolean") {
				$valor=getboolean($valor,$edit,0,$campo,$table,$myid);
			} elseif($type=="ajaxboolean") {
				$valor=getboolean($valor,$edit,1,$campo,$table,$myid);
			} elseif($type=="date") {
				$valor=convert_date($valor);
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
			} elseif($type=="time") {
				$valor=substr($valor,0,5);
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
			} elseif($type=="unixtime" || $type=="timestamp") {
				$valor=date("d/m/Y H:i",$valor);
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
			} elseif($type=="datetime") {
				$valor=convert_date(substr($valor,0,10))." ".substr($valor,11,5);
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
			} elseif($type=="color") {
				if($valor) $valor=hexadecimal($valor,6,true);
				$extra=$valor?getmuestra($table,$campo,$valor,$myid,$edit):"";
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
				$valor="<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='22' align='center'>$extra</td><td nowrap><div class='texts' align='center'>$valor</div></td><td width='22' align='center'>&nbsp;</td></tr></table>";
			} elseif($type=="text" || $type=="textarea" || $type=="textareaold") {
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
				if(!$edit && $type=="textareaold") $valor=htmlentities($valor,ENT_COMPAT,"UTF-8");
				if(!$edit) $title=text_cutter($valor,$size);
			} elseif($type=="integer") {
				$valor=intval($valor);
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
			} elseif($type=="real" || $type=="decimal" || $type=="float" || $type=="double") {
				$valor=floatval($valor);
				if($edit) $js="onkeydown='if(event.keyCode==13) return false;'";
				if($edit) $valor=getinput($table,$campo,$valor,$size,$myid,$bad,$js);
			}
			if(strval($valor)=="") $valor="&nbsp;";
			$js="onclick='swapchk($num)'";
			if($edit) $js="onclick='setchk($num,true)'";
			$hassearch=0;
			if($type=="select") $hassearch=1;
			if($type=="date") $hassearch=1;
			if($type=="boolean") $hassearch=1;
			$url="redir(\"inicio.php?page=list&table=$table&search=field.$campo.id.$myid\");";
			$icono=make_title_icon("search",_LANG("list_icon_filter").$texto,_LANG("list_icon_filter_title").$texto,$url);
			if($hassearch) $valor="<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='22' align='center'>$icono</td><td nowrap><div class='texts' align='center'>$valor</div></td><td width='22' align='center'>&nbsp;</td></tr></table>";
			if($title) $js.=" title=\"$title\"";
			putcolumn($valor,"center",$size,"","",$js);
		}
		if(!$count_campos) putcolumn("&nbsp;","center","100%");
		$temp="&nbsp;";
		if($selects) {
			$url="redir(\"inicio.php?page=form&form=show&table=$table&id=$myid\");";
			$temp=make_title_icon("search",_LANG("list_icon_view"),_LANG("list_icon_view_title"),$url);
		}
		putcolumn($temp,"center","22","","","style='border-right-width:0px'");
		$temp="&nbsp;";
		if($updates) {
			$url="redir(\"inicio.php?page=form&table=$table&id=$myid\");";
			$temp=make_title_icon("highlight",_LANG("list_icon_edit"),_LANG("list_icon_edit_title"),$url);
		}
		putcolumn($temp,"center","22","","","style='border-left-width:0px;border-right-width:0px'");
		$temp="&nbsp;";
		if($inserts && ($selects || $updates)) {
			$url="redir(\"inicio.php?page=form&form=copy&table=$table&id=$myid\");";
			$temp=make_title_icon("special_paste",_LANG("list_icon_copy"),_LANG("list_icon_copy_title"),$url);
		}
		putcolumn($temp,"center","22","","","style='border-left-width:0px;border-right-width:0px'");
		$temp="&nbsp;";
		if($deletes && !is_needed($table,$myid)) {
			$url="msgdel(\"inicio.php?page=process&action=delete&table=$table&id=$myid\");";
			$temp=make_title_icon("button_cancel",_LANG("list_icon_delete"),_LANG("list_icon_delete_title"),$url);
		}
		putcolumn($temp,"center","22","","","style='border-left-width:0px;border-right-width:0px'");
		$temp="&nbsp;";
		if($updates) {
			$url="save($myid);";
			$temp=make_title_icon("3floppy_unmount",_LANG("list_icon_save"),_LANG("list_icon_save_title"),$url);
		}
		putcolumn($temp,"center","22","","","style='border-left-width:0px'");
		closerow();
		$num++;
	}
	dbFree($result);
} else {
	$temp=_LANG("list_label_not_data");
	if($search!="" && $search!="*") $temp=_LANG("list_label_not_search");
	openrow();
	settds("tbody nodata");
	putcolumn("&nbsp;","center","22");
	putcolumn("<div class='errors'>$temp</div>","center","100%",$count_campos);
	putcolumn("&nbsp;","center","110","5");
	closerow();
}
closeform();
escribe("","","height=2");
openform("100%");
openrow("height=22");
settds("trans");
$temp="<img src=\"img/arrow_ltr.gif\" />";
putcolumn($temp,"center","44");
$url="setallchk(true);";
$temp=make_title_icon("tab_new",_LANG("list_icon_selall"),_LANG("list_icon_selall_title"),$url);
putcolumn($temp,"center","22");
$url="swapallchk();";
$temp=make_title_icon("tab_duplicate",_LANG("list_icon_selinv"),_LANG("list_icon_selinv_title"),$url);
putcolumn($temp,"center","22");
$url="setallchk(false);";
$temp=make_title_icon("tab_remove",_LANG("list_icon_selnone"),_LANG("list_icon_selnone_title"),$url);
putcolumn($temp,"center","22");
$temp="&nbsp;";
if($selects) {
	$url="multipleselect();";
	$temp=make_title_icon("search",_LANG("list_icon_selview"),_LANG("list_icon_selview_title"),$url);
}
putcolumn($temp,"center","22");
$temp="&nbsp;";
if($updates) {
	$url="multipleupdate();";
	$temp=make_title_icon("highlight",_LANG("list_icon_seledit"),_LANG("list_icon_seledit_title"),$url);
}
putcolumn($temp,"center","22");
$temp="&nbsp;";
if($inserts && ($selects || $updates)) {
	$url="multiplecopy();";
	$temp=make_title_icon("special_paste",_LANG("list_icon_selcopy"),_LANG("list_icon_selcopy_title"),$url);
}
putcolumn($temp,"center","22");
$temp="&nbsp;";
if($deletes) {
	$url="multipledelete();";
	$temp=make_title_icon("button_cancel",_LANG("list_icon_seldelete"),_LANG("list_icon_seldelete_title"),$url);
}
putcolumn($temp,"center","22");
$temp="&nbsp;";
if($updates) {
	$url="multiplesave();";
	$temp=make_title_icon("3floppy_unmount",_LANG("list_icon_selsave"),_LANG("list_icon_selsave_title"),$url);
}
putcolumn($temp,"center","22");
$existsoffset=0;
for($i=0;$i<$total;$i=$i+$oldlimit) {
	$temp1=intval($i+1);
	$temp2=intval($i+$oldlimit);
	if($temp2>$total) $temp2=$total;
	if($i==$offset) {
		$mylabel=_LANG("list_select_reg_show_title");
		$mylabel=str_replace("#numero1#",$temp1,$mylabel);
		$mylabel=str_replace("#numero2#",$temp2,$mylabel);
		list($title,$text)=make_title_text("list",_LANG("list_select_reg_show"),$mylabel,"");
		$existsoffset=1;
		$len=strlen($temp1." - ".$temp2);
	}
}
if(!$existsoffset) {
	$mylabel=_LANG("list_select_reg_show_title");
	$mylabel=str_replace("#numero1#",0,$mylabel);
	$mylabel=str_replace("#numero2#",0,$mylabel);
	list($title,$text)=make_title_text("list",_LANG("list_select_reg_show"),$mylabel,"");
	$len=3;
}
$len=40+$len*7;
$select="<select name='offset' id='offset' class='inputs ui-state-default ui-corner-all' OnChange='javascript:pagina()' style='width:${len}px' title='$title'>\n";
$existsoffset=0;
for($i=0;$i<$total;$i=$i+$oldlimit) {
	$temp1=intval($i+1);
	$temp2=intval($i+$oldlimit);
	if($temp2>$total) $temp2=$total;
	$selected="";
	if($i==$offset) {
		$selected="selected";
		$existsoffset=1;
	}
	$select.="<option value='$i' $selected>$temp1 - $temp2</option>\n";
}
if(!$existsoffset) $select.="<option value='0' selected>0 - 0</option>\n";
$select.="</select>\n";
$existslimit=0;
for($i=10;$i<=100;$i=$i+5) {
	if($i==$oldlimit) {
		$mylabel=_LANG("list_select_reg_pag_title");
		$mylabel=str_replace("#numero#",$i,$mylabel);
		list($title,$text)=make_title_text("list",_LANG("list_select_reg_pag"),$mylabel,"");
		$existslimit=1;
		$len=strlen($i);
	}
}
if(!$existslimit) {
	$mylabel=_LANG("list_select_reg_pag_title");
	$mylabel=str_replace("#numero#",$total,$mylabel);
	list($title,$text)=make_title_text("list",_LANG("list_select_reg_pag"),$mylabel,"");
	$len=strlen($total);
}
$len=40+$len*7;
$select2="<select name='limit' id='limit' class='inputs ui-state-default ui-corner-all' OnChange='javascript:pagina()' style='width:${len}px' title='$title'>\n";
$existslimit=0;
for($i=10;$i<=100;$i=$i+5) {
	$selected="";
	if($i==$oldlimit) {
		$selected="selected";
		$existslimit=1;
	}
	$select2.="<option value='$i' $selected>$i</option>\n";
}
if(!$existslimit) $select2.="<option value='$total' selected>$total</option>\n";
$select2.="</select>\n";
$temp=_LANG("list_label_reg_show").$select;
putcolumn("&nbsp;","center","50%");
putcolumn($temp,"center","","","texts2");
putcolumn("&nbsp;","center","22");
$temp=_LANG("list_label_reg_pag").$select2;
putcolumn($temp,"center","","","texts2");
putcolumn("&nbsp;","center","50%");
list($title,$text)=make_title_text("3floppy_unmount",_LANG("list_button_export"),_LANG("list_button_export_title"),_LANG("list_button_export"));
$url="redir(\"inicio.php?page=export&table=$table\");";
$temp=get_button($title,$url,"","22","",$text);
putcolumn($temp,"right");
closerow();
closeform();
if(count($error)>0) msgbox(_LANG("list_message_error"));
closef1form();
echo "<script type='text/javascript'>\n";
echo "function msgdel(arg) {\n";
echo "    buttons={\n";
echo "        '"._LANG("list_confirm_button_yes")."':function() { $('#dialog').dialog('close'); redir(arg); },\n";
echo "        '"._LANG("list_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
echo "    };\n";
echo "    msgbox(\""._LANG("list_confirm_delete")."\",buttons);\n";
echo "}\n";
echo "function msgset(arg) {\n";
echo "    buttons={\n";
echo "        '"._LANG("list_confirm_button_yes")."':function() { $('#dialog').dialog('close'); redir(arg); },\n";
echo "        '"._LANG("list_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
echo "    };\n";
echo "    msgbox(\""._LANG("list_confirm_update")."\",buttons);\n";
echo "}\n";
echo "function save(id) {\n";
echo "    buttons={\n";
echo "        '"._LANG("list_confirm_button_yes")."':function() { $('#dialog').dialog('close'); document.f1.id.value=id; mysubmit(); },\n";
echo "        '"._LANG("list_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
echo "    };\n";
echo "    msgbox(\""._LANG("list_confirm_update")."\",buttons);\n";
echo "}\n";
echo "function multipleselect() {\n";
echo "    var ids=getchkids();\n";
echo "    if(ids.length==0) {\n";
echo "        msgbox(\""._LANG("list_message_selreg")."\");\n";
echo "    } else {\n";
echo "        redir('inicio.php?page=form&form=show&table='+table+'&id='+ids);\n";
echo "    }\n";
echo "}\n";
echo "function multipleupdate() {\n";
echo "    var ids=getchkids();\n";
echo "    if(ids.length==0) {\n";
echo "        msgbox(\""._LANG("list_message_selreg")."\");\n";
echo "    } else {\n";
echo "        redir('inicio.php?page=form&table='+table+'&id='+ids);\n";
echo "    }\n";
echo "}\n";
echo "function multiplecopy() {\n";
echo "    var ids=getchkids();\n";
echo "    if(ids.length==0) {\n";
echo "        msgbox(\""._LANG("list_message_selreg")."\");\n";
echo "    } else {\n";
echo "        redir('inicio.php?page=form&form=copy&table='+table+'&id='+ids);\n";
echo "    }\n";
echo "}\n";
echo "function multipledelete() {\n";
echo "    var ids=getchkids();\n";
echo "    var arg='inicio.php?page=process&action=delete&table='+table+'&id='+ids;\n";
echo "    if(ids.length==0) {\n";
echo "        msgbox(\""._LANG("list_message_selreg")."\");\n";
echo "    } else {\n";
echo "        buttons={\n";
echo "            '"._LANG("list_confirm_button_yes")."':function() { $('#dialog').dialog('close'); redir(arg); },\n";
echo "            '"._LANG("list_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
echo "        };\n";
echo "        msgbox(\""._LANG("list_confirm_seldelete")."\",buttons);\n";
echo "    }\n";
echo "}\n";
echo "function multiplesave() {\n";
echo "    var ids=getchkids();\n";
echo "    if(ids.length==0) {\n";
echo "        msgbox(\""._LANG("list_message_selreg")."\");\n";
echo "    } else {\n";
echo "        buttons={\n";
echo "            '"._LANG("list_confirm_button_yes")."':function() { $('#dialog').dialog('close'); document.f1.id.value=ids; mysubmit(); },\n";
echo "            '"._LANG("list_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
echo "        };\n";
echo "        msgbox(\""._LANG("list_confirm_selupdate")."\",buttons);\n";
echo "    }\n";
echo "}\n";
echo "function mostrar(arg,total) {\n";
echo "    if(total>100) {\n";
echo "        buttons={\n";
echo "            '"._LANG("list_confirm_button_yes")."':function() { $('#dialog').dialog('close'); redir(arg); },\n";
echo "            '"._LANG("list_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
echo "        };\n";
echo "        msgbox(str_replace('#numero#',total,\""._LANG("list_confirm_biglist")."\"),buttons);\n";
echo "    } else {\n";
echo "        resp=true;\n";
echo "        redir(arg);\n";
echo "    }\n";
echo "}\n";
echo "</script>\n";
?>