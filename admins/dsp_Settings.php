<h1>System Configuration</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.settings&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="vat">VAT</label>
				<input type="text" id="vat" name="vat" value="<?=$vat['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="from">From</label>
				<input type="text" id="from" name="from" value="<?=$from['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="meta_title">Meta title</label>
				<input type="text" id="meta_title" name="meta_title" value="<?=$meta_title['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="meta_keywords">Meta keywords</label>
				<input type="text" id="meta_keywords" name="meta_keywords" value="<?=$meta_keywords['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="meta_description">Meta description</label>
				<textarea id="meta_description" name="meta_description"><?=$meta_description['value'] ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="fb_code">Facebook code</label>
				<textarea id="fb_code" name="fb_code"><?=$fb_code['value'] ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="fb_meta">Facebook metadata</label>
				<textarea id="fb_meta" name="fb_meta"><?=$fb_meta['value'] ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="google_category_id">Default Google Category</label>
				<select id="google_category_id" name="google_category_id">
				<?
					while($row = $google_categories->FetchRow())
					{
						if($row['id'] == $google_category_id['value'])
							echo '<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';
						else
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
					}
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="postcode_search_distance">Zip search treshold</label>
				<input type="text" id="postcode_search_distance" name="postcode_search_distance" value="<?=$postcode_search_distance['value'] ?>" />&nbsp;miles
			</div>
			<div class="form-field clearfix">
				<label for="postcode_search_results">Zip search results no.</label>
				<input type="text" id="postcode_search_results" name="postcode_search_results" value="<?=$postcode_search_results['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="cron_orders_period">Order cron days</label>
				<input type="text" id="cron_orders_period" name="cron_orders_period" value="<?=$cron_orders_period['value'] ?>" />&nbsp;ago
			</div>
			<div class="form-field clearfix">
				<label for="cron_orders_distance">Order cron treshold</label>
				<input type="text" id="cron_orders_distance" name="cron_orders_distance" value="<?=$cron_orders_distance['value'] ?>" />&nbsp;miles
			</div>
			<div class="form-field clearfix">
				<label for="cron_orders_commission">Order cron commission</label>
				<input type="text" id="cron_orders_commission" name="cron_orders_commission" value="<?=$cron_orders_commission['value'] ?>" />%
			</div>
			<div class="form-field clearfix">
				<label for="product_options">Product options</label>
				<select id="product_options" name="product_options">
					<option value="upc_only" <? if($product_options['value'] == 'upc_only'): ?> selected="selected"<? endif; ?>>UPC codes only</option>
					<option value="upc_ean" <? if($product_options['value'] == 'upc_ean'): ?> selected="selected"<? endif; ?>>UPC & EAN codes</option>
				</select>
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