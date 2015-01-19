<!doctype html>

<html lang="en">
<head>
	<title><?=$elems->meta('title'); ?></title>
	<meta name="keywords" content="<?=$elems->meta('keywords'); ?>">
	<meta name="description" content="<?=$elems->meta('description'); ?>">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	
	<? include "inc_Head.php"; ?>
	
	<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCSC03b5ZNSFXZkGzbmAkgPk2xXL1mzFCw&sensor=true">
    </script>
	
	
	
</head>

<body>

<div id="page-wrapper">
	<? include "inc_Header.php"; ?>
	
	<? print trim($Fusebox["layout"]); ?>
		
	<? include "inc_Footer.php"; ?>
</div>

</body>
</html>