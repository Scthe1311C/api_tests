<!-- images view -->
<?php
	$flickr = new phpFlickr( $flickr_key); // now it's just called being lazy..
	
	if( $photos !== NULL){
		//$photo = $photos["photo"][2];
		foreach( $photos["photo"] as $photo){
			echo "[ title='" . $photo["title"] . "'; id = '" .$photo["id"]. "']<br/>";
			$photo_sizes = $flickr->photos_getSizes( $photo["id"]); 
			echo "<img src=\"" . $photo_sizes["size"][2]["source"] . "\" />";
		}
	}else{
		echo "No images found !";
	}

	/*
	base gallery..
	$columns = 3;
	//$rows = 2;
	$total_images = 9;
	
	//$images_per_page = $columns * $rows;
	//$pages = $total_images / $images_per_page;
	$td_class = "span".( (int) floor(12 / $columns));
	
	echo "<table class=\".table\" style=\"width:100%\">";
	for ($i = 1; $i <= $total_images; $i++) {
		if( ($i - 1) % $columns == 0 ) echo "<tr>";
		echo "<td width=\"33%\">";
		echo "<img src=\"img/test_img.jpg\" width=\"100%\"/>";
		echo "<td/>";
		if( $i % $columns == 0 ) echo "</tr>";
	}
	if( $i % $columns != 0 )
			echo "</tr>";
	echo "</table>";
	*/
?>

<!-- pagination -->
<?php $pages=0; if( $pages > 1){ ?>
<div class="pagination pagination-right">
  <ul>
	<li><a href="#">Prev</a></li>
	<?php for ($i = 1; $i <= $pages+1; $i++) { ?>
		<li><a href="#"><?php echo $i; ?></a></li>
	<?php } ?>
	<li><a href="#">Next</a></li>
  </ul>
</div>
<?php } ?>
<!-- /images view -->
