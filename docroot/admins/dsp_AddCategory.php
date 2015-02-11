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
<h1>Add Category</h1>
<form enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addCategory&amp;act=add" <?= $wysiwyg->form(); ?>>


<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Description</a></li>
			<li><a href="#tabs-3">Restrictions</a></li>
			<li><a href="#tabs-4">Fields</a></li>
			<li><a href="#tabs-5">META Tags</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" />
			</div>
			<div class="form-field clearfix">
				<label for="link_category_id">Reference link</label>
				<select id="link_category_id" name="link_category_id">
					<option value="">Please Select</option>
				<?
					while($row = $categories->FetchRow())
					{
						$name = array();
						foreach((array)unserialize($row['trail']) as $key=>$item)
						{
							if($key < 2)
								continue;
							$name[] = $item['name'];
						}
						//$name[] = $row['name'];
						echo '<option value="'.$row['id'].'">'.implode(' &gt; ', $name).'</option>';
					}
				?>
				</select>
			</div>
			<!--<div class="form-field clearfix">
				<label for="custom_search">Show Custom Search?</label>
				<input type="checkbox" id="custom_search" name="custom_search" />
			</div>-->
			<div class="form-field clearfix">
				<label for="childord">Child Categories</label>
				<select name="childord">
					<option value="0">Order Alphabetically</option>
					<option value="1">Order Manually</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="productord">Product Order</label>
				<select name="productord">
					<option value="price_desc">Price High To Low</option>
					<option value="price_asc">Price Low To High</option>
					<option value="newest">Newest</option>
					<option value="manual">Manual</option>
				</select>
			</div>
            <input type="hidden" name="listing_type" value="default" />
			<!--<div class="form-field clearfix">
				<label for="listing_type">Product listing image</label>
				<select name="listing_type">
					<option value="default">Default</option>
					<option value="horizontal">Horizontal</option>
					<option value="vertical">Vertical</option>
				</select>
			</div>-->
			<div class="form-field clearfix">
				<label for="discount">Category Discount</label>
				<input type="text" id="discount" name="discount" /> %
			</div>
			<div class="form-field clearfix">
				<label for="discount_trigger">Discount Trigger</label>
				<input type="text" id="discount_trigger" name="discount_trigger" />
			</div>
			<div class="form-field clearfix">
				<label for="buy_3_cheapest_free">Buy three items and the cheapest is free</label>
				<input type="checkbox" id="buy_3_cheapest_free" name="buy_3_cheapest_free" value="1" />
			</div>
			<div class="form-field clearfix">
				<label for="fitting_guide">Sidebar &gt; Fitting Guide</label>
				<input type="checkbox" id="fitting_guide" name="fitting_guide" value="1"/>
			</div>
			<div class="form-field clearfix">
				<label for="exclude_discounts">Exclude from all discounts</label>
				<input type="checkbox" id="exclude_discounts" name="exclude_discounts" value="1" />
			</div>
			<div class="form-field clearfix">
				<label for="content_visible">Show description</label>
				<input type="checkbox" id="content_visible" name="content_visible" value="1" checked="checked" />
			</div>
            <div class="form-field clearfix">
				<label for="hidden">Hide category</label>
				<input type="checkbox" id="hidden" name="hidden" value="1" />
			</div>
			<!--<div class="form-field clearfix">
				<label for="color">Category Color</label>
				<input type="text" id="color" name="color" />
			</div>-->
			<div class="form-field clearfix">
				<label for="image">Category Image<em>Width 373px</em></label>
				<input type="file" id="image" name="image" />
			</div>
            <div class="form-field clearfix">
				<label for="box_image">Box Image<em>281x281</em></label>
				<input type="file" id="box_image" name="box_image" /><br />
			</div>
		</div>
		<div id="tabs-2">
			<?= $wysiwyg->editor(); ?>
		</div>
		<div id="tabs-3">
			<p>Category should be hidden in the following regions:</p>
			<?
				$count=0;
				while($row=$areas->FetchRow())
				{
					echo "<div class=\"form-field clearfix\"><label for=\"area_{$count}\">{$row['name']}</label>
							<input type=\"checkbox\" id=\"area_{$count}\" name=\"area[]\" value=\"{$row['id']}\" /></div>";

					$count++;
				}
			?>
		</div>
		<div id="tabs-4">
			<table class="values nocheck" id="fields">
				<tr>
					<th class="fit">&nbsp;</th>
					<th class="fieldName">Field Name</th>
					<th><img onclick="addFieldRow();" src="<?= $config['dir'] ?>images/admin/add.png" width="16" height="16" alt="+" title="Add" /></th>
				</tr>
				<tr id="field_row_0">
					<td id="field_cell1_0"><img id="field_up_0" onclick="moveFieldRowUp(0);" src="<?= $config['dir'] ?>images/admin/up.png" width="16" height="16" alt="/\" title="Up" align="top" /><img id="field_down_0" onclick="moveFieldRowDown(0);" src="<?= $config['dir'] ?>images/admin/down.png" width="16" height="16" alt="\/" title="Down" align="top" /></td>
					<td id="field_cell2_0"><input class="fieldName" type="text" id="field_name_0" name="shopfield[name][]" /></td>
					<td id="field_cell3_0"><img id="field_del_0" onclick="removeFieldRow(0);" src="<?= $config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="X" title="Delete" align="top" /></td>
				</tr>
			</table>
		</div>
		<div id="tabs-5">
			<div class="form-field clearfix">
				<label for="">META Title</label>
				<input type="text" id="meta_title" name="meta_title" />
			</div>
			<div class="form-field clearfix">
				<label for="">META Description</label>
				<textarea id="meta_description" name="meta_description" rows="3" cols="40"></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="">META Keywords</label>
				<textarea id="meta_keywords" name="meta_keywords" rows="3" cols="40"></textarea>
			</div>
		</div>
	</div>

	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<input type="hidden" name="parent_id" value="<?=$_REQUEST['parent_id'] ?>" />
	</div>
</form>
