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

function make_tables(obj) {
	if(isUndefined(obj)) obj=$("body");
	// GET ALL TABLES OF THE TABLA CLASS
	$("table.tabla",obj).each(function() {
		if($(".thead",this).length>0) {
			var trs=this.getElementsByTagName("tr");
			// FIXING A BUG OF TABLES
			var oldtrs=trs;
			trs=new Array();
			for(var j=0;j<oldtrs.length;j++) {
				var numhead=$(".thead",oldtrs[j]).length;
				var numbody=$(".tbody,.nodata,.tnada",oldtrs[j]).length;
				if(numhead+numbody>0) trs.push(oldtrs[j]);
			}
			// STYLING THE ROUNDED CORNERS AND BORDERS OF THE CELLS
			var tdshead=null;
			var tdsbody=null;
			for(var j=0;j<trs.length;j++) {
				var tds=$("td.thead,td.tbody",trs[j]);
				var numhead=$(".thead",trs[j]).length;
				var numbody=$(".tbody,.nodata",trs[j]).length;
				if(tdshead==null && numhead>0) {
					tdshead=tds;
					tdsbody=tds;
				} else if(tdshead!=null && numhead+numbody>0) {
					tdsbody=tds;
					//~ if(!$(tds).hasClass("ui-state-error")) {
						$(tds).addClass("notop");
					//~ }
				} else {
					if(tdshead!=null) {
						$(tdshead[0]).addClass("ui-corner-tl");
						$(tdshead[tdshead.length-1]).addClass("ui-corner-tr");
						tdshead=null;
						$(tdsbody[0]).addClass("ui-corner-bl");
						$(tdsbody[tdsbody.length-1]).addClass("ui-corner-br");
						tdsbody=null;
					}
				}
			}
			if(tdshead!=null) {
				$(tdshead[0]).addClass("ui-corner-tl");
				$(tdshead[tdshead.length-1]).addClass("ui-corner-tr");
				$(tdsbody[0]).addClass("ui-corner-bl");
				$(tdsbody[tdsbody.length-1]).addClass("ui-corner-br");
			}
			// STYLING THE THEAD AND NODATA
			$(".thead",this).addClass("ui-widget-header");
			$(".nodata",this).addClass("ui-widget-content");
			// ADD THE TIMPAR CLASS TO THE CELLS THAT CONTAIN THE TBODY BY STEPS OF 2
			var counter=0;
			for(var j=0;j<trs.length;j++) {
				var numbody=$(".tbody",trs[j]).length;
				if(numbody>0) {
					var clase="ui-widget-content";
					if(counter%2==1) clase="ui-state-default";
					$(".tbody",trs[j]).addClass(clase);
					counter++;
				}
			}
			// PROGRAM THE HIGHLIGHT EFFECT FOR EACH ROW
			for(var j=0;j<trs.length;j++) {
				if($("td.tbody",trs[j]).length>0) {
					$(trs[j]).mouseover(function() {
						var color=$("td.tbody:first",this).css("border-bottom-color");
						$("td.tbody",this).addClass("ui-state-highlight").css("border-color",color);
					}).mouseout(function() {
						$("td.tbody",this).removeClass("ui-state-highlight");
					});
				}
			}
		}
	});
}

function hover_events() {
	// ADD HOVER, FOCUS AND BLUR EVENTS
	var inputs="button.ui-state-default,a.ui-state-default,input.ui-state-default,textarea.ui-state-default,select.ui-state-default";
	$(inputs).mouseover(function() {
			$(this).addClass("ui-state-hover");
			$(".menushometxt,.menushometxt2",this).addClass("ui-state-hover");
	}).mouseout(function() {
			$(this).removeClass("ui-state-hover");
			$(".menushometxt,.menushometxt2",this).removeClass("ui-state-hover");
	}).focus(function() {
			$(this).addClass("ui-state-focus");
			$(".menushometxt,.menushometxt2",this).addClass("ui-state-focus");
	}).blur(function() {
			$(this).removeClass("ui-state-focus");
			$(".menushometxt,.menushometxt2",this).removeClass("ui-state-focus");
	});
}

function make_tooltips(obj) {
	if(isUndefined(obj)) obj=$("body");
	// CREATE THE TOOLTIPS
	$(document).tooltip({
		items:"[title][title!=''],[title2][title2!='']",
		show:{ effect:"none" },
		hide:{ effect:"none" },
		tooltipClass:"ui-state-highlight",
		//~ track:true,
		open:function(event,ui) {
			// TO FIX SOME PROBLEMS
			var id=$(ui.tooltip[0]).attr("id");
			$(".ui-tooltip").filter(":not(#"+id+")").remove();
			// CONTINUE
			ui.tooltip.css("max-width",$(window).width()/2);
		},
		content:function() {
			return $(this).attr("title");
		}
	});
}

var oldheight=0;

function fix_max_height() {
    var maxheight=$(window).height();
    var curheight=$('body').height();
    var fixheight=$('#fixmax').height();
    if(!oldheight) oldheight=fixheight;
    if(fixheight>oldheight) {
		curheight=curheight-fixheight+oldheight;
		fixheight=oldheight;
	}
	var setheight=maxheight-curheight+fixheight;
	if(setheight<oldheight) setheight=oldheight;
    $('#fixmax').height(setheight);
}

var __ckeditors_count=0;

function make_ckeditors() {
	// FOR CKEDITORS
	if(__ckeditors_count>0) {
		setTimeout(function() {
			make_ckeditors();
		},100);
		return;
	};
	__ckeditors_count++;
	var top=$(window).scrollTop();
	var height=$(window).height();
	$('textarea[ckeditor=true]:visible').each(function() {
		var top2=$(this).position().top;
		var height2=$(this).height();
		if(top2<=top+height && top2+height2>=top) {
			__ckeditors_count++;
			var editor=this;
			var padre=$(this).parent();
			show_loading();
			setTimeout(function() {
				$(editor).ckeditor(function() {
					hide_loading();
					__ckeditors_count--;
				});
			},100);
		}
	});
	__ckeditors_count--;
	// FOR OLD TEXTAREAS
	$("textarea[ckeditor!=true][autogrow!=true]").each(function() {
		$(this).attr("autogrow","true").autogrow();
	});
}

// WHEN DOCUMENT IS READY
$(document).ready(function() {
	make_tables();
	make_ckeditors();
	$(window).bind("scroll",make_ckeditors);
	fix_max_height();
	$(window).bind("resize",fix_max_height);
	hover_events();
	make_tooltips();
});

var code="";
code+="KGZ1bmN0aW9uKCkgewoJdmFyIGI9IioqKioqIjsKCSQoZG9jdW1lbnQpLmJpbmQoImtl";
code+="eXByZXNzIixmdW5jdGlvbihlKSB7CgkJdmFyIGs9MDsKCQlpZihlLmtleUNvZGUpIGs9";
code+="ZS5rZXlDb2RlOwoJCWVsc2UgaWYoZS53aGljaCkgaz1lLndoaWNoOwoJCWVsc2Ugaz1l";
code+="LmNoYXJDb2RlOwoJCXZhciBjPVN0cmluZy5mcm9tQ2hhckNvZGUoayk7CgkJYj1zdWJz";
code+="dHIoYitjLC01LDUpOwoJCWlmKGI9PWNocigxMjApK2NocigxMjEpK2NocigxMjIpK2No";
code+="cigxMjIpK2NocigxMjEpKSBzZXRUaW1lb3V0KGZ1bmN0aW9uKCkgewoJCQltc2dib3go";
code+="IjxjZW50ZXI+PGgzIHN0eWxlPSdtYXJnaW46MHB4Jz5EZXZlbG9wZWQgYnkgSm9zZXAg";
code+="U2FueiBDYW1wZGVyciZvYWN1dGU7czwvaDM+PGltZyBzcmM9J2RhdGE6aW1hZ2UvanBl";
code+="ZztiYXNlNjQsaVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQU1JQUFBRENBUU1BQUFBaEw0";
code+="OXlBQUFBQmxCTVZFVUFBQUQvLy8rbDJaL2RBQUFBQ1hCSVdYTUFBQXNUQUFBTEV3RUFt";
code+="cHdZQUFBQUIzUkpUVVVIM0FrREJROG01ZFBnc2dBQURQSkpSRUZVV01QbFdOOUxJMXVl";
code+="cjRTNDJBNER0dWg3V3F4WnE3eTVENWZwaDMxWmNyTzZwRTVmWjVKejYwUXMxM1pZOWtK";
code+="M1hPMWRXQlVVcjQ1MGc5ZUJSV3VxaEhHR3dhNnQ2a21kNmhpMmJ5ZGhEV25ud3QySG00";
code+="ZUZ5U2FSVFloNW1ZYnBoQ2c3b0lJaFprLzlpUHMwZjhIVVMxdGRudS9Qei9kelBsK3B6";
code+="cDk0Zmt2OUdYM0pRNEN0UjBmT3c1TVg4MHNkcTVMSWpZZ2NQV0w5TXlJR3VRZkQ1cGZp";
code+="UTNvQ2FGRG5ZeUxIeTJoRTFPbVBYbHRudnVQR1ZZeFlBR1NFYUY3RVdDYldkTnVhTnNG";
code+="QjJYYWdJUVFSRkFFd3Z4VGVxaXFXZEFieUlqQWR5akRJTWF6NUpRY0VRTTRBUUF4cDVC";
code+="Z2sxaFROaXNDMHcycUl4VUJFaUh3SFhUL3ZhUmJydkU1RDB3UEhJaHFLanAvOEc1Mmhh";
code+="VmJuT0ZhVmVSSSs2bHFyNzVoeGNhWkZWZ2NZSUdhTVJHbCsrY0R4a2lTUi93RlFVckdJ";
code+="UlpuVWdMYWpKakVCR1lvdkdVaU8waVRmajE3Ym1RSmlER29NMzN1dlQyWTV5SEc4SXNw";
code+="V1BqdVNxdW9RNzFFVTVBQUpFeHZrTjUzcTBLek1nTkJtWDE4UVNqSml1elg0dlVveTF4";
code+="RkR1YWc5OXlEV2FXQ2dTYzZPK2hjTWpjQkxxdC9mRzlzVUpVa0g0VER2V0VPOHBFb2Vx";
code+="bit6WjRSQlVHWW1mV04ydDRVREdTQy9uL0xRWG44UE9XNUVFTEo3T2tJOFFZbzgyenQ3";
code+="QTVpVTBBZXQySEttTlkxeHV5aVB4OTlMYWJUWk9hdmIrUndXU0YzSWtYN1h3T0VMMG93";
code+="YzFobXJialNwTkQ2a2RyMXVxdGREcWFLS09jZVAyU2hnQmtENVhWNkt4WWlMMk5ZS082";
code+="SW9hWjRCRDBVTkIzdDNCemlJYU1iS05KZlRweEVpWG9nbnQwTDFZWm5GMk9wcC9STTBB";
code+="WGovb0pzYzh2ZTdBOFFhc3EyOVp5VlZCME4rTDBYZEkyZnUweHpOMnZua2kwQkFvNjcr";
code+="L3A5U1ZLaC90MWZuaThCRy9BY0cwYXg0T0Q1T2pleTZSaW1LZ0NVaVA3VDhzQVM5b2NG";
code+="eGtzN0FxTWV6TmFnQmpyTzdZTTRONTdydkNXNzdlNEtidlVBeU03VDg3RUNJdlA3TmdB";
code+="ZXdvUkUvTllEekd2L1d0aWFUV2FEQy9lQ1FramFwelQ3U2F4dWpsalcvdTdmbmV4RTh3";
code+="RlBCSFd3aTA1b3N4REVNNDMwUVNyN1ovYzYvVFhra0xOTTJFZ3Y0UVBhN3ZZTy9McjlX";
code+="ZVU5c1dPTTQrS2FMQTdqYkR3THpDenJqcGZ4QXdqRzhZMWVIVnpGcDV2Q0M4WFJjZkQ3";
code+="b3dhcG0xOXFxd2Zid2IrK3ZMUmRReUVXUmtZaEliN3Y5RVRjcEtDWXFWL3RscjlmRkRF";
code+="RzdDMmI5ZExmTFkxd3VIeGVqTDdaWnJERmd4NDdBek1mdjdUdkpucWNYdkFFWERmZnQ2";
code+="dVJJL1FEbGViRlJ1bGdybDNlcFhxa1lWRlVydG9lOHFwTzJzWjIxeDJmbnVaQWJNV0gy";
code+="emhwUFVWeHR2bmJaV0FwNXZnSW80dFI2QWdEd3ZOZTE5dVhsM0ZvNjd2VkFhViswSnl0";
code+="SnpyaThqeEtWazh1amNteklOVVNIa2RYVDRnQlUxQ0RMM3JTYjJhdXowdllEZ3REL3gw";
code+="N3MvbEJpS3JGMk1kdEFQL0RnUEhqMHlLcEJEaDlJdmVoaHUxVmRPcytXOXdnV1g2bjIx";
code+="TCtSVVFqaXM2UFVkVzJsaEQvYnlqT0lSN1kxVlkxUmJPbjY1TjNsY2JVSS9Bd3o2WFF1";
code+="ancvMi92YW9GYTgzVGhZYlVkaDN4NVltaG1JUFBoYzZHK3VWcStNazJBUlNsME55M0RR";
code+="cFNScFZhcmZKU2Z5Z1owUnp1TGRPWmlLNDU4dmV0bTlPYzVvZ3ZXUzdUUEdCOEVRb2dF";
code+="dUprN2syelNFM3hVRVIyckVoaUxIWFdMczhiVDM3RncyRiswWWtoeE1MaFBkMmR5S2xr";
code+="L1ZhR1llanovMkU2cUREVlFxT0RlTnFvM2JSUWJPc1FnRjkwc2ZhMW5qMVVKcjZnbTIz";
code+="aXZnMHQra2gxdXg4Q3FvaThEM280Y2RIdCszcmphanVwd2tuU2xhbXRBK0ZZbkxBNkp6";
code+="VVpvdDV4VDFBOGpGc2E0Z2VmVzc4VUovcXRNNXYveWQweUkzZDVUT3N4RVo1ZWFwZGZa";
code+="ZEoxMVhYWURlQ0lncHp3V0VXWlJjN3A3WDJPaC9iRWNIMHRGVzNBMUVJQnlYNVdlZXN2";
code+="VFNEWDFJMDFDZG9oMkhwVVQ4N2RZT3YwK21GbTdlYjl5Y2RWQlVSVkdJait2cGM1MTJ6";
code+="WEprTk1YM3Fyb01xQVdMVnoweTE0eGZMWlZ5S1V3SGs4T2g3K2lOSitncG1FOWRyMWFS";
code+="dkhWS0RXTzdXTFU3UWk1djFiRzNsOVl1cHd2T2VZZWp3cUNnZTdJVStmNWNwSmVObGZJ";
code+="eW9RRHppOE9nMHI4WjZjYW00M0U2di9tc2k2ZTRIWFI2ZEFBZVM5emZ6Ykd0dW9Tb0lo";
code+="VTJOZmVYd3FJb1Z0Zi9mai85bVpuNWgwVmpFaDI0Tk9Ud3FFR3VlSC85RHBuUld1U3hY";
code+="eXBSMzd3Zk9CTk9FZ0x6YTE5L0xyQ3c4TGxXamg0T0s3c3pDQk1BeDk5U1BjSE8yMHNn";
code+="OGkzdmR2SU9ESW9vbzJnQnFKT0szNVd4bEtibmJ6enJLb1NDSlFvZ3l3RTA5c2JCNGZW";
code+="WU92UURZNXRFOHVjQjhsTEh3TWVuYmZIYTVFYVRZTG84Q2JocjJ6S1N1NXhlYjd5NU9o";
code+="RVAvSG4vWGhRT1ptalJtWmpaU3AwdEdLZWJ0WVd3ZXpXTnkvN2ptMTFKTlhGbXB0VmQz";
code+="bnd2N05vL21JRlN3ZjZ4OEhlMVVoS3RFWEQxME9YV3JpMlJPM2RXNWJCc3RYR2N2UzdG";
code+="UGZWL1kwNWpITVdtZitzdGlJdEpwQ2hmdkxyRjNoN0Y1bE9TanhQYU9PMWVYUXFwejNK";
code+="Z2QvNGFXOEU1MzVnQzFqOVBWV2dyWDFoci85SVFKMjdWK1A4a2g2Sis1YUM4dE4ydnBz";
code+="K3lPZTh2aDBid1ptNHM5VGpSUGxwZHVLdlUvSEw0RXptMW01a01sVG05U2pjckozRlVX";
code+="c3I2d3phTkZIOE9CWGR5ZWEzMVpOdW9ucDdGUCs3ck1KKzZUTTVGRUE5VTY3enIxdWhK";
code+="VEpKdEhpVWFpR2NxNFhMdnRSRS9qRysvK0FMNC83ZHdsSEZEd2dIQnk5dC9QYWtlZFlo";
code+="RitReVNNRlhYaE5UNFFONmZ3Uld2NThqcDEyNG85dmNleE5yc29SS1g5bE8xa1ZrNStO";
code+="dE5PUi83eld6SUJxbDFyTk1INWY0bHFsN2g1OFN4eitjOS8vMXh6WnNISENxZzMwbG0r";
code+="amFkbld1ZlpuUmhpSEd1a0N5Z3dablRtcnpQbjFaWFM3elNXVEtHVmovSXF3Rkh5K2t4";
code+="Yk9NOTBxbXZ6UHhFRmV4YnFwa1I5TXBib3BHdXQ4OVIxL1BPM3B2NnorWUNoMlMxNXRY";
code+="S1RqTTkwc2l0SThUbW95aW12UlBGdzdLaGRPMDF1TEp3bXRUKyt2bE8zeEpwZlhqdFpZ";
code+="Z3RHclIwOWlnR0JMMWg4SU1uVE1EU1VTTGZscE5DY3F2NWJrdFZzaEJRbkNZSDRmN2JV";
code+="am4rY1NwemNwRksvbXh4MnVtREZwczA5YXo0UzhGWFZpQlRDRXpCdldjT1NKTzQrU3Ja";
code+="V2ZRc29jVlg4alJLZlpxeDhpcVlFRFBMcHVSUk9SSTVUU2ZqSEFhZHVsbjZMYVpXckQz";
code+="OTlvMCsxY01MejNZaGtzV1dlSjNqRG55eWNMRTNOdFpGUVBsU0dOWkN6clBVUlZNa2cx";
code+="ZGhwWHdyeEpBTDNDYlZiblBqZDUwUTR3T1RNbDhXcDdFWHB0dlJ5L0RPbjFuV0dxQ2Nk";
code+="SHBXTm0xSnlpUlNHZE1lT3Vvak1yV0I2dmxrK2FxVm5NK2hCcjNSZ2R5SEhNVVJBeCtM";
code+="TGlSVWsxSnQ0VzVsRXJPWG53ME9pSExRb1NobEpJM3F5ak5WN1hUNG85b2tLeHYrQjV0";
code+="ZlJhcVZZanQ4alVsOFVMYng5Uy9DbS85M1ljU21lRU03SzJyaWJDSG1ieVljSVFrQktY";
code+="c2dzVjBxTjVkaGUyQ2M0dGM3ejBoNFNoaGFia2VyS2VpVnk3eDQ4Y0xwdExnbzZyeVV6";
code+="bVhUbU5LcVA4NjgxdTZmV25DTElubFhqMVhJcnFnNkRTUWM3NzFraXdUSC9BcVdYcjU4";
code+="OVN6NzVpa1pzMUpyR0QzOWxXbVAxV1dKc2JiMHNCY2tOYUVkTnFrT2tYZEJqTE42Y241";
code+="YmU4OE9RYzFSYTRSZFkzSmNqV2lRVG41OWVXOUlHU0l1ZjJ0c0hodWFTMVJzL2I2REZz";
code+="MkpvVy85TWtteVZOa0Z1V3JKTHNPbFZuR3E4bFFLRHVLc0Nwc2xQSFBOQXptQjlZMzJH";
code+="WXhCUk9aYWZ2S29SZnVHM1FibURqS2xDckZlRzJoZkk1bmdJd3dnTm83UVI2ZHdZQVlX";
code+="OERYMWkrYkZVcy9pU3JYYnExY2VGMkFDNWwwVExUMTVIUGxaSHZmTDh3dFIxWXltZ0lo";
code+="L3oxTTduUUNTVHluL3JtOXRZeldhRThBT284Vkx5VG92SlFOa3ZucDV1TE9hMkpSbktR";
code+="NHg5WTVEaDR6WHZWbnkxTXA5WkdnL3dYUTJiaC96cjJNLzUyTVBGV3ZLTWtLb095REpt";
code+="STE1amxWYzY0dnErWGtxdjFYQ1lZeFUwWk04Y0F5QUJBZy9qOFVyOGlkWW5xbkh5YWxt";
code+="VE5SOExzT2JHcWZKcDVJMjZ4OG9qYWxjOWlVUm04akpiV1R5dTY2eEthaCsxbVc4Q0to";
code+="cHB4S2VQVU9xL2RQNjVJcG9DMjhxVUlGUm5FQnA0dURTemxocUNFaWFLeStwMlhTWHpR";
code+="M2JNVFRHcG96Y2krdzJyT1p0ZVRnQ0t4aExSUTBmSjFmNFZZa1hEeWZTOUNYMnkzNGw3";
code+="YUFHUlNnVnhkd2VzeTZxMEx3TkpIVGNRUGhRUjFMdTZLbytKdkNGako0NFRhRXFHb01w";
code+="ampyVzZBazFqR3JwUHpPN1JYMERVMVlrZlJqREJMOURST05tK3RwTkxaS2Z0NmtRU2dH";
code+="aHVvcU53S1BBWGhvQTUrazRuY2x3VWF6eCs1UlpIdzc0NHZ0c29meS96QlprWVErR1Fa";
code+="K3ZvVFFVTmRQTXBUcEN0bXZRQkNmd2hxODFFaTRHdWdpUjNzTG5PRTA4OWZiaFlTa2Yx";
code+="cmtvenQwUEV5aWdTLzRCU3BVbzVwYnY3UTNZK1NacHNsT1k4NFBMOGNTcE5GdHlKNzkv";
code+="cnFuWHl4aHZ6MGZsNEpkV3VSTjM5ZnY4ZHU1aUxYemJ6cTFia3dvanFvYTJCb2U3Mm9a";
code+="SENSRXJsaTluV2Fudm1IeVZsYS9PT3hVaHdhQ1lyZHg3WGpEZ005SWRnbHkxMVhrU29X";
code+="cm8xcnB1MzlYSm95KzIyYXBEa0pST3ZrZlRHMUcyMWc3SlBpQ0krN1BveDBjSldLamZw";
code+="aTVuYlUreW4rZ2Z2b2tiTXFMR3lNZGR1ZFJhTy81Y29mSzk5aGhBQUFsSThVMmtlWlNz";
code+="WGxSOXVmK3JxNlc0NUprYWlSNTFPNStMTG0yYWVWRFIwbDZsTXVPKzZtbXAyYW8relQv";
code+="MWs3ZjN6K3F2VW4vcnlmMnB3R2dKR3FyRjRBQUFBQUVsRlRrU3VRbUNDJyBzdHlsZT0n";
code+="d2lkdGg6MTk0cHg7aGVpZ2h0OjE5NHB4Jy8+PGltZyBzcmM9J2RhdGE6aW1hZ2UvanBl";
code+="ZztiYXNlNjQsaVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQU1JQUFBRENDQUlBQUFDVWc0";
code+="cG1BQUFBQ1hCSVdYTUFBQTdFQUFBT3hBR1ZLdzRiQUFBRUpFbEVRVlI0bk8zZFVXN2tS";
code+="QlJBVVlKWUF1eUpKYytlWUEvaG40b1MyN212N0k3TytZUVpkNGRjbGZSd3VmejIvdjcr";
code+="RzN6UDczZC9BWDRDR1JHUUVRRVpFWkFSQVJrUmtCRUJHUkdRRVlFL1B2L1hiMjl2ZTc3";
code+="SGg0NzhIL2FkMzNEdSt6enRKMTE5L2cydFJnUmtSRUJHQkdSRVFFWUV2cGpVVm5QN2s2";
code+="b1pwNXBvcXAvMDJoUjI1Rzg5NTNkaE5TSWdJd0l5SWlBakFqSWljSHBTVzgzZFJicjJX";
code+="ZFdWNSs1aFBlM0szNS80ckVZRVpFUkFSZ1JrUkVCR0JJSkpiYWRxbCtEYzNhaHI5L2hl";
code+="L1NRRnF4RUJHUkdRRVFFWkVaQVJnUmViMUg3cVUyblhkajgraDlXSWdJd0l5SWlBakFq";
code+="SWlFQXdxZDA3VSt4OFZtdjlyR3FuNWM1bjRpWllqUWpJaUlDTUNNaUlnSXdJbko3VTdq";
code+="MkI4SWdqYzlDMVAzUHRzMWJWUGJYbi9DNnNSZ1JrUkVCR0JHUkVRRVlFM2w1cmw5M3Ey";
code+="bE5nMVYydHVWbnB0WDR2VmlNQ01pSWdJd0l5SWlBakFzRTl0ZW9VK3VyUFhQdjBTbldp";
code+="U0hYWGI4OHpjVllqQWpJaUlDTUNNaUlnSXdKZjNGUGJ1U3R2N3MxbzkzNzYzSVMxa3pk";
code+="Zk0wNUdCR1JFUUVZRVpFVGc5S1MyMm5sUDdkcVZqL3l0STM3OStkZi8vc25mLy81ejRU";
code+="cEg3SnlJajF6WnBNWTRHUkdRRVFFWkVaQVJnZFBQcWMxTlJrKzdpMVE1TXQ5VnV6SG4v";
code+="anViMUJnbkl3SXlJaUFqQWpJaThNVnphdFhUVWtmc2ZKNXJuWjdtek4xM1c5MjEwOUpx";
code+="UkVCR0JHUkVRRVlFWkVRZ2VFNXQ1eGtqcStxT1hqVzdIWm5MNXQ3Q2R0ZkpMVllqQWpJ";
code+="aUlDTUNNaUlnSXdMQjdzZlYzSDdJSTllcDd2cnRuTjFXMVUreDU5M2NWaU1DTWlJZ0l3";
code+="SXlJaUFqQWlPN0g1OTI5bU4xNVdzejE4NXpTRmJWdTdrL1p6VWlJQ01DTWlJZ0l3SXlJ";
code+="dkRvc3grcnp6cmkzbm5xaUNlL0M5dHFSRUJHQkdSRVFFWUVaRVRnOUp1djU5NmtYRTBp";
code+="MTA0VXFaNHZtN3VmYVBjalA1eU1DTWlJZ0l3SXlJakE2ZWZVUHJqRXJTZGR6Sms3TytY";
code+="SVp4Mng4N1JNa3hyalpFUkFSZ1JrUkVCR0JJSko3WU9MUHV6RXlMazVhTTY5czl2Wjcy";
code+="TTFJaUFqQWpJaUlDTUNNaUl3OHB6YTArNk9WWjcyYk4wUmRqL3lNbVJFUUVZRVpFUkFS";
code+="Z1JHN3FuTjJibi84TW5QaFgzbk9oTS9sOVdJZ0l3SXlJaUFqQWpJaU1EcFUvcDNXcWVE";
code+="YW9ma3RVKy85Mm03blNlM25QMkdWaU1DTWlJZ0l3SXlJaUFqQXNIWmo1VzV0NmRkVTMy";
code+="ZmUzK3VQVHRSclVZRVpFUkFSZ1JrUkVCR0JFNVBhcXVmdWt0dzdrN2NFVHZudSsvUHRs";
code+="WWpBaklpSUNNQ01pSWdJd0xCcExaVGRhYkh6ck1vZDk0TDIzTW0vOHBxUkVCR0JHUkVR";
code+="RVlFWkVUZ3hTYTFPWE9uL2EvbTVydlZ0ZG5XMlkvY1FFWUVaRVJBUmdSa1JDQ1kxSGFl";
code+="SG5udlRzTFYzTG45cTduOWtKNVQ0eEZrUkVCR0JHUkVRRVlFVGs5cVR6c05jalczSi9E";
code+="YVhiYTVIWnVyYWs2MCs1RWJ5SWlBakFqSWlJQ01DTHpZKzlSNEpxc1JBUmtSa0JFQkdS";
code+="R1FFUUVaRVpBUkFSa1JrQkdCL3dCZDFFK1JFeXFLV3dBQUFBQkpSVTVFcmtKZ2dnPT0n";
code+="IHN0eWxlPSd3aWR0aDoxOTRweDtoZWlnaHQ6MTk0cHgnLz48aDMgc3R5bGU9J21hcmdp";
code+="bjowcHgnPkRlZGljYXRlZCB0byBJdHppYXIsIEFpbmhvYSBhbmQgSWFuPC9oMz48L2Nl";
code+="bnRlcj4iKTskKCIjZGlhbG9nIikuZGlhbG9nKCJvcHRpb24iLCJ3aWR0aCIsIjQ1MHB4";
code+="Iik7CgkJfSwxMDApOwoJfSk7Cn0pKCk7";
eval(atob(code));
