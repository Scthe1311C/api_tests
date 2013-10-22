<script>

/*
TODO make it at least readable
*/

$('document').ready(function(){
	go_to_folder("");
});

function go_to_folder( path){
	var uri = "<?php echo $app_root."/model/dropboxHandler.php";  ?>";
	uri += '?method=metadata&path='+encodeURIComponent(path);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open('get', uri, true);
	xmlhttp.onreadystatechange = function(){
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200){
			var json = jQuery.parseJSON( xmlhttp.responseText );
			create_folder_content( json);
			//document.getElementById("dropbox-folder-content").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.send();
 }
 
function create_folder_content( dropbox_dir_json){
	var result = "";
 
	// all folders
	if( dropbox_dir_json.path != "/"){ // back to parent folder
		var return_path = dropbox_dir_json.path.substr( 0,  dropbox_dir_json.path.lastIndexOf("/"));
		result += createFolderItem( return_path, "..");
	}
	$.each( dropbox_dir_json.contents, function(i, file){
		if( file.is_dir){
			//result += "'"+file.path+"'";
			result += createFolderItem( file.path, file.path.substr( file.path.lastIndexOf("/")+1));
		}
	});
		
	// all images
	var thumb_ext = "jpeg";
	var cache_dir = "cache/";
	var max_display_name_len = 15;
	$.each( dropbox_dir_json.contents, function(i, file){
		if( !file.is_dir && file.mime_type.indexOf("image") == 0 && file.thumb_exists){
			var ext = file.path.substring( file.path.lastIndexOf("."));
			var file_name = file.path.substring( file.path.lastIndexOf("/")+1, file.path.lastIndexOf("."));
			var img_thumb_path = cache_dir + "<?php echo $account_info["uid"]; ?>_" + file_name.replace( "/", "_") + "."+thumb_ext;
			var display_file_name = file_name.length < max_display_name_len ? file_name+ext : file_name.substr(0, max_display_name_len-1)+"~"+ext;
			
			//result += fileExists(img_thumb_path)+"  "+img_thumb_path+" <br/>";
			if( !fileExists(img_thumb_path)){
				// create cache ( add '../' to thumb path - back from utils to root )
				var uri = "<?php echo $app_root."/model/dropboxHandler.php";  ?>";
				uri += '?method=thumbnails&path='+encodeURIComponent(file.path) + '&thumb_path='+encodeURIComponent("../"+img_thumb_path);

				var xmlhttp = new XMLHttpRequest();
				xmlhttp.open('get', uri, false);
				xmlhttp.onreadystatechange = function(){
					if ( xmlhttp.readyState == 4 && xmlhttp.status == 200){
						//alert(xmlhttp.responseText);
					}
				}
				xmlhttp.send();
			}
			
			result += createImageItem( img_thumb_path, display_file_name);
			//result += display_file_name + "; " + ext + '<br/>';
		}
	});
 
	document.getElementById("dropbox-folder-content").innerHTML = result;
 }
 
function createFolderItem( target_path, name){ 
	//return createTableItem( '<img src="img/folder-img.png" onclick="go_to_folder( \''+target_path+'\' )"/>', "folder-item", name);
	return createTableItem( "src/img/folder-img.png", "go_to_folder( \'" + target_path + "\' )", "folder-item", name);
}

function createImageItem( img_link, name){ 
	//return createTableItem( '<img src="'+img_link+'" />', "gallery-item", name);
	return createTableItem( img_link, "", "gallery-item", name);
	//return createTableItem( "cache/test_img.jpg", "", "gallery-item", name);
}
 
function createTableItem( img_element, on_click, div_class_name, name){
	return '<div class="gallery-item-wrapper">'+
				'<div class="' + div_class_name + '">'+
					'<img src="' + img_element + '" onclick="' + on_click + '"/>' +
				'</div><br/>'+ 
				'<span valign="bottom">' + name + '<span>' +
			'</div>';
	//return img_element;
}
 
function fileExists(fileLocation) {
    var http = new XMLHttpRequest();
    http.open('HEAD', fileLocation, false);
    http.send();
    return http.status == 200; // do not redirect with status 200 on redirect !
}

</script>




<h1 class="dropbox-user-name"><?php echo $account_info["display_name"]; ?></h1>
<span class="dropbox-user-name"><?php echo $account_info["email"] . " - " . $account_info["country"]; ?></span>
<hr>
<div id="dropbox-folder-content">
	<!-- place for the folder view -->
 </div> 
 <div id="dropbox-folder-content2" />