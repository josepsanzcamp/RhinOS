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
define("__BUF_HEADER__",0);
define("__BUF_PRODUCT__",1);
define("__BUF_FOOTER__",2);
define("__BUF_VOID__",3);
define("__QUERY_PRODUCT__",4);
define("__QUERY_PRICE__",5);
define("__QUERY_UNITS__",6);
define("__QUERY_DISCOUNT__",7);
define("__QUERY_TAX__",8);
define("__QUERY_SHIPPING__",9);
define("__INSERT_MAIN__",10);
define("__INSERT_DETAIL__",11);
define("__INSERT_STOCK__",12);

function tienda($param) {
	static $buffers=array();
	static $queryes=array();
	static $old_output=0;
	static $cursor=0;
	// OTRAS VARIABLES
	$proc="NOTHING";
	include_once("dbapp2.php");
	$temp=strtok($param," ");
	if($temp=="BEGIN") {
		$temp=strtok(" ");
		if($temp=="HEADER") {
			dbapp2("SET __BEGIN_END__ 1");
			$old_output=get_output("tienda_buffer");
			set_output(0,"tienda_buffer");
			clear_buffer();
		} elseif($temp=="PRODUCT") {
			$buffers[__BUF_HEADER__]=get_buffer();
			clear_buffer();
		} elseif($temp=="FOOTER") {
			$buffers[__BUF_PRODUCT__]=get_buffer();
			clear_buffer();
		} elseif($temp=="VOID") {
			dbapp2("SET __BEGIN_END__ 1");
			$old_output=get_output("tienda_buffer");
			set_output(0,"tienda_buffer");
			clear_buffer();
		}
	} elseif($temp=="END") {
		$temp=strtok(" ");
		if($temp=="FOOTER") {
			$buffers[__BUF_FOOTER__]=get_buffer();
			clear_buffer();
			set_output($old_output,"tienda_buffer");
			$proc="QUERY";
			dbapp2("RESET __BEGIN_END__");
		} elseif($temp=="VOID") {
			$buffers[__BUF_VOID__]=get_buffer();
			clear_buffer();
			set_output($old_output,"tienda_buffer");
			dbapp2("RESET __BEGIN_END__");
		}
	} elseif($temp=="QUERY") {
		$temp=strtok(" \n\t");
		$value=trim(strtok(""));
		$value=_dbapp2_replace($value);
		if($temp=="PRODUCT") {
			$queryes[__QUERY_PRODUCT__]=$value;
		} elseif($temp=="PRICE") {
			$queryes[__QUERY_PRICE__]=$value;
		} elseif($temp=="UNITS") {
			$queryes[__QUERY_UNITS__]=$value;
		} elseif($temp=="DISCOUNT") {
			$queryes[__QUERY_DISCOUNT__]=$value;
		} elseif($temp=="TAX") {
			$queryes[__QUERY_TAX__]=$value;
		} elseif($temp=="SHIPPING") {
			$queryes[__QUERY_SHIPPING__]=$value;
		} elseif($temp=="MAIN") {
			$queryes[__INSERT_MAIN__]=$value;
		} elseif($temp=="DETAIL") {
			$queryes[__INSERT_DETAIL__]=$value;
		} elseif($temp=="STOCK") {
			$queryes[__INSERT_STOCK__]=$value;
		}
	} elseif($temp=="PRODUCT") {
		$id=strtok(" ");
		$action=strtok(" ");
		$units=strtok(" ");
		$id=_dbapp2_replace($id);
		$action=_dbapp2_replace($action);
		$units=_dbapp2_replace($units);
		$units=abs($units);
		_tienda_cart($queryes,$id,$action,$units);
	} elseif($temp=="RETURN") {
		$temp=strtok(" ");
		if($temp=="REFERER") {
			if(!isset($_SERVER["HTTP_REFERER"])) die();
			$temp=$_SERVER["HTTP_REFERER"];
		} else {
			$temp=get_base().$temp;
		}
		_tienda_new_location($temp);
	} elseif($temp=="INSERT") {
		$proc=$temp;
		$temp=strtok(" ");
		if($temp=="PRINT") $proc=$temp;
	} elseif($temp=="RESET") {
		sessions("SET shoppingcart NULL");
	} elseif($temp=="REFRESH") {
		$cart=sessions("GET shoppingcart");
		if(is_array($cart)) {
			foreach($cart["prod"] as $prod) {
				_tienda_cart($queryes,$prod["id"],"SET",$prod["units"]);
			}
		}
	} elseif($temp=="GET") {
		$temp=strtok(" ");
		if($temp=="CART") {
			$cart=sessions("GET shoppingcart");
			if(is_array($cart)) {
				unset($cart["prod"]);
				$cursor=0;
				return $cart;
			}
			return null;
		} elseif($temp=="PRODUCT") {
			$cart=sessions("GET shoppingcart");
			if(is_array($cart)) {
				$counter=0;
				foreach($cart["prod"] as $prod) {
					if($counter==$cursor) {
						$cursor++;
						return $prod;
					}
					$counter++;
				}
			}
			return null;
		} else {
			if(checkDebug("DEBUG_TIENDA")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
		}
	} elseif(in_array($temp,array("PRINT","IMAGE","FILE","VIDEO","IF","ELIF","ELSEIF","ELSE","ENDIF"))) {
		dbapp2($param);
	} else {
		if(checkDebug("DEBUG_TIENDA")) echo_buffer(__TAG1__." UNKNOWN ACTION: $param ".__TAG2__);
	}
	if($proc=="QUERY") {
		$cart=sessions("GET shoppingcart");
		if(is_array($cart)) {
			if(isset($buffers[__BUF_HEADER__])) _dbapp2_parser($cart,$buffers[__BUF_HEADER__]);
			foreach($cart["prod"] as $prod) {
				$row=_tienda_query_row($queryes,__QUERY_PRODUCT__,_tienda_array_row(array("id"=>$prod["id"])));
				if(isset($buffers[__BUF_PRODUCT__])) _dbapp2_parser($prod,$buffers[__BUF_PRODUCT__],array(),$row);
			}
			if(isset($buffers[__BUF_FOOTER__]))_dbapp2_parser($cart,$buffers[__BUF_FOOTER__]);
		} else {
			$cart=array();
			if(isset($buffers[__BUF_VOID__])) _dbapp2_parser($cart,$buffers[__BUF_VOID__]);
		}
	} elseif(in_array($proc,array("INSERT","PRINT"))) {
		$insert=($proc=="INSERT")?1:0;
		$print=($proc=="PRINT")?1:0;
		$cart=sessions("GET shoppingcart");
		if(is_array($cart)) {
			$query=isset($queryes[__INSERT_MAIN__])?$queryes[__INSERT_MAIN__]:"";
			if($query) {
				$query=_dbapp2_replace($query,array(),$cart,"ROW");
				if($insert) dbQuery($query);
				if($print) echo_buffer("<pre>$query</pre>");
			}
			foreach($cart["prod"] as $prod) {
				$query=isset($queryes[__INSERT_DETAIL__])?$queryes[__INSERT_DETAIL__]:"";
				if($query) {
					$row=_tienda_query_row($queryes,__QUERY_PRODUCT__,_tienda_array_row(array("id"=>$prod["id"])));
					$query=_dbapp2_replace($query,array(),array_merge(_tienda_array_row($prod),$row),"ROW");
					if($insert) dbQuery($query);
					if($print) echo_buffer("<pre>$query</pre>");
				}
				$query=isset($queryes[__INSERT_STOCK__])?$queryes[__INSERT_STOCK__]:"";
				if($query) {
					$query=_dbapp2_replace($query,array(),_tienda_array_row($prod),"ROW");
					if($insert) dbQuery($query);
					if($print) echo_buffer("<pre>$query</pre>");
				}
			}
		}
	}
}

function _tienda_cart($queryes,$id,$action,$units) {
	// RESTORE THE CART FROM THE SESSION
	$cart=sessions("GET shoppingcart");
	if(!is_array($cart)) {
		// NEW CART IF NOT EXISTS WITH DEFAULT STRUCTURE
		$cart=array();
		$cart["units"]=0;
		$cart["base"]=0;
		$cart["discount"]=0;
		$cart["tax"]=0;
		$cart["shipping"]=0;
		$cart["total"]=0;
		$cart["prod"]=array();
	}
	// RENAME SOME COMMANDS (ONLY IMPLEMENTS THE SET AND ADD)
	if($action=="DEL") { $action="SET"; $units=0; }
	if($action=="SUB") { $action="ADD"; $units*=-1; }
	// FIND AND UPDATE THE UNITS IF EXISTS
	$exists=0;
	foreach($cart["prod"] as $key=>$prod) {
		if($prod["id"]==$id) {
			if($action=="SET") $prod["units"]=$units;
			elseif($action=="ADD") $prod["units"]+=$units;
			$prod=_tienda_modify_product($queryes,$prod);
			$cart["prod"][$key]=$prod;
			$exists=1;
		}
	}
	if(!$exists) {
		// CREATE THE NEW PRODUCT IF NOT EXISTS
		$prod=array();
		$prod["id"]=$id;
		$prod["units"]=$units;
		$prod=_tienda_modify_product($queryes,$prod);
		$cart["prod"][]=$prod;
	}
	// REMOVE PRODUCTS THAT HAS <=0 UNITS
	foreach($cart["prod"] as $key=>$prod) {
		if($prod["units"]<=0) unset($cart["prod"][$key]);
	}
	// CALCULATE ALL TOTALS
	$cart["units"]=0;
	$cart["base"]=0;
	foreach($cart["prod"] as $prod) {
		$cart["units"]+=$prod["units"];
		$cart["base"]+=$prod["total"];
	}
	$cart["discount"]=_tienda_query_value($queryes,__QUERY_DISCOUNT__);
	$cart["tax"]=_tienda_query_value($queryes,__QUERY_TAX__);
	$cart["shipping"]=_tienda_query_value($queryes,__QUERY_SHIPPING__);
	$cart=_tienda_apply_discount_tax_shipping($cart);
	$cart=_tienda_number_format($cart);
	// STORE THE MODIFIED CART IN THE SESSION
	if(count($cart["prod"])!=0) sessions("SET shoppingcart",$cart);
	else sessions("SET shoppingcart NULL");
}

function _tienda_modify_product($queryes,$prod) {
	$units=_tienda_query_value($queryes,__QUERY_UNITS__,_tienda_array_row(array("id"=>$prod["id"],"units"=>$prod["units"])));
	if($units!="" && $prod["units"]>floatval($units)) $prod["units"]=floatval($units);
	$prod["price"]=floatval(_tienda_query_value($queryes,__QUERY_PRICE__,_tienda_array_row(array("id"=>$prod["id"],"units"=>$prod["units"]))));
	$prod["base"]=$prod["price"]*$prod["units"];
	$prod["discount"]=_tienda_query_value($queryes,__QUERY_DISCOUNT__,_tienda_array_row(array("id"=>$prod["id"],"units"=>$prod["units"])));
	$prod["tax"]=_tienda_query_value($queryes,__QUERY_TAX__,_tienda_array_row(array("id"=>$prod["id"],"units"=>$prod["units"])));
	$prod["shipping"]=_tienda_query_value($queryes,__QUERY_SHIPPING__,_tienda_array_row(array("id"=>$prod["id"],"units"=>$prod["units"])));
	$prod=_tienda_apply_discount_tax_shipping($prod);
	$prod=_tienda_number_format($prod);
	return $prod;
}

function _tienda_apply_discount_tax_shipping($prod) {
	$prod["total"]=$prod["base"];
	if(substr(trim($prod["shipping"]),-1,1)=="%") $prod["total"]+=round($prod["total"]*floatval($prod["shipping"])/100,2);
	else $prod["total"]+=round(floatval($prod["shipping"]),2);
	if(substr(trim($prod["discount"]),-1,1)=="%") $prod["total"]-=round($prod["total"]*floatval($prod["discount"])/100,2);
	else $prod["total"]-=round(floatval($prod["discount"]),2);
	if(substr(trim($prod["tax"]),-1,1)=="%") $prod["total"]+=round($prod["total"]*floatval($prod["tax"])/100,2);
	else $prod["total"]+=round(floatval($prod["tax"]),2);
	return $prod;
}

function _tienda_number_format($prod) {
	if(isset($prod["price"])) $prod["price"]=number_format($prod["price"],2,".","");
	$prod["base"]=number_format($prod["base"],2,".","");
	$prod["total"]=number_format($prod["total"],2,".","");
	return $prod;
}

function _tienda_query_value($queryes,$querytype,$row=array()) {
	$row=_tienda_query_row($queryes,$querytype,$row);
	$value=isset($row["value"])?$row["value"]:"";
	return $value;
}

function _tienda_query_row($queryes,$querytype,$row=array()) {
	$query=isset($queryes[$querytype])?$queryes[$querytype]:"";
	if($query!="") {
		$query=_dbapp2_replace($query,array(),$row,"ROW");
		$result=dbQuery($query);
		if(!dbNumRows($result)) $row=array();
		else $row=dbFetchRow($result);
		dbFree($result);
	} else {
		$row=array();
	}
	return $row;
}

function _tienda_array_row($row) {
	if(isset($row["id"])) {
		$temp=preg_split("/[-_.,;:]/",$row["id"]);
		foreach($temp as $key=>$val) $row["id".$key]=$val;
	}
	return $row;
}

function _tienda_new_location($param) {
	header_powered();
	header_expires(false);
	header("Content-Type: text/html");
	echo_buffer("<script type='text/javascript'>\n");
	echo_buffer("if(typeof(_tienda_new_location)=='function') _tienda_new_location('$param');\n");
	echo_buffer("else window.location.href='$param';\n");
	echo_buffer("</script>\n");
	die();
}
