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
	$head=0;$main=0;$tail=0;
	include("inicio.php");
	if(!check_user()) die();
	$table=getParam("table");
	if(!checkTable($table)) {
		$head=1;$main=0;$tail=0;
		include("inicio.php");
		msgbox(_LANG("export_message_unallowed"),"inicio.php");
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	$format=getParam("format");
	srand(intval(microtime(true)*1000000));
	$cache=get_temp_directory().md5(uniqid(rand(),true)).".".substr($format,0,3);
	$title=_LANG("export_label_list_of").getnametable($table);
	switch($format) {
		case "pdf":
			$marginleft=20;
			$marginright=20;
			$margintop=30;
			$marginbottom=30;
			$marginheader=20;
			$marginfooter=20;
			$widthcol1=50;
			$widthcol2=120;
			include("lib/tcpdf/vendor/autoload.php");
			class PDF extends TCPDF {
				function Header() {
					global $marginleft,$marginheader,$margintop;
					global $widthcol1,$widthcol2;
					global $pagename,$title;
					$this->SetFillColor(224,224,224);
					$this->RoundedRect($marginleft,$marginheader,$widthcol1+$widthcol2,4,1,"1111","F");
					$this->SetFont("Helvetica","B",8);
					$this->SetTextColor(64,64,64);
					$this->SetXY($marginleft,$marginheader);
					$this->MultiCell($widthcol1+$widthcol2,4," ".$title,0,"L");
					$this->SetXY($marginleft,$marginheader);
					$this->MultiCell($widthcol1+$widthcol2,4,$pagename." ",0,"R");
					$this->SetXY($marginleft,$margintop);
				}
				function Footer() {
					global $marginleft,$marginfooter;
					global $widthcol1,$widthcol2;
					$this->SetFillColor(224,224,224);
					$this->SetXY($marginleft,-$marginfooter);
					$this->RoundedRect($marginleft,$this->GetY(),$widthcol1+$widthcol2,4,1,"1111","F");
					$this->SetFont("Helvetica","B",8);
					$this->SetTextColor(64,64,64);
					$this->SetXY($marginleft,-$marginfooter);
					$this->MultiCell($widthcol1+$widthcol2,4,_LANG("export_label_page_number").($this->PageNo())." ",0,"R");
					$this->SetTextColor(160,160,160);
					$this->SetXY($marginleft,-$marginfooter);
					$this->MultiCell($widthcol1+$widthcol2,4," ".get_name_version_revision(true),0,"L");
				}
			}
			$pdf=new PDF("P","mm","A4");
			$pdf->SetCreator(get_name_version_revision(true));
			$pdf->SetMargins($marginleft,$margintop,$marginright);
			$pdf->SetAutoPageBreak(true,$marginbottom);
			$paginacion=getParam("paginacion");
			$first=true;
			break;
		case "xls5":
		case "xls7":
			require_once "lib/phpspreadsheet/vendor/autoload.php";
			$objPHPExcel = new PhpOffice\PhpSpreadsheet\Spreadsheet();
			$objPHPExcel->getProperties()->setCreator($title);
			$objPHPExcel->getProperties()->setLastModifiedBy($title);
			$objPHPExcel->getProperties()->setTitle($title);
			$objPHPExcel->getProperties()->setSubject($title);
			$objPHPExcel->getProperties()->setDescription($title);
			$objPHPExcel->getProperties()->setKeywords($title);
			$objPHPExcel->getProperties()->setCategory($title);
			$objPHPExcel->setActiveSheetIndex(0);
			$fila=1;
			break;
		case "csv":
			$fd=fopen($cache,"w");
			$separador=getParam("separador");
			break;
		case "zip":
			$cache2=substr($cache,0,-4);
			mkdir($cache2);
			break;
		default:
			die();
	}
	getformconfig($table);
	reparaformconfig();
	getdinamicsconfig();
	$count_campos=count($campos);
	$primeralinea=getParam("primeralinea");
	$campos_export=array();
	$lineas=array();
	for($i=0;$i<$count_campos;$i++) {
		$campo=$campos[$i];
		$temp=explode(":",$campo);
		$campo_real=$temp[0];
		if(isset($temp[1])) $campo_arg=$temp[1];
		$texto=$textos[$i];
		$type=$types[$i];
		if($type=="select") {
			$query="SELECT * FROM db_selects WHERE tbl='$table' AND row='$campo'";
			$result=dbQuery($query);
			$row=dbFetchRow($result);
			$table_ref=$row["table_ref"];
			$value_ref=$row["value_ref"];
			$text_ref=$row["text_ref"];
			dbFree($result);
		}
		$mostrar_campo=getParam("mostrar_$campo");
		if($mostrar_campo) {
			$lineas[]=$texto;
			$campos_export[]=$campo;
		}
	}
	$labels=$lineas;
	if($primeralinea) {
		switch($format) {
			case "xls5":
			case "xls7":
				$columns=array();
				for($i=ord("A");$i<=ord("Z");$i++) $columns[]=sprintf("%c",$i);
				for($i=ord("A");$i<=ord("Z");$i++) for($j=ord("A");$j<=ord("Z");$j++) $columns[]=sprintf("%c%c",$i,$j);
				foreach($lineas as $key=>$val) {
					$cell=sprintf("%s%d",$columns[$key],$fila);
					$objPHPExcel->getActiveSheet()->setCellValue($cell, $val);
				}
				$fila++;
				break;
			case "csv":
				$lineas="\"".implode("\"".$separador."\"",$lineas)."\"";
				fwrite($fd,$lineas."\n");
				break;
		}
	}
	switch($format) {
		case "pdf":
			if($paginacion=="0") $pdf->AddPage("P");
			$oldmax=0;
			foreach($labels as $key=>$val) {
				$labels[$key]=$val;
				$pdf->SetFont("Helvetica","B",8);
				$newmax=$pdf->GetStringWidth($val);
				if($newmax>$oldmax) $oldmax=$newmax;
			}
			$widthcol1=$oldmax+4;
			$widthmax=210-$marginleft-$marginright;
			if($widthcol1>$widthmax*2/3) $widthcol1=$widthmax*2/3;
			$widthcol2=210-$marginleft-$marginright-$widthcol1;
			break;
	}
	set_db_cache(false);
	// FIX AN IMPORTANT BUG
	$campos_fix=$campos;
	$types_fix=$types;
	$textos_fix=$textos;
	getlistconfig($table);
	$limit=checkNumberInf(getParam("limit"));
	$offset=checkNumber(getParam("offset"))-1;
	$query=process_query();
	$query="SELECT {$table}_id FROM ($query) fix";
	$result=dbQuery($query);
	$fix=array();
	while($row=dbFetchRow($result)) $fix[]=$row[$table."_id"];
	$fix=implode(",",$fix);
	if($fix=="") $fix="-1";
	$campos=$campos_fix;
	$types=$types_fix;
	$textos=$textos_fix;
	// APPLY THE FIX AND CONTINUE
	$limit="inf";
	$offset=0;
	$search="";
	$query=process_query();
	$query="SELECT * FROM ($query) fix WHERE {$table}_id IN ($fix)";
	$result=dbQuery($query);
	if(dbNumRows($result)==0) {
		$head=1;$main=0;$tail=0;
		include("inicio.php");
		msgbox(_LANG("export_message_not_data"),"inicio.php?page=list&table=".$table);
		$head=0;$main=0;$tail=1;
		include("inicio.php");
		die();
	}
	while($row=dbFetchRow($result)) {
		$myid=$row["$table"."_id"];
		$lineas=array();
		$extras=array();
		$files=array();
		$labels0=$labels;
		for($i=0;$i<$count_campos;$i++) {
			$campo=$campos[$i];
			if(in_array($campo,$campos_export)) {
				$temp=explode(":",$campo);
				$campo_real=$temp[0];
				if(isset($temp[1])) $campo_arg=$temp[1];
				$texto=$textos[$i];
				$type=$types[$i];
				if(isset($row["$table"."_$campo_real"])) $valor=$row["$table"."_$campo_real"];
				if(!isset($row["$table"."_$campo"."_file"])) $file="";
				else $file=$row["$table"."_$campo"."_file"];
				$extra="";
				if($type=="select") {
					if(isset($row[resolveselect($table,$campo)])) $valor=$row[resolveselect($table,$campo)];
					else $valor=getmultiselect($table,$campo,$valor,0,0);
				} elseif($type=="multiselect") {
					if(strpos($valor,",")===false && isset($row[resolveselect($table,$campo)])) $valor=$row[resolveselect($table,$campo)];
					else $valor=getmultiselect($table,$campo,$valor,0,0);
				} elseif($type=="file") {
					if($valor=="") $valor=_LANG("export_label_not_file");
				} elseif($type=="photo") {
					if($valor=="") $valor=_LANG("export_label_not_photo");
					else $extra="/files/".$file;
				} elseif($type=="boolean") {
					if($valor=="1") $valor=_LANG("export_label_yes");
					if($valor=="0") $valor=_LANG("export_label_not");
				} elseif($type=="date") {
					$valor=convert_date($valor);
				} elseif($type=="time") {
					$valor=substr($valor,0,5);
				} elseif($type=="unixtime" || $type=="timestamp") {
					$valor=date("d/m/Y H:i",$valor);
				} elseif($type=="datetime") {
					$valor=convert_date(substr($valor,0,10))." ".substr($valor,11,5);
				} elseif($type=="color") {
					$valor=hexadecimal($valor,6);
					$extra="#";
				} elseif($type=="textarea") {
					$valor=html_entity_decode($valor,ENT_COMPAT,"UTF-8");
				} elseif($type=="textareaold") {
					$extra="HTML";
				}
				if($type=="ajaxdinamic") {
					if($valor=="") $valor="||";
					if($valor=="|") $valor="||";
					$valor=explode("|",$valor);
					if(!isset($valor[2])) {
						$valor[0]=explode(",",$valor[0]);
						$valor[2]=array();
						for($j=0;$j<count($valor[0]);$j++) $valor[2][]="1";
						$valor[0]=implode(",",$valor[0]);
						$valor[2]=implode(",",$valor[2]);
					}
					if($valor[0]!="") {
						$valor[0]=explode(",",$valor[0]);
						$valor[1]=explode(",",$valor[1]);
						$valor[2]=explode(",",$valor[2]);
						$valor[3]=array();
						foreach($valor[0] as $key=>$val) {
							$tipo=$valor[1][$key];
							if($dinamics[$tipo]["type"]=="photo") {
								$valor[3][]=$val."_file";
							}
						}
						$valor[0]=implode(",",$valor[0]);
						$valor[3]=implode(",",$valor[3]);
						$mycampos=($valor[3]!="")?$valor[0].",".$valor[3]:$valor[0];
						$query2="SELECT $mycampos FROM $table WHERE id='".$row[$table."_id"]."'";
						$valor[0]=explode(",",$valor[0]);
						$result2=dbQuery($query2);
						$row2=dbFetchRow($result2);
						dbFree($result2);
						$count_dinamics=0;
						$count_lineas=count($lineas);
						foreach($valor[0] as $key=>$val) {
							$tipo=$valor[1][$key];
							$extra="";
							if($dinamics[$tipo]["type"]=="photo") {
								if($row2[$val."_file"]!="") $extra="/files/".$row2[$val."_file"];
							}
							if($dinamics[$tipo]["type"]=="textarea") {
								$row2[$val]=html_entity_decode($row2[$val],ENT_COMPAT,"UTF-8");
							}
							if($dinamics[$tipo]["type"]=="textareaold") {
								$extra="HTML";
							}
							$val=$row2[$val];
							$lineas[]=$val;
							$extras[]=$extra;
							$count_dinamics++;
						}
						$labels1=array_slice($labels0,0,$count_lineas+1);
						$labels2=($count_dinamics>1)?array_fill(0,$count_dinamics-1,""):array();
						$labels3=array_slice($labels0,$count_lineas+1);
						$labels0=array_merge($labels1,$labels2,$labels3);
					} else {
						$lineas[]="";
						$extras[]="";
					}
				} elseif($type=="serialize") {
					capture_next_error();
					$valor1=base64_decode($valor);
					$error1=get_clear_error();
					capture_next_error();
					$valor2=unserialize($valor1);
					$error2=get_clear_error();
					if($error2!="") {
						capture_next_error();
						$valor2=unserialize(fix_serialized_string($valor1));
						if(get_clear_error()=="") $error2="";
					}
					if($error1!="") {
						$lineas[]=$error1;
						$extras[]="HTML";
						$lineas[]=$valor;
						$extras[]="HTML";
					} elseif($error2!="") {
						$lineas[]=$error2;
						$extras[]="HTML";
						$lineas[]=$valor;
						$extras[]="HTML";
						$lineas[]=$valor1;
						$extras[]="HTML";
					} elseif(is_array($valor2)) {
						foreach($valor2 as $key=>$val) {
							$lineas[]=$val["name"].": ".$val["value"];
							$extras[]="";
						}
					}
				} else {
					$lineas[]=$valor;
					$extras[]=$extra;
				}
			}
		}
		switch($format) {
			case "pdf":
				if($paginacion=="1") {
					$pdf->AddPage("P");
				} elseif(!$first) {
					$pdf->SetFillColor(224,224,224);
					$pdf->RoundedRect($pdf->GetX(),$pdf->GetY(),$widthcol1+$widthcol2,4,1,"1111","F");
					$pdf->Ln(8);
				}
				$first=false;
				foreach($lineas as $key=>$val) {
					if($key>0) {
						$pdf->SetDrawColor(224,224,224);
						$pdf->SetLineWidth(0.2);
						$pdf->Line($marginleft,$pdf->GetY()-2,$marginleft+$widthcol1+$widthcol2,$pdf->GetY()-2);
					}
					$extra=$extras[$key];
					if(substr($extra,0,1)=="/") {
						$pixels=intval(150*100/72);
						$oldimage=getcwd().$extra;
						$preimage=get_temp_directory().md5($oldimage).".jpg";
						$newimage=get_temp_directory().md5($oldimage.$pixels).".jpg";
						if(!file_exists($newimage)) {
							system("convert {$oldimage} -quality 70 {$preimage}");
							$size=getimagesize($preimage);
							if($size[0]>$pixels || $size[1]>$pixels) {
								system("convert {$oldimage} -resize {$pixels}x{$pixels} -quality 70 {$newimage}");
							} else {
								rename($preimage,$newimage);
							}
						}
						if(file_exists($newimage)) {
							$size=getimagesize($newimage);
							$oldy=$pdf->GetY();
							$pdf->SetX($marginleft+$widthcol1);
							$size[0]=0.175*$size[0];
							$size[1]=0.175*$size[1];
							$pdf->MultiCell($widthcol2,$size[1],"\n",0,"L");
							$newy=$pdf->GetY();
							if($newy<$oldy) $oldy=$margintop;
							$pdf->Image($newimage,$marginleft+$widthcol1+1,$oldy,$size[0],$size[1]);
							$pdf->SetXY($marginleft+$widthcol1,$oldy);
							$pdf->MultiCell($widthcol2,$size[1],"\n",0,"L");
							$newy=$pdf->GetY();
							$pdf->SetY($oldy);
							$pdf->SetFont("Helvetica","B",8);
							$pdf->SetTextColor(64,64,64);
							$label=isset($labels0[$key])?$labels0[$key]:"";
							$pdf->MultiCell($widthcol1,4,$label,0,"R");
							$pdf->SetY($newy);
						} else {
							$oldy=$pdf->GetY();
							$pdf->SetFont("Helvetica","B",8);
							$pdf->SetTextColor(64,64,64);
							$pdf->MultiCell($widthcol2,4,"\n",0,"L");
							$newy=$pdf->GetY();
							if($newy<$oldy) $oldy=$margintop;
							$pdf->SetY($oldy);
							$label=isset($labels0[$key])?$labels0[$key]:"";
							$pdf->MultiCell($widthcol1,4,$label,0,"R");
							$newy=$pdf->GetY();
							if($newy<$oldy) $oldy=$margintop;
							$pdf->SetXY($marginleft+$widthcol1,$oldy);
							$pdf->SetFont("Helvetica","",8);
							$pdf->SetTextColor(0,0,0);
							$val=_LANG("export_label_photo_not_available");
							$pdf->MultiCell($widthcol2,4,$val,0,"L");
						}
					} elseif($extra=="#") {
						$oldy=$pdf->GetY();
						$pdf->SetFont("Helvetica","B",8);
						$pdf->SetTextColor(64,64,64);
						$pdf->MultiCell($widthcol2,4,"\n",0,"L");
						$newy=$pdf->GetY();
						if($newy<$oldy) $oldy=$margintop;
						$pdf->SetY($oldy);
						$label=isset($labels0[$key])?$labels0[$key]:"";
						$pdf->MultiCell($widthcol1,4,$label,0,"R");
						$newy=$pdf->GetY();
						if($newy<$oldy) $oldy=$margintop;
						$pdf->SetXY($marginleft+$widthcol1,$oldy);
						$pdf->SetFont("Helvetica","",8);
						$pdf->SetTextColor(0,0,0);
						$val="#".hexadecimal($val,6,true);
						$pdf->MultiCell($widthcol2,4,$val,0,"L");
						$newy=$pdf->GetY();
						if($newy<$oldy) $oldy=$margintop;
						$widthextra=$pdf->GetStringWidth($val);
						$pdf->SetXY($marginleft+$widthcol1+$widthextra+3,$oldy);
						$pdf->SetFillColor(color2dec($val,"R"),color2dec($val,"G"),color2dec($val,"B"));
						$pdf->MultiCell(4,4,"\n",0,"L",true);
					} else {
						$oldy=$pdf->GetY();
						$pdf->SetFont("Helvetica","B",8);
						$pdf->SetTextColor(64,64,64);
						$pdf->MultiCell($widthcol2,4,"\n",0,"L");
						$newy=$pdf->GetY();
						if($newy<$oldy) $oldy=$margintop;
						$pdf->SetY($oldy);
						$label=isset($labels0[$key])?$labels0[$key]:"";
						$pdf->MultiCell($widthcol1,4,$label,0,"R");
						$newy=$pdf->GetY();
						if($newy<$oldy) $oldy=$margintop;
						$pdf->SetXY($marginleft+$widthcol1,$oldy);
						$pdf->SetFont("Helvetica","",8);
						$pdf->SetTextColor(0,0,0);
						if($extra!="HTML") {
							$val=str_replace(array("\n","\r"),"",$val);
							$val=str_replace(array("<br>","<br/>","<br />"),"\n",$val);
							$val=strip_tags($val);
						}
						$val=trim($val);
						$pdf->MultiCell($widthcol2,4,$val,0,"L");
					}
					$pdf->Ln(4);
				}
				break;
			case "xls5":
			case "xls7":
				foreach($lineas as $key=>$val) {
					$cell=sprintf("%s%d",$columns[$key],$fila);
					if($extra!="HTML") {
						$val=str_replace(array("\n","\r"),"",$val);
						$val=str_replace(array("<br>","<br/>","<br />"),"\n",$val);
						$val=strip_tags($val);
					}
					$val=trim($val);
					$objPHPExcel->getActiveSheet()->setCellValue($cell, $val);
				}
				$fila++;
				break;
			case "csv":
				foreach($lineas as $key=>$val) {
					if($extra!="HTML") {
						$val=str_replace(array("\n","\r"),"",$val);
						$val=str_replace(array("<br>","<br/>","<br />"),"\n",$val);
						$val=strip_tags($val);
					}
					$val=trim($val);
					$lineas[$key]=$val;
				}
				$lineas="\"".implode("\"".$separador."\"",$lineas)."\"";
				fwrite($fd,$lineas."\n");
				break;
			case "zip":
				foreach($lineas as $key=>$val) {
					$extra=$extras[$key];
					if(substr($extra,0,1)=="/") {
						copy(getcwd().$extra,$cache2."/".$val);
					}
				}
				break;
		}
	}
	dbFree($result);
	switch($format) {
		case "pdf":
			$pdf->Output($cache,"F");
			$mime="application/pdf";
			break;
		case "xls5":
		case "xls7":
			$objPHPExcel->getActiveSheet()->setTitle(substr($title,0,31));
			$objPHPExcel->setActiveSheetIndex(0);
			if($format=="xls5") {
				$objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xls");
			}
			if($format=="xls7") {
				$objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");
			}
			$objWriter->save($cache);
			$mime="application/x-excel";
			break;
		case "csv":
			fclose($fd);
			$mime="application/csv";
			break;
		case "zip":
			$files=glob("$cache2/*");
			if(count($files)) {
				$disableds_string=ini_get("disable_functions").",".ini_get("suhosin.executor.func.blacklist");
				$disableds_array=$disableds_string?explode(",",$disableds_string):array();
				foreach($disableds_array as $key=>$val) $disableds_array[$key]=strtolower(trim($val));
				$cmd="zip -j $cache $cache2/*";
				if(!in_array("passthru",$disableds_array)) {
					ob_start();
					passthru($cmd);
					ob_clean();
				} elseif(!in_array("system",$disableds_array)) {
					ob_start();
					system($cmd);
					ob_clean();
				} elseif(!in_array("exec",$disableds_array)) {
					ob_start();
					exec($cmd);
					ob_clean();
				} elseif(!in_array("shell_exec",$disableds_array)) {
					ob_start();
					shell_exec($cmd);
					ob_clean();
				}
				foreach($files as $file) unlink($file);
			} else {
				touch($cache);
			}
			rmdir($cache2);
			$mime="application/zip";
			break;
	}
	$name=str_replace(" ","_",_LANG("export_label_list_of").getnametable($table)).".".substr($format,0,3);
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, no-transform");
	header("Content-Type: $mime");
	header("Content-Length: ".filesize($cache));
	header("Content-Disposition: attachment; filename=\"$name\"");
	header("Content-Transfer-Encoding: binary");
	$fp=fopen($cache,"rb");
	while(!feof($fp)) echo fread($fp,1048576);
	fclose($fp);
	unlink($cache);
	disconnect();
	die();
}
openf1form();
puthidden("include","php/export.php");
puthidden("table",$table);
escribe(get_migas());
escribe();
escribe(_LANG("export_title_of").getnametable($table),"texts2");
escribe();
openform("900","","","","class='tabla'");
openrow();
settds("thead");
putcolumn(_LANG("export_output_format"),"right","33%","","texts2");
$js="$(\"#paginacion\").attr(\"disabled\",this.value!=\"pdf\");";
$js.="$(\"#separador\").attr(\"disabled\",this.value!=\"csv\");";
$js.="$(\"input[name=primeralinea]\").attr(\"disabled\",this.value==\"pdf\");";
$select="<select name='format' id='format' style='width:600px;' onchange='$js'>\n";
$formatos=array("pdf","xls5","xls7","csv","zip");
$textos=array(_LANG("export_output_format_pdf"),_LANG("export_output_format_xls5"),_LANG("export_output_format_xls7"),_LANG("export_output_format_csv"),_LANG("export_output_format_zip"));
$count=count($formatos);
for($i=0;$i<$count;$i++) $select.="<option value='$formatos[$i]'>$textos[$i]</option>\n";
$select.="</select>\n";
settds("tbody");
putcolumn("&nbsp;$select","left","","","texts2","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("export_separator_field"),"right","33%","","texts2");
$select="<select name='separador' id='separador' style='width:600px;'>\n";
$options=array(";",",",":",".");
$textos=array(_LANG("export_separator_field_1"),_LANG("export_separator_field_2"),_LANG("export_separator_field_3"),_LANG("export_separator_field_4"));
$count=count($options);
for($i=0;$i<$count;$i++) $select.="<option value='$options[$i]'>$textos[$i]</option>\n";
$select.="</select>\n";
settds("tbody");
putcolumn("&nbsp;$select","left","","","texts2","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("export_first_line"),"right","33%","","texts2");
settds("tbody");
putboolean("primeralinea","1","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("export_paginate_model"),"right","33%","","texts2");
$select="<select name='paginacion' id='paginacion' style='width:600px;'>\n";
$options=array("1","0");
$textos=array(_LANG("export_paginate_model_1"),_LANG("export_paginate_model_0"));
$count=count($options);
for($i=0;$i<$count;$i++) $select.="<option value='$options[$i]'>$textos[$i]</option>\n";
$select.="</select>\n";
settds("tbody");
putcolumn("&nbsp;$select","left","","","texts2","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("export_first_register"),"right","","","texts2");
settds("tbody");
putinput("offset","1","edit","onkeyup=\"javascript:mascara_num(this,1)\"","","");
closerow();
openrow();
settds("thead");
putcolumn(_LANG("export_total_registers"),"right","","","texts2");
settds("tbody");
getlistconfig($table);
$query=process_query(0,1);
$result=dbQuery($query);
$row=dbFetchRow($result);
dbFree($result);
putinput("limit",$row["count"],"edit","onkeyup=\"javascript:mascara_num(this,1)\"","","");
closerow();
$js="$('#format').each(function() { $js });";
put_javascript_code("$(document).ready(function() { $js });");
closeform();
escribe();
escribe(_LANG("export_fields_to_export"),"texts2");
escribe();
openform("900","","","","class='tabla'");
getlistconfig($table);
$campos_list=$campos;
getformconfig($table);
reparaformconfig();
$count_campos=count($campos);
for($i=0;$i<$count_campos;$i++) {
	openrow();
	$campo=$campos[$i];
	$texto=$textos[$i];
	settds("thead");
	putcolumn("$texto:","right","50%","","texts2");
	$bool=0;
	if(in_array($campo,$campos_list)) $bool=1;
	settds("tbody");
	putboolean("mostrar_$campo",$bool,"");
	closerow();
}
closeform();
escribe();
openform();
list($title,$text)=make_title_text("3floppy_unmount",_LANG("export_button_ok"),_LANG("export_button_ok_title"),_LANG("export_button_ok"));
$url="document.f1.submit();";
$temp=get_button($title,$url,"","22","",$text);
list($title,$text)=make_title_text("quick_restart",_LANG("export_button_sel"),_LANG("export_button_sel_title"),_LANG("export_button_sel"));
$url="selectall();";
$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
list($title,$text)=make_title_text("button_cancel",_LANG("export_button_cancel"),_LANG("export_button_cancel_title"),_LANG("export_button_cancel"));
$url="redir(\"inicio.php?page=list&table=$table\");";
$temp.="&nbsp;".get_button($title,$url,"","22","",$text);
openrow();
putcolumn($temp,"center","","2");
closerow();
closeform();
closef1form();
