<h1>Edit Shipping Country</h1>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editCountry&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Country Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="area_id">Area:</label>				
				<select id="area_id" name="area_id">
					<?
						while($area=$areas->FetchRow())
						{
							echo "<option value=\"{$area['id']}\"";
							if($country['area_id']==$area['id'])
								echo " selected=\"selected\"";
							echo ">{$area['name']}</option>\n";
						}
					?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="name">Name:</label>				
				<input type="text" id="name" name="name" value="<?= $country['name']; ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="default">Default:</label>				
				<input type="checkbox" id="default" name="default" value="1" <? if($country['default']): ?>checked="checked"<? endif; ?> />
			</div>
			<!--<div class="form-field clearfix">
				<label for="price">Price per 100g($)</label>
				<input type="text" id="price" name="price" value="<?= price($country['price']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="minimal_price">Minimal Price ($)</label>
				<input type="text" id="minimal_price" name="minimal_price" value="<?= price($country['minimal_price']) ?>" />
			</div>-->
		</div>
	</div>
	
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Save" />
			<input type="hidden" name="country_id" value="<?=$country['id'] ?>" />
		</span>
	</div>

</form>