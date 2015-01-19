<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#fancybox-content', parent.document).css('height', ($('#page-wrapper').height()+20)+'px');
		parent.$.fancybox.center(true);
		
		$('#btn_close').click(function(){
			parent.$.fancybox.close();
		});
	});
/* ]]> */
</script>
<div class="overlay" id='fitting-guide-overlay'>
	<div class="header content-box">
		<h1>Write a review</h1>
	</div>
	<div class="content-box">
		<div id="review-content" class="clearfix">
			<form method="post" action="<?=$config['dir'] ?>addReview/<?=$product['id'] ?>?act=save" class="options revRight" id="frm">
				<? if($ok): ?>
					<p>Thank you for your review.</p>
				<? else: ?>
					<p>There was a problem whilst registering, please try again.</p>
				<? endif; ?>
				<p class="buttons"><a href="#" class="btn-red" id="btn_close">Close</a></p>
			</form>
			<div class="revLeft">
			<?
				$name = htmlentities($product['name'], ENT_NOQUOTES, 'UTF-8');
				if(trim($product['code']) != '')
					$name .= ' <em>'.htmlentities($product['code'], ENT_NOQUOTES, 'UTF-8').'</em>';
			?>
				<h1><?=$name ?></h1>
				<p class="price">Price <?=price($product['price']) ?></p>
			</div>
		</div>
	</div>
</div>