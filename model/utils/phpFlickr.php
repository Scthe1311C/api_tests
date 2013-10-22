<?php 

class phpFlickr{

	// example gallery id: 		438069-72157622245833229
	// example gallery link:	http://www.flickr.com/photos/wurz/galleries/72157622245833229
	
	// url construction process
	// http://www.flickr.com/services/api/misc.urls.html
	
	// photos response
	// http://code.flickr.net/2008/08/19/standard-photos-response-apis-for-civilized-age/
	
	private static $flickr_api = "http://api.flickr.com/services/rest/";
	private $api_key;
	
	public function __construct( $api_key) {
		$this->api_key = $api_key;
	}
	
	private function execute( $method, $data, $responseRoot=NULL, $debug=false){
		$data_ = array(
				"method" => $method,
				"api_key" => $this->api_key,
				"format" => "json",
				"nojsoncallback" => "1"
			);
			$data_ = array_merge( $data_, $data);
			$response = get( self::$flickr_api, $data_);
			$object =	json_decode($response, true);
			if( $object["stat"] == "ok" ){
				if( $debug)
					print_json($response);
				return $responseRoot == NULL? $object : $object[$responseRoot];
			}else{
				//print_json($response);
				return NULL;
			}
	
	}
	
	/**
	 *	get gallery data
	 */
	public function urls_lookupGallery( $gallery_link){
			$method = "flickr.urls.lookupGallery";
			$data = array(
				"url" => rawurlencode( $gallery_link),
			);
			return self::execute($method, $data, "gallery", false);
	}
	
	/**
	 *	get photos from selected gallery
	 */	
	public function galleries_getPhotos( $gallery){
		$method = "flickr.galleries.getPhotos";
		$data = array(
			"gallery_id"=>$gallery["id"],
			"per_page"=>"4",
		);
		return self::execute($method, $data, "photos", false);
	}
	
	/**
	 *	get photo's available sizes
	 */	
	public function photos_getSizes( $id){
		// TODO just create by hand
		$method = "flickr.photos.getSizes";
		$data = array(
			"photo_id"=>$id,
		);
		return self::execute($method, $data, "sizes", false);
	}



}

 ?>

