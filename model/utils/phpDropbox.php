<?php 

// reqiures 'php_oAuth20.php' !
//require_once 'utils/php_oAuth20.php';

class phpDropbox{
	// https://www.dropbox.com/developers/core/docs

	
	private static $access_type = "dropbox";
	private static $authorize_endpoint = "https://www.dropbox.com/1/oauth2/authorize";
    private static $token_endpoint = "https://api.dropbox.com/1/oauth2/token";
	
	private $_token;
	private $dropbox_key;
	private $dropbox_secret;
	private $redirect_url;
	
	public function __construct( $dropbox_key, $dropbox_secret, $redirect_url, $debug=false) {
		$this->dropbox_key = $dropbox_key;
		$this->dropbox_secret = $dropbox_secret;
		$this->redirect_url = $redirect_url;
	
		$tokenStorage = new TokenStorage();
		$_token = $tokenStorage->retrieveAccessToken();
		if( $_token === NULL){
			echo '<div class="alert alert-error">Token authorization not found ! Requesting a new one..</div>';
			
			// get new token
			
			$oAuth = new oAuth2( $this->dropbox_key, $this->dropbox_secret, $this->redirect_url, self::$authorize_endpoint, self::$token_endpoint);
			if (!isset($_GET['code'])) {
				// authorization phase 1
				//echo "authorization phase 1";
				$oAuth->authorize();
			}else{
				// authorization phase 2
				// retrieve access token from endpoint
				//echo "authorization phase 2";
				$this->_token = $oAuth->read_access_token();
				$tokenStorage->storeAccessToken( $this->_token);
			}
		}else if( $debug){
			echo '<div class="alert alert-success">Token authorization found in the storage !</div>';
		}
	}
	
	public function get_token(){
		$tokenStorage = new TokenStorage();
		$token = $tokenStorage->retrieveAccessToken();
		//check if token is invalid
        if ($token->getLifeTime() && $token->getLifeTime() < time()) {
			$oAuth = new oAuth2( $this->dropbox_key, $this->dropbox_secret, $this->redirect_url, self::$authorize_endpoint, self::$token_endpoint);
            $token = $this->refreshAccessToken($token);
			$tokenStorage->storeAccessToken( $this->_token);
        }
		return $token;
	}
	
	private function execute( $method, $data=NULL, $type="GET", $debug=false){
		//$host = Host::getDefault();
		$tokenStorage = new TokenStorage();
		$token = $tokenStorage->retrieveAccessToken();
		
		//check if token is invalid
        if ($token->getLifeTime() && $token->getLifeTime() < time()) {
			$oAuth = new oAuth2( $this->dropbox_key, $this->dropbox_secret, $this->redirect_url, self::$authorize_endpoint, self::$token_endpoint);
            $token = $this->refreshAccessToken($token);
			$tokenStorage->storeAccessToken( $this->_token);
			//echo '<div class="alert">Authorization token renew !</div>';
        }

		$curl = curl_init($method);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_LOW_SPEED_LIMIT, 1024);
		curl_setopt($curl, CURLOPT_LOW_SPEED_TIME, 10);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
        // TODO: Figure out how to encode clientIdentifier (urlencode?)
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"User-Agent: ".$token->getUserId()." Dropbox-PHP-SDK",
			"Authorization: Bearer ". $token->getAccessToken()
		));
		//echo "User-Agent: ".$token->getUserId()." Dropbox-PHP-SDK";
		//echo "Authorization: Bearer ". $token->getAccessToken();
		if( $type != "GET"){
			curl_setopt($curl, CURLOPT_POST, true);
			if($data !== NULL)
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data) );
		}else{
			if($data !== NULL){
				curl_setopt($curl, CURLOPT_URL, $method."?".http_build_query($data) );
				//echo "<br/>".$method."?".http_build_query($data)."<br/>";
			}
			curl_setopt($curl, CURLOPT_HTTPGET, true);
		}
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
	
	public function account_info(){
		$url = "https://api.dropbox.com/1/account/info";
		$response = $this->execute( $url);
		//print_json( $response);
		return json_decode($response, true);
	}
	
	/**
		@returns metadata of file or folder's content
	*/
	public function metadata( $root, $path, $debug=false){
		$url = "https://api.dropbox.com/1/metadata/";
		if( $root !== "sandbox" && $root !== "dropbox" )
			 throw new Exception("Valid values for 'root' are sandbox and dropbox.");
		$url .= $root."/".$path;
		$response = $this->execute( $url);
		if($debug)
			print_json( $response);
		return json_decode($response, true);
	}
	
	/**
		@returns file link to embed on the webpage
	*/
	public function shares( $root, $path, $debug=false){
		$url = "https://api.dropbox.com/1/shares/";
		if( $root !== "sandbox" && $root !== "dropbox" )
			 throw new Exception("Valid values for 'root' are sandbox and dropbox.");
		$url .= $root."/".$path;
		$response = $this->execute( $url, array("short_url"=>"false"), "POST");
		if($debug)
			print_json( $response);
		return json_decode($response, true);
	}
	
	/**
		@returns image thumbnails in God-only-knows-what format
		@param size One of the following values :
			xs(32); s (64); m (128); l	(640x480); xl	(1024x768)
	*/
	public function thumbnails( $root, $path, $debug=false, $size="s"){
		$url = "https://api-content.dropbox.com/1/thumbnails/"; // removed ending '/' !
		if( $root !== "sandbox" && $root !== "dropbox" )
			 throw new Exception("Valid values for 'root' are sandbox and dropbox.");
		$url .= $root.$path;
		$response = $this->execute( $url, array("format"=>"jpeg", "size"=>$size));
		//$response = $this->execute( $url);
		return $response;
	}
	
	/**
		@returns image to download
	*/
	public function files_get( $root, $path, $debug=false, $size="s"){
		$url = "https://api-content.dropbox.com/1/files/";
		if( $root !== "sandbox" && $root !== "dropbox" )
			 throw new Exception("Valid values for 'root' are sandbox and dropbox.");
		$url .= $root."/".$path;
		$response = $this->execute( $url);
		if($debug)
			print_json( $response);
		return json_decode($response, true);
	}
	
}	

class TokenStorage{

    //public function __construct() {
    //    session_start();
    //}

    public function retrieveAccessToken() {
		if( isset($_SESSION['oauth2_token']) ){
			$token = $_SESSION['oauth2_token'];
			$token = unserialize (serialize ($token));
			return $token;
			//return $_SESSION['oauth2_token'];
		}
        return NULL;
    }

    public function storeAccessToken(Token $token) {
        $_SESSION['oauth2_token'] = $token;
    }

    //public function  __destruct() {
    //    session_write_close();
    //}
}

 ?>

