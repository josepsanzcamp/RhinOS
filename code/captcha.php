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

/*
Idea original para programar este captcha obtenida de este post:
- http://sentidoweb.com/2007/01/03/laboratorio-ejemplo-de-captcha.php
Tambien aparece en otros posts buscando en google:
- http://www.google.es/search?q=captcha+alto_linea
*/

function captcha($param) {
	require_once("dbapp2.php");
	$param=explode(" ",trim($param));
	$count_param=count($param);
	$id=_dbapp2_replace($param[0]);
	if($id=="") $id="captcha";
	$width=150;
	$height=75;
	$letter=10;
	$number=20;
	$angle=10;
	$color="5C8ED1";
	$bgcolor="C8C8C8";
	$fgcolor="B4B4B4";
	$type="math";
	$length=5;
	$period=2;
	$amplitude=10;
	$blur=1;
	for($j=1;$j<$count_param;$j++) {
		if($param[$j]=="WIDTH") $width=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="HEIGHT") $height=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="LETTER") $letter=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="NUMBER") $number=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="ANGLE") $angle=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="COLOR") $color=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="BGCOLOR") $bgcolor=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="FGCOLOR") $fgcolor=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="TYPE") $type=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="LENGTH") $length=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="PERIOD") $period=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="AMPLITUDE") $amplitude=_dbapp2_replace($param[++$j]);
		elseif($param[$j]=="BLUR") $blur=_dbapp2_replace($param[++$j]);
	}
	srand((float)microtime(true)*10000000);
	// DEFINE THE CODE AND REAL CAPTCHA
	if($type=="number") {
		$code=str_pad(rand(0,pow(10,$length)-1),$length,"0",STR_PAD_LEFT);
		sessions("SET ${id}_value $code");
		sessions("SET ${id}_ipaddr ".(isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"NULL"));
		sessions("CLOSE");
	} elseif($type=="math") {
		$max=pow(10,round($length/2))-1;
		do {
			$num1=rand(0,$max);
			$oper=rand(0,1)?"+":"-";
			$num2=rand(0,$max);
			$code=$num1.$oper.$num2;
			$real=eval("return $code;");
		} while(strlen($code)!=$length || $real<0 || !_captcha_isprime($num1) || !_captcha_isprime($num2) || substr($num2,0,1)=="7");
		sessions("SET ${id}_value $real");
		sessions("SET ${id}_ipaddr ".(isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:"NULL"));
		sessions("CLOSE");
	} else {
		die();
	}
	// CREATE THE BACKGROUND IMAGE
	$im=imagecreatetruecolor($width,$height);
	$color2=imagecolorallocate($im,_captcha_color2dec($color,"R"),_captcha_color2dec($color,"G"),_captcha_color2dec($color,"B"));
	$bgcolor2=imagecolorallocate($im,_captcha_color2dec($bgcolor,"R"),_captcha_color2dec($bgcolor,"G"),_captcha_color2dec($bgcolor,"B"));
	$fgcolor2=imagecolorallocate($im,_captcha_color2dec($fgcolor,"R"),_captcha_color2dec($fgcolor,"G"),_captcha_color2dec($fgcolor,"B"));
	imagefill($im,0,0,$bgcolor2);
	$letters="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$font=getcwd()."/code/lib/fonts/GorriSans.ttf";
	$bbox=imagettfbbox($letter,0,$font,$letters[0]);
	$heightline=abs($bbox[7]-$bbox[1]);
	$numlines=intval($height/$heightline)+1;
	$maxletters=strlen($letters);
	for($i=0;$i<$numlines;$i++) {
		$posx=0;
		$posy=($heightline/2)+($heightline+$letter/4)*$i;
		while($posx<$width) {
			$oneletter=$letters[rand(0,$maxletters-1)];
			$oneangle=rand(-$angle,$angle);
			$bbox=imagettfbbox($letter,$oneangle,$font,$oneletter);
			imagettftext($im,$letter,rand(-$angle,$angle),(int)$posx,(int)$posy,$fgcolor2,$font,$oneletter);
			$posx+=$bbox[2]-$bbox[0]+$letter/4;
		}
	}
	// CREATE THE CAPTCHA CODE
	$im2=imagecreatetruecolor($width,$height);
	$color2=imagecolorallocate($im,_captcha_color2dec($color,"R"),_captcha_color2dec($color,"G"),_captcha_color2dec($color,"B"));
	$bgcolor2=imagecolorallocate($im,_captcha_color2dec($bgcolor,"R"),_captcha_color2dec($bgcolor,"G"),_captcha_color2dec($bgcolor,"B"));
	$fgcolor2=imagecolorallocate($im,_captcha_color2dec($fgcolor,"R"),_captcha_color2dec($fgcolor,"G"),_captcha_color2dec($fgcolor,"B"));
	imagefill($im2,0,0,$bgcolor2);
	imagecolortransparent($im2,$bgcolor2);
	$angles=array();
	$widths=array();
	$heights=array();
	$widthsum=0;
	for($i=0;$i<strlen($code);$i++) {
		$angles[$i]=rand(-$angle,$angle);
		$bbox=imagettfbbox($number,$angles[$i],$font,$code[$i]);
		$widths[$i]=abs($bbox[2]-$bbox[0]);
		$heights[$i]=abs($bbox[7]-$bbox[1]);
		$widthsum+=$widths[$i];
	}
	$widthmiddle=$width/2;
	$heightmiddle=$height/2;
	$posx=$widthmiddle-$widthsum/2;
	for($i=0;$i<strlen($code);$i++) {
		$posy=$heights[$i]/2+$heightmiddle;
		imagettftext($im2,$number,$angles[$i],(int)$posx,(int)$posy,$color2,$font,$code[$i]);
		$posx+=$widths[$i];
	}
	// COPY THE CODE TO BACKGROUND USING WAVE TRANSFORMATION
	$rel=3.1416/180;
	$inia=rand(0,360);
	$inib=rand(0,360);
	for($i=0;$i<$width;$i++) {
		$a=sin((($i*$period)+$inia)*$rel)*$amplitude;
		for($j=0;$j<$height;$j++) {
			$b=sin((($j*$period)+$inib)*$rel)*$amplitude;
			if($i+$b>=0 && $i+$b<$width && $j+$a>=0 && $j+$a<$height) imagecopymerge($im,$im2,(int)$i,(int)$j,(int)($i+$b),(int)($j+$a),1,1,100);
		}
	}
	// APPLY BLUR
	if($blur && function_exists("imagefilter")) {
		imagefilter($im,IMG_FILTER_GAUSSIAN_BLUR);
	}
	// OUTPUT IMAGE
	header_powered();
	header_expires(false);
	header("Content-type: image/png");
	imagepng($im);
	imagedestroy($im);
	die();
}

function _captcha_color2dec($color,$component) {
	$offset=array("R"=>0,"G"=>2,"B"=>4);
	if(!isset($offset[$component])) show_php_error(array("phperror"=>"Unknown component"));
	return hexdec(substr($color,$offset[$component],2));
}

function _captcha_isprime($num) {
	// SEE www.polprimos.com FOR UNDERSTAND IT
	if($num<2) return false;
	if($num%2==0 && $num!=2) return false;
	if($num%3==0 && $num!=3) return false;
	if($num%5==0 && $num!=5) return false;
	// PRIMER NUMBERS ARE DISTRIBUTED IN 8 COLUMNS
	$div=7;
	$max=intval(sqrt($num));
	while(1) {
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=4;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=2;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=4;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=2;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=4;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=6;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=2;
		if($num%$div==0 && $num!=$div) return false;
		if($div>=$max) break;
		$div+=6;
	}
	return true;
}
?>