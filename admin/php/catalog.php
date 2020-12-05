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
openf1form();
puthidden("page","catalog.php");
escribe(get_migas());
escribe();
openform("900","","","","class='tabla'");
openrow();
settds("thead");
putcolumn(_LANG("catalog_show_icons_search"),"right","33%","","texts2");
settds("tbody");
putinput("search",$search,"");
closerow();
openrow();
list($title,$text)=make_title_text("button_ok",_LANG("catalog_button_ok"),_LANG("catalog_button_ok_title"),_LANG("catalog_button_ok"));
$url="mysubmit();";
$temp=get_button($title,$url,"","22","",$text);
list($title,$text)=make_title_text("quick_restart",_LANG("catalog_button_all"),_LANG("catalog_button_all_title"),_LANG("catalog_button_all"));
$url="redir(\"inicio.php?page=catalog.php&search=null\");";
$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
list($title,$text)=make_title_text("button_cancel",_LANG("catalog_button_cancel"),_LANG("catalog_button_cancel_title"),_LANG("catalog_button_cancel"));
$url="redir(\"inicio.php\");";
$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
settds("");
putcolumn($temp,"center","","2","","style='height:33px'");
closerow();
closeform();
escribe();
openform();
$expr1=array(" ","*");
$expr2=array("%","%");
$search_string=str_replace($expr1,$expr2,$search);
$search_array=explode("|",$search_string);
$where="0";
if($search!="") {
	$count_search=count($search_array);
	for($i=0;$i<$count_search;$i++) {
		$search_string=$search_array[$i];
		$where.=" OR icon LIKE '%$search_string%'";
	}
}
if($where=="0") $where="1";
$query="SELECT icon FROM def_icons WHERE $where ORDER BY icon";
$result=dbQuery($query);
$path="lib/crystal";
$sizes=array("16x16","32x32","48x48");
$count_sizes=count($sizes);
$max=16;
$i=0;
while($row=dbFetchRow($result)) {
	$icon=$row["icon"];
	if($i%$max==0) opentr();
	$temp="<table>";
	for($j=0;$j<$count_sizes;$j++) {
		$size=$sizes[$j];
		$file="$path/$size/$icon";
		$temp2=explode("x",$size);
		$width=$temp2[0];
		$height=$temp2[1];
		$temp.="<tr><td><img src='$file' title='$icon' width='$width' height='$height' /></td></tr>";
	}
	$temp.="</table>";
	escribetd($temp,"class='menushome ui-state-default ui-corner-all'");
	if($i%$max==$max-1) closetr();
	$i++;
}
dbFree($result);
$temp=str_replace("<table>","<table style='opacity:.0;'>",$temp);
while($i%$max!=0) {
	escribetd($temp,"class='menushome ui-state-default ui-corner-all'");
	$i++;
	if($i%$max==0) closetr();
}
closeform();
closef1form();
put_javascript_code("$(document).ready(function() { myfocus('search'); });");
?>