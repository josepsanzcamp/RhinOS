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
function ismail($addr){
	if(defined("__FORCE_ISMAIL__")) return __FORCE_ISMAIL__;
	$exp="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/";
	if(preg_match($exp, $addr)) return true;
	return false;
}

function sendmail($from,$fromname,$replyto,$replytoname,$to,$subject,$body,$arg1="",$arg2="",$arg3="",$arg4="") {
	$from=strtolower($from);
	$to=strtolower($to);
	if(is_array($arg1)) {
		$files=$arg1;
		$host=$arg2;
		$user=$arg3;
		$pass=$arg4;
	} elseif(is_array($arg4)) {
		$host=$arg1;
		$user=$arg2;
		$pass=$arg3;
		$files=$arg4;
	} else {
		$host=$arg1;
		$user=$arg2;
		$pass=$arg3;
		$files="";
	}
	$libraries=array("lib/phpmailer/class.phpmailer.php","lib/phpmailer/class.smtp.php");
	foreach($libraries as $library) {
		$libpath=getcwd()."/".$library;
		if(!file_exists($libpath)) $libpath=getcwd()."/admin/".$library;
		if(!file_exists($libpath)) $libpath=getcwd()."/../admin/".$library;
		require_once($libpath);
	}
	$mail=new PHPMailer();
	if(!$mail->set("XMailer",get_name_version_revision())) return $mail->ErrorInfo;
	if(!$mail->AddCustomHeader("X-Originating-IP",getServer("REMOTE_ADDR"))) if($mail->ErrorInfo) return $mail->ErrorInfo;
	if(!$mail->set("CharSet","UTF-8")) return $mail->ErrorInfo;
	if(!$mail->SetFrom($from,$fromname)) return $mail->ErrorInfo;
	if(!$mail->set("WordWrap",50)) return $mail->ErrorInfo;
	if(!$mail->set("SMTPOptions",array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,"allow_self_signed"=>true)))) return $mail->ErrorInfo;
	if($replyto!="") if(!$mail->AddReplyTo($replyto,$replytoname)) if($mail->ErrorInfo) return $mail->ErrorInfo;
	$mail->IsHTML();
	if(!in_array($host,array("mail","sendmail","qmail",""))) {
		$mail->IsSMTP();
		$host=explode(":",$host);
		if(!$mail->set("Host",$host[0])) return $mail->ErrorInfo;
		if(isset($host[1]) && $host[1]!="") if(!$mail->set("Port",$host[1])) return $mail->ErrorInfo;
		if(isset($host[2]) && $host[2]!="") if(!$mail->set("SMTPSecure",$host[2])) return $mail->ErrorInfo;
		if(!$mail->set("Username",$user)) return $mail->ErrorInfo;
		if(!$mail->set("Password",$pass)) return $mail->ErrorInfo;
		if(!$mail->set("SMTPAuth",($user!="" || $pass!=""))) return $mail->ErrorInfo;
		if(!$mail->set("Hostname",$host[0])) return $mail->ErrorInfo;
	} else {
		if($host=="mail") $mail->IsMail();
		elseif($host=="sendmail") $mail->IsSendmail();
		elseif($host=="qmail") $mail->IsQmail();
	}
	if(!$mail->set("Subject",$subject)) return $mail->ErrorInfo;
	if(!$mail->set("Body",$body)) return $mail->ErrorInfo;
	if(!$mail->set("AltBody",$mail->html2text($body))) return $mail->ErrorInfo;
	if(is_array($files)) {
		$count=count($files);
		for($i=0;$i<$count;$i=$i+3) {
			$file=$files[$i+0];
			$name=$files[$i+1];
			$mime=$files[$i+2];
			$mail->AddAttachment($file,$name,"base64",$mime);
		}
	}
	$mail->AddAddress($to);
	if(!ismail($to)) return function_exists("_LANG")?_LANG("mailer_invalid_address"):"Invalid address";
	if(!$mail->Send()) return $mail->ErrorInfo;
	return "";
}

function beginreport($subject) {
	$message="<html>\n";
	$message.="<head>\n";
	$message.="<title>$subject</title>\n";
	$message.="</head>\n";
	$message.="<body bgcolor='".colorreport("bgbody")."'>\n";
	$message.="<table cellspacing='2px' cellpadding='2px' border='0px' width='600px'>\n";
	$message.=headreport($subject);
	return $message;
}

function textreport($label,$value) {
	if($label!="") $label.=":";
	$message="<tr>\n";
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='right' width='200px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.=$label;
	$message.="</b></font>";
	$message.="</td>\n";
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='left' width='400px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.=$value;
	$message.="</b></font>";
	$message.="</td>\n";
	$message.="</tr>\n";
	return $message;
}

function textareareport($label,$value,$repare=true) {
	if($label!="") $label.=":";
	$message="";
	if($label!="") {
		$message.="<tr>\n";
		$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='center' width='600px' colspan='2'>";
		$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
		$message.=$label;
		$message.="</b></font>";
		$message.="</td>\n";
		$message.="</tr>\n";
	}
	if($repare) $value=str_replace("\n","<br/>",$value);
	$message.="<tr>\n";
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='left' width='600px' colspan='2' height='100px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.=$value;
	$message.="</b></font>";
	$message.="</td>\n";
	$message.="</tr>\n";
	return $message;
}

function mailreport($label,$value) {
	if($label!="") $label.=":";
	$message="<tr>\n";
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='right' width='200px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.=$label;
	$message.="</b></font>";
	$message.="</td>\n";
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='left' width='400px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.="<a style='color:".colorreport("fgreport")."' href='mailto:$value'>$value</a>";
	$message.="</b></font>";
	$message.="</td>";
	$message.="</tr>\n";
	return $message;
}

function linkreport($label,$value,$text="") {
	if($label!="") $label.=":";
	$message="<tr>\n";
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='right' width='200px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.=$label;
	$message.="</b></font>";
	$message.="</td>\n";
	if($text=="") $text=$value;
	$message.="<td bgcolor='".colorreport("bgreport")."' valign='top' align='left' width='400px'>";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgreport")."'><b>";
	$message.="<a style='color:".colorreport("fgreport")."' href='$value'>$text</a>";
	$message.="</b></font>";
	$message.="</td>";
	$message.="</tr>\n";
	return $message;
}

function endreport() {
	$message="</table>\n";
	$message.="</body>\n";
	$message.="</html>\n";
	return $message;
}

function headreport($subject) {
	$message="<tr>\n";
	$message.="<td bgcolor='".colorreport("bgheader")."' valign='top' align='center' width='600px' colspan='2' >";
	$message.="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='".colorreport("fgheader")."'><b>";
	$message.=$subject;
	$message.="</b></font>";
	$message.="</td>\n";
	$message.="</tr>\n";
	return $message;
}

function separatorreport() {
	$message="<tr>";
	$message.="<td width='600px' colspan='2'>&nbsp;</td>";
	$message.="</tr>";
	return $message;
}

function colorreport($arg) {
	static $colors=array(
		"bgbody"=>"#FFFFFF",
		"bgheader"=>"#336699",
		"fgheader"=>"#FFFFFF",
		"bgreport"=>"#EEEFFF",
		"fgreport"=>"#666666");
	if(is_array($arg)) {
		foreach($arg as $key=>$val) $colors[$key]=$val;
		return;
	}
	if(!isset($colors[$arg])) return "";
	return $colors[$arg];
}
?>
