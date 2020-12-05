<?php
if ( function_exists( 'stream_resolve_include_path' ) && stream_resolve_include_path( 'geshi.php' ) === FALSE ) {
    die( '<pre class="html">Please install the GeSHi library. Refer to plugins/codesnippetgeshi/README.md for more information.' );
}

include_once 'geshi.php';

$json_string = file_get_contents( 'php://input' );
$json_object = json_decode( $json_string );

$geshi = new GeSHi( $json_object->html, $json_object->lang );

echo $geshi->parse_code();
?>