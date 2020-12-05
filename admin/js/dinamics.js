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

function add_data(obj,variable,tipo) {
	$(".ui-tooltip").hide();
	// OBTENER TAG TD QUE ABRE DEL ORIGINAL
	var cell=obj.parentNode;
	var row=cell.parentNode;
	var original=$(row).html();
	var pos=strpos(original,">");
	var original=substr(original,0,pos+1);
	// SI NO HAY TIPO, BUSCARLO DEL SELECT
	if(!tipo) {
		tipo=$("select",row).val();
		if(!tipo) return;
		$("select",row).val("");
	}
	// OBTENER TEMPLATE DE BOTONERA ADD
	var buttons=$("input[name='"+variable+"_add']").val();
	buttons=base64_decode(buttons);
	buttons=utf8_decode(buttons);
	// OBTENER TEMPLATE DE BOTONERA DEL
	var borrar=$("input[name='"+variable+"_del']").val();
	borrar=base64_decode(borrar);
	borrar=utf8_decode(borrar);
	// BUSCAR ROW EN LA TABLA PARA INSERTAR DATOS DE AJAX
	var table=row.parentNode;
	$("tr",table).each(function() {
		if(this==row) {
			var otros=explode("+",tipo);
			if(max_data(variable)>=count(otros)) {
				var campo=all_data(variable);
				var temp=explode(".",variable);
				tipo=otros.shift();
				var url="inicio.php?include=php/getdinamic.php&table="+temp[1]+"&type="+tipo+"&campo="+campo+"&form=insert";
				show_loading();
				$.ajax({
					url:url,
					dataType:"html",
					success:function(data,textStatus) {
						buttons=original+buttons+"</td>";
						$("<tr>"+buttons+"</tr>").insertAfter(row);
						var pos=strpos(data,">");
						data=substr(data,0,pos+1)+borrar+substr(data,pos+1);
						var row2=$("<tr movable='true'>"+data+"</tr>");
						$(row2).insertAfter(row);
						row=row2;
						all_data(variable,tipo); // PONE EL TIPO EN EL ELEMENTO QUE NO TIENE EL ATTRIBUTO TIPO (QUE EVIDENTEMENTE ES EL NUEVO)
						while(tipo=otros.shift()) {
							var campo=all_data(variable);
							var url="inicio.php?include=php/getdinamic.php&table="+temp[1]+"&type="+tipo+"&campo="+campo+"&form=insert";
							$.ajax({
								url:url,
								dataType:"html",
								async:false,
								success:function(data,textStatus) {
									var row2=$("<tr extra='true'>"+data+"</tr>");
									$(row2).insertAfter(row);
									row=row2;
									all_data(variable,tipo); // PONE EL TIPO EN EL ELEMENTO QUE NO TIENE EL ATTRIBUTO TIPO (QUE EVIDENTEMENTE ES EL NUEVO)
								}
							});
						}
						hover_events();
						make_tables();
						make_ckeditors();
						fix_max_height();
						hide_loading();
					},
					error:function (XMLHttpRequest,textStatus,errorThrown) {
						hide_loading();
						alert("Error "+XMLHttpRequest.status+": "+XMLHttpRequest.statusText);
					}
				});
			} else {
				msgbox($("input[name='"+variable+"_msg']").val());
			}
		}
	});
}

function all_data(variable,tipo) {
	var maxdata=$("input[name='"+variable+"_max']").val(); // NUMERO MAXIMO DE CAMPOS
	var temp=explode(".",variable);
	var prefix=temp[0]+"_data_";
	var postfix="."+temp[1]+"."+temp[2];
	var namedata=""; // FUTURA LISTA DE CAMPOS
	var typedata=""; // FUTURA LISTA DE TIPOS
	var groupdata=new Array(); // FUTURA LISTA DE GRUPOS
	var allid=""; // FUTURA LISTA CON LOS IDS QUE SON NUMEROS, SIRVE PARA CALCULAR EL PROXIMO NOMBRE DEL CAMPO A USAR
	$("*[name^='"+prefix+"'][name$='"+postfix+"']").each(function() {
		curid=str_replace(prefix,"",str_replace(postfix,"",$(this).attr("name")));
		if(strpos(curid,"_")===false) {
			if(namedata!="") namedata+=",";
			namedata+=str_replace(postfix,"",$(this).attr("name"));
			if(isUndefined($(this).attr("tipo"))) $(this).attr("tipo",tipo);
			if(typedata!="") typedata+=",";
			typedata+=$(this).attr("tipo");
			if(allid!="") allid+=",";
			allid+=intval(curid);
			// GROUPDATA IMPROVEMENT
			var tr=this;
			var maxiter=100;
			while(!$(tr).is("tr")) {
				tr=$(tr).parent();
				maxiter--;
				if(!maxiter) break;
			}
			if($(tr).attr("extra")!="true") {
				groupdata.push(1);
			} else {
				groupdata[groupdata.length-1]++;
			}
		}
	});
	groupdata=implode(",",groupdata);
	//~ console.debug(namedata);
	//~ console.debug(typedata);
	//~ console.debug(groupdata);
	$("input[name='"+variable+"_name']").val(namedata);
	$("input[name='"+variable+"_type']").val(typedata);
	$("input[name='"+variable+"_group']").val(groupdata);
	// BUSCAR EL PROXIMO NOMBRE DE CAMPO A USAR
	var temp=explode(",",allid);
	var temp2=sort(temp,"SORT_NUMERIC");
	var temp3="";
	for(var i=0;i<temp2.length;i++) {
		if(typeof(temp2[i])=="string") {
			if(temp3!="") temp3+=",";
			temp3+=temp2[i];
		}
	}
	temp3=explode(",",temp3);
	// TEMP3 EXISTE POR UN BUG DE LA FUNCION SORT (RETORNA UN ELEMENTO DEL ARRAY QUE ES UNA FUNCION)
	var nextid=-1;
	if(allid!="") {
		if(intval(temp3[0])>0) nextid=0;
	}
	for(var i=0;i<temp3.length-1;i++) {
		if(intval(temp3[i+1])-intval(temp3[i])>1) {
			nextid=intval(temp3[i])+1;
			break;
		}
	}
	if(nextid==-1) {
		if(allid=="") nextid=0;
		else nextid=intval(temp3[temp3.length-1])+1;
	}
	maxdata=intval(maxdata);
	if(nextid>=maxdata) return "";
	var result=prefix+nextid+postfix;
	return result;
}

function max_data(variable) {
	var maxdata=$("input[name='"+variable+"_max']").val(); // NUMERO MAXIMO DE CAMPOS
	var temp=explode(".",variable);
	var prefix=temp[0]+"_data_";
	var postfix="."+temp[1]+"."+temp[2];
	var count=0;
	$("*[name^='"+prefix+"'][name$='"+postfix+"']").each(function() {
		curid=str_replace(prefix,"",str_replace(postfix,"",$(this).attr("name")));
		if(strpos(curid,"_")===false) {
			count++;
		}
	});
	return maxdata-count;
}

function del_data(obj,variable) {
	$(".ui-tooltip").hide();
	show_loading();
	var cell=obj.parentNode;
	var row=cell.parentNode;
	var table=row.parentNode;
	var borrar=0;
	$("tr",table).each(function() {
		if(this==row) {
			var extra=$("tr",this).length;
			borrar=2+extra;
		}
		if($(this).attr("extra")=="true") {
			if(borrar>0) {
				var extra=$("tr",this).length;
				borrar+=1+extra;
			}
		}
		if(borrar) {
			// REMOVE THE CKEDITOR IF EXISTS
			$("textarea",this).each(function() {
				var name=$(this).attr("name");
				if(CKEDITOR.instances[name]) CKEDITOR.instances[name].destroy();
			});
			// CONTINUE NORMAL OPERATION
			$(this).remove();
			borrar--;
		}
	});
	all_data(variable);
	hover_events();
	make_tables();
	make_ckeditors();
	fix_max_height();
	hide_loading();
}

function up_data(obj,variable) {
	move_data(obj,variable,-1);
}

function down_data(obj,variable) {
	move_data(obj,variable,1);
}

function move_data(obj,variable,offset) {
	$(".ui-tooltip").hide();
	show_loading();
	var cell=obj.parentNode;
	var row=cell.parentNode;
	var table=row.parentNode;
	var mover=0;
	var rows=new Array();
	$("tr",table).each(function() {
		if(this==row) {
			var extra=$("tr",this).length;
			mover=2+extra;
		}
		if($(this).attr("extra")=="true") {
			if(mover>0) {
				var extra=$("tr",this).length;
				mover+=1+extra;
			}
		}
		if(mover) {
			// REMOVE THE CKEDITOR IF EXISTS
			$("textarea",this).each(function() {
				var name=$(this).attr("name");
				if(CKEDITOR.instances[name]) CKEDITOR.instances[name].destroy();
			});
			// CONTINUE NORMAL OPERATION
			if(!$(this).attr("role")) rows.push(this);
			mover--;
		}
	});
	if(offset<0) {
		var temp=rows[0];
		for(var j=0;j<2;j++) {
			if($(temp).prev().attr("extra")=="true") j--;
			temp=$(temp).prev();
		}
		if($(temp).attr("movable")=="true") {
			for(var i=0;i<rows.length;i++) {
				for(var j=0;j<2;j++) {
					if($(rows[i]).prev().attr("extra")=="true") j--;
					$(rows[i]).insertBefore($(rows[i]).prev());
				}
			}
		}
	}
	if(offset>0) {
		var temp=rows[0];
		for(var j=0;j<2;j++) {
			if($(temp).next().attr("extra")=="true") j--;
			temp=$(temp).next();
		}
		if($(temp).attr("movable")=="true") {
			for(var i=rows.length-1;i>=0;i--) {
				for(var j=0;j<2;j++) {
					if($(rows[i]).next().attr("extra")=="true") j--;
					$(rows[i]).insertAfter($(rows[i]).next());
				}
			}
		}
	}
	all_data(variable);
	hover_events();
	make_tables();
	make_ckeditors();
	fix_max_height();
	hide_loading();
}
