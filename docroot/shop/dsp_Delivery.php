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
	});
/* ]]> */
</script>
<article id="innerShop">
<?
	$content_area = $elems->qry_ContentArea(4);
	echo $content_area['description'];
?>
</article>
<article id="shopContent">
	<div class="splitTwo clearfix">
		<h3>Choose an existing delivery address</h3>
	<?
		$addresses = array();
		foreach($delivery as $index=>$row)
			$addresses[$index] = '
				<form action="'.$config['dir'].'delivery?act=chooseAddress" method="post">
					<input type="hidden" name="address_id" value="'.$row['id'].'" />
					<address>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</address>
					<address>'.htmlentities($row['line1'], ENT_NOQUOTES, 'UTF-8').'</address>
					<address>'.htmlentities($row['line2'], ENT_NOQUOTES, 'UTF-8').'</address>
					<address>'.htmlentities($row['line3'], ENT_NOQUOTES, 'UTF-8').'</address>
					<address>'.htmlentities($row['line4'], ENT_NOQUOTES, 'UTF-8').'</address>
					<address>'.htmlentities($row['postcode'], ENT_NOQUOTES, 'UTF-8').'</address>
					<address>'.htmlentities($row['country'], ENT_NOQUOTES, 'UTF-8').'</address>
					<input type="submit" value="Choose this address" class="grayButton" />
				</form>
			';
		$delivery = array();
		for($i=0;$i<count($addresses);$i+=2)
		{
			if(isset($addresses[$i+1]))
				$delivery[] = '<div class="deliveryAddress secondRow">'.$addresses[$i+1].'</div>';
			$delivery[] = '<div class="deliveryAddress">'.$addresses[$i].'</div>';
		}
		echo implode('', $delivery);
	?>
	</div>

	<hr />

	<form id="quickCheckout" action="<?=$config['dir'] ?>delivery?act=saveAddress" method="post" class="splitTwo">
		<h3>or add a new one</h3>
		<p class="row">
			<span><input type="text" value="Full Name" placeholder="Full Name" id="name" name="name" tabindex="1" /></span>
			<span><input type="text" value="Email" placeholder="Email" id="email" name="email" tabindex="7" /></span>
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="Address Line 1" placeholder="Address Line 1" id="line1" name="line1" tabindex="2" /></span>
			<span><input type="text" value="Telephone" placeholder="Telephone" id="phone" name="phone" tabindex="8" /></span>
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="Address Line 2" placeholder="Address Line 2" id="line2" name="line2" tabindex="3" /></span>						
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="Address Line 3" placeholder="Address Line 3" id="line3" name="line3" tabindex="4" /></span>						
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="Address Line 4" placeholder="Address Line 4" id="line4" name="line4" tabindex="5" /></span>						
			<br clear="all"/>
		</p>
		<p class="row">
			<span><input type="text" value="Postcode / Zip" placeholder="Postcode / Zip" id="postcode" name="postcode" tabindex="6" /></span>
			<br clear="all"/>
		</p>
		<ul class="selector row" style="margin-bottom: 16px;">
			<li style="width: 250px;">
				<span><a style="width: 244px; border: 1px solid #8A8B8C" class="arrowed validate" href="#" tabindex="7">Country</a></span>
				<br clear="all"/>
				<dl>
				<?
					while($row = $countries->FetchRow())
						echo '<dd><a href="#" rel="'.$row['id'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></dd>';
				?>
				</dl>
				<input type="hidden" id="country_id" name="country_id" value="" />
			</li>
		</ul>
		<br clear="all"/>
		<p class="row">
			<input type="submit" value="Use this address" placeholder="Use this address" class="grayButton" tabindex="9" />
		</p>
	</form>
	<br/>
	<br/>
	<br/>
	<br/>
</article>