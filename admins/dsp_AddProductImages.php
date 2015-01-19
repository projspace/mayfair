<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/plugins/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/swfupload_handlers.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var swfu_zip;
	
	$(document).ready(function(){
		swfu_zip = new SWFUpload({
			// Backend settings
			upload_url: "<?= $config['dir'] ?>admins/act_UploadFile.php",
			file_post_name: "document",

			// Flash file settings
			file_size_limit : "100 MB",
			file_types : "*.zip",			// or you could use something like: "*.doc;*.docx;*.pdf",
			file_types_description : "ZIP Files",
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
			button_placeholder_id : "zip_spanButtonPlaceholder",
			button_width: 61,
			button_height: 22,
			
			// Flash Settings
			flash_url : "<?=$config['dir'] ?>lib/swfupload/Flash/swfupload.swf",

			custom_settings : {
				progress_target : "zip_uploadProgress",
				hidden_input : "zip_file_id",
				input : "zip_file",
				form : "frmImages",
				upload_successful : false,
				file_queued: false
			},
			
			// Debug settings
			debug: false
		});
		
		$('#frmImages').submit(function(){
			try {
				var zip_stats = swfu_zip.getStats();
				if(zip_stats.files_queued !== 0)
				{
					swfu_zip.startUpload();
					return false;
				}
			} catch (e) {
			}
		});
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('select.color').change(function(){
			postback(
				this
				,'addProduct'
				,['category_id','image_id','product_id','act','color_id']
				,[<?=$_REQUEST['category_id']+0 ?>,$(this).attr('image_id'),<?=$_REQUEST['product_id']+0 ?>,'colorImage',$(this).val()]
			);
			return false;
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

<form id="postback" method="post" action="none"></form>
<h1>Add Product Images</h1>

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Current Images</a></li>
			<li><a href="#tabs-2">Upload Image & Crop</a></li>
			<li><a href="#tabs-6">Upload Image No Crop</a></li>
			<li><a href="#tabs-3">360&deg; View Images</a></li>
			<!--<li><a href="#tabs-4">Upload 360&deg; View Images</a></li>-->
			<li><a href="#tabs-5">Upload 360&deg; ZIP</a></li>
		</ul>
		<div id="tabs-1">
			<?
				if($images->RecordCount()===0)
					echo "<div class=\"form-field clearfix\"><strong>None</strong></div>";
				else
				{
					while($row=$images->FetchRow())
					{
						echo "<div class=\"form-field clearfix\">
								<a style=\"margin-right: 10px;\" class=\"button button-grey\" href=\"#\" title=\"Delete\" onclick=\"return postbackConf(
									this
									,'addProduct'
									,['category_id','imageid','product_id','act']
									,[{$_REQUEST['category_id']},{$row['id']},{$_REQUEST['product_id']},'removeImage']
									,'delete'
									,'image')\"><span style=\"width: auto;\">Delete</span></a>
								<a style=\"margin-right: 10px;\" class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.addProduct&amp;act=reuploadImage&amp;image_id={$row['id']}&amp;product_id={$_REQUEST['product_id']}&amp;category_id={$_REQUEST['category_id']}\"><span style=\"width: auto;\">Re-upload</span></a>
								<select class=\"color\" style=\"width: auto;\" image_id=\"{$row['id']}\">";
									echo '<option value="">Not color related</option>';
						foreach($colors as $color)
							if($color['id'] == $row['color_id'])
								echo '<option value="'.$color['id'].'" selected="selected">'.$color['code'].' - '.$color['name'].'</option>';
							else
								echo '<option value="'.$color['id'].'">'.$color['code'].' - '.$color['name'].'</option>';
						echo "</select>
								<img src=\"{$config['dir']}images/product/medium/{$row['id']}.{$row['imagetype']}\" />
							</div>";
					}
				}
			?>
		</div>
		<div id="tabs-2">
			<form id="frmImages" method="post" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addProduct&act=addImage">
				<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id'] ?>"/>
				<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id'] ?>"/>
				<input type="hidden" id="screen_width" name="screen_width" value="950" />

				<!--<div class="form-field clearfix">
					<span class="button button-small" style="margin-bottom:5px">
						<input type="button" onclick="javascript: return addFileRow(this, 'image');" value="Upload More" />
					</span>
				</div>
				-->
				<div class="form-field clearfix">
					<div id="files_image">
						<label for="image_0">Select Image</label>
						<input type="file" id="image_0" name="image[]">
						<span class="button button-small submit" style="width: auto; float: right;">
							<input class="submit" type="submit" value="Submit" />
						</span>
						<br />
					</div>
				</div>
			</form>
		</div>
		<div id="tabs-6">
			<form id="frmImages" method="post" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addProduct&act=addCroppedImages">
				<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id'] ?>"/>
				<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id'] ?>"/>
				
				<? 
					$count = 0;
					foreach(array('all-products'/*,'landscape','portrait'*/) as $type)
					{
						//echo '<h2>'.ucwords(str_replace('-', ' ', $type)).'</h2>';
						foreach($image_sizes as $key=>$size)
							if($key != 'original')
							{
								if($size['type'] != $type)
									continue;
								echo '
									<div class="form-field clearfix">
                                        <label for="image_'.$key.'">'.$size['description'].'<em>'.$size['x'].' x '.$size['y'].'</em></label>
                                        <input type="file" id="image_'.$key.'" name="cropped['.$key.']">
                                        '.(($count == count($image_sizes)-1)?'<span class="button button-small submit" style="width: auto; float: right;">
                                            <input class="submit" type="submit" value="Submit" />
                                        </span>':'').'
									</div>';
								$count++;
							}
					}
				?>
			</form>
		</div>
		<div id="tabs-3">
			<?
				if($images_360->RecordCount()===0)
					echo "<div class=\"form-field clearfix\"><strong>None</strong></div>";
				else
				{
					while($row=$images_360->FetchRow())
					{
						echo "<div class=\"form-field clearfix\">
								<a style=\"margin-right: 10px;\" class=\"button button-grey\" href=\"#\" title=\"Delete\" onclick=\"return postbackConf(
									this
									,'addProduct'
									,['category_id','imageid','product_id','act']
									,[{$_REQUEST['category_id']},{$row['id']},{$_REQUEST['product_id']},'remove360Image']
									,'delete'
									,'image')\"><span>Delete</span></a>
								<img src=\"{$config['dir']}images/product/360_view/{$row['id']}.{$row['image_type']}\" />
							</div>";
					}
				}
			?>
		</div>
		<!--<div id="tabs-4">
			<div class="form-field clearfix">
				<span class="button button-small" style="margin-bottom:5px">
					<input type="button" onclick="javascript: return addFileRow(this, '360_view');" value="Upload More" />
				</span>
			</div>
			<div class="form-field clearfix">
				<div id="files_360_view">
					<label for="360_view_0">Select Image</label>
					<input type="file" id="360_view_0" name="360_view[]"><br />
				</div>
			</div>
		</div>-->
		<div id="tabs-5">
			<form method="post" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addProduct&act=addImage">
				<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id'] ?>"/>
				<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id'] ?>"/>
				<input type="hidden" id="screen_width" name="screen_width" value="950" />

				<div class="form-field clearfix">
					<label for="audio">Zip file</label>
					<div>
						<input type="hidden" name="zip_file_id" id="zip_file_id" value="" />
						<input type="text" class="text upload" id="zip_file" disabled="true"/>
						<div class="btnContainer"><span id="zip_spanButtonPlaceholder"></span></div>
						<span class="button button-small submit" style="width: auto;">
							<input class="submit" type="submit" value="Submit" />
						</span>
						<br />
						
						<div id="zip_uploadProgress" class="uploadProgress">
							<div class="progress_bar">&nbsp;</div>
							<div class="upload_message">0%</div>
						</div>
					</div><br />
					<p>Rules:</p>
					<ul>
						<li>only the images in the root of the zip arhive will be imported</li>
						<li>only the following image formats will be imported: jpg, png, gif</li>
						<li>only the images that do not exceed 600x600 will be imported</li>
						<li>images must have a number,based on which they will be sorted (eg: img123.jpg)</li>
						<li>images must NOT have multiple numbers(eg: img-360-123.jpg); this will cause random ordering/filtering</li>
					</ul>
				</div>
			</form>
		</div>
	</div>

	<div class="tab-panel-buttons clearfix">
		<a class="button button-grey" style="float: right;" href="<?= $config['dir'] ?>index.php?fuseaction=admin.products&amp;category_id=<?=$_REQUEST['category_id'] ?>"><span>I'm Finished</span></a>
	</div>