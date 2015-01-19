<link rel="stylesheet" href="<?=$config['dir'] ?>admin-assets/js/jquery.ui/css/mytheme/style.css"/>
<script type="text/javascript" src="<?=$config['dir'] ?>admin-assets/js/jquery.ui/jquery.ui.custom.js"></script>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var countries = [];
	<?
		foreach($areas as $area)
			echo 'countries['.$area['id'].']='.json_encode($area['countries']).";\n";
	?>
	var fields = [];
	fields[fields.length] = {'id':'address1', 'name':"Address 1", 'type':'required'};
	fields[fields.length] = {'id':'postcode', 'name':"Zip code", 'type':'required'};
	fields[fields.length] = {'id':'area_id', 'name':"Country", 'type':'required'};
	fields[fields.length] = {'id':'country_id', 'name':"State", 'type':'required'};
	fields[fields.length] = {'id':'delivery_after', 'name':"Deliver after", 'type':'required'};
	
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

		if(!ret)
			return false;

		return true;
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
		$('#delivery_after').datepicker({
			dateFormat: 'dd/mm/yy'
			,minDate: <?= GIFT_DAYS_ADVANCE+0 ?>
		});
		$('#area_id').change(function(){
			var area_id = parseInt($(this).val());
			if(!isNaN(area_id) && countries[area_id])
				$('#country_id').selectBox('options', countries[area_id]);
			else
				$('#country_id').selectBox('options', {'':'Please select'});
			$('#country_id').selectBox('control').data('selectBox-options').addClass('selectBox-options-std-form');
			if(area_id == <?=disp($_REQUEST['area_id'], $_SESSION['gift_setup']['area_id'])+0 ?>)
				$('#country_id').selectBox('value', <?=disp($_REQUEST['country_id'], $_SESSION['gift_setup']['country_id'])+0 ?>);
		}).change();
		$('#address1, #postcode, #area_id, #country_id, #delivery_after').keyup(validateInput).change(validateInput);
	});
/* ]]> */
</script>
<div id="content-wrapper">
	<article id="fitting-guide">
		<header class="content-box"><h1>Gift Registry / Setup</h1></header>
		<section class="content-box">
			<form method="post" action="" class="std-form inner" id="frm" style="width: auto;">
				<input type="hidden" name="is_post" value="1"/>
				<?=$validator->displayMessage() ?>
				<p>Please give us the details on where we should deliver your gifts. <a href="<?= $config['dir'] ?>/shipping-policy" target="_blank">More delivery info here</a></p>
				<fieldset>
					<div class="row">
						<label for="address1">Address 1</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['address1'], $_SESSION['gift_setup']['address1']) ?>" name="address1" id="address1" />
					</div>
					<div class="row">
						<label for="address2">Address 2</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['address2'], $_SESSION['gift_setup']['address2']) ?>" name="address2" id="address2" />
					</div>
					<div class="row">
						<label for="address3">Address 3</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['address3'], $_SESSION['gift_setup']['address3']) ?>" name="address3" id="address3" />
					</div>
					<div class="row">
						<label for="postcode">Zip code</label>
						<input type="text" class="text" value="<?=disp($_REQUEST['postcode'], $_SESSION['gift_setup']['postcode']) ?>" name="postcode" id="postcode" />
					</div>
					<div class="row">
						<label for="area_id">Country</label>
						<select name="area_id" id="area_id">
							<option value="">Please select</option>
						<?
							foreach($areas as $row)
								if($row['id'] == disp($_REQUEST['area_id'], $_SESSION['gift_setup']['area_id']))
									echo '<option value="'.$row['id'].'" selected="selected">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
								else
									echo '<option value="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</option>';
						?>
						</select>
					</div>
					<div class="row">
						<label for="country_id">State</label>
						<select name="country_id" id="country_id">
							<option value="">Please select</option>
						</select>
					</div>
					<div class="row">
						<label for="delivery_after">Deliver after</label>
						<input type="date" class="text" value="<?=disp($_REQUEST['delivery_after'], $_SESSION['gift_setup']['delivery_after']) ?>" name="delivery_after" id="delivery_after" />
					</div>
				</fieldset>
				<div class="submit"><a href="#" class="btn-red submit">Next &gt;</a></div>
			</form>
		</section>
	</article>
</div>