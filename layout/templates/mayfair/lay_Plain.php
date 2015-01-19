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
    <style type="text/css">
    html { background-color: #C2C2C2; }
    .main { width: auto; padding: 0; }
    .main .block { padding-bottom: 0; }
    </style>
</head>

<body>
    <? include "inc_BodyStart.php"; ?>

    <!--==========================
    Content Area Start From Here
    ===========================-->
    <div id="content">
        <div class="main">
            <? print trim($Fusebox["layout"]); ?>
        </div>
    </div>
    <!--====================
    Content Area End Here
    ========================-->
    <? include "inc_BodyEnd.php"; ?>
</body>
</html>