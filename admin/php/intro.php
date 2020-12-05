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
if(!check_user()) {
	$demo="";
	if(check_demo("admin")) $demo="demo";
	escribe(get_migas());
	escribe();
	if($_SESSION["user"]!="" || $_SESSION["pass"]!="") {
		escribe("<div class='errors ui-state-error ui-corner-all' style='width:390px;padding:5px'>"._LANG("intro_access_denied")."</div><br/>","texts","id='denied'");
		echo "<script type='text/javascript'>\n";
		echo "$(document).ready(function() {\n";
		echo "    setTimeout(function() {\n";
		echo "        $('#denied').hide('fast',function() {\n";
		echo "            fix_max_height();\n";
		echo "        });\n";
		echo "    },3000);";
		echo "});\n";
		echo "</script>\n";
	}
	openf1form();
	puthidden("include","php/login.php");
	puthidden("action","login");
	puthidden("querystring",$querystring);
	openform("400","","","","class='tabla'");
	openrow();
	settds("thead");
	putcolumn(_LANG("intro_username"),"right","50%","","texts2");
	settds("tbody");
	putinput("user",$demo,"","onkeypress='if(event.keyCode==13) document.getElementById(\"pass\").focus()'","width:150px");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("intro_password"),"right","50%","","texts2");
	settds("tbody");
	putinput("pass",$demo,"","type='password' onkeypress='if(event.keyCode==13) submit()'","width:150px");
	closerow();
	openrow();
	list($title,$text)=make_title_text("button_ok",_LANG("intro_button_ok"),_LANG("intro_button_ok_title"),_LANG("intro_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	settds("");
	putcolumn($temp,"center","","2","","style='height:33px'");
	closerow();
	closeform();
	escribe();
	openform("700","","","","class='tabla'");
	openrow();
	settds("");
	putcolumn(_LANG("intro_remember"),"right","66%","","texts2");
	$temp=0;
	if(isset($_COOKIE["remember"])) $temp=$_COOKIE["remember"];
	$width_obj="33%";
	putboolean("remember",$temp,"");
	$width_obj="66%";
	closerow();
	closeform();
    escribe();
    if(check_demo("admin")) {
		$temp="<table><tr><td class='texts2' align='center' colspan='2'>"._LANG("intro_demo")."</td></tr><tr><td width='50%' class='texts' align='right'>"._LANG("intro_username")."</td><td width='50%' class='texts2'>$demo</td></tr><tr><td width='50%' class='texts' align='right'>"._LANG("intro_password")."</td><td width='50%' class='texts2'>$demo</td></tr></table>";
		escribe($temp);
	}
	closef1form();
	put_javascript_code("$(document).ready(function() { myfocus('user'); });");
} else {
	// DEFINE CLOSEMENU FUNCTION
	function _intro_closemenu() {
		global $j;
		global $corners;

		while($j%3!=0) {
			escribetd("<button disabled=disabled type='button' class='menushome ui-state-default ui-state-disabled ${corners[$j]}' style='width:100%;height:60px'></button>","width='33%' style='padding:1px'");
			$j++;
			if($j%3==0) closetr();
		}
	}
	// CONTINUE
	escribe(get_migas(),"","colspan='3'");
	escribe();
	if(check_admin()) {
		if(!isset($error)) $error=array();
		if(check_demo("admin")) $error[]="WARNING:"._LANG("intro_demo_enabled");
		if(check_block_admin()) $error[]="WARNING:"._LANG("intro_block_admin");
		if(checkRegisterGlobals()) $error[]="ERROR:"._LANG("intro_register_globals");
		if(checkZlibOutputCompression()) $error[]="ERROR:"._LANG("intro_zlib_output_compression");
		checkDependencies();
		check_db_schema();
		update_db_spec_from_file();
		puterrores();
	}
	$menu=getmenu();
	$total=count($menu["name"]);
	if(!$total) {
		$error[]="WARNING:"._LANG("intro_config_not_found");
		puterrores();
		unset($error[count($error)-1]);
	}
	$matrix=array();
	$lastgroup=($total>0)?intval($menu["pos"][0]/100):0;
	$j=0;
	for($i=0;$i<$total;$i++) {
		$pos=$menu["pos"][$i];
		$group=intval($pos/100);
		if($lastgroup!=$group) {
			while($j%3!=0) {
				$matrix[]=$lastgroup;
				$j++;
			}
		}
		$matrix[]=$group;
		$j++;
		$lastgroup=$group;
	}
	while($j%3!=0) {
		$matrix[]=$group;
		$j++;
	}
	$corners=array();
	for($i=0;$i<$j;$i++) {
		$corner="";
		if($i%3==0) {
			if($i==0) $corner.=" ui-corner-tl";
			if($i>=3) if($matrix[$i-3]!=$matrix[$i]) $corner.=" ui-corner-tl";
			if($i==$j-3) $corner.=" ui-corner-bl";
			if($i<$j-3) if($matrix[$i+3]!=$matrix[$i]) $corner.=" ui-corner-bl";
		} elseif($i%3==2) {
			if($i==2) $corner.=" ui-corner-tr";
			if($i>=3) if($matrix[$i-3]!=$matrix[$i]) $corner.=" ui-corner-tr";
			if($i==$j-1) $corner.=" ui-corner-br";
			if($i<$j-3) if($matrix[$i+3]!=$matrix[$i]) $corner.=" ui-corner-br";
		}
		$corners[]=$corner;
	}
	$lastgroup=($total>0)?intval($menu["pos"][0]/100):0;
	$htmlgroup=getnamegroup($lastgroup*100);
	if($htmlgroup!="") {
		escribe($htmlgroup,"","colspan='3'","left");
		escribe();
	}
	$j=0;
	for($i=0;$i<$total;$i++) {
		$name=$menu["name"][$i];
		$tbl=$menu["tbl"][$i];
		$desc=$menu["desc"][$i];
		$icon=$menu["icon"][$i];
		$pos=$menu["pos"][$i];
		$url="inicio.php?page=list&table=$tbl";
		if(substr($tbl,-4)==".php") $url="inicio.php?page=$tbl";
		if(substr($tbl,-4)==".adm") $url="inicio.php?func=$tbl";
		$action="redir(\"$url\");";
		$direct=array("download_db_spec.adm","download_backup.adm");
		if(in_array($tbl,$direct)) $action="document.location.href=\"inicio.php?include=php/db_spec.php&func=$tbl\"";
		$name=str_replace("'","",$name);
		$name=str_replace(array("&lt;","&gt;"),array("<",">"),$name);
		$desc=str_replace("'","",$desc);
		$desc=str_replace(array("&lt;","&gt;"),array("<",">"),$desc);
		$title="<table><tr><td rowspan=2><img src=\"lib/crystal/48x48/$icon\" align=\"top\" /></td><td height=1 valign=top><em>$name</em></td></tr><tr><td valign=top>$desc</td></tr></table>";
		$text="<table width='100%' height='100%'><tr><td rowspan=2 width=1><img src=\"lib/crystal/48x48/$icon\" /></td><td height=1 valign=top class=\"menushometxt2 ui-state-default\" style=\"background:none;border:none;font-weight:bold;padding-left:5px;padding-top:5px;\">$name</td></tr><tr><td valign=top class=\"menushometxt ui-state-default\" style=\"background:none;border:none;font-weight:normal;padding-left:5px;\">$desc</td></tr></table>";
		$group=intval($pos/100);
		if($lastgroup!=$group) {
			$lastgroup=$group;
			_intro_closemenu();
			escribe();
			$htmlgroup=getnamegroup($lastgroup*100);
			if($htmlgroup!="") {
				escribe($htmlgroup,"","colspan='3'","left");
				escribe();
			}
		}
		$temp=get_button($title,$action,"100%","60","menushome ui-state-default ${corners[$j]}",$text,array("menushometxt2","menushometxt"));
		if($j%3==0) opentr();
		escribetd($temp,"width='33%' style='padding:1px'");
		if($j%3==2) closetr();
		$j++;
	}
	_intro_closemenu();
	$login=get_and_clear_login();
	if($login) putnotes();
	if($login && check_demo("user")) msgbox(_LANG("intro_demo_message"));
}
?>