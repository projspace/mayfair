<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<h1>Add Shipping Country</h1>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addCountry&act=add">
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
							if($area['id'] == $_REQUEST['area_id'])
								echo "<option value=\"{$area['id']}\" selected=\"selected\">{$area['name']}</option>\n";
							else
								echo "<option value=\"{$area['id']}\">{$area['name']}</option>\n";
					?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="name">Name:</label>				
				<input type="text" id="name" name="name" />
			</div>
			<div class="form-field clearfix">
				<label for="default">Default:</label>				
				<input type="checkbox" id="default" name="default" value="1" />
			</div>
			<!--<div class="form-field clearfix">
				<label for="price">Price per 100g($)</label>
				<input type="text" id="price" name="price" value="<?= price(0) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="minimal_price">Minimal Price ($)</label>
				<input type="text" id="minimal_price" name="minimal_price" value="<?= price(0) ?>" />
			</div>-->
		</div>
	</div>
	
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
	</div>

</form>