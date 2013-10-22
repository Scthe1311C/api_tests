<?php 

require_once 'utils/phpBf3.php';
require_once 'utils/phpFlickr.php';
require_once 'utils/php_oAuth20.php';
require_once 'utils/phpDropbox.php';

function bf3_get_player( $player_name){
	$response = bf3_player_statistics($player_name);
	$object = json_decode($response, true);
	return $object !== NULL && $object["status"] !== "error" ? $object : NULL;
}

function flickr_get_gallery_photos( $gallery_url){
	include "keys.php";
	$flickr = new phpFlickr( $flickr_key);
	$gallery = $flickr->urls_lookupGallery($gallery_url);
	//print_r( $gallery);
	if( $gallery !== NULL && !array_key_exists( 'stat', $gallery)){
		$ph = $flickr->galleries_getPhotos($gallery);
		if( $ph !== NULL)
			$photos = $ph;
	}
	if( !isset( $photos))
		$photos = NULL;
	return $photos;
}

function dropbox_user_login(){
	include "keys.php";
	$dropbox = new phpDropbox( $dropbox_key, $dropbox_secret, $app_root."/dropbox");
	return $dropbox->account_info();
}

///
/// utils functions
///
function get( $url, $data){
	$url_ = $url;
	$f = true;
	foreach( $data as $key => $val){
		$url_=$url_.($f ? "?" : "&").$key."=".$val; // TODO just use some encode array to parameters method
		$f = false;
	}
	//echo $url_;
	$curl = curl_init( $url_);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

function post( $url, $data){
	/*
	debug
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	$verbose = fopen('C:\\programs\\portable\\xampp\\htdocs\\a\\OAuth2\\temp.txt', 'w+');
	curl_setopt($ch, CURLOPT_STDERR, $verbose);
	!rewind($verbose);
	$verboseLog = stream_get_contents($verbose);
	echo "Verbose information:\n<pre>!", htmlspecialchars($verboseLog), "!</pre>\n";
	echo "error: '".curl_error($ch)."'";
	*/
	
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// certificate problems:
	// set CURLOPT_SSL_VERIFYPEER to false to disable SSL certification
	// note: this a hack around providing correct certificate authorities list to check against
	// http://stackoverflow.com/questions/17478283/paypal-access-ssl-certificate-unable-to-get-local-issuer-certificate
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

function print_json( $json){
	echo "<pre style=\"background-color: #888;\">";
	$jsonPP =	json_decode($json, true);
	print_r($jsonPP);
	echo "</pre>";
}

?>