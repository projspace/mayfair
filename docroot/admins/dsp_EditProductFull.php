<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#add').click(add_tag);
		$('#new_tag').keydown(function(event){
			$(this).parent().parent().css('overflow', 'visible');
			if(event.keyCode == 13)
			{
				if(!$($('#autocompleteContainer .yui-ac-content').get(0)).is(':visible'))
					add_tag();
				return false;
			}
		});
		$('.remove_tag').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function add_tag(){
		var val = jQuery.trim($('#new_tag').val());
		if(val == '')
			return false;
		val = val.toLowerCase();
		var found = false;
		$('.hidden_tag').each(function(i){
			var hidden_val = jQuery.trim($(this).val());
			if(val == hidden_val)
				found = true;
		});
		if(found)
		{
			//alert('The tag "'+val+'" is already in the tag list.');
			$('#new_tag').val('');
			return false;
		}
		var last_id = $('tr:last', '#table_tags').attr('id');
		var next_id;
		if(last_id != undefined)
			next_id = parseInt(last_id) + 1;
		else
			next_id = 1;
		var row_class = (next_id % 2)?'light':'dark';
		$('#table_tags').append('<tr class="'+row_class+'" id="'+next_id+'"><td><input type="hidden" name="tags[]" value="'+val+'" class="hidden_tag" />'+val+'</td><td class="right"><a href="#" class="remove_tag"><img src="<?=$config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="Remove" /></a></td></tr>');
		$('.remove_tag').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
		$('#new_tag').val('');
		return false;
	}
/* ]]> */
</script>

<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#add_warning').click(add_warning);
		$('#message, #trigger').keydown(function(event){
			if(event.keyCode == 13)
			{
				add_warning();
				return false;
			}
		});
		$('.remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function validateWarning()
	{
		$('#message, #trigger').css('border', '1px solid #A7A6AA');
		
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).css('border', '1px solid #F00');
			}
		});

		validation.addField('message', "Message", 'required');
		validation.addField('trigger', "Trigger", 'integer');
		return validation.validate();
	}
	
	function add_warning(){
		var message = jQuery.trim($('#message').val());
		var trigger = jQuery.trim($('#trigger').val());
		if(trigger == '')
			trigger = -1;
		
		if(!validateWarning())
			return false;
			
		var found = false;
		$('.hidden_trigger').each(function(i){
			var hidden_val = jQuery.trim($(this).val());
			if(trigger == hidden_val)
				found = true;
		});
		if(found)
		{
			$('#message, #trigger').val('');
			return false;
		}
		var last_id = $.trim($('tr:last', '#table_warnings').attr('id'));
		var next_id;
		if(last_id != '')
			next_id = parseInt(last_id) + 1;
		else
			next_id = 1;
		var row_class = (next_id % 2)?'light':'dark';
		$('#table_warnings').append('<tr class="'+row_class+'" id="'+next_id+'"><td><input type="hidden" name="message[]" value="'+message+'" class="hidden_message" />'+message+'</td><td><input type="hidden" name="trigger[]" value="'+trigger+'" class="hidden_trigger" />'+((trigger >= 0)?trigger:'N/A')+'</td><td class="right"><a href="#" class="remove_row"><img src="<?=$config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="Remove" /></a></td></tr>');
		$('.remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
		$('#message, #trigger').val('');
		return false;
	}
/* ]]> */
</script>

<!-- YAHOO AUTOCOMPLETE -->

<!--CSS file (default YUI Sam Skin) -->
<link type="text/css" rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/autocomplete/assets/skins/sam/autocomplete.css">

<!-- Dependencies -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/datasource/datasource-min.js"></script>

<!-- OPTIONAL: Get (required only if using ScriptNodeDataSource) -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/get/get-min.js"></script>

<!-- OPTIONAL: Connection (required only if using XHRDataSource) -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/connection/connection-min.js"></script>

<!-- OPTIONAL: Animation (required only if enabling animation) -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/animation/animation-min.js"></script>

<!-- OPTIONAL: JSON (enables JSON validation) -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/json/json-min.js"></script>

<!-- Source file -->
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/autocomplete/autocomplete-min.js"></script>

<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/plugins/swfupload.swfobject.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>lib/swfupload/swfupload_handlers.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var swfu_audio;
	var swfu_video;
	
	$(document).ready(function(){
		/*swfu_audio = new SWFUpload({
			// Backend settings
			upload_url: "<?= $config['dir'] ?>admins/act_UploadFile.php",
			file_post_name: "document",

			// Flash file settings
			file_size_limit : "100 MB",
			file_types : "*.mp3;*.aac;*.m4a",			// or you could use something like: "*.doc;*.docx;*.pdf",
			file_types_description : "Audio",
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
			button_placeholder_id : "audio_spanButtonPlaceholder",
			button_width: 61,
			button_height: 22,
			
			// Flash Settings
			flash_url : "<?=$config['dir'] ?>lib/swfupload/Flash/swfupload.swf",

			custom_settings : {
				progress_target : "audio_uploadProgress",
				hidden_input : "audio_file_id",
				input : "audio_file",
				form : "frmProduct",
				upload_successful : false,
				file_queued: false
			},
			
			// Debug settings
			debug: false
		});
		*/
		swfu_video = new SWFUpload({
			// Backend settings
			upload_url: "<?= $config['dir'] ?>admins/act_UploadFile.php",
			file_post_name: "document",

			// Flash file settings
			file_size_limit : "100 MB",
			file_types : "*.flv;*.mp4",			// or you could use something like: "*.doc;*.docx;*.pdf",
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
				form : "frmProduct",
				upload_successful : false,
				file_queued: false
			},
			
			// Debug settings
			debug: false
		});
		
		$('#frmProduct').submit(function(){
			try {
				//var audio_stats = swfu_audio.getStats();
				var video_stats = swfu_video.getStats();
				
				var stop = false;
				/*if(audio_stats.files_queued !== 0)
				{
					swfu_audio.startUpload();
					stop = true;
				}*/
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

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#frmProduct input:text, #frmProduct input:file, #frmProduct textarea').removeClass('error').next('label.error').hide();
		$('.ui-tabs .ui-tabs-nav li a').css('color','#333333');
		var validation = new Validator(function(errors){
			var error = '';
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error');
				var label = $(errors[i].dom).next('label.error');
				if(label.length)
					label.text(errors[i].errorMsg).show();
				else
				{
					if($(errors[i].dom).attr('type') == 'file')
						$(errors[i].dom).after('<label class="error" style="width: 136px;">'+errors[i].errorMsg+'</label>');
					else
						$(errors[i].dom).after('<label class="error">'+errors[i].errorMsg+'</label>');
				}
				
				var tab_id = $(errors[i].dom).parents('.ui-tabs-panel').attr('id');
				$('.ui-tabs-nav a[href="#'+tab_id+'"]').css('color','#E64825');
			}
		});

		validation.addField('name','Name','required');
		validation.addField('guid','Unique Identifier','required');
		if($('#home_slider').is(':checked'))
		{
			validation.addField('slider_title','Slider Title','required');
			validation.addField('slider_description','Slider Description','required');
			validation.addField('slider_image','Slider Image','required');
		}
		
		if(!validation.validate())
			return false;

		var global_ret = true;
		$.ajax({
			async: false,
			url: '<?=$config['dir'] ?>ajax/act_ValidateProductGUID.php',
			type: 'get',
			dataType: 'json',
			data: 'product_id=<?=$product['id'] ?>&guid='+$('#guid').val(),
			success: function(json){
				if(!json.status)
				{
					global_ret = false;
					
					$('#guid').addClass('error');
					var label = $('#guid').next('label.error');
					if(label.length)
						label.text(json.message).show();
					else
						$('#guid').after('<label class="error">'+json.message+'</label>');
					
					var tab_id = $('#guid').parents('.ui-tabs-panel').attr('id');
					$('.ui-tabs-nav a[href="#'+tab_id+'"]').css('color','#E64825');
				}
			}
		});
			
		return global_ret;
	}
	
	$(document).ready(function(){
		$('#frmProduct').submit(validateFRM);
	});
/* ]]> */
</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('div.tabs').tabs({theme: 'Redmond'});
	});
/* ]]> */
</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#add_option').click(add_option);
		$('#upc_code, #ean_code, #size_id, #color_id, #width_id, #quantity, #price_differential').keydown(function(event){
			if(event.keyCode == 13)
			{
				add_option();
				return false;
			}
		});
		$('#table_options .remove_row').live('click', function(){
			$(this).parent().parent().remove();
			return false;
		});
        $('#table_options .edit_row').live('click',function(){
            var tr = $(this).closest('tr');
            $('#option_row_id').val(tr.attr('id'));
			$('#upc_code').val(tr.find('.hidden_upc_code').val());
			$('#ean_code').val(tr.find('.hidden_ean_code').val());
			$('#size_id').val(tr.find('.hidden_size_id').val());
			$('#width_id').val(tr.find('.hidden_width_id').val());
			$('#color_id').val(tr.find('.hidden_color_id').val());
			$('#quantity').val(tr.find('.hidden_quantity').val());
			$('#price_differential').val(tr.find('.hidden_price').val());

            $('#add_option').addClass('edit').find('span').text('Save');
			return false;
		});
	});

	function validateOption()
	{
		$('#upc_code, #size_id, #color_id, #width_id, #quantity, #price_differential').css('border', '1px solid #A7A6AA');

		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).css('border', '1px solid #F00');
			}
		});

		validation.addField('upc_code', "UPC Code", 'required');
		validation.addField('size_id', "Size", 'integer');
		validation.addField('color_id', "Color", 'integer');
		validation.addField('width_id', "Width", 'integer');
		validation.addField('quantity', "Quantity", 'integer');
		validation.addField('price_differential', "Price", 'float');
		return validation.validate();
	}

	function add_option(){
		var upc_code = jQuery.trim($('#upc_code').val());
		var ean_code = jQuery.trim($('#ean_code').val());
		var size_id = jQuery.trim($('#size_id').val());
		var width_id = jQuery.trim($('#width_id').val());
		var color_id = jQuery.trim($('#color_id').val());
		var quantity = jQuery.trim($('#quantity').val());
		var price = jQuery.trim($('#price_differential').val());

		if(!validateOption())
			return false;

		var size = $('#size_id').find('option[value="'+size_id+'"]').text();
		var width = $('#width_id').find('option[value="'+width_id+'"]').text();
		var color = $('#color_id').find('option[value="'+color_id+'"]').text();

		var found = false;
		$('.hidden_upc_code').each(function(i){
			var hidden_val = jQuery.trim($(this).val());
			if(upc_code == hidden_val)
				found = $(this).closest('tr').attr('id');

			if(
				size_id == jQuery.trim($(this).parent().parent().find('.hidden_size_id').val())
				&&
				width_id == jQuery.trim($(this).parent().parent().find('.hidden_width_id').val())
				&&
				color_id == jQuery.trim($(this).parent().parent().find('.hidden_color_id').val())
			)
				found = $(this).closest('tr').attr('id');
		});
		if(found && found != $('#option_row_id').val())
		{
			alert('Duplicate option. Please check the form and try again.');
			return false;
		}

		if($('#add_option').hasClass('edit'))
        {
            var tr = $('#table_options tr#'+$('#option_row_id').val());
            var val;

            tr.find('.hidden_upc_code').val(val = $('#upc_code').val()).siblings('span').text(val);
			tr.find('.hidden_ean_code').val(val = $('#ean_code').val()).siblings('span').text(val);
			tr.find('.hidden_size_id').val(val = $('#size_id').val()).siblings('span').text($('#size_id option[value="'+val+'"]').text());
			tr.find('.hidden_width_id').val(val = $('#width_id').val()).siblings('span').text($('#width_id option[value="'+val+'"]').text());
			tr.find('.hidden_color_id').val(val = $('#color_id').val()).siblings('span').text($('#color_id option[value="'+val+'"]').text());
			tr.find('.hidden_quantity').val(val = $('#quantity').val()).siblings('span').text(val);
			tr.find('.hidden_price').val(val = $('#price_differential').val()).siblings('span').text(val);
        }
        else
        {
            var last_id = $.trim($('tr:last', '#table_options').attr('id'));
            var next_id;
            if(last_id != '')
                next_id = parseInt(last_id) + 1;
            else
                next_id = 1;
            var row_class = (next_id % 2)?'light':'dark';
            $('#table_options').append('<tr class="'+row_class+'" id="'+next_id+'"><td><input type="hidden" name="upc_code[]" value="'+upc_code+'" class="hidden_upc_code" /><span>'+upc_code+'</span></td><td><input type="hidden" name="ean_code[]" value="'+ean_code+'" class="hidden_ean_code" /><span>'+ean_code+'</span></td><td><input type="hidden" name="size_id[]" value="'+size_id+'" class="hidden_size_id" /><span>'+size+'</span></td><td><input type="hidden" name="width_id[]" value="'+width_id+'" class="hidden_width_id" /><span>'+width+'</span></td><td><input type="hidden" name="color_id[]" value="'+color_id+'" class="hidden_color_id" /><span>'+color+'</span></td><td><input type="hidden" name="quantity[]" value="'+quantity+'" class="hidden_quantity" /><span>'+quantity+'</span></td><td><input type="hidden" name="price_differential[]" value="'+price+'" class="hidden_price" /><span>'+price+'</span></td><td class="right"><a href="#" class="remove_row"><img src="<?=$config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="Remove" /></a> <a href="#" class="edit_row"><img src="<?=$config['dir'] ?>images/admin/edit.png" width="16" height="16" alt="Edit" /></a></td></tr>');
        }

		$('#upc_code, #ean_code, #size_id, #color_id, #width_id').val('');
		$('#quantity, #price_differential').val('0');
        $('#add_option').removeClass('edit').find('span').text('Add');
        $('#option_row_id').val('');
		return false;
	}
/* ]]> */
</script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#name').keyup(function(){
			$('#guid').val(name2page($(this).val()));
		});
	});
/* ]]> */
</script>

<?
	$custom=unserialize($product['custom']);
	$shopopt=unserialize($product['options']);
?>
<h1>Edit Product - <?= $product['name'] ?></h1>
<form id="frmProduct" class="yui-skin-sam" enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editProduct&act=save" method="post" <?= $wysiwyg->form(); ?>>
	<input type="hidden" id="screen_width" name="screen_width" value="730" />
	
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<!--<input type="hidden" name="brand_id" value="1" />-->
			<div class="form-field clearfix">
				<label for="brand_id">Brand</label>
				<select id="brand_id" class="custom-skin" name="brand_id">
					<?
						$bkeys=$brands->GetKeys();
						while($brow=$brands->FetchRow())
						{
							echo "<option value=\"{$brow[$bkeys['shop_brands.id']]}\"";
							if($product['brand_id']==$brow[$bkeys['shop_brands.id']])
								echo " selected";
							echo ">{$brow[$bkeys['shop_brands.name']]}</option>";
						}
					?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="hidden">Visibility</label>
				<select id="hidden" name="hidden" class="custom-skin">
					<option value="0"<? if($product['hidden']==0) echo " selected=\"selected\""; ?>>Visible</option>
					<option value="1"<? if($product['hidden']==1) echo " selected=\"selected\""; ?>>Hidden</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="label">Label</label>
				<select id="label" name="label">
					<option value="none" <? if($product['label']=='none'):?>selected="selected"<? endif; ?>>None</option>
					<option value="new_product" <? if($product['label']=='new_product'):?>selected="selected"<? endif; ?>>New Product</option>
					<option value="best_seller" <? if($product['label']=='best_seller'):?>selected="selected"<? endif; ?>>Best Seller</option>
					<option value="on_sale" <? if($product['label']=='on_sale'):?>selected="selected"<? endif; ?>>On Sale</option>
					<option value="bloch_stars" <? if($product['label']=='bloch_stars'):?>selected="selected"<? endif; ?>>Bloch Stars</option>
				</select>
			</div>
			<!--<div class="form-field clearfix">
				<label for="gender">Gender</label>
				<select id="gender" name="gender">
					<option value="female" <? if($product['gender']=='female'):?>selected="selected"<? endif; ?>>Female</option>
					<option value="male" <? if($product['gender']=='male'):?>selected="selected"<? endif; ?>>Male</option>
					<option value="unisex" <? if($product['gender']=='unisex'):?>selected="selected"<? endif; ?>>Unisex</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="age">Age group</label>
				<select id="age" name="age">
					<option value="adult" <? if($product['age']=='adult'):?>selected="selected"<? endif; ?>>Adult</option>
					<option value="kids" <? if($product['age']=='kids'):?>selected="selected"<? endif; ?>>Kids</option>
				</select>
			</div>-->
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="<?= $product['name'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="guid">Product URL</label>
				<input type="text" id="guid" name="guid" value="<?= $product['guid'] ?>" />
			</div>
			<!--<div class="form-field clearfix">
				<label for="name">Style</label>
				<input type="text" id="code" name="code" value="<?= $product['code'] ?>" />
			</div>-->
			<div class="form-field clearfix">
				<label for="price">Price ($)</label>
				<input type="text" id="price" name="price" value="<?= price($product['price']) ?>" />
			</div>
			<!--<div class="form-field clearfix">
				<label for="price_old">Old Price ($)</label>
				<input type="text" id="price_old" name="price_old" value="<?= price($product['price_old']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="discount">Discount ($)</label>
				<input type="text" id="discount" name="discount" value="<?= price($product['discount']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="packing">Packing ($)</label>
				<input type="text" id="packing" name="packing" value="<?= price($product['packing']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="shipping">Shipping ($)</label>
				<input type="text" id="shipping" name="shipping" value="<?= price($product['shipping']) ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="no_shipping">No Shipping</label>
				<input type="checkbox" id="no_shipping" name="no_shipping" value="1"<? if($product['no_shipping']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label>Current Stock</label>
				<input type="text" id="stock" name="stock" value="<?= $product['stock']+0 ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="low_stock_trigger">Low Stock Trigger</label>
				<input type="text" id="low_stock_trigger" name="low_stock_trigger" value="<?= $product['low_stock_trigger']+0 ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="hide_stock_trigger">Hide Product on Stock Trigger</label>
				<input type="text" id="hide_stock_trigger" name="hide_stock_trigger" value="<?= $product['hide_stock_trigger']+0 ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="vat">Pick up only</label>
				<input type="checkbox" id="pick_up_only" name="pick_up_only" value="1"<? if($product['pick_up_only']==1) echo " checked=\"checked\""; ?>/>
			</div>
			<div class="form-field clearfix">
				<label for="vat">Add VAT</label>
				<input type="checkbox" id="vat" name="vat" value="1"<? if($product['vat']==1) echo " checked=\"checked\""; ?>/>
			</div>
			<div class="form-field clearfix">
				<label for="buy_1_get_1_free">Buy One / Get One Free</label>
				<input type="checkbox" id="buy_1_get_1_free" name="buy_1_get_1_free" value="1"<? if($product['buy_1_get_1_free']==1) echo " checked=\"checked\""; ?>/>
			</div>
			<div class="form-field clearfix">
				<label for="recent_productions">From Recent Productions</label>
				<input type="checkbox" id="recent_productions" name="recent_productions" value="1"<? if($product['recent_productions']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="product_search">Product Search</label>
				<input type="checkbox" id="product_search" name="product_search" value="1"<? if($product['product_search']==1) echo " checked=\"checked\""; ?> />
			</div>-->
			<div class="form-field clearfix">
				<label for="added">Date Added</label>
				<span><input type="text" class="calendar" id="added" name="added" value="<?=date('d/m/Y', strtotime($product['added'])) ?>" /></span>
			</div>
			<!--<div class="form-field clearfix">
				<label for="special">Special</label>
				<input type="checkbox" id="special" name="special" value="1"<? if($product['special']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="reviews">Reviews</label>
				<input type="checkbox" id="reviews" name="reviews" value="1"<? if($product['reviews']==1) echo " checked=\"checked\""; ?> />
			</div>-->
			<div class="form-field clearfix">
				<label for="alt_size">Use alternating size</label>
				<input type="checkbox" id="alt_size" name="alt_size" value="1"<? if($product['alt_size']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="exclude_discounts">Exclude from all discounts</label>
				<input type="checkbox" id="exclude_discounts" name="exclude_discounts" value="1"<? if($product['exclude_discounts']==1) echo " checked=\"checked\""; ?> />
			</div>
			<!--<div class="form-field clearfix">
				<label for="vat_exempt">VAT Exempt</label>
				<input type="checkbox" id="vat_exempt" name="vat_exempt" value="1"<? if($product['vat_exempt']==1) echo " checked=\"checked\""; ?>/>
			</div>-->
			<div class="form-field clearfix">
				<label for="flat_rate_shipping">Flat Rate Shipping</label>
				<input type="checkbox" id="flat_rate_shipping" name="flat_rate_shipping" value="1"<? if($product['flat_rate_shipping']==1) echo " checked=\"checked\""; ?>/>
			</div>
            <div class="form-field clearfix">
				<label for="hide_quick_view">Hide Quick View</label>
				<input type="checkbox" id="hide_quick_view" name="hide_quick_view" value="1"<? if($product['hide_quick_view']==1) echo " checked=\"checked\""; ?>/>
			</div>
            <div class="form-field clearfix">
				<label for="hide_add_cart">Hide Add to Cart</label>
				<input type="checkbox" id="hide_add_cart" name="hide_add_cart" value="1"<? if($product['hide_add_cart']==1) echo " checked=\"checked\""; ?>/>
			</div>
            <div class="form-field clearfix">
				<label for="hide_more_details">Hide More Details</label>
				<input type="checkbox" id="hide_more_details" name="hide_more_details" value="1"<? if($product['hide_more_details']==1) echo " checked=\"checked\""; ?>/>
			</div>
            <div class="form-field clearfix">
				<label for="hide_price">Hide Price</label>
				<input type="checkbox" id="hide_price" name="hide_price" value="1"<? if($product['hide_price']==1) echo " checked=\"checked\""; ?>/>
			</div>
            <div class="form-field clearfix">
				<label for="featured">Featured</label>
				<select id="featured" name="featured">
                <? for($i=0;$i<=4;$i++): ?>
					<option value="<?= $i ?>" <? if($product['featured']==$i):?>selected="selected"<? endif; ?>><?=$i?:'No'?></option>
				<? endfor; ?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="zoom">Use magic zoom</label>
				<select id="zoom" name="zoom">
					<option value="no" <? if($product['zoom']=='no'):?>selected="selected"<? endif; ?>>No</option>
					<option value="yes" <? if($product['zoom']=='yes'):?>selected="selected"<? endif; ?>>Yes</option>
					<!--<option value="portrait" <? if($product['zoom']=='portrait'):?>selected="selected"<? endif; ?>>Yes + Portrait image for clothing</option>-->
				</select>
			</div>
            <div class="form-field clearfix">
				<label for="warehouse">Warehouse</label>
				<select id="warehouse" name="warehouse">
					<option value="mayfair_house" <? if($product['warehouse']=='mayfair_house'):?>selected="selected"<? endif; ?>>Mayfairhouse</option>
					<option value="fulfillment_house" <? if($product['warehouse']=='fulfillment_house'):?>selected="selected"<? endif; ?>>Fulfillment House</option>
					<option value="brand" <? if($product['warehouse']=='brand'):?>selected="selected"<? endif; ?>>Brand</option>
				</select>
			</div>
			<!--<div class="form-field clearfix">
				<label for="image">Thumbnail Image</label>
				<input type="file" id="image" name="image" /><br />
				<?
					if($product['imagetype']!="")
					{
						echo "<label>&nbsp;</label><img src=\"{$config['dir']}images/product/medium/{$product['id']}.{$product['imagetype']}?time=".time()."\" /><br />";
						echo "
							<label for=\"delete\">Delete Image</label>
							<input class=\"nb\" type=\"checkbox\" id=\"delete\" name=\"delete\" />";
					}
				?>
			</div>-->
		</div>
		<br clear="all"/>
	</div>
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-2">Options</a></li>
		</ul>
		<div id="tabs-2">
            <input type="hidden" id="option_row_id" value="" />
			<div class="form-field clearfix">
				<label for="upc_code">UPC Code</label>
				<span><input type="text" class="text" id="upc_code" name="upc_code" value="" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="ean_code">EAN Code</label>
				<span><input type="text" class="text" id="ean_code" name="ean_code" value="" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="size_id">Size</label>
				<select id="size_id" name="size_id">
                    <option value="">None</option>
				<?
					while($row = $sizes->FetchRow())
						echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="width_id">Width</label>
				<select id="width_id" name="width_id">
					<option value="">None</option>
				<?
					while($row = $widths->FetchRow())
						echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="color_id">Color</label>
				<select id="color_id" name="color_id">
                    <option value="">None</option>
				<?
					while($row = $colors->FetchRow())
						echo '<option value="'.$row['id'].'">'.$row['code'].' - '.$row['name'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="quantity">Quantity</label>
				<span><input type="text" class="text" id="quantity" name="quantity" value="0" /></span>
			</div>
            <div class="form-field clearfix">
				<label for="price_differential">Price Differential</label>
				<span><input type="text" class="text" id="price_differential" name="price_differential" value="0" /></span>
				<a class="button button-small" style="margin-left: 10px;" href="#" id="add_option"><span style="width: auto; padding: 7px 15px 3px;">Add</span></a><br /><br />
			</div>
			<script language="javascript" type="text/javascript">
			/* <![CDATA[ */
				$(document).ready(function(){
					$('#table_options .sort').click(function(){
						if($(this).hasClass('desc'))
							var dir = 'desc';
						else
							var dir = 'asc';
							
						var index = $(this).parent().prevAll('th').length;
						var sort = [];
						$('#table_options tr td:nth-child('+(index+1)+')').each(function(i){
							var value = $.trim($(this).text());
							if(index == 4)
								value = parseInt(value);
							sort[sort.length] = {'index': i, 'value': value};
						});
						
						sort.sort(function(row1, row2){
							if(dir == 'desc')
							{
								var sort_up = -1;
								var sort_down = 1;
							}
							else
							{
								var sort_up = 1;
								var sort_down = -1;
							}
							return ((row1['value'] == row2['value']) ? 0 : ((row1['value'] > row2['value']) ? sort_up : sort_down));
						});
						
						var html = $('<table class="values nocheck" id="table_options"></table>');
						$('#table_options tr:first').clone(true,true).appendTo(html);
						html.find('.sort').removeClass('asc').removeClass('desc');
						html.find('.sort:eq('+index+')').addClass((dir == 'desc')?'asc':'desc');
						for(var i=0;i<sort.length;i++)
							$('#table_options tr:nth-child('+(sort[i].index+2)+')').clone(true,true).appendTo(html);
						$('#table_options').replaceWith(html);
						return false;
					})
				});
			/* ]]> */
			</script>
			<table class="values nocheck" id="table_options">
				<tr>
					<th class="sortable first"><a href="#" title="Sort on UPC Code" class="sort desc">UPC Code</a></th>
					<th class="sortable"><a href="#" title="Sort on EAN Code" class="sort desc">EAN Code</a></th>
					<th class="sortable"><a href="#" title="Sort on Size" class="sort">Size</a></th>
					<th class="sortable"><a href="#" title="Sort on Width" class="sort">Width</a></th>
					<th class="sortable"><a href="#" title="Sort on Color" class="sort">Color</a></th>
					<th class="sortable"><a href="#" title="Sort on Quantity" class="sort">Quantity</a></th>
					<th class="sortable last"><a href="#" title="Sort on Price" class="sort">Price</a></th>
					<th>&nbsp;</th>
				</tr>
				<?
					$next_id = 0;
					while($row=$product_options->FetchRow())
					{
						$next_id++;
						if($class=="light")
							$class="dark";
						else
							$class="light";

						echo "
						<tr class=\"$class\" id=\"$next_id\">
							<td>
								<input type=\"hidden\" name=\"saved_ids[]\" value=\"{$row['id']}\" />
								<input type=\"hidden\" name=\"saved_upc_code[]\" value=\"{$row['upc_code']}\" class=\"hidden_upc_code\" /> <span>{$row['upc_code']}</span>
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_ean_code[]\" value=\"{$row['ean_code']}\" class=\"hidden_ean_code\" /> <span>{$row['ean_code']}</span>
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_size_id[]\" value=\"{$row['size_id']}\" class=\"hidden_size_id\" /> <span>".((trim($row['size']) == '')?'None':$row['size'])."</span>
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_width_id[]\" value=\"{$row['width_id']}\" class=\"hidden_width_id\" /> <span>".((trim($row['width']) == '')?'None':$row['width'])."</span>
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_color_id[]\" value=\"{$row['color_id']}\" class=\"hidden_color_id\" /> <span>".((trim($row['color']) == '')?'None':$row['color'])."</span>
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_quantity[]\" value=\"{$row['quantity']}\" class=\"hidden_quantity\" /> <span>{$row['quantity']}</span>
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_price_differential[]\" value=\"{$row['price']}\" class=\"hidden_price\" /> <span>{$row['price']}</span>
							</td>
							<td class=\"right\">
								<a href=\"#\" class=\"remove_row\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></a>
								<a href=\"#\" class=\"edit_row\"><img src=\"{$config['dir']}images/admin/edit.png\" width=\"16\" height=\"16\" alt=\"Edit\" /></a>
							</td>
						</tr>";
					}
				?>
			</table>
		</div>
		<br clear="all"/>
	</div>
    <div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-21">Shipping</a></li>
		</ul>
		<div id="tabs-21">
			<div class="form-field clearfix">
				<label for="weight">Weight (grammes)</label>
				<input type="text" id="weight" name="weight" value="<?= $product['weight'] ?>" />
			</div>
            <div class="form-field clearfix">
				<label for="width">Width (inches)</label>
				<input type="text" id="width" name="width" value="<?= $product['width'] ?>" />
			</div>
            <div class="form-field clearfix">
				<label for="height">Height (inches)</label>
				<input type="text" id="height" name="height" value="<?= $product['height'] ?>" />
			</div>
            <div class="form-field clearfix">
				<label for="length">Length (inches)</label>
				<input type="text" id="length" name="length" value="<?= $product['length'] ?>" />
			</div>
		</div>
		<br clear="all"/>
	</div>
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-3">Description</a></li>
		</ul>
		<div id="tabs-3">
			<div class="form-field clearfix">
				<label>Description</label><br />
				<?= $wysiwyg->editor($product['description']); ?>
			</div>
			<div class="form-field clearfix">
				<label>Short Description</label><br />
				<?= $wysiwyg->editor($product['short_description']); ?>
			</div>
		</div>
		<br clear="all"/>
	</div>
	<!--<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-4">Restrictions</a></li>
		</ul>
		<div id="tabs-4">			
			<p>Product should be hidden in the following regions:</p>
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
		<br clear="all"/>
	</div>
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-5">Tags</a></li>
		</ul>
		<div id="tabs-5">
			<div class="form-field clearfix">
				<label for="new_tag">New tag</label>
				<div id="autoComplete" style="float: left;width: 230px; padding-bottom: 2em;">
					<input type="text" id="new_tag" name="new_tag" style="width: 200px;" /> <a href="#" style="margin-left: 210px;" id="add"><img src="<?=$config['dir'] ?>images/admin/add.png" width="16" height="16" alt="Add" /></a>
					<div id="autocompleteContainer" style="font-size: 120%; width: 202px;"></div>
				</div>
			</div>
			<script type="text/javascript">
			YAHOO.example.BasicRemote = function() {
				// Use an XHRDataSource
				var oDS = new YAHOO.util.XHRDataSource("<?=$config['dir'] ?>ajax/qry_ProductTags.php");
				// Set the responseType
				oDS.responseType = YAHOO.util.XHRDataSource.TYPE_TEXT;
				// Define the schema of the delimited results
				oDS.responseSchema = {
					recordDelim: "\n",
					fieldDelim: "\t"
				};
				// Enable caching
				oDS.maxCacheEntries = 20;

				// Instantiate the AutoComplete
				var oAC = new YAHOO.widget.AutoComplete("new_tag", "autocompleteContainer", oDS);
				
				return {
					oDS: oDS,
					oAC: oAC
				};
			}();
			</script>
			<br /><br />
			<table class="values" id="table_tags">
			<?
				$next_id = 0;
				while($row=$product_tags->FetchRow())
				{	
					$next_id++;
					if($class=="light")
						$class="dark";
					else
						$class="light";
						
					echo "
					<tr class=\"$class\" id=\"$next_id\">
						<td>
							<input type=\"hidden\" name=\"saved_tags[]\" value=\"{$row['id']}\" class=\"hidden_tag\" /> {$row['name']}
						</td>
						<td class=\"right\">
							<a href=\"#\" class=\"remove_tag\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></a>
						</td>
					</tr>";
				}
			?>
			</table>
		</div>
		<br clear="all"/>
	</div>-->
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-6">META Tags</a></li>
		</ul>
		<div id="tabs-6">
			<div class="form-field clearfix">
				<label for="meta_title">META Title</label>
				<input type="text" id="meta_title" name="meta_title" value="<?= $product['meta_title'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="meta_description">META Description</label>
				<textarea id="meta_description" name="meta_description" rows="3" cols="40"><?= $product['meta_description'] ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="meta_keywords">META Keywords</label>
				<textarea id="meta_keywords" name="meta_keywords" rows="3" cols="40"><?= $product['meta_keywords'] ?></textarea>
			</div>
		</div>
		<br clear="all"/>
	</div>
	<!--<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-7">Fitting Guide</a></li>
		</ul>
		<div id="tabs-7">
			<script language="javascript" type="text/javascript">
			/* <![CDATA[ */
				function select_fittings(){
					$('#fittings td').css('background-color','transparent');
					
					var selectors = new Array();
					$('#fittings .column').each(function(i){
						if($(this).is('[type="hidden"]') || $(this).is(':checked'))
							selectors[selectors.length] = 'td:eq('+i+')';
					});

					if(selectors.length)
						selectors = selectors.join(",");
					else
						selectors = 'td';
					$('#fittings .guide[value="1"]').each(function(i){
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
						<th></th>
					<?
						for($j=1;$j<=$fitting_count['column_count'];$j++)
						{
							if($category_fitting_guide_columns[$fitting_guides_columns[$j]['column_id']])
								$checked = '<input type="hidden" class="column" value="1"/>';
							else
								$checked = '<input type="checkbox" class="column" name="column_ids[]" value="'.$fitting_guides_columns[$j]['column_id'].'" '.($product_fitting_guide_columns[$fitting_guides_columns[$j]['column_id']]?'checked="checked"':'').'/>';
								
							echo '<td>'.$checked.'</td>';
						}
					?>
					</tr>
				<?
					for($i=1;$i<=$fitting_count['row_count'];$i++)
					{
						$checked = ($category_fitting_guides[$fitting_guides_rows[$i]['row_id']])?true:false;
							
						echo '<tr><th><input type="hidden" class="guide" value="'.($checked?1:0).'"/>'.$fitting_guides_rows[$i]['row_name'].'</th>';
						for($j=1;$j<=$fitting_count['column_count'];$j++)
							echo '<td>'.$fitting_guides[$i.','.$j]['size'].'</td>';
						echo '</tr>';
					}
				?>
				</table>
			</div>
		</div>
		<br clear="all"/>
	</div>-->
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-8">Filters</a></li>
		</ul>
		<div id="tabs-8">
			<?
				foreach($filters as $filter)
				{
					echo '<div class="form-field clearfix"><label for="filter_'.$filter['id'].'">'.$filter['name'].'</label>';
					echo '<select id="filter_'.$filter['id'].'" name="filter_ids[]" '.(($filter['type'] == 'multiple')?'multiple="multiple"':'').'>';
					if($filter['type'] == 'single')
						echo '<option value="">Please select</option>';
					foreach($filter['items'] as $row)
						if(isset($filter_ids[$row['id']]))
							echo '<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';
						else
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
					echo '</select></div>';
				}
			?>
		</div>
		<br clear="all"/>
	</div>
	<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-9">Media</a></li>
		</ul>
		<div id="tabs-9">
			<div class="form-field clearfix">
				<label for="video">Video file (flv,mp4 only)</label>
				<div>
					<input type="hidden" name="video_file_id" id="video_file_id" value="" />
					<input type="text" class="text upload" id="video_file" disabled="true"/>
					<div class="btnContainer"><span id="video_spanButtonPlaceholder"></span></div><br />
					
					<div id="video_uploadProgress" class="uploadProgress">
						<div class="progress_bar">&nbsp;</div>
						<div class="upload_message">0%</div>
					</div>
				</div><br />
				<? if($product['video_type']!=""): ?>
					<script language="javascript" type="text/javascript">
					/* <![CDATA[ */
						$(document).ready(function(){
							$("#video_play").click(function(){
								$.fancybox({
									'hideOnOverlayClick': false,
									'hideOnContentClick': false,
									'enableEscapeButton': false,
									'width'				: 620,
									'height'			: 400,
									'autoScale'     	: false,
									'transitionIn'		: 'none',
									'transitionOut'		: 'none',
									'type'				: 'iframe',
									'href'				: $(this).attr('href'),
									'scrolling'			: 'no'
								});
								return false;
							});
						});
					/* ]]> */
					</script>
					<label>&nbsp;</label>
					<a id="video_play" style="float: left" href="<?=$config['dir'] ?>index.php?fuseaction=shop.video&amp;product_id=<?=$product['id'] ?>">Preview</a>
					<a style="float: left" href="<?=$config['dir'] ?>downloads/product/video/<?=$product['id'].'.'.$product['video_type'] ?>">&nbsp;| Download</a>
					<span style="float: left">&nbsp;| Delete&nbsp;</span><input type="checkbox" name="video_delete" value="1"/><br />
				<? endif; ?>
			</div>
			<div class="form-field clearfix">
				<label for="360_view">360&deg; View</label>
				<input type="checkbox" id="360_view" name="360_view" value="1"<? if($product['360_view']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="facebook">Facebook Link</label>
				<input type="checkbox" id="facebook" name="facebook" value="1"<? if($product['facebook']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="twitter">Twitter Link</label>
				<input type="checkbox" id="twitter" name="twitter" value="1"<? if($product['twitter']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="google">Google Link</label>
				<input type="checkbox" id="twitter" name="google" value="1"<? if($product['google']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="pinterest">Pinterest Link</label>
				<input type="checkbox" id="twitter" name="pinterest" value="1"<? if($product['pinterest']==1) echo " checked=\"checked\""; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="pdf">PDF Document</label>
				<input type="file" id="pdf" name="pdf" /><br />
				<?
					if($product['pdf'])
					{
						echo "<label>&nbsp;</label><a href=\"{$config['dir']}downloads/product/pdf/{$product['id']}.pdf\">Download</a><br />";
						echo "
							<label for=\"pdf_delete\">Delete PDF</label>
							<input class=\"nb\" type=\"checkbox\" id=\"pdf_delete\" name=\"pdf_delete\" />";
					}
				?>
			</div>
		</div>
		<br clear="all"/>
	</div>
	<!--<div class="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-10">Warnings</a></li>
		</ul>
		<div id="tabs-10">
			<div class="form-field clearfix">
				<label for="message">Message</label>
				<textarea id="message" name="message"></textarea><br />
			</div>
			<div class="form-field clearfix">
				<label for="trigger">Trigger</label>
				<input type="text" id="trigger" name="trigger" value="" /> <a href="#" id="add_warning"><img src="<?=$config['dir'] ?>images/admin/add.png" width="16" height="16" alt="Add" /></a><br /><br />
			</div>
			<table class="values nocheck" id="table_warnings">
				<tr>
					<th>Message</th>
					<th>Trigger</th>
					<th>&nbsp;</th>
				</tr>
				<?
					$next_id = 0;
					while($row=$product_warnings->FetchRow())
					{	
						$next_id++;
						if($class=="light")
							$class="dark";
						else
							$class="light";
							
						echo "
						<tr class=\"$class\" id=\"$next_id\">
							<td>
								<input type=\"hidden\" name=\"saved_message[]\" value=\"{$row['message']}\" class=\"hidden_message\" /> {$row['message']}
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_trigger[]\" value=\"{$row['trigger']}\" class=\"hidden_trigger\" /> ".(($row['trigger'] >=0)?$row['trigger']:'N/A')."
							</td>
							<td class=\"right\">
								<a href=\"#\" class=\"remove_row\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></a>
							</td>
						</tr>";
					}
				?>
			</table>
		</div>
	</div>-->

	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.products&category_id=<?=$_REQUEST['category_id'] ?>"><span>Cancel</span></a>
		<input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
		<input type="hidden" name="category_id" value="<?= $_REQUEST['category_id'] ?>" />
	</div>
	

</form>
