<?php 

function flickr_gallery( $gallery_url){
	$photos = flickr_get_gallery_photos( $gallery_url);
	return render_template( array(
		"content" => "flickr.php",
		"photos" => $photos
	));
}

function bf3_player_stats( $player_name){
	$player_stats = bf3_get_player( $player_name);
	$title = "Bf3 - ".$player_name."'s stats";
	return render_template( array(
		"content" => "bf3.php",
		"title" => $title,
		"player_name" => $player_name,
		"player_stats" => $player_stats
	));
}

function dropbox_gallery(){
	$account_info = dropbox_user_login();
	//$title = $account_info["display_name"]."'s dropbox";
	return render_template( array(
		"content" => "dropbox.php",
		"title" => "dropbox gallery",
		"account_info" => $account_info
	));
}

function welcome(){
	return render_template( array(
		"content" => "home.php"
	));
}

function render_template(array $args, $path = 'src/templates/app_layout.php'){
	if ( !in_array('title', $args)){
		$args["title"] = "drop app";
	}
    extract($args);
    ob_start();
	include 'keys.php';
    require $path;
    $html = ob_get_clean();
    return $html;
}

?>