<?
	$page = $elems->qry_Page404();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?=$page['title']['value'] ?></title>
	<meta name="keywords" content="<?=$page['keywords']['value'] ?>" />
	<meta name="description" content="<?=$page['description']['value'] ?>" />

	<? require "inc_Head.php"; ?>

	<!-- Site specific -->
	<link rel="stylesheet" href="<?=$config['layout_dir'] ?>css/jScrollPane.css" type="text/css" media="screen, projection" />

	<!-- JavaScript dependencies -->
	<script src="<?=$config['layout_dir'] ?>js/jquery.mousewheel.js" type="text/javascript"></script>
	<script src="<?=$config['layout_dir'] ?>js/jScrollPane-1.2.3.min.js" type="text/javascript"></script>

	<style type="text/css">
	/* <![CDATA[ */

	/* ]]> */
	</style>
</head>

<body>

	<div id="page-container">
	
		<? require "inc_Header.php"; ?>
		
		<div id="content">

			<h1><?=htmlentities($page['title']['value'], ENT_NOQUOTES, 'UTF-8') ?></h1>
			<div id="text-page">
				<div class="scroll-pane"><?=$page['content']['value'] ?></div>
			</div>
		</div>
	</div>
	<? require "inc_Footer.php"; ?>

</body>
</html>
