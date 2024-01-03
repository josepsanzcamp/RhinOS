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
$head=0;$main=0;$tail=0;
include("inicio.php");
ob_start();
$table=getParam("table");
$has_perm=0;
if(check_permissions("select",$table)) $has_perm=1;
if(check_permissions("insert",$table)) $has_perm=1;
if(check_permissions("update",$table)) $has_perm=1;
if(!$has_perm) intro();
$type=getParam("type");
getdinamicsconfig();
if(!isset($dinamics[$type])) die(_LANG("getdinamic_unknown_type").$type);
$tipo=$dinamics[$type]["type"];
$texto=$dinamics[$type]["text"];
$campo=getParam("campo");
$valor=getParam("valor");
$valor_file=getParam("valor_file");
$valor_size=getParam("valor_type");
$valor_type=getParam("valor_size");
settds("thead ui-widget-header");
$bigfields=array("textarea","file","photo");
$bigfield=in_array($tipo,$bigfields)?$bigfield=" bigfield":"";
putcolumn($texto.":","right","33%","","texts2".$bigfield);
settds("tbody ui-widget-content");
$form=getParam("form");
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
		if(check_demo("user")) putcolumn("<div class='texts ui-state-error' style='border:0;background:none'>"._LANG("getdinamic_demo_disabled")."</div>","left",$width_obj);
		else putfile($campo,$valor,$valor_file,$valor_size,$valor_type,$form);
		break;
	case "photo":
		if(check_demo("user")) putcolumn("<div class='texts ui-state-error' style='border:0;background:none'>"._LANG("getdinamic_demo_disabled")."</div>","left",$width_obj);
		else putphoto($campo,$valor,$valor_file,$valor_size,$valor_type,$form);
		break;
	default:
		die(_LANG("getdinamic_unknown_type").$tipo);
		break;
}
disconnect();
$buffer=ob_get_clean();
ob_start_protected("ob_gzhandler");
header_powered();
header_expires(false);
echo $buffer;
ob_end_flush();
