<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/plugins/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/swfupload_handlers.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var swfu_video;
	
	$(document).ready(function(){
		swfu_video = new SWFUpload({
			// Backend settings
			upload_url: "<?= $config['dir'] ?>admins/act_UploadFile.php",
			file_post_name: "document",

			// Flash file settings
			file_size_limit : "100 MB",
			file_types : "*.mp4",			// or you could use something like: "*.doc;*.docx;*.pdf",
			file_types_description : "Video",
			file_upload_limit : "1",
			file_queue_limit : "1",

			// Event handler settings
			swfupload_loaded_handler : swfUploadLoaded,
			
			file_dialog_start_handler: fileDialogStart,
			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			
			upload_start_handler : uploadStart,	// I could do some client/JavaScript validation here, but I don't need to.
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,

			// Button Settings
			button_image_url : "<?=$config['dir'] ?>images/XPButtonUploadText_61x22.png",
			button_placeholder_id : "video_spanButtonPlaceholder",
			button_width: 61,
			button_height: 22,
			
			// Flash Settings
			flash_url : "<?=$config['dir'] ?>lib/swfupload/Flash/swfupload.swf",

			custom_settings : {
				progress_target : "video_uploadProgress",
				hidden_input : "video_file_id",
				input : "video_file",
				form : "frmCategory",
				upload_successful : false,
				file_queued: false
			},
			
			// Debug settings
			debug: false
		});
		
		$('#frmCategory').submit(function(){
			try {
				var video_stats = swfu_video.getStats();
				
				var stop = false;
				if(video_stats.files_queued !== 0)
				{
					swfu_video.startUpload();
					stop = true;
				}
				if(stop)
					return false;
			} catch (e) {
			}
		});
	});
/* ]]> */
</script>
<style type="text/css">
/* <![CDATA[ */
	.btnContainer { float: left; margin: 1px 0 0.5em 4px; width: 70px; }
	.upload { float: left; margin: 0.6em 0pt; width: 250px !important; }
	.uploadProgress { display: none; height: 17px; margin: 0; padding: 0; position: relative; }
	.uploadProgress .progress_bar { z-index: 998; width: 0; position: absolute; top: 0; left: 0; height: 17px; background: transparent url("<?=$config['dir'] ?>images/upload_progress.gif") repeat-x;  }
	.uploadProgress .upload_message { z-index: 999; width: 100%; position: absolute; top: 0; left: 0; height: 17px; text-align: center; color: grey; }
/* ]]> */
</style>

<h1>Edit Category</h1>
<form id="frmCategory" enctype="multipart/form-data" method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editCategory&amp;act=save" <?= $wysiwyg->form(); ?>>


	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<? if(!$category['link_category_id']+0): ?>
			<li><a href="#tabs-2">Description</a></li>
			<li><a href="#tabs-3">Restrictions</a></li>
			<li><a href="#tabs-4">Fields</a></li>
			<li><a href="#tabs-5">META Tags</a></li>
			<? if(!$child_count): ?><li><a href="#tabs-6">Fitting Guide</a></li><? endif; ?>
			<? if(!$child_count): ?><li><a href="#tabs-7">Delivery Details</a></li><? endif; ?>
			<? endif; ?>
			<? if($category['parent_id'] == 1 && !$category['no_landing_page']): ?>
			<li><a href="#tabs-8">Landing page</a></li>
			<? endif; ?>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="<?= $category['name']; ?>" />
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
						if($row['id'] == $category['link_category_id'])
							echo '<option value="'.$row['id'].'" selected="selected">'.implode(' &gt; ', $name).'</option>';
						else
							echo '<option value="'.$row['id'].'">'.implode(' &gt; ', $name).'</option>';
					}
				?>
				</select>
			</div>
			<? if(!$category['link_category_id']+0): ?>
			<!--<div class="form-field clearfix">
				<label for="custom_search">Show Custom Search?</label>
				<input type="checkbox" id="custom_search" name="custom_search" <? if($category['custom_search']==1) echo " checked=\"checked\""; ?> />
			</div>-->
			<div class="form-field clearfix">
				<label for="google_category_id">Google Category</label>
				<select id="google_category_id" name="google_category_id">
					<option value="">Please Select</option>
				<?
					while($row = $google_categories->FetchRow())
					{
						if($row['id'] == $category['google_category_id'])
							echo '<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';
						else
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
					}
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="childord">Child Categories</label>
				<select name="childord">
					<option value="0"<? if($category['childord']==0) echo " selected"; ?>>Order Alphabetically</option>
					<option value="1"<? if($category['childord']==1) echo " selected"; ?>>Order Manually</option>
				</select />
			</div>
			<div class="form-field clearfix">
				<label for="productord">Product Order</label>
				<select name="productord">
					<option value="price_desc" <?=($category['productord']=="price_desc")?'selected="selected"':'' ?>>Price High To Low</option>
					<option value="price_asc" <?=($category['productord']=="price_asc")?'selected="selected"':'' ?>>Price Low To High</option>
					<option value="newest" <?=($category['productord']=="newest")?'selected="selected"':'' ?>>Newest</option>
					<option value="manual" <?=($category['productord']=="manual")?'selected="selected"':'' ?>>Manual</option>
				</select>
			</div>
			<? if(!$child_count): ?>
            <input type="hidden" name="listing_type" value="<?=$category['listing_type'] ?>" />
			<!--<div class="form-field clearfix">
				<label for="listing_type">Product listing image</label>
				<select name="listing_type">
					<option value="default" <?=($category['listing_type']=="default")?'selected="selected"':'' ?>>Default</option>
					<option value="horizontal" <?=($category['listing_type']=="horizontal")?'selected="selected"':'' ?>>Horizontal</option>
					<option value="vertical" <?=($category['listing_type']=="vertical")?'selected="selected"':'' ?>>Vertical</option>
				</select>
			</div>-->
			<? endif; ?>
			<div class="form-field clearfix">
				<label for="discount">Category Discount</label>
				<input type="text" id="discount" name="discount" value="<?= $category['discount'] ?>" /> %
			</div>
			<div class="form-field clearfix">
				<label for="discount_trigger">Discount Trigger</label>
				<input type="text" id="discount_trigger" name="discount_trigger" value="<?= $category['discount_trigger'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="buy_3_cheapest_free">Buy three items and the cheapest is free</label>
				<input type="checkbox" id="buy_3_cheapest_free" name="buy_3_cheapest_free" value="1"<? if($category['buy_3_cheapest_free']==1) echo " checked=\"checked\""; ?>/>
			</div>
			<? if(!$child_count): ?>
			<div class="form-field clearfix">
				<label for="fitting_guide">Sidebar &gt; Fitting Guide</label>
				<input type="checkbox" id="fitting_guide" name="fitting_guide" value="1"<? if($category['fitting_guide']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="exclude_discounts">Exclude from all discounts</label>
				<input type="checkbox" id="exclude_discounts" name="exclude_discounts" value="1"<? if($category['exclude_discounts']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="content_visible">Show description</label>
				<input type="checkbox" id="content_visible" name="content_visible" value="1"<? if($category['content_visible']==1) echo " checked=\"checked\""; ?> />
			</div>
			<? endif; ?>
            <div class="form-field clearfix">
				<label for="hidden">Hide category</label>
				<input type="checkbox" id="hidden" name="hidden" value="1"<? if($category['hidden']==1) echo " checked=\"checked\""; ?> />
			</div>
			<? if($category['main_category']): ?>
			<div class="form-field clearfix">
				<label for="hidden_new_products">Hide 'New products' subcategory</label>
				<input type="checkbox" id="hidden_new_products" name="hidden_new_products" value="1"<? if($category['hidden_new_products']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="hidden_clearance">Hide 'Special/Clearance' subcategory</label>
				<input type="checkbox" id="hidden_clearance" name="hidden_clearance" value="1"<? if($category['hidden_clearance']==1) echo " checked=\"checked\""; ?> />
			</div>
			<? endif; ?>
			<!--<? if($category['parent_id'] == 1): ?>
			<div class="form-field clearfix">
				<label for="no_landing_page">Switch off landing page</label>
				<input type="checkbox" id="no_landing_page" name="no_landing_page" value="1"<? if($category['no_landing_page']==1) echo " checked=\"checked\""; ?> />
			</div>
			<? endif; ?>
			<div class="form-field clearfix">
				<label for="color">Category Color</label>
				<input type="text" id="color" name="color" value="<?= $category['color'] ?>" />
			</div>-->
			<div class="form-field clearfix">
				<label for="image">Category Image<em>Width 373px</em></label>
				<input type="file" id="image" name="image" /><br />
				<?
					if($category['imagetype']!="")
					{
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/category/thumbs/{$category['id']}.{$category['imagetype']}\" /><br />";
						echo "<label for=\"delete\">Delete Image</label>
							<input class=\"blank\" type=\"checkbox\" name=\"delete\" />\n";
					}
				?>
			</div>
            <div class="form-field clearfix">
				<label for="box_image">Box Image<em>281x281</em></label>
				<input type="file" id="box_image" name="box_image" /><br />
				<?
					if($category['box_imagetype']!="")
					{
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/category/box_{$category['id']}.{$category['box_imagetype']}\" /><br />";
						echo "<label for=\"delete\">Delete Image</label>
							<input class=\"blank\" type=\"checkbox\" name=\"box_delete\" />\n";
					}
				?>
			</div>
            <? else: ?>
            <div class="form-field clearfix">
				<label for="hidden">Hide category</label>
				<input type="checkbox" id="hidden" name="hidden" value="1"<? if($category['hidden']==1) echo " checked=\"checked\""; ?> />
			</div>
			<? endif; ?>
		</div>
		<? if(!$category['link_category_id']+0): ?>
		<div id="tabs-2">
			<?= $wysiwyg->editor($category['content']); ?>
		</div>
		<div id="tabs-3">
			<p>Category should be hidden in the following countries:</p>
			<?
				$count=0;
				while($row=$areas->FetchRow())
				{
					echo "<div class=\"form-field clearfix\"><label for=\"area_{$count}\">{$row['name']}</label>
							<input type=\"checkbox\" id=\"area_{$count}\" name=\"area[]\" value=\"{$row['id']}\"";
					if($row['restriction_id']!="")
						echo " checked=\"checked\"";
					echo " /></div>";
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
				<?
					$count=0;
					foreach($fields as $field)
					{
						echo "
							<tr id=\"field_row_{$count}\">
								<td id=\"field_cell1_{$count}\"><img id=\"field_up_{$count}\" onclick=\"moveFieldRowUp({$count});\" src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"/\\\" title=\"Up\" align=\"top\" /><img id=\"field_down_{$count}\" onclick=\"moveFieldRowDown({$count});\" src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"\\/\" title=\"Down\" align=\"top\" /></td>
								<td id=\"field_cell2_{$count}\"><input class=\"fieldName\" type=\"text\" id=\"field_name_{$count}\" name=\"shopfield[name][]\" value=\"{$field}\" /></td>
								<td id=\"field_cell3_{$count}\"><img id=\"field_del_{$count}\" onclick=\"removeFieldRow({$count});\" src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"X\" title=\"Delete\" align=\"top\" /></td>
							</tr>
						";
						$count++;
					}
				?>
			</table>
		</div>
		<div id="tabs-5">
			<div class="form-field clearfix">
				<label for="">META Title</label>
				<input type="text" id="" name="meta_title" value="<?= $category['meta_title'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="">META Description</label>
				<textarea id="" name="meta_description" rows="3" cols="40"><?= $category['meta_description'] ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="">META Keywords</label>
				<textarea id="" name="meta_keywords" rows="3" cols="40"><?= $category['meta_keywords'] ?></textarea>
			</div>
		</div>
		<? if(!$child_count): ?>
		<div id="tabs-6">
			<script language="javascript" type="text/javascript">
			/* <![CDATA[ */
				function select_fittings(){
					$('#fittings td').css('background-color','transparent');
					
					var selectors = new Array();
					$('#fittings .column').each(function(i){
						if($(this).is(':checked'))
							selectors[selectors.length] = 'td:eq('+i+')';
					});

					if(selectors.length)
						selectors = selectors.join(",");
					else
						selectors = 'td';
					$('#fittings .guide:checked').each(function(i){
						$(this).parent().parent().find(selectors).css('background-color','#ffcc00');
					});
				}
				$(document).ready(function(){
					$('#fittings :checkbox').click(select_fittings);
					select_fittings();
				});
			/* ]]> */
			</script>
			<style type="text/css">
			/* <![CDATA[ */
				#fittings th, #fittings td { padding: 4px; }
			/* ]]> */
			</style>
			<div id="fittings" style="overflow: scroll;">
				<table>
					<tr>
						<th></th><th></th>
					<?
						for($j=1;$j<=$fitting_count['column_count'];$j++)
						{
							if($category_fitting_guide_columns[$fitting_guides_columns[$j]['column_id']])
								$checked = 'checked="checked"';
							else
								$checked = '';
								
							echo '<td><input type="checkbox" class="column" name="column_ids[]" value="'.$fitting_guides_columns[$j]['column_id'].'" '.$checked.'/></td>';
						}
					?>
					</tr>
				<?
					for($i=1;$i<=$fitting_count['row_count'];$i++)
					{
						if($category_fitting_guides[$fitting_guides_rows[$i]['row_id']])
							$checked = 'checked="checked"';
						else
							$checked = '';
							
						echo '<tr><th><input type="checkbox" class="guide" name="guide_ids[]" value="'.$fitting_guides_rows[$i]['row_id'].'" '.$checked.'/></th><th>'.$fitting_guides_rows[$i]['row_name'].'</th>';
						for($j=1;$j<=$fitting_count['column_count'];$j++)
							echo '<td>'.$fitting_guides[$i.','.$j]['size'].'</td>';
						echo '</tr>';
					}
				?>
				</table>
			</div>
			<div class="form-field clearfix">
				<label for="fitting_pdf_visible">Use PDF instead of chart</label>
				<input type="checkbox" id="fitting_pdf_visible" name="fitting_pdf_visible" value="1" <?=$category['fitting_pdf_visible']?'checked="checked"':'' ?> />
			</div>
			<div class="form-field clearfix">
				<label for="fitting_pdf">PDF Guide</label>
				<input type="file" id="fitting_pdf" name="fitting_pdf"/>
				<?
					if($category['fitting_pdf'])
					{
						echo "<label>&nbsp;</label><a style=\"float: left; margin-bottom: 10px;\" href=\"{$config['dir']}downloads/fitting_pdf/{$category['id']}.pdf\">Download</a><br />";
						echo "
							<label for=\"fitting_pdf_delete\">Delete PDF</label>
							<input class=\"nb\" type=\"checkbox\" id=\"fitting_pdf_delete\" name=\"fitting_pdf_delete\" />";
					}
				?>
			</div>
		</div>
		<div id="tabs-7">
			<?= $wysiwyg->editor($category['delivery']); ?>
		</div>
		<? endif; ?>
		<? endif; ?>
		<? if($category['parent_id'] == 1): ?>
		<div id="tabs-8">
			<div class="form-field clearfix">
				<label for="video">Video mp4 only</label>
				<div>
					<input type="hidden" name="video_file_id" id="video_file_id" value="" />
					<input type="text" class="text upload" id="video_file" disabled="true"/>
					<div class="btnContainer"><span id="video_spanButtonPlaceholder"></span></div><br />
					
					<div id="video_uploadProgress" class="uploadProgress">
						<div class="progress_bar">&nbsp;</div>
						<div class="upload_message">0%</div>
					</div>
				</div><br />
				<!--<label for="landing_video">Video mp4 only</label>
				<input type="file" id="landing_video" name="landing_video" /><br />-->
				<?
					if($category['landing_video_type']!="")
						echo "<label>&nbsp;</label><input type=\"checkbox\" name=\"delete_landing_video\" value=\"1\"> Delete | <a href=\"{$config['dir']}downloads/category/landing_{$category['id']}.{$category['landing_video_type']}\">View</a>";
				?>
			</div>
			<div class="form-field clearfix">
				<label for="landing_image">Image 740 x 493</label>
				<input type="file" id="landing_image" name="landing_image" /><br />
				<?
					if($category['landing_image_type']!="")
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/category/landing_{$category['id']}.{$category['landing_image_type']}\" /><br />";
				?>
			</div>
		</div>
		<? endif; ?>
	</div>

	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Save" />
		</span>
		<input type="hidden" name="parent_id" value="<?=$category['parent_id'] ?>" />
		<input type="hidden" name="category_id" value="<?=$category['id'] ?>" />		
	</div>

</form>

