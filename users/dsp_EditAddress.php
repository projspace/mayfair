<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#fancybox-content', parent.document).css('height', ($('#page-wrapper').height()+20)+'px');
		parent.$.fancybox.center(true);
	});
/* ]]> */
</script>
<link rel="stylesheet" href="<?=$config['dir'] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
<script type="text/javascript" src="<?=$config['dir'] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var fields = [];
	fields[fields.length] = {'id':'name', 'name':"Name", 'type':'required|name'};
	fields[fields.length] = {'id':'email', 'name':"Email", 'type':'required|email'};
	fields[fields.length] = {'id':'phone', 'name':"Phone", 'type':'required|phone'};
	fields[fields.length] = {'id':'country_id', 'name':"Country", 'type':'required'};
	fields[fields.length] = {'id':'line1', 'name':"Line 1", 'type':'required'};
	fields[fields.length] = {'id':'postcode', 'name':"Postcode", 'type':'required|postcode'};
	
	function validateForm()
	{
		$('#frm input, #frm textarea').removeClass('error').removeClass('valid');
		$('#frm label.error, #frm label.valid').remove();
		
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+errors[i].errorMsg+'</label>');
			}
		});

		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);
		
		var ret = validation.validate();
		
		for(i=0;i<fields.length;i++)
			if(!$('#'+fields[i].id+' ~ label.error').length)
				$('#'+fields[i].id).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');

		return ret;
	}
	
	function validateInput()
	{
		var validation = new Validator(function(errors){});
		for(i=0;i<fields.length;i++)
			validation.addField(fields[i].id, fields[i].name, fields[i].type);
			
		var ret = validation.validateInput($(this).attr('id'));
		$(this).removeClass('error').removeClass('valid').find(' ~ label.error').remove();
		if(!ret.status)
			$(this).addClass('error').after('<label class="error" for="'+$(this).attr('id')+'" generated="true">'+ret.details.errorMsg+'</label>');
		else
			$(this).addClass('valid').after('<label class="error valid" for="'+$(this).attr('id')+'" generated="true"></label>');
	}
	
	$(document).ready(function(){
		$('#frm').submit(validateForm);
		
		$('#name, #email, #phone, #country_id, #line1, #postcode').keyup(validateInput).change(validateInput);
	});
/* ]]> */
</script>
<? if($_REQUEST['ajax']): ?>
<div class="overlay">
	<div class="header content-box"><h1>Edit address</h1></div>
	<div class="content-box">
<? else: ?>
<div id="content-wrapper">
	<article id="fitting-guide">
		<header class="content-box"><h1>Edit address</h1></header>
		<section class="content-box">
<? endif; ?>

	<form method="post" action="<?=$config['dir'] ?>account/editAddress/<?=$address['id'] ?>?act=save<? if(isset($_REQUEST['ajax'])): ?>&amp;ajax=1<? endif; ?>" style="padding: 30px 35px 70px;" class="std-form inner" id="frm" style="width: auto;">
		<?=$validator->displayMessage() ?>
		<fieldset>
			<div class="row">
				<label for="name">Name *</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['name'], $address['name']) ?>" name="name" id="name" />
			</div>
			<div class="row">
				<label for="email">Email *</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['email'], $address['email']) ?>" name="email" id="email" />
			</div>
			<div class="row">
				<label for="phone">Phone *</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['phone'], $address['phone']) ?>" name="phone" id="phone" />
			</div>
			<div class="row">
				<label for="country_id">Country *</label>
				<select name="country_id" id="country_id">
				<?
					while($row = $countries->FetchRow())
						if($row['id'] == disp($_REQUEST['country_id'], $address['country_id']))
							echo '<option value="'.$row['id'].'" selected="selected">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
						else
							echo '<option value="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
				?>
				</select>
			</div>
			<!--
			<div class="row">
				<label for="line1">Line 1 *</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['line1'], $address['line1']) ?>" name="line1" id="line1" />
			</div>
			<div class="row">
				<label for="line2">Line 2</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['line2'], $address['line2']) ?>" name="line2" id="line2" />
			</div>
			-->
			<div class="row">
				<label for="line1">Street *</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['line1'], $address['line1']) ?>" name="line1" id="line1" />
			</div>
			<div class="row">
				<label for="line3">City/Town</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['line3'], $address['line3']) ?>" name="line3" id="line3" />
			</div>
			<div class="row">
				<label for="line4">County</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['line4'], $address['line4']) ?>" name="line4" id="line4" />
			</div>
			<div class="row">
				<label for="postcode">Postcode *</label>
				<input type="text" class="text" value="<?=disp($_REQUEST['postcode'], $address['postcode']) ?>" name="postcode" id="postcode" />
			</div>
			<div class="row">
				<label for="billing">Billing</label>
				<input type="checkbox" value="1" name="billing" id="billing" <?=(disp($_REQUEST['billing'], $address['billing'])+0)?'checked="checked"':'' ?> />
			</div>
			<div class="row">
				<label for="delivery">Delivery</label>
				<input type="checkbox" value="1" name="delivery" id="delivery" <?=(disp($_REQUEST['delivery'], $address['delivery'])+0)?'checked="checked"':'' ?> />
			</div>
		</fieldset>
		<div class="submit"><a href="#" class="btn-red submit">Update</a></div>
	</form>
	
<? if($_REQUEST['ajax']): ?>
	</div>
</div>
<? else: ?>
		</section>
	</article>
</div>
<? endif; ?>