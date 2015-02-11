<h1>Invoice</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.invoice&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="invoice_company">Company</label>
				<input type="text" id="invoice_company" name="invoice_company" value="<?=$invoice['company'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_address1">Address Line 1</label>
				<input type="text" id="invoice_address1" name="invoice_address1" value="<?=$invoice['address1'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_address2">Address Line 2</label>
				<input type="text" id="invoice_address2" name="invoice_address2" value="<?=$invoice['address2'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_address3">Address Line 3</label>
				<input type="text" id="invoice_address3" name="invoice_address3" value="<?=$invoice['address3'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_address4">Address Line 4</label>
				<input type="text" id="invoice_address4" name="invoice_address4" value="<?=$invoice['address4'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_phone">Phone</label>
				<input type="text" id="invoice_phone" name="invoice_phone" value="<?=$invoice['phone'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_fax">Fax</label>
				<input type="text" id="invoice_fax" name="invoice_fax" value="<?=$invoice['fax'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_email">Email</label>
				<input type="text" id="invoice_email" name="invoice_email" value="<?=$invoice['email'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_footer_left">Footer Left</label>
				<input type="text" id="invoice_footer_left" name="invoice_footer_left" value="<?=$invoice['footer_left'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="invoice_footer_right">Footer Right</label>
				<input type="text" id="invoice_footer_right" name="invoice_footer_right" value="<?=$invoice['footer_right'] ?>" />
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