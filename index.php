<?php 

// http://getbootstrap.com/2.3.2/base-css.html
// http://blog.teamtreehouse.com/html5css3-image-thumbnail-gallery-with-lightbox-effect
// http://tympanus.net/codrops/2013/03/19/thumbnail-grid-with-expanding-preview/
// http://kylerush.net/blog/tutorial-flickr-api-javascript-jquery-ajax-json-build-detailed-photo-wall/
// http://www.w3schools.com/jsref/jsref_obj_string.asp


///////////////////////////////////////////////////////////////////////////
// todo move to Env. & config files (?)
///////////////////////////////////////////////////////////////////////////

$DEBUG_REDIRECT = true;
$DEBUG_FLICKR = true;

// turn on error reporting ?
error_reporting(E_ALL|E_STRICT);
ini_set("display_errors", true);

session_start();
ob_start(); // do not send headers, wait till the buffer is full
//session_unset();

///////////////////////////////////////////////////////////////////////////


require_once 'keys.php';
require_once 'model/model.php';
require_once 'controllers.php';


// SERVER_NAME ; PHP_SELF ; SCRIPT_FILENAME ; REQUEST_URI
$request_uri = $_SERVER["REQUEST_URI"];
$page_name = substr( $request_uri, 0, strrpos( $request_uri, "?")===FALSE ? strlen($request_uri): strrpos( $request_uri, "?"));
$page_name = substr( $page_name, strrpos( $page_name, "/"));

if( $page_name==="/flickr"){
	$gallery_url = $_POST["gallery_url"];
	$content = flickr_gallery($gallery_url);
	
}else if( $page_name=="/bf3"){
	$player_name = $_POST["player_name"];
	$content = bf3_player_stats( $player_name);
	
}else if( $page_name=="/dropbox"){
	$content = dropbox_gallery();
	
}else if( $page_name=="/"){
	$content = welcome();
	
} else {
    $content =  '<html><body><h1>Page Not Found</h1></body></html>';
    header('Status: 404 Not Found');
}

// render content
echo $content;
?>