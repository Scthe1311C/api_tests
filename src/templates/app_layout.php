<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title><?php echo $title; ?></title>

		<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="src/css/style.css"/>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</head>
	
	<body>
		<div class="container">
			
			<!-- top ( navigation) bar style="position:fixed;"-->
			<?php include 'nav_bar.php' ?>

			<div class="content">
				<?php include 'breadcrumbs.php' ?>
				<!-- main page content -->
				<div class="row">
					<div class="span2 ">
						<?php include 'left_menu.php' ?>
					</div>
					<div class="span10">
						<?php include $content; ?>
					</div>
				</div>
			</div>
			
		</div>
	</body>
</html>