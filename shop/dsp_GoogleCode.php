<!-- Google Code for purchase http Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1001153664;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "yx99CKjqnAMQgMmx3QM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1001153664/?value=0&amp;label=yx99CKjqnAMQgMmx3QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Analytics Ecommerce Tag Code Implementation -->
<script type="text/javascript">
    try{
        var _gaq = _gaq || [];
        //_gaq.push(['_setAccount', 'UA-33040780-1']);
        _gaq.push(['_setAccount', 'UA-40212976-1']);
        //_gaq.push(['_setDomainName', '.blochworld.com']);
		_gaq.push(['_setAllowLinker', true]);
        _gaq.push(['_trackPageview']);
        _gaq.push(['_addTrans',
            '<?=$order_details['session_id'] ?>',            // order ID - required
            '<?=addcslashes($config['company'], "'") ?>',  // affiliation or store name
            '<?=number_format($order_details['total']+$order_details['shipping']+$order_details['packing']+$order_details['tax'], 2, '.', '') ?>',           // total - required
            '<?=number_format($order_details['tax'], 2, '.', '') ?>',            // tax
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
            ga.src = ('https:' == '<?=str_replace('//', '', $config['protocol']) ?>' ? 'https://ssl' : 'http://www') + '.google-analytics.com/u/ga_debug.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    } catch(err) {}
</script>