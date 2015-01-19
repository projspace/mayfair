<div class="right">
	<form method="post" action="http://{$config.url}{$config.dir}index.php/fuseaction/shop.checkout/act/details">
		<input type="hidden" name="shopsid" value="{$params.session_id}" />
		<input type="hidden" name="country_id" value="{$params.country_id}" />
		<input class="submit" type="Submit" value="Continue" />
	</form>
</div>