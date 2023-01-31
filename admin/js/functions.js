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

function isUndefined(a) {
	return typeof a == 'undefined';
}

function isNull(a) {
	return typeof a == 'object' && !a;
}

function isNumber(a) {
	return typeof a == 'number' && isFinite(a);
}

function setchk(num,bool) {
	var chk=document.getElementById('chk'+num)
	chk.checked=bool;
}

function setallchk(bool) {
	var num=0
	var chk=document.getElementById('chk'+num)
	while(chk!=null) {
		setchk(num,bool);
		num++;
		chk=document.getElementById('chk'+num)
	}
}

function swapchk(num) {
	var chk=document.getElementById('chk'+num)
	chk.checked=!chk.checked;
}

function swapallchk() {
	var num=0
	var chk=document.getElementById('chk'+num)
	while(chk!=null) {
		swapchk(num);
		num++;
		chk=document.getElementById('chk'+num)
	}
}

function anadir(keycode) {
	if(keycode==13) {
		var iter=document.getElementById('iter')?document.getElementById('iter').value:1;
		redir('inicio.php?page=form&table='+table+'&iter='+iter);
	}
}

function getchkids() {
	var num=0
	var ids='';
	var chk=document.getElementById('chk'+num)
	while(chk!=null) {
		if(chk.checked) {
			if(ids.length>0) ids=ids+',';
			ids=ids+chk.value;
		}
		num++;
		var chk=document.getElementById('chk'+num)
	}
	return ids;
}

function pagina() {
	var offset=document.getElementById('offset').value;
	var limit=document.getElementById('limit').value;
	redir('inicio.php?page=list&table='+table+'&offset='+offset+'&limit='+limit);
}

function buscador(keycode) {
	if(keycode==13) {
		var search=document.getElementById('search').value;
		if(search=='') search='null';
		redir('inicio.php?page=list&table='+table+'&search='+search);
	}
}

function hexadecimal(obj,maximo) {
	var valor=obj.value;
	var viejo=strtoupper(valor);
	var nuevo="";
	for(var i=0;i<viejo.length && i<maximo;i++) {
		ishex=false;
		var letra=viejo.substr(i,1);
		if(letra>="0" && letra<="9") ishex=true;
		if(letra>="A" && letra<="F") ishex=true;
		if(ishex) nuevo+=letra;
	}
	if(nuevo!=valor) obj.value=nuevo;
}

function mascara(d,sep,pat,nums){
	if(d.valant != d.value){
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),'g')
					val2 = val2.replace(letra,'')
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			} else {
				if(val3[q] != ''){
					val += sep + val3[q]
				}
			}
		}
		d.value = val
		d.valant = val
	}
}

function mascara_num(obj,punto) {
	var texto=obj.value;
	var texto2="";
	for(i=0;i<texto.length;i++) {
		var letra=texto.substr(i,1);
		if(letra>='0' && letra<='9') {
			texto2+=letra;
		} else if((letra=='.' || letra==',') && !punto) {
			texto2+='.';
			punto=1;
		} else if(letra=='-' && texto2.length==0) {
			texto2+='-';
		}
	}
	if(texto!=texto2) obj.value=texto2;
}

function seleccionar(arg1,arg2,arg3) {
	var orig=document.getElementById(arg1+'_'+arg3);
	var dest=document.getElementById(arg2+'_'+arg3);
	for(i=0;i<orig.length;i++) {
		if(orig.options[i].selected) {
			orig.options[i].selected=false;
			dest.appendChild(orig.options[i]);
			i--;
		}
	}
	var sel=document.getElementById('sel_'+arg3);
	var sel2=document.getElementById(arg3);
	var lista='';
	for(i=0;i<sel.length;i++) {
		if(i>0) lista=lista+',';
		lista=lista+sel.options[i].value;
	}
	sel2.value=lista;
}

function change_style() {
	var style=document.getElementById("style").value;
	$.ajax({ url:'inicio.php?include=php/style.php',data:'style='+style,type:'post',async:false,success:myreload });
}

var count_loading=0;

function show_loading() {
	var sx=document.body.scrollLeft;
	var sy=document.body.scrollTop;
	var wx=window.innerWidth;
	var wy=window.innerHeight;
	if(isUndefined(wx)) {
		wx=screen.width;
		wy=screen.height-200;
	}
	div=document.getElementById('loading');
	div.style.left="-1000px";
	div.style.top="-1000px";
	div.style.display='block';
	var pi=pos_and_dim(div);
	div.style.left=intval(sx+wx/2-pi.w/2)+"px";
	div.style.top=intval(sy+wy/2-pi.h/2)+"px";
	count_loading++;
}

function hide_loading() {
	count_loading--;
	if(count_loading==0) {
		div=document.getElementById('loading');
		div.style.display='none';
	}
}

function redir(arg) {
	create_redir(arg);
	show_loading();
	document.f2.submit();
}

function create_redir(arg) {
	temp1=arg.split('?');
	document.f2.action=temp1[0];
	if(temp1.length==2) {
		temp2=temp1[1].split('&');
		for(i=0;i<temp2.length;i++) {
			temp3=temp2[i].split('=');
			for(j=2;j<temp3.length;j++) temp3[1]=temp3[1]+'='+temp3[j];
			input=document.createElement('input');
			input.setAttribute('type','hidden');
			input.setAttribute('name',temp3[0]);
			input.setAttribute('value',temp3[1]);
			document.f2.appendChild(input);
		}
	}
}

function myreturnhere(val) {
	document.f1.returnhere.value=val;
}

function mysubmit() {
	show_loading();
	unused_files();
	$(document.f1).submit();
}

function unused_files() {
	$("input[type=file]").each(function() {
		if($(this).val()=="") {
			$(this).attr("disabled","disabled");
		}
	});
}

function myreload() {
	show_loading();
	document.location.reload();
}

function myfocus(obj) {
	var temp=document.getElementById(obj);
	if(!isNull(temp)) temp.focus();
	else setTimeout('myfocus(\"'+obj+'\")',500);
}

function redir2(arg) {
	document.location.href=arg;
}

function setajaxform(id,value) {
	var f1=document.f1;
	for(i=0;i<f1.length;i++) {
		if(f1[i].name==id) {
			f1[i].value=value;
			return;
		}
	}
	var input=document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('name',id);
	input.setAttribute('value',value);
	document.f1.appendChild(input);
}

function pos_and_dim(obj) {
	var x=0;
	var y=0;
	var w=obj.offsetWidth;
	var h=obj.offsetHeight;
	while(obj !=null) {
		x+=obj.offsetLeft;
		y+=obj.offsetTop;
		obj=obj.offsetParent;
	}
	return {x:x,y:y,w:w,h:h}
}

function selectall() {
	var valor=null;
	var count=0;
	if(document.f1) {
		for(i=0;i<document.f1.elements.length;i++) {
			obj=document.f1.elements[i];
			if(obj.type) {
				if(obj.type=="radio") {
					if(count>=2) {
						if(valor==null) valor=!obj.checked;
						if(count%2==0) obj.checked=valor;
						if(count%2==1) obj.checked=!valor;
					}
					count++;
				}
			}
		}
	}
}
