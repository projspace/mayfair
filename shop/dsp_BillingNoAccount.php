<script src="<?=$config['dir'] ?>VLib/js/validator.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#quickCheckout input:text').removeClass('error').next('label.error').hide();
		$('#quickCheckout #country_id').siblings('span').find('.arrowed').removeClass('error').next('label.error').hide();
		
		var defaults = {
			'name':'Full Name'
			,'email':'Email'
			,'line1':'Address Line 1'
			,'line2':'Address Line 2'
			,'line3':'Address Line 3'
			,'line4':'Address Line 4'
			,'phone':'Telephone'
			,'postcode':'Postcode / Zip'
		};
		for(var id in defaults)
		{
			if($('#'+id).val() == defaults[id])
				$('#'+id).val('');
		}
		
		var validation = new Validator(function(errors){
			var error = '';
			for(i=0;i<errors.length;i++)
			{
				var input;
				if($(errors[i].dom).attr('id') == 'country_id')
					input = $(errors[i].dom).siblings('span').find('.arrowed');
				else
					input = $(errors[i].dom);
					
				input.addClass('error');
				var label = input.next('label.error');
				if(label.length)
					label.text(errors[i].errorMsg).show();
				else
					input.after('<label class="error">'+errors[i].errorMsg+'</label>');
			}
		});

		validation.addField('name','Full Name','required');
		validation.addField('email','Email','required|email');
		validation.addField('line1','Address Line 1','required');
		validation.addField('line2','Address Line 2','required');
		validation.addField('phone','Telephone','required|phone');
		validation.addField('postcode','Postcode / Zip','required');
		validation.addField('country_id','Country','required|integer');
		
		if(!validation.validate())
		{
			for(var id in defaults)
				if($('#'+id).val() == '')
					$('#'+id).val(defaults[id]);
			return false;
		}

		return true;
	}
	
	$(document).ready(function(){
		$('#quickCheckout').submit(validateFRM);
		
		$('#same_delivery').click(function(){
			if($(this).is(':checked'))
			{
				$('#name').val('<?=addcslashes($session->session->fields['delivery_name'], "'") ?>');
				$('#email').val('<?=addcslashes($session->session->fields['delivery_email'], "'") ?>');
				$('#line1').val('<?=addcslashes($session->session->fields['delivery_line1'], "'") ?>');
				$('#line2').val('<?=addcslashes($session->session->fields['delivery_line2'], "'") ?>');
				$('#line3').val('<?=addcslashes($session->session->fields['delivery_line3'], "'") ?>');
				$('#line4').val('<?=addcslashes($session->session->fields['delivery_line4'], "'") ?>');
				$('#phone').val('<?=addcslashes($session->session->fields['delivery_phone'], "'") ?>');
				$('#postcode').val('<?=addcslashes($session->session->fields['delivery_postcode'], "'") ?>');
				$('#country_id').val('<?=$session->session->fields['delivery_country_id']+0 ?>');
				$('#country').text($('.selector dd a[rel="<?=$session->session->fields['delivery_country_id']+0 ?>"]').text());
			}
		});
	});
/* ]]> */
</script>
<article id="innerShop">
<?
	$content_area = $elems->qry_ContentArea(5);
	echo $content_area['description'];
?>
</article>
<article id="shopContent">
	<form id="quickCheckout" action="<?=$config['dir'] ?>billing?act=useAddress" method="post" class="splitTwo">
		<!--<h3>No account? quick checkout here</h3>
		<p>You don't need an account to buy - you can register at the end if you like. Just fill in the details below</p>-->
		<h3>Billing Address</h3>
		<p class="row">
			<input type="checkbox" id="same_delivery" />
			Same as delivery address
		</p>
		<p class="row">
			<span><input type="text" value="<?=$session->session->fields['billing_name']?$session->session->fields['billing_name']:'Full Name' ?>" placeholder="Full Name" id="name" name="name" tabindex="1" /></span>
			<span><input type="text" value="<?=$session->session->fields['billing_email']?$session->session->fields['billing_email']:'Email' ?>" placeholder="Email" id="email" name="email" tabindex="8" /></span>
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="<?=$session->session->fields['billing_line1']?$session->session->fields['billing_line1']:'Address Line 1' ?>" placeholder="Address Line 1" id="line1" name="line1" tabindex="2" /></span>
			<span><input type="text" value="<?=$session->session->fields['billing_phone']?$session->session->fields['billing_phone']:'Telephone' ?>" placeholder="Telephone" id="phone" name="phone" tabindex="9" /></span>
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="<?=$session->session->fields['billing_line2']?$session->session->fields['billing_line2']:'Address Line 2' ?>" placeholder="Address Line 2" id="line2" name="line2" tabindex="3" /></span>
			<input type="submit" name="submit" value="pay now" class="redDoubleArrow floatRight" tabindex="8" />
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="<?=$session->session->fields['billing_line3']?$session->session->fields['billing_line3']:'Address Line 3' ?>" placeholder="Address Line 3" id="line3" name="line3" tabindex="4" /></span>
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="<?=$session->session->fields['billing_line4']?$session->session->fields['billing_line4']:'Address Line 4' ?>" placeholder="Address Line 4" id="line4" name="line4" tabindex="5" /></span>
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="<?=$session->session->fields['billing_postcode']?$session->session->fields['billing_postcode']:'Postcode / Zip' ?>" placeholder="Postcode / Zip" id="postcode" name="postcode" tabindex="6" /></span>
			<br clear="all"/>
		</p>
		<ul class="selector row" style="margin-bottom: 16px;">
			<li style="width: 250px;">
				<?
					$selected = array('value'=>'', 'name'=>'Country');
					$options = '';
					while($row = $countries->FetchRow())
					{
						if($session->session->fields['billing_country_id'] == $row['id'])
							$selected = array('value'=>$row['id'], 'name'=>htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8'));
						$options .= '<dd><a href="#" rel="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></dd>';
					}
				?>
				<span><a style="width: 244px; border: 1px solid #8A8B8C" class="arrowed validate" href="#" tabindex="7" id="country"><?=$selected['name'] ?></a></span>
				<br clear="all"/>
				<dl><?=$options ?></dl>
				<input type="hidden" id="country_id" name="country_id" value="<?=$selected['value'] ?>" />
			</li>
		</ul>
		<br clear="all"/>
	</form>
	<br/>
	<br/>
	<br/>
	<br/>
</article>