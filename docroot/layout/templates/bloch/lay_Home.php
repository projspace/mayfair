<!doctype html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<title>Shakespeare's Globe | Shop</title>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<? include "inc_Head.php"; ?>
</head>

<body>

	<div id="container">
		<? include "inc_Sidebar.php"; ?>
		<? include "inc_Header.php"; ?>
		
		<section id="main">
			<nav id="breadcrumbs">
				<a href="<?=$config['dir'] ?>" class="single">shop</a>
			</nav>
			<nav id="submenu">
			<?
				foreach($elems->qry_Categories() as $row)
					echo '<a href="'.category_url($row['id'],$row['name']).'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a> &bull; ';
			?>
			</nav>
			<? 
				$products = $elems->qry_RecentProductions(); 
				if(count($products))
				{
					echo '<article id="shop"><ul class="recent sMagenta"><li><h3>from recent productions</h3></li>';
					foreach($products as $row)
					{
						$url = product_url($row['id'],$row['name']);
						echo '
							<li>
								<a class="productPreview" href="'.$url.'"><img class="productPreview" src="'.$config['dir'].'images/product/thumb/'.$row['id'].'.'.$row['imagetype'].'" width="71" height="69" alt="*"/></a>
								<h4><a href="'.$url.'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h4>
								<p class="action"><em class="price">'.price($row['price']).'</em> | <a class="buyNow" href="basket.html">Buy Now</a> | <a href="'.$url.'">More info</a></p>
							</li>';
					}
					echo '</ul></article>';
				}
			?>
			<? $products = $elems->qry_HomeSlider(); ?>
			<!--[if lt IE 7 ]>
			<div class="sAccordion">
				<ul id="ie6ShopSplines">
				<?
					$colors = array('#14a751','#d91b5f','#680007','#16bde3');
					$index = 0;
					foreach($products as $row)
					{
						$url = product_url($row['id'],$row['name']);
						echo '
							<li style="background-color:'.$colors[$index].';">
								<a class="mainPreview" href="'.$url.'"><img src="'.$config['dir'].'images/product/slider/'.$row['id'].'.'.$row['slider_image_type'].'" width="400" height="288" alt="*"/></a>
								<div>
									<h3>'.htmlentities($row['slider_title'], ENT_NOQUOTES, 'UTF-8').'</h3>
									<p class="desription">'.htmlentities(strip_tags($row['slider_description']), ENT_NOQUOTES, 'UTF-8').'</p>
									<p><em class="price">'.price($row['price']).'</em><a href="basket.html" class="redButton">buy now</a><a href="'.$url.'" class="redButton">more info</a></p>
								</div>
							</li>';
						$index++;
					}
				?>
				</ul>
				<div class="info"></div>
			</div>
			<![endif]-->
			<!--[if (gte IE 7)|!(IE)]><!-->
			<div id="shopSlider">
				<dl>
				<?
					$index = 0;
					foreach($products as $row)
					{
						$url = product_url($row['id'],$row['name']);
						echo '
							<dt style="background-color:'.$colors[$index].';"><a href="#">'.htmlentities($row['slider_title'], ENT_NOQUOTES, 'UTF-8').'</a></dt>
							<dd style="background-color:'.$colors[$index].';">
								<a class="mainPreview" href="'.$url.'"><img src="'.$config['dir'].'images/product/slider/'.$row['id'].'.'.$row['slider_image_type'].'" width="400" height="288" alt="*"/></a>
								<h3>'.htmlentities($row['slider_title'], ENT_NOQUOTES, 'UTF-8').'</h3>
								<p class="desription">'.htmlentities(strip_tags($row['slider_description']), ENT_NOQUOTES, 'UTF-8').'</p>
								<p><em class="price">'.price($row['price']).'</em><a href="basket.html" class="redButton">buy now</a><a href="'.$url.'" class="redButton">more info</a></p>
							</dd>';
						$index++;
					}
				?>
				</dl>
			</div>
			<!--<![endif]-->
		</section>
	</div>
	<? include "inc_Footer.php"; ?>

</body>
</html>