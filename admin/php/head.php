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
opentr();
opentd("class='header ui-widget-header ui-corner-bottom'");
opentable("height='50px'");
opentr();
if(isset($logo)) {
	if(!file_exists("files/".$logo)) unset($logo);
	else $logo="<img src='inicio.php?include=php/phpthumb.php&src=$logo&w=460&h=46&f=png' />";
}
if(!isset($logo)) $logo=$pagename;
$logo="<a href='inicio.php' style='text-decoration:none;'>$logo</a>";
if(!check_user()) {
	escribetd("&nbsp;&nbsp;&nbsp;","width='10px'");
	escribetd($logo,"align='center' class='titulos' width='100%'");
	escribetd("&nbsp;&nbsp;&nbsp;","width='10px'");
} else {
	escribetd("&nbsp;&nbsp;&nbsp;","width='10px' rowspan='2'");
	escribetd($logo,"align='left' class='titulos' width='100%' rowspan='2'");
	escribetd("&nbsp;&nbsp;&nbsp;","width='10px' rowspan='2'");
	$temp="";
	list($title,$text)=make_title_text("password",_LANG("head_button_password"),_LANG("head_button_password_title"),_LANG("head_button_password"));
	$url="redir(\"inicio.php?page=password.php\");";
	$temp.=get_button($title,$url,"","25","menustop ui-state-default ui-corner-bottom",$text);
	list($title,$text)=make_title_text("xfmail",_LANG("head_button_contact"),_LANG("head_button_contact_title"),_LANG("head_button_contact"));
	$url="redir(\"inicio.php?page=contact.php\");";
	$temp.=get_button($title,$url,"","25","menustop ui-state-default ui-corner-bottom",$text);
	list($title,$text)=make_title_text("khelpcenter",_LANG("head_button_about"),_LANG("head_button_about_title"),_LANG("head_button_about"));
	$url="redir(\"inicio.php?page=about.php\");";
	$temp.=get_button($title,$url,"","25","menustop ui-state-default ui-corner-bottom",$text);
	list($title,$text)=make_title_text("logout",_LANG("head_button_logout"),_LANG("head_button_logout_title"),_LANG("head_button_logout"));
	$url="logout();";
	$temp.=get_button($title,$url,"","25","menustop ui-state-default ui-corner-bottom",$text);
	$temp.="<script type='text/javascript'>\n";
	$temp.="function logout() {\n";
	$temp.="    buttons={\n";
	$temp.="        '"._LANG("head_confirm_button_yes")."':function() { $('#dialog').dialog('close'); redir('inicio.php?include=php/login.php&action=logout'); },\n";
	$temp.="        '"._LANG("head_confirm_button_not")."':function() { $('#dialog').dialog('close'); }\n";
	$temp.="    };\n";
	$temp.="    msgbox(\""._LANG("head_confirm_logout")."\",buttons);\n";
	$temp.="}\n";
	$temp.="</script>\n";
	escribetd($temp,"valign='top' align='right'");
	escribetd("&nbsp;&nbsp;&nbsp;","width='10px' rowspan='2'");
	closetr();
	opentr();
	list($title,$text)=make_title_text("colors",_LANG("head_button_style"),_LANG("head_button_style_title").ucwords(str_replace(array("_","-",".")," ",$style)),_LANG("head_button_style"));
	$temp="<a title='$title'>$text</a>&nbsp;";
	$temp.="<select class='inputs ui-state-default ui-corner-all' name='style' id='style' onchange='change_style()' title='$title'>\n";
	$files=array_merge(glob("lib/jquery/jquery-ui.*"),glob("jquery-ui.*"));
	foreach($files as $key=>$val) {
		$files[$key]=implode(".",array_slice(explode(".",$val),-2,2));
	}
	$files=array_diff($files,array("min.js"));
	sort($files);
	foreach($files as $file) {
		$selected="";
		if($file==$style) $selected="selected";
		$temp.="<option value='$file' $selected>".ucwords(str_replace(array("_","-",".")," ",$file))."</option>\n";
	}
	$temp.="</select>";
	escribetd($temp,"class='texts4' height='25' align='right'");
}
closetr();
closetable();
closetd();
closetr();
