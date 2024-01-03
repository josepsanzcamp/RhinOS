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
function dbmailer($from,$fromname,$to,$subject,$body,$host,$user,$pass) {
	$subject=addslashes($subject);
	$body=addslashes($body);
	$hora=date("H:i:s",time());
	$fecha=date("Y-m-d",time());
	$query="INSERT INTO tbl_queue(`id`,`host`,`user`,`pass`,`from`,`fromname`,`to`,`subject`,`body`,`status`,`hora`,`fecha`,`fichero`,`fichero_file`,`fichero_size`,`fichero_type`) VALUES(NULL,'$host','$user','$pass','$from','$fromname','$to','$subject','$body','0','$hora','$fecha','','','','')";
	dbQuery($query);
}

function setdata($file,$data) {
	$fp=fopen($file,"w");
	fwrite($fp,$data);
	fclose($fp);
}

function adddata($file,$data) {
	$fp=fopen($file,"a");
	fwrite($fp,$data);
	fclose($fp);
}

function getdata($file) {
	$fp=fopen($file,"r");
	$data=fread($fp,filesize($file));
	fclose($fp);
	return $data;
}

define("SENDING","files/dbmailer.sending");
define("RUN","files/dbmailer.run");
define("RESPONSE","files/dbmailer.response");
define("REPORT","DBMailer Report");

function dbdaemon() {
	if(!file_exists(RUN)) touch(RUN);
	while(file_exists(RUN)) {
		$query="SELECT * FROM tbl_queue WHERE status='0' ORDER BY id ASC";
		$result=dbQuery($query);
		$numrows=dbNumRows($result);
		if(!$numrows) sleep(15);
		while($row=dbFetchRow($result)) {
			$id=$row["id"];
			$host=$row["host"];
			$user=$row["user"];
			$pass=$row["pass"];
			$from=$row["from"];
			$fromname=$row["fromname"];
			$to=$row["to"];
			$subject=$row["subject"];
			$body=$row["body"];
			$fichero=$row["fichero_file"];
			if($fichero!="") $body=file_get_contents("files/$fichero");
			$to=str_replace(array("<br>","<br/>","<br />"),"\n",$to);
			$to=strip_tags($to);
			$allto=preg_split("/[,;\n ]/",$to);
			$count=count($allto);
			$sending=0;
			if(file_exists(SENDING)) $sending=intval(getdata(SENDING));
			for($i=$sending;$i<$count;$i++) {
				setdata(SENDING,$i);
				$oneto=trim($allto[$i]);
				if($oneto!="") {
					$temp=sendmail($from,$fromname,"","",$oneto,$subject,$body,$host,$user,$pass);
					if($temp=="") $temp="<font color=\"#00aa00\">Ok</font>";
					else $temp="<font color=\"#aa0000\">$temp</font>";
					$response="$oneto: $temp<br/>\n";
					adddata(RESPONSE,$response);
					usleep(500000);
				}
			}
			$response=getdata(RESPONSE);
			$query="UPDATE tbl_queue SET status='1' WHERE id='$id'";
			dbQuery($query);
			$hora=date("H:i:s",time());
			$fecha=date("Y-m-d",time());
			$response=addslashes($response);
			$query="INSERT INTO tbl_response(`id`,`id_queue`,`response`,`hora`,`fecha`) VALUES(NULL,'$id','$response','$hora','$fecha')";
			dbQuery($query);
			$report=beginreport(REPORT);
			$report.=textreport("Host",$host);
			$report.=textreport("User",$user);
			$report.=textreport("Pass",$pass);
			$report.=textreport("From",$from);
			$report.=textreport("FromName",$fromname);
			$report.=textreport("Subject",$subject);
			$report.=textareareport("To",$response,false);
			$report.=textareareport("Body",get_body($body),false);
			$report.=endreport();
			sendmail($from,$fromname,"","",$from,REPORT,$report,$host,$user,$pass);
			if(file_exists(SENDING)) unlink(SENDING);
			if(file_exists(RESPONSE)) unlink(RESPONSE);
		}
		dbFree($result);
	}
}

if(!function_exists("getParam")) {
	$head=0;$main=0;$tail=0;
	include("inicio.php");
	set_db_cache(false);
	dbdaemon();
}
