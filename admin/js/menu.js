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

var __menu_div;
var __menu_tbl;
var __menu_ifr;
var __menu_numx;
var __menu_numy;
var __menu_tempy;
var __menu_tempx;
var __menu_vis;
var __menu_incr;

function menu_init() {
	__menu_div=document.getElementById('fmd');
	__menu_tbl=document.getElementById('fmt');
	__menu_ifr=document.getElementById('fmi');
	__menu_div.style.display='block';
	__menu_numx=parseInt(__menu_tbl.offsetWidth);
	__menu_numy=100;
	__menu_tempx=$(window).scrollLeft();
	__menu_tempy=$(window).scrollTop();
	__menu_div.style.left=parseInt(25-__menu_numx+__menu_tempx)+"px";
	__menu_div.style.top=parseInt(__menu_numy+__menu_tempy)+"px";
	__menu_ifr.style.width=parseInt(__menu_tbl.offsetWidth)+"px";
	__menu_ifr.style.height=parseInt(__menu_tbl.offsetHeight)+"px";
	__menu_ifr.style.left=parseInt(__menu_div.style.left)+"px";
	__menu_ifr.style.top=parseInt(__menu_div.style.top)+"px";
	__menu_ifr.style.display='block';
	__menu_vis=false;
	__menu_incr=50;
	$(window).scroll(function() { __menu_ontop(); });
}

function __menu_show() {
	if(__menu_vis) return;
	__menu_vis=true;
	setTimeout('__menu_show2();',50);
}

function __menu_show2() {
	__menu_div.style.left=parseInt(parseInt(__menu_div.style.left)+__menu_incr)+"px";
	__menu_ifr.style.left=parseInt(__menu_div.style.left)+"px";
	if(parseInt(__menu_div.style.left)>=__menu_tempx) {
		__menu_div.style.left=__menu_tempx+"px";
		__menu_ifr.style.left=parseInt(__menu_div.style.left)+"px";
	} else {;
		setTimeout('__menu_show2();',50);
	}
}

function __menu_hide() {
	if(!__menu_vis) return;
	__menu_vis=false;
	setTimeout('__menu_hide2();',1000);
}

function __menu_hide2() {
	if(__menu_vis) return;
	__menu_div.style.left=parseInt(parseInt(__menu_div.style.left)-__menu_incr)+"px";
	__menu_ifr.style.left=parseInt(__menu_div.style.left)+"px";
	if(parseInt(__menu_div.style.left)<=25-__menu_numx+__menu_tempx) {;
		__menu_div.style.left=parseInt(25-__menu_numx+__menu_tempx)+"px";
		__menu_ifr.style.left=parseInt(__menu_div.style.left)+"px";
	} else {;
		setTimeout('__menu_hide2();',50);
	}
}

function __menu_ontop() {
	var oldtempx=__menu_tempx;
	var oldtempy=__menu_tempy;
	__menu_tempx=$(window).scrollLeft();
	__menu_tempy=$(window).scrollTop();
	if(__menu_tempy!=oldtempy) {
		__menu_div.style.top=parseInt(parseInt(__menu_div.style.top)+(__menu_tempy-oldtempy))+"px";
		__menu_ifr.style.top=parseInt(__menu_div.style.top)+"px";
	}
	if(__menu_tempx!=oldtempx) {
		__menu_div.style.left=parseInt(parseInt(__menu_div.style.left)+(__menu_tempx-oldtempx))+"px";
		__menu_ifr.style.left=parseInt(__menu_div.style.left)+"px";
	}
}
