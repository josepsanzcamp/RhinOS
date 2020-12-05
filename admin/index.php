<?php
if(empty($_SERVER["QUERY_STRING"])) $querystring="";
else $querystring="?".$_SERVER["QUERY_STRING"];
header("Location: inicio.php".$querystring);
?>