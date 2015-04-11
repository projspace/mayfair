<!doctype html>

<html lang="en">
<head>
	<title><?=$elems->meta('title'); ?></title>
	<meta name="keywords" content="<?=$elems->meta('keywords'); ?>">
	<meta name="description" content="<?=$elems->meta('description'); ?>">
	
	<? include "inc_Head.php"; ?>
</head>

<body>

<div id="page-wrapper" style="width: auto;">
	<? print trim($Fusebox["layout"]); ?>
</div>

</body>
</html>