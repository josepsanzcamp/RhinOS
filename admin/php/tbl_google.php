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
if(!function_exists("getParam")) {
	$head=1;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();

	foreach($_POST as $key=>$val) {
		$temp=explode("_",$key);
		if($temp[0]=="lat") {
			$id=$temp[1];
			$lat=getParam("lat_$id");
			$lon=getParam("lon_$id");
			$name=getParam("logo_$id");
			$file=getParam("logo_$id"."_file");
			$size=getParam("logo_$id"."_size");
			$type=getParam("logo_$id"."_type");
			$texto=getParam("texto_$id");
			if(isset($_FILES["logo_$id"."_new"]["name"])) {
				$name_new=$_FILES["logo_$id"."_new"]["name"];
				if($name_new!="") {
					$query="SELECT logo_file FROM tbl_google WHERE id='$id'";
					$result=dbQuery($query);
					$row=dbFetchRow($result);
					if($row["logo_file"] && file_exists("files/".$row["logo_file"])) unlink("files/".$row["logo_file"]);
					dbFree($result);
					$name=$_FILES["logo_$id"."_new"]["name"];
					$size=$_FILES["logo_$id"."_new"]["size"];
					$type=$_FILES["logo_$id"."_new"]["type"];
					$tmpname=$_FILES["logo_$id"."_new"]["tmp_name"];
					$temp=explode(".",$name);
					$count_temp=count($temp);
					$ext="dat";
					if($count_temp>0) $ext=$temp[$count_temp-1];
					$ext=strtolower($ext);
					if($ext=="php") $ext="dat"; // FOR SECURITY REASONS
					$file=time().".".md5($name).".$ext";
					move_uploaded_file($tmpname,"files/".$file);
				}
			}
			$query="UPDATE tbl_google SET lat='$lat',lon='$lon',logo='$name',logo_file='$file',logo_size='$size',logo_type='$type',texto='$texto' WHERE id='$id'";
			dbQuery($query);
		}
	}
	location("inicio.php?page=$page");
	$head=0;$main=0;$tail=1;
	include("inicio.php");
	die();
}

if(!check_user()) die();

function row_tail() {
	openrow();
	settds("thead");
	list($title,$text)=make_title_text("button_ok",_LANG("tblgoogle_button_ok"),_LANG("tblgoogle_button_ok_title"),_LANG("tblgoogle_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("tblgoogle_button_cancel"),_LANG("tblgoogle_button_cancel_title"),_LANG("tblgoogle_button_cancel"));
	$url="redir(\"inicio.php\");";
	$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
	settds("tnada");
	putcolumn($temp,"center","","2","","style='height:33px'");
	closerow();
}

function row_header($text="") {
	openrow();
	settds("tnada");
	putcolumn($text,"center","","2","texts2","style='height:33px'");
	closerow();
}

openf1form();
puthidden("include","php/$page");
puthidden("page",$page);
escribe(get_migas());
escribe();
openform("900","","","","class='tabla'");

$query="SELECT COUNT(*) count FROM tbl_google";
$result=dbQuery($query);
$row=dbFetchRow($result);
$count=$row["count"];
dbFree($result);

if(!$count) {
	$query="INSERT INTO tbl_google(`id`,`lat`,`lon`,`logo`,`logo_file`,`logo_size`,`logo_type`,`texto`,`titulo`) VALUES(NULL,'','','','','0','','','')";
	dbQuery($query);
}

$query="SELECT * FROM tbl_google";
$result=dbQuery($query);
$first=1;
while($row=dbFetchRow($result)) {
	if(!$first) escribe();
	$first=0;
	$temp="";
	if(isset($row["titulo"])) if($row["titulo"]!="") $temp=_LANG("tblgoogle_label_for").$row["titulo"];
	row_header(_LANG("tblgoogle_label_title").$temp);
	openrow();
	settds("thead");
	putcolumn(_LANG("tblgoogle_label_lat"),"right","","","texts2");
	settds("tbody");
	putinput("lat".".".$row["id"],$row["lat"],"edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblgoogle_label_lon"),"right","","","texts2");
	settds("tbody");
	putinput("lon".".".$row["id"],$row["lon"],"edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblgoogle_label_logo"),"right","","","texts2 bigfield");
	settds("tbody");
	putphoto("logo".".".$row["id"],$row["logo"],$row["logo_file"],$row["logo_size"],$row["logo_type"],"over");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblgoogle_label_text"),"right","","","texts2 bigfield");
	settds("tbody");
	puttextarea("texto".".".$row["id"],$row["texto"],"edit");
	closerow();
	row_tail();
}

dbFree($result);

closeform();
closef1form();
