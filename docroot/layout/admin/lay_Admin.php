<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= PRODUCT_NAME ?> <?= PRODUCT_VERSION ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/reset.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/style.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/buttons.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/utils.css"/>

	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/js/fancybox/jquery.fancybox-1.3.1.css"/>
	
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/libraries.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/ddroundies.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->

	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/main.js"></script>
	<script type="text/javascript">
	    //<![CDATA[
		var date_format = 'M dd, YY'
	    //]]>
	</script>
	
	<script language="JavaScript" type="text/Javascript" src="<?= $config["dir"] ?>lib/cfg_Admin.js.php"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?= $config["dir"] ?>lib/lib_Admin.js"></script>
	
	<?
		if(isset($wysiwyg))
			echo $wysiwyg->head();
	?>
	<!--[if IE]>
	<style>
		img { behavior: url("/lib/lib_PNG.htc"); }
	</style>
<![endif]-->
</head>
<body>
	<?php 
		
		//var_dump();
		
	?>
	<div id="wrapper">
		<div id="header">

			<a class="title" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start">Administrative <span>panel</span></a>
			<div class="header-meta">
				<span class="wrapper">
					Logged in with <?= $session->session->fields['username']; ?> <a href="<?= $config["dir"] ?>index.php?fuseaction=admin.logout">[Logout]</a>
				</span>
			</div>
			<?
					$sections = array();
					
					$sections['Sales'] = array(
						array(	'action'=>	'orders', 
								'name'	=>	'Current Orders')
						,array(	'action'=>	'records', 
								'name'	=>	'Past Orders')
                        ,array(	'action'=>	'giftRegistry',
								'name'	=>	'Gift Registry')
						,array(	'action'=>	'reports', 
								'name'	=>	'Reports')
						,array(	'action'=>	'sales_reports', 
								'name'	=>	'Sales Reports')
						,array(	'action'=>	'voucher_reports', 
								'name'	=>	'Voucher Reports')
						,array(	'action'=>	'commission_reports', 
								'name'	=>	'Commission Reports')
						,array(	'action'=>	'papHistory', 
								'name'	=>	'Pick &amp; Pack History')
					);
					
					$sections['Catalog'] = array(
						array(	'action'=>	'products', 
								'name'	=>	'Products')
						,array(	'action'=>	'categories', 
								'name'	=>	'Categories')
						,array(	'action'=>	'discountCodes', 
								'name'	=>	'Discount Codes')
						,array(	'action'=>	'brands', 
								'name'	=>	'Brands')
						/*,array(	'action'=>	'suppliers', 
								'name'	=>	'Suppliers')*/
					);
					
					$sections['Customers'] = array(
						array(	'action'=>	'users', 
								'name'	=>	'Manage Customers')
						/*,array(	'action'=>	'reviews',
								'name'	=>	'Reviews')*/
					);
					
					$sections['Access Control'] = array(
						array(	'action'=>	'accounts', 
								'name'	=>	'Users')
						,array(	'action'=>	'ACLGroups', 
								'name'	=>	'Groups')
					);
					
					$sections['Settings'] = array(
						array(	'action'=>	'pages', 
								'name'	=>	'Pages')
						,array(	'action'=>	'page404', 
								'name'	=>	'404 Page')
						,array(	'action'=>	'emails', 
								'name'	=>	'System Emails')
						,array(	'action'=>	'settings', 
								'name'	=>	'System Configuration')
						,array(	'action'=>	'cart', 
								'name'	=>	'Manage Basket')
						,array(	'action'=>	'invoice', 
								'name'	=>	'Manage Invoice')
						,array(	'action'=>	'areas', 
								'name'	=>	'Shipping')
						,array(	'action'=>	'companyDetails', 
								'name'	=>	'Company Details')
						/*,array(	'action'=>	'homeBanner',
								'name'	=>	'Home Banner')
						,array(	'action'=>	'press',
								'name'	=>	'Press / Ads')
						,array(	'action'=>	'fittingGuides',
								'name'	=>	'Fitting Guides')*/
                        ,array(	'action'=>	'manageGiftRegistry',
								'name'	=>	'Manage Gift Registry')
						,array(	'action'=>	'giftTypes',
								'name'	=>	'Gift Registry Types')
					);
					
					$sections['Variables'] = array(
						array(	'action'=>	'sizes', 
								'name'	=>	'Product Sizes')
						,array(	'action'=>	'widths', 
								'name'	=>	'Product Widths')
						,array(	'action'=>	'colors', 
								'name'	=>	'Product Colors')
					);
					
				/*
					$sections['Orders'] = array(
						array(	'action'=>	'orders', 
								'name'	=>	'Orders')
						,array(	'action'=>	'records', 
								'name'	=>	'Records')
						,array(	'action'=>	'search', 
								'name'	=>	'Search')
					);
					
					$sections['Products &amp; Stock'] = array(
						array(	'action'=>	'products', 
								'name'	=>	'Products')
						,array(	'action'=>	'categories', 
								'name'	=>	'Categories')
						,array(	'action'=>	'stock', 
								'name'	=>	'Stock')
						,array(	'action'=>	'brands', 
								'name'	=>	'Brands')
						,array(	'action'=>	'suppliers', 
								'name'	=>	'Suppliers')
						,array(	'action'=>	'meta_tags', 
								'name'	=>	'Meta Tags')
						,array(	'action'=>	'vat', 
								'name'	=>	'VAT')
						,array(	'action'=>	'discountCodes', 
								'name'	=>	'Discount Codes')
					);
					
					$sections['Shipping'] = array(
						array(	'action'=>	'rules', 
								'name'	=>	'Rules')
						,array(	'action'=>	'testRules', 
								'name'	=>	'Test Rules')
						,array(	'action'=>	'areas', 
								'name'	=>	'Areas')
						,array(	'action'=>	'countries', 
								'name'	=>	'Countries')
					);
					
					$sections['Shop Data'] = array(
						array(	'action'=>	'feed', 
								'name'	=>	'Product Feed')
						,array(	'action'=>	'reports', 
								'name'	=>	'Reports')
					);

					$sections['Content'] = array(
						array(	'action'=>	'pages', 
								'name'	=>	'Pages')
						,array(	'action'=>	'pendingEdits', 
								'name'	=>	'Pending Edits')
						,array(	'action'=>	'pendingAdditions', 
								'name'	=>	'Pending Additions')
						,array(	'action'=>	'pendingRemovals', 
								'name'	=>	'Pending Removals')
						,array(	'action'=>	'deleted', 
								'name'	=>	'Deleted Pages')
						,array(	'action'=>	'layouts', 
								'name'	=>	'Layouts')
						,array(	'action'=>	'page404', 
								'name'	=>	'Page 404')
						,array(	'action'=>	'emails', 
								'name'	=>	'Emails')
						,array(	'action'=>	'homeBanner', 
								'name'	=>	'Home Banner')
					);
					
					$sections['Configuration'] = array(
						array(	'action'=>	'password', 
								'name'	=>	'Change Password')
						,array(	'action'=>	'setup', 
								'name'	=>	'Setup')
					);
					
					$sections['Accounts'] = array(
						array(	'action'=>	'accounts', 
								'name'	=>	'Administrator')
						,array(	'action'=>	'ACLGroups', 
								'name'	=>	'Access Groups')
						,array(	'action'=>	'sessions', 
								'name'	=>	'Current Sessions')
					);
				*/
			?>
			<ul id="menu">
				<?
					foreach($sections as $name => $actions ) {
						$flag = false;
						$current = false;
						foreach($actions as $row) {
							if($acl->check($row['action'])) {
								$flag = true;
							}
							if( $Fusebox['fuseaction'] == $row['action'] ) {
								$current = true;
							}
						}
						
						if($flag) {
							
							echo '<li', $current ? ' class="selected"' : false ,'>';
								echo '<a href=""><em>', $name ,'</em></a>';
								echo '<ul>';
									foreach($actions as $row) {
										if($acl->check($row['action'])) {
											echo '<li>';	
												echo '<a href="', $config['dir'] ,'index.php?fuseaction=admin.', $row['action'] ,'">';
													echo '<strong>', $row['name'] ,'</strong>';
													echo '<em></em>';
												echo '</a>';
											echo '</li>';
										}
									}
								echo '</ul>';
							echo '</li>';
							
						}
					}
				?>
				
			</ul>
		</div>
		<? if($_SESSION['alert']): ?>
		<div id="flash-message" class="flash alert">
			<p><?=$_SESSION['alert'] ?></p>
			<div class="clearfix"></div>
		</div>
		<script language="javascript" type="text/javascript">
		/* <![CDATA[ */
			$(window).load(function(){
				setTimeout(function(){
					$('#flash-message').hide('normal');
				}, 5000);
			});
		/* ]]> */
		</script>
		<? unset($_SESSION['alert']); ?>
		<? endif; ?>
		<div id="middle">
			<?php /*
			<div class="help-container">
				<div class="help-title">Welcome to your Dashboard</div>
				<div class="help-content">
					<p>The dashboard shows daily statistics about your website via Google Analytics: number of unique visitors, the total number of page views, and the percentage of people who left after only viewing one page. &nbsp;You can change the time interval shown by clicking on the dates.</p>
				</div>
				<br /><br />
				<a class="help-edit" href="/admin/settings_help/edit/hcontroller/index/haction/home">Edit this entry</a>
			</div>
			*/ ?>
			
			<? print trim($Fusebox["layout"]); ?>
			<div class="clear"></div>
		</div>
		<div id="footer">
			Copyright <?=date('Y') ?> <a href="http://www.webstarsltd.com/" target="_blank"><strong>Webstars Ltd</strong></a>
		</div>
	</div>

</body>
</html><?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 * /
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?= PRODUCT_NAME ?> <?= PRODUCT_VERSION ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/reset.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/style.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/buttons.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/css/utils.css"/>

	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/js/jquery.ui/css/webstars/style.css"/>
	<link rel="stylesheet" href="<?= $config["dir"] ?>admin-assets/js/fancybox/jquery.fancybox-1.3.1.css"/>
	
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/libraries.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/ddroundies.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->

	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="<?= $config["dir"] ?>admin-assets/js/main.js"></script>
	<script type="text/javascript">
	    //<![CDATA[
		var date_format = 'M dd, YY'
	    //]]>
	</script>
	
	<script language="JavaScript" type="text/Javascript" src="<?= $config["dir"] ?>lib/cfg_Admin.js.php"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?= $config["dir"] ?>lib/lib_Admin.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?= $config["dir"] ?>VLib/js/jquery-1.3.2.min.js"></script>
	<?
		if(isset($wysiwyg))
			echo $wysiwyg->head();
	?>
	<!--[if IE]>
	<style>
		img { behavior: url("/lib/lib_PNG.htc"); }
	</style>
<![endif]-->
</head>
<body>
<div id="header">
	<div id="logo"><a href="<?= $config["dir"] ?>index.php?fuseaction=admin.start"><img src="<?= $config["dir"] ?>images/admin/logo.png" width="166" height="50" alt="<?= PRODUCT_NAME." ".PRODUCT_VERSION ?>" /></a><img src="<?= $config["dir"] ?>images/admin/gradientbg.jpg" width="200" height="50" alt="Gradient" /></div>
	<div id="actions">
		<!--<a onclick="javascript:help();"><img src="<?= $config["dir"] ?>images/admin/help.png" width="60" height="29" alt="Help" /></a>-->
		<a href="<?= $config["dir"] ?>index.php?fuseaction=admin.logout"><img src="<?= $config["dir"] ?>images/admin/logout.png" width="72" height="29" alt="Logout" /></a>
	</div>
</div>
<div id="shadow"><img src="<?= $config["dir"] ?>images/admin/navgradient.gif" width="166" height="13" alt="Drop Shadow" /></div>
<?
	$sections = array();
	
	$sections['Sales'] = array(
		array(	'action'=>	'orders', 
				'name'	=>	'Current Orders')
		,array(	'action'=>	'records', 
				'name'	=>	'Past Orders')
		,array(	'action'=>	'reports', 
				'name'	=>	'Reports')
		,array(	'action'=>	'areas', 
				'name'	=>	'Shipping')
	);
	
	$sections['Catalog'] = array(
		array(	'action'=>	'products', 
				'name'	=>	'Products (incl blocks)')
		,array(	'action'=>	'categories', 
				'name'	=>	'Categories')
		,array(	'action'=>	'discountCodes', 
				'name'	=>	'Discount Codes')
	);
	
	$sections['Customer Accounts'] = array(
		array(	'action'=>	'users', 
				'name'	=>	'Manage Customers')
		,array(	'action'=>	'reviews', 
				'name'	=>	'Reviews')
	);
	
	$sections['Access Control'] = array(
		array(	'action'=>	'accounts', 
				'name'	=>	'Users')
		,array(	'action'=>	'ACLGroups', 
				'name'	=>	'Groups')
	);
	
	$sections['Settings'] = array(
		array(	'action'=>	'pages', 
				'name'	=>	'Static Pages')
		,array(	'action'=>	'page404', 
				'name'	=>	'404 Page')
		,array(	'action'=>	'emails', 
				'name'	=>	'System Emails')
		,array(	'action'=>	'vat', 
				'name'	=>	'Manage VAT')
		,array(	'action'=>	'companyDetails', 
				'name'	=>	'Company Details')
		,array(	'action'=>	'homeBanner', 
				'name'	=>	'Home Banner')
		,array(	'action'=>	'deepLinking', 
				'name'	=>	'Deep Linking')
	);
	
/*
	$sections['Orders'] = array(
		array(	'action'=>	'orders', 
				'name'	=>	'Orders')
		,array(	'action'=>	'records', 
				'name'	=>	'Records')
		,array(	'action'=>	'search', 
				'name'	=>	'Search')
	);
	
	$sections['Products &amp; Stock'] = array(
		array(	'action'=>	'products', 
				'name'	=>	'Products')
		,array(	'action'=>	'categories', 
				'name'	=>	'Categories')
		,array(	'action'=>	'stock', 
				'name'	=>	'Stock')
		,array(	'action'=>	'brands', 
				'name'	=>	'Brands')
		,array(	'action'=>	'suppliers', 
				'name'	=>	'Suppliers')
		,array(	'action'=>	'meta_tags', 
				'name'	=>	'Meta Tags')
		,array(	'action'=>	'vat', 
				'name'	=>	'VAT')
		,array(	'action'=>	'discountCodes', 
				'name'	=>	'Discount Codes')
	);
	
	$sections['Shipping'] = array(
		array(	'action'=>	'rules', 
				'name'	=>	'Rules')
		,array(	'action'=>	'testRules', 
				'name'	=>	'Test Rules')
		,array(	'action'=>	'areas', 
				'name'	=>	'Areas')
		,array(	'action'=>	'countries', 
				'name'	=>	'Countries')
	);
	
	$sections['Shop Data'] = array(
		array(	'action'=>	'feed', 
				'name'	=>	'Product Feed')
		,array(	'action'=>	'reports', 
				'name'	=>	'Reports')
	);

	$sections['Content'] = array(
		array(	'action'=>	'pages', 
				'name'	=>	'Pages')
		,array(	'action'=>	'pendingEdits', 
				'name'	=>	'Pending Edits')
		,array(	'action'=>	'pendingAdditions', 
				'name'	=>	'Pending Additions')
		,array(	'action'=>	'pendingRemovals', 
				'name'	=>	'Pending Removals')
		,array(	'action'=>	'deleted', 
				'name'	=>	'Deleted Pages')
		,array(	'action'=>	'layouts', 
				'name'	=>	'Layouts')
		,array(	'action'=>	'page404', 
				'name'	=>	'Page 404')
		,array(	'action'=>	'emails', 
				'name'	=>	'Emails')
		,array(	'action'=>	'homeBanner', 
				'name'	=>	'Home Banner')
	);
	
	$sections['Configuration'] = array(
		array(	'action'=>	'password', 
				'name'	=>	'Change Password')
		,array(	'action'=>	'setup', 
				'name'	=>	'Setup')
	);
	
	$sections['Accounts'] = array(
		array(	'action'=>	'accounts', 
				'name'	=>	'Administrator')
		,array(	'action'=>	'ACLGroups', 
				'name'	=>	'Access Groups')
		,array(	'action'=>	'sessions', 
				'name'	=>	'Current Sessions')
	);
* /
?>
<div id="container">
	<div id="left">
		<ul>
		<?
			foreach($sections as $name=>$actions)
			{
				$count = 0;
				foreach($actions as $row)
					if($acl->check($row['action']))
						$count++;
				if($count > 0)
				{
					echo '
						<li><span>'.$name.'</span>
							<ul class="nav">';
					foreach($actions as $row)
						if($acl->check($row['action']))
							echo '
								<li><a href="'.$config['dir'].'index.php?fuseaction=admin.'.$row['action'].'">'.$row['name'].'</a></li>';
					echo '	</ul>
						</li>';
				}
			}
		?>
		</ul>
	</div>
	<div id="main">
		<div id="content">
			<? print trim($Fusebox["layout"]); ?>
		</div>
	</div>
	<br class="clear" />
	<div class="clear">&nbsp;</div>
</div>
<div id="copyright">
	<a href="<?= COPYRIGHT_URL ?>" target="_new">&copy; <?= COPYRIGHT_YEAR ?> <?= COPYRIGHT ?></a> | <a href="<?= $config["dir"] ?>index.php?fuseaction=admin.about">About</a>
</div>
</body>
</html> <? */ ?>