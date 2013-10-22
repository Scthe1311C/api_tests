<?php
// This is the server-side script

session_start(); // :)
ob_start(); // do not send headers, wait till buffer is full
			
// Set the content type
header('Content-Type: text/plain');
 
require_once 'utils/php_oAuth20.php';
require_once 'utils/phpDropbox.php';
 
$method = $_GET["method"];
include "../keys.php";
$dropbox = new phpDropbox( $dropbox_key, $dropbox_secret, $app_root."/dropbox");

if( $method === "metadata"){
	$path  = $_GET["path"];
	$files_list = $dropbox->metadata("dropbox",$path,false);
	$return = json_encode($files_list, true);
}else{ // method==thumbnails
	// parameters
	$path  = $_GET["path"];
	$img_thumb_path  = $_GET["thumb_path"];

	// create requested file
	$img_data = $dropbox->thumbnails("dropbox", $path, false, "l"); 
	$file = fopen( $img_thumb_path, "wb");
	fwrite($file, $img_data);
	fclose( $file);
	$return = '{"staus":"ok"}';
	$return = 	$img_thumb_path."___";
	//print_r($img_data);

}


// Send the data back
$a =  isset($_SESSION['oauth2_token']);
//echo "This is the returned text.".$a.$return;
echo $return;
?>