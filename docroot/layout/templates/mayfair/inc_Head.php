<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="author" content="Webstars ltd // staff@webstarsltd.com" />
<link rel="shortcut icon" type="image/x-icon" href="<?=$config['layout_dir'] ?>images/favicon.ico" />
<link href='<?= substr($config['layout_dir'], 0, 5) == 'https' ? 'https' : 'http'; ?>://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="<?=$config['layout_dir'] ?>css/style.css"  media="screen" />
<link rel="stylesheet" type="text/css" href="<?=$config['layout_dir'] ?>css/fs.css"  media="screen" />
<!-- <link href="//cloud.webtype.com/css/03959781-98f9-4b52-83d3-727e262b3a41.css" rel="stylesheet" type="text/css" /> -->
<script type="text/javascript" src="<?=$config['layout_dir'] ?>js/modernizr-2.6.2.min.js"></script>

<? if($config['development']): ?>
<meta name="robots" content="noindex, nofollow" />
<meta name="googlebot" content="noindex, nofollow" />
<? endif; ?>

<? if($Fusebox["circuit"]=="shop" && $Fusebox["fuseaction"]=="category"): ?>
<link rel="canonical" href="<?=$config['protocol'].$config['url'].category_url($category['id'], $category['name']) ?>"/>
<? endif; ?>
<? if($Fusebox["circuit"]=="home" && $Fusebox["fuseaction"]=="main"): ?>
<meta name="msvalidate.01" content="5466E556A07A5503C6D4C80A2431032E" />
<? endif; ?>

<?=$elems->qry_ShopVariable('fb_meta') ?>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var config_dir = '<?=$config['dir'] ?>';
/* ]]> */
</script>