<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= $elems->meta('title'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="description" content="<?= $elems->meta('description'); ?>" />
	<meta name="keywords" content="<?= $elems->meta('keywords'); ?>" />
	<link rel="stylesheet" href="<?= $config['dir'] ?>css/site.css" type="text/css" media="screen,projection" />
	<link href="<?= $config['dir'] ?>css/shop.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?= $config['dir'] ?>lib/lib_Rollovers.js"></script>
	<script type="text/javascript" src="<?= $config['dir'] ?>lib/lib_Shop.js.php"></script>
</head>

<body>
	<div id="container" >

		<div id="header">
			<h1>WebStars Shop</h1>
			<h2>Slogan goes here</h2>
		</div>

		<div id="navigation">
			<? $elems->navigation(); ?>
		</div>

		<div id="breadcrumb">
			<?= $elems->TrailShop($category_id); ?>
		</div>
		<div id="content">
			<?= $elems->content(); ?>
		</div>

		<div id="sidebar">
			<? $elems->cart(); ?>
			<? $elems->categories(); ?>
			<? $elems->search(); ?>
		</div>

		<div id="footer">
			<p>&copy; 2005-2006 <a href="#">WebStars Ltd.</a> | Design by <a href="http://andreasviklund.com">Andreas Viklund</a></p>
		</div>

	</div>
</body>
</html>