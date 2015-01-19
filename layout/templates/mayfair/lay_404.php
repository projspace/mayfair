<?
	$page = $elems->qry_Page404();
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js " lang="en"> <!--<![endif]-->

<head>
	<title><?=$page['title']['value'] ?></title>
	<meta name="keywords" content="<?=$page['keywords']['value'] ?>" />
	<meta name="description" content="<?=$page['description']['value'] ?>" />

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
            <div class="banner banner-small">
                <div class="banner-info">
                    <div class="banner-content billing-img">
                        <?=$page['content']['value'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================
    Content Area End Here
    ========================-->
    <? include "inc_Footer.php"; ?>
    <? include "inc_BodyEnd.php"; ?>
</body>
</html>