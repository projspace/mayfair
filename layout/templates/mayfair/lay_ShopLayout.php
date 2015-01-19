<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js " lang="en"> <!--<![endif]-->

<head>
	<title><?=$elems->meta('title'); ?></title>
	<meta name="keywords" content="<?=$elems->meta('keywords'); ?>">
	<meta name="description" content="<?=$elems->meta('description'); ?>">
	
	<? include "inc_Head.php"; ?>
</head>

<body>
    <? include "inc_BodyStart.php"; ?>
    <? include "inc_Header.php"; ?>

    <!--==========================
    Content Area Start From Here
    ===========================-->
    <div id="content">
        <div class="main">
            <? $elems->content(); ?>
        </div>
    </div>
    <!--====================
    Content Area End Here
    ========================-->
    <? include "inc_Footer.php"; ?>
    <? include "inc_BodyEnd.php"; ?>
</body>
</html>