<?
	$page_boxes = array();
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null, 'SignMeUp'=>null,'Banner'=>null,'FollowUs'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'home.main' || \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'home.Fusebox.defaultFuseaction'; // home page
		")
	);
	
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null,'RecentlyViewed'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'shop.category'; // category page
		")
	);
	
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null,'WhatAbout'=>$product_id,'RecentlyViewed'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'shop.product'; // product page
		")
	);
	
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'user.main' || \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'user.Fusebox.defaultFuseaction'; // account page
		")
	);
	
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'user.orders'; // account orders page
		")
	);
	
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'shop.search'; // search page
		")
	);
	
	$page_boxes[] = array(
		'boxes'			=>	array('Account'=>null)
		,'checkPage'	=>create_function('',"
			global \$Fusebox;
			return \$Fusebox['targetCircuit'].'.'.\$Fusebox['fuseaction'] == 'shop.cart'; // cart page
		")
	);
	
	$boxes = array('SignMeUp'=>null,'Banner'=>null,'FollowUs'=>null); //default
	foreach($page_boxes as $row)
		if($row['checkPage']())
			$boxes = $row['boxes'];
?>
<aside>
<?
	echo file_get_contents('http://www.shakespearesglobe.com/cache/menu_top.html');
?>
	<script language="javascript" type="text/javascript">
	/* <![CDATA[ */
		$(document).ready(function(){
			$('#advancedFind').submit(function(){
				if($('#keyword').val() == 'I\'m looking for...')
					$('#keyword').val('')
			});
		});
	/* ]]> */
	</script>
	<form id="advancedFind" action="<?=$config['dir'] ?>search" method="get" class="generic">
		<input class="text" type="text" name="keyword" id="keyword" value="I'm looking for..." placeholder="I'm looking for..." /><input class="submit" type="submit" value="find it" /> <a href="<?=$config['dir'] ?>advancedSearch" class="advanced">Advanced search</a>
	</form>
<?
	foreach($boxes as $function=>$parameters)
	{
		$function = 'dsp_Box'.$function;
		echo $elems->$function($parameters);
	}
?>
</aside>