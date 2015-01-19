<h1>Basket</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.cart&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Gift card</a></li>
			<li><a href="#tabs-2">Carrier bag</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="gift_voucher_start">Start price</label>
				<input type="text" id="gift_voucher_start" name="gift_voucher_start" value="<?=price($gift_voucher_start['value']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="gift_voucher_increment_value">Increment value</label>
				<input type="text" id="gift_voucher_increment_value" name="gift_voucher_increment_value" value="<?=price($gift_voucher_increment_value['value']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="gift_voucher_increment_count">Increment count</label>
				<input type="text" id="gift_voucher_increment_count" name="gift_voucher_increment_count" value="<?=$gift_voucher_increment_count['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="gift_voucher_increment_visible">Visible in basket</label>
				<input type="checkbox" id="gift_voucher_increment_visible" name="gift_voucher_increment_visible" value="1" <? if($gift_voucher_increment_visible['value']+0): ?>checked="checked"<? endif; ?> />
			</div>
		</div>
		<div id="tabs-2">
			<div class="form-field clearfix">
				<label for="packing">Price</label>
				<input type="text" id="packing" name="packing" value="<?=price($packing['value']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="packing_visible">Visible in basket</label>
				<input type="checkbox" id="packing_visible" name="packing_visible" value="1" <? if($packing_visible['value']+0): ?>checked="checked"<? endif; ?> />
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.start"><span>Cancel</span></a>
	</div>
</form>