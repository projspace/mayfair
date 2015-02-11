<?
	$is_original = is_file($config['path'].'images/product/original/'.$image['id'].'.'.$image['imagetype']);
?>
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
				,'editProduct'
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
<h1>Edit Product Images</h1>

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Upload Image</a></li>
			<? if($is_original): ?><li><a href="#tabs-2">Crop Image</a></li><? endif; ?>
		</ul>
		<div id="tabs-1">
			<form id="frmImages" method="post" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editProduct&act=editCroppedImages">
				<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id'] ?>"/>
				<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id'] ?>"/>
				<input type="hidden" name="image_id" value="<?=$image['id'] ?>"/>

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
									</div>';
								$count++;
							}
					}
				?>

				<div class="tab-panel-buttons clearfix">
					<span class="button button-small submit">
						<input class="submit" type="submit" value="Submit" />
					</span>
				</div>
			</form>
		</div>
		<? if($is_original): ?>
		<div id="tabs-2">
		<? 
			$count = 0;
			foreach(array('all-products'/*,'landscape','portrait'*/) as $type)
			{
				//echo '<h2>'.ucwords(str_replace('-', ' ', $type)).'</h2>';
				foreach($config['size']['product'] as $key=>$size)
					if($key != 'original')
					{
						if($size['type'] != $type)
							continue;
							
						if($key != 'image')
							$img = $config['dir'].'images/product/'.$key.'/'.$image['id'].'.'.$image['imagetype'].'?time='.time();
						else
							$img = $config['dir'].'images/product/'.$image['id'].'.'.$image['imagetype'].'?time='.time();
							
						echo '
							<div class="form-field clearfix">
								<label for="image_'.$key.'">'.$size['description'].'<em>'.$size['x'].' x '.$size['y'].'</em></label>
								<a href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&act=editCroppedImage&type='.$key.'&image_id='.$image['id'].'&product_id='.$_REQUEST['product_id'].'&category_id='.$_REQUEST['category_id'].'"><img src="'.$img.'" /></a>
							</div>';
						$count++;
					}
			}
		?>
		</div>
		<? endif; ?>
	</div>