<?
	$page = $elems->qry_Page404();
?>
<!doctype html>

<html lang="en">
<head>
	<title><?=$page['title']['value'] ?></title>
	<meta name="keywords" content="<?=$page['keywords']['value'] ?>" />
	<meta name="description" content="<?=$page['description']['value'] ?>" />
	
	<? include "inc_Head.php"; ?>
</head>

<body>

<div id="page-wrapper">
	<? include "inc_Header.php"; ?>
	
	<div id="content-wrapper">
		<article id="page-404">
			<?=$page['content']['value'] ?>
		</article>
	</div>
		
	<? include "inc_Footer.php"; ?>
</div>

</body>
</html>