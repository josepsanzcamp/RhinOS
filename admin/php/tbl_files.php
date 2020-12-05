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
if(!function_exists("getParam")) {
	$head=1;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();
	if(check_demo("user")) {
		msgbox(_LANG("tblfiles_demo_disabled"),"back");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	$newtag=str_replace(" ","_",strtoupper(getParam("newtag")));
	$image=getParam("image");
	$deltag=getParam("deltag");
	if($newtag!="") {
		if(!check_admin()) die();
		$query="SELECT tag FROM tbl_files WHERE tag='$newtag'";
		$result=dbQuery($query);
		$numrows=dbNumRows($result);
		dbFree($result);
		if(!$numrows) {
			$name=$_FILES["file_new"]["name"];
			$size=$_FILES["file_new"]["size"];
			$type=$_FILES["file_new"]["type"];
			$tmpname=$_FILES["file_new"]["tmp_name"];
			$temp=explode(".",$name);
			$count_temp=count($temp);
			$ext="dat";
			if($count_temp>0) $ext=$temp[$count_temp-1];
			$ext=strtolower($ext);
			if($ext=="php") $ext="dat"; // FOR SECURITY REASONS
			$file=time().".".md5($name).".$ext";
			$query="INSERT INTO tbl_files(id,tag,image,file,file_file,file_size,file_type) VALUES(NULL,'$newtag','$image','$name','$file','$size','$type')";
			dbQuery($query);
			move_uploaded_file($tmpname,"files/".$file);
			location("inicio.php?page=$page");
		} else {
			msgbox(_LANG("tblfiles_message_tag_exists"),"inicio.php?page=$page");
		}
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	if($deltag!="") {
		if(!check_admin()) die();
		$deltag_array=explode(",",$deltag);
		foreach($deltag_array as $deltag) {
			$query="SELECT file_file FROM tbl_files WHERE tag='$deltag'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			if($row["file_file"] && file_exists("files/".$row["file_file"])) unlink("files/".$row["file_file"]);
			dbFree($result);
			$query="DELETE FROM tbl_files WHERE tag='$deltag'";
			dbQuery($query);
		}
		location("inicio.php?page=$page");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	foreach($_FILES as $key=>$val) {
		$tag=str_replace("_new","",$key);
		if(getParam($tag."_del")) {
			$query="SELECT file_file FROM tbl_files WHERE tag='$tag'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			if($row["file_file"] && file_exists("files/".$row["file_file"])) unlink("files/".$row["file_file"]);
			dbFree($result);
			$query="UPDATE tbl_files SET file='',file_file='',file_size='0',file_type='' WHERE tag='$tag'";
			dbQuery($query);
		}
		$name=$val["name"];
		if($name!="") {
			$query="SELECT file_file FROM tbl_files WHERE tag='$tag'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			if($row["file_file"] && file_exists("files/".$row["file_file"])) unlink("files/".$row["file_file"]);
			dbFree($result);
			$size=$val["size"];
			$type=$val["type"];
			$tmpname=$val["tmp_name"];
			$temp=explode(".",$name);
			$count_temp=count($temp);
			$ext="dat";
			if($count_temp>0) $ext=$temp[$count_temp-1];
			$ext=strtolower($ext);
			if($ext=="php") $ext="dat"; // FOR SECURITY REASONS
			$file=time().".".md5($name).".$ext";
			$query="UPDATE tbl_files SET file='$name',file_file='$file',file_size='$size',file_type='$type' WHERE tag='$tag'";
			dbQuery($query);
			move_uploaded_file($tmpname,"files/".$file);
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
	list($title,$text)=make_title_text("button_ok",_LANG("tblfiles_button_ok"),_LANG("tblfiles_button_ok_title"),_LANG("tblfiles_button_ok"));
	$url="mysubmit();";
	$temp=get_button($title,$url,"","22","",$text);
	list($title,$text)=make_title_text("button_cancel",_LANG("tblfiles_button_cancel"),_LANG("tblfiles_button_cancel_title"),_LANG("tblfiles_button_cancel"));
	$url="redir(\"inicio.php\");";
	$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
	settds("tnada");
	putcolumn($temp,"center","","2","","style='height:33px'");
	closerow();
}

function row_header($text="") {
	openrow();
	settds("tdsh");
	putcolumn($text,"center","","2","texts2","style='height:33px'");
	closerow();
}

function row_header2($text="") {
	openrow();
	settds("thead");
	putcolumn($text,"center","","2","texts2");
	closerow();
}

openf1form();
puthidden("include","php/$page");
puthidden("page",$page);
escribe(get_migas());
escribe();
openform("900","","","","class='tabla'");

if(check_admin()) {
	row_header(_LANG("tblfiles_title_create_file"));
	openrow();
	settds("thead");
	putcolumn(_LANG("tblfiles_label_tag"),"right","","","texts2");
	settds("tbody");
	putinput("newtag","","edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblfiles_label_isfile"),"right","","","texts2");
	settds("tbody");
	putboolean("image","0","edit");
	closerow();
	openrow();
	settds("thead");
	putcolumn(_LANG("tblfiles_label_file"),"right","","","texts2");
	settds("tbody");
	putfile("file","","","","","new");
	closerow();
	row_tail();
	escribe();
	row_header(_LANG("tblfiles_title_delete_file"));
	openrow();
	settds("thead");
	putcolumn(_LANG("tblfiles_label_delete"),"right","","","texts2");
	settds("tbody");
	putmultiselect("tbl_files","deltag","edit");
	closerow();
	row_tail();
	escribe();
}

$query="SELECT * FROM tbl_files ORDER BY tag";
$result=dbQuery($query);

if(dbNumRows($result)>0) {
	row_header(_LANG("tblfiles_title_current_files"));
	while($row=dbFetchRow($result)) {
		$temp=$row["tag"];
		$temp=str_replace("_"," ",$temp);
		$temp=strtolower($temp);
		$temp=ucfirst($temp);
		row_header2($temp);
		openrow();
		if(!$row["image"]) {
			settds("thead");
			putcolumn(_LANG("tblfiles_label_file"),"right","","","texts2 bigfield");
			settds("tbody");
			putfile($row["tag"],$row["file"],$row["file_file"],$row["file_size"],$row["file_type"],"over");
		} else {
			settds("thead");
			putcolumn(_LANG("tblfiles_label_photo"),"right","","","texts2 bigfield");
			settds("tbody");
			putphoto($row["tag"],$row["file"],$row["file_file"],$row["file_size"],$row["file_type"],"over");
		}
		settds("tbody");
		closerow();
		row_tail();
		escribe();
	}
}

if(!check_admin() && !dbNumRows($result)) {
	msgbox(_LANG("tblfiles_message_nodata"),"inicio.php");
}

dbFree($result);

closeform();
closef1form();
?>