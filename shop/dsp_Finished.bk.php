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
			<header class="content-box"><h1>Order Complete</h1></header>
			<section class="content-box">
			
			<?
				if(!$account_id && !$user_session->check() && $_GET['register'] !== 'done')
				{
					$page = $elems->qry_Page(24);
					$_REQUEST['additional_payment_session_id'] = $_REQUEST['sess_id'];
					$_REQUEST['redirect_url'] = $config['dir']."index.php/fuseaction/shop.finished";
					include("../users/dsp_RegisterForm.php");
				}
				else
				{	
					$page = $elems->qry_Page(22);
					
					echo '<form class="std-form inner" action="'.$config['dir'].'account/payment-update" method="post">
							'.$page['content'];
					echo '<p>Order number is: '.$order['id'].'</p>';
					echo '</form>';
				}
			?>
				
			</section>
		</article>
	</div>
</div>
<? if($config['protocol'] == 'https://'): ?>
<!-- Google Code for Purchase confirmation (https) Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1012737394;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "vzDgCLbWxgIQ8sr04gM";
var google_conversion_value = <?=number_format($order_details['total']+$order_details['shipping']+$order_details['packing'], 2, '.', '') ?>;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1012737394/?label=vzDgCLbWxgIQ8sr04gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code for Purchase Confirmation Remarketing List -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1012737394;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "kPkdCKbYxgIQ8sr04gM";
var google_conversion_value = <?=number_format($order_details['total']+$order_details['shipping']+$order_details['packing'], 2, '.', '') ?>;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1012737394/?label=kPkdCKbYxgIQ8sr04gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<? else: ?>
<!-- Google Code for Purchase confirmation (http) Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1012737394;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "ze47CL7VxgIQ8sr04gM";
var google_conversion_value = <?=number_format($order_details['total']+$order_details['shipping']+$order_details['packing'], 2, '.', '') ?>;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1012737394/?label=ze47CL7VxgIQ8sr04gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code for Purchase Confirmation Remarketing List -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1012737394;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "kPkdCKbYxgIQ8sr04gM";
var google_conversion_value = <?=number_format($order_details['total']+$order_details['shipping']+$order_details['packing'], 2, '.', '') ?>;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1012737394/?label=kPkdCKbYxgIQ8sr04gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<? endif; ?>

<!-- Google Analytics Ecommerce Tag Code Implementation -->
<script type="text/javascript">
	try{
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-26777039-1']);
		_gaq.push(['_setDomainName', 'www.blochshop.co.uk']);
		_gaq.push(['_trackPageview']);
		_gaq.push(['_addTrans',
			'<?=$order_details['session_id'] ?>',            // order ID - required
			'<?=addcslashes($config['company'], "'") ?>',  // affiliation or store name
			'<?=number_format($order_details['total']+$order_details['shipping']+$order_details['packing'], 2, '.', '') ?>',           // total - required
			'<?=number_format($order_details['vat'], 2, '.', '') ?>',            // tax
			'<?=number_format($order_details['shipping'], 2, '.', '') ?>',           // shipping
			'',        // city
			'',      // state or province
			'<?=addcslashes($order_details['country'], "'") ?>'              // country
		]);


		// add item might be called for every item in the shopping cart
		// where your ecommerce engine loops through each item in the cart and
		// prints out _addItem for each 
		<? foreach($order_details['products'] as $row): ?>
			_gaq.push(['_addItem',
			'<?=$order_details['session_id'] ?>',           // order ID - necessary to associate item with transaction
			'<?=$row['code'] ?>',           // SKU/code - required
			'<?=addcslashes($row['name'], "'") ?>',        // product name
			'<?=addcslashes($row['category'], "'") ?>',   // category or variation
			'<?=number_format($row['price'], 2, '.', '') ?>',          // unit price - required
			'<?=number_format($row['quantity'], 0, '.', '') ?>'               // quantity - required
			]);
		<? endforeach; ?>
		_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == '<?=str_replace('//', '', $config['protocol']) ?>' ? 'https://ssl' : 'http://www') + '.googleanalytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	} catch(err) {}
</script>