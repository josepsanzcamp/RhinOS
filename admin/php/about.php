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
if(!check_user()) intro();
escribe(get_migas());
escribe();
$temp="<table><tr>\n";
$temp.="<td valign='top' style='padding-right:10px' rowspan='100'><img src='img/rhinos.png' width='76' height='100' /></td>\n";
$temp.="<td class='texts2' valign='top' colspan='3'>".get_name_version_revision(true)."</td>\n";
$temp.="<td rowspan='4' align='right'><img src='img/gplv3.png' width='100' height='40' /></td>";
$temp.="</tr><tr><td style='height:10px' colspan='3'></td></tr><tr>\n";
$temp.="<td class='texts2' valign='top' colspan='3'>"._LANG("about_copyright")."</td>\n";
$temp.="</tr><tr><td style='height:10px' colspan='3'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' width='800' nowrap>&nbsp;"._LANG("about_developers")."</td>\n";
$temp.="</tr><tr>\n";
list($title,$text)=make_title_text("web",_LANG("about_link_project"),_LANG("about_link_project_title"),"","48");
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' href='mailto:josep.sanz@saltos.org'>Josep Sanz (Main developer)</a></td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' nowrap>&nbsp;"._LANG("about_translators")."</td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' href='mailto:josep.sanz@saltos.org'>Español - España (Josep Sanz)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' href='mailto:josep.sanz@saltos.org'>Català - Espanya (Josep Sanz)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' href='mailto:josep.sanz@saltos.org'>English - USA (Josep Sanz)</a></td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts2' valign='top' colspan='4'>"._LANG("about_software")."</td>\n";
$temp.="</tr><tr><td style='height:10px' colspan='4'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' nowrap>&nbsp;"._LANG("about_server_base")."</td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://httpd.apache.org'>Apache 2.2/2.4 (ASL-2.0)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.php.net'>PHP 5.6-8.2 (PHP-3.01)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.sqlite.org'>SQLite 3 (Public domain)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.mysql.com'>MySQL 5.1-5.8 (GPL-2.0)</a></td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.mariadb.org'>MariaDB 5.5-10.10 (GPL-2.0)</a></td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' nowrap>&nbsp;"._LANG("about_php_plugins")."</td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://phpthumb.sourceforge.net/'>PHPThumb 1.7.11 (GPL-2.0)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.tcpdf.org'>TCPDF 6.6.2 (LGPL-3.0)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='https://github.com/PHPOffice/PhpSpreadsheet'>PHPSpreadsheet 1.28.0 (LGPL-2.1)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='https://github.com/PHPMailer/PHPMailer'>PHPMailer 6.7.1 (LGPL-2.1)</a></td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' nowrap>&nbsp;"._LANG("about_javascript_base")."</td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.phpjs.org/'>PHPJS 3.26 (MIT & GPL-2.0)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.jquery.com/'>JQuery 3.6.3 (MIT)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.jqueryui.com/'>JQuery UI 1.13.2 (MIT)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://ckeditor.com/'>CKEditor 4.20.2 (GPL-2.0, LGPL-2.1 & MPL-1.1)</a></td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' nowrap>&nbsp;"._LANG("about_jquery_plugins")."</td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.eyecon.ro/colorpicker/'>Color picker (MIT & GPL*)</a></td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts2 sectiontitle' colspan='4' nowrap>&nbsp;"._LANG("about_server_plugins")."</td>\n";
$temp.="</tr><tr>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.everaldo.com/crystal/'>Crystal Project (LGPL*)</a></td>\n";
$temp.="<td class='texts item sectioncontentb' nowrap><a title='$title' target='_blank' href='http://www.imagemagick.org/'>ImageMagick 6.4 (Free Soft.)</a></td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="<td class='texts sectioncontentb' nowrap>&nbsp;</td>\n";
$temp.="</tr><tr><td style='height:10px'></td></tr><tr>\n";
$temp.="<td class='texts vxinfo' valign='top' colspan='3'>"._LANG("about_vxinfo")."</td>\n";
$temp.="<td align='right'>\n";
list($title,$text)=make_title_text("button_ok",_LANG("about_button_return"),_LANG("about_button_return_title"),_LANG("about_button_return"));
$url="redir(\"inicio.php\");";
$temp.=get_button($title,$url,"","22","",$text);
$temp.="</td></tr></table>\n";
$temp.="<script type='text/javascript'>\n";
//$temp.="$(document).ready(function() {\n";
$temp.="	$('.item').each(function() { $(this).html('&nbsp;&diams;&nbsp;'+$(this).html()); });\n";
$temp.="	$('.item a').css('font-weight','normal');\n";
$temp.="	$('.sectiontitle').addClass('ui-widget-header ui-corner-top');\n";
$temp.="	$('.sectioncontent').addClass('ui-state-default').css('border','none');\n";
$temp.="	$('.sectioncontentb').addClass('ui-state-default').css('border-top','none').css('border-right','none').css('border-left','none');\n";
$temp.="	$('.sectioncontentb').addClass('ui-state-default ui-corner-bl').css('border-top','none').css('border-right','none');\n";
$temp.="	$('.sectioncontentb').addClass('ui-state-default ui-corner-br').css('border-top','none').css('border-left','none');\n";
$temp.="	$('.vxinfo').each(function() { $(this).css('font-weight','normal'); });";
$temp.="	fix_max_height();\n";
//$temp.="});\n";
$temp.="</script>\n";
escribe($temp,"texts2");
