<?php 
function bf3_player_statistics( $player_name){
	$link = "http://api.bf3stats.com/pc/player/";
	$response = post($link, 'player='.$player_name);
	return $response;
}
?>