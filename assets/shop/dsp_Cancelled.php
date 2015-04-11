<div id="content-wrapper" class="yui3-g">
	<aside id="sidebar" class="yui3-u">
		<ul class="pages">
			<li><a href="#">Addresses</a></li>
			<li><a href="#">Payment</a></li>
			<li><a href="#" class="on">Thank you</a></li>
		</ul>
	</aside>
	<div id="content" class="yui3-u home">
		<article>
			<header class="content-box"><h1>Order Cancelled</h1></header>
			<section class="content-box">
			
			<?
				$page = $elems->qry_Page(51);
				
				echo '<form class="std-form inner" action="'.$config['dir'].'account/payment-update" method="post">
						'.$page['content'];
				echo '</form>';
			?>
				
			</section>
		</article>
	</div>
</div>