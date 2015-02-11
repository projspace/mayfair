<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#add').click(add_price);
		$('#weight, #price').keydown(function(event){
			if(event.keyCode == 13)
			{
				add_price();
				return false;
			}
		});
		$('.remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function validatePrice()
	{
		$('#weight, #price').css('border', '1px solid #A7A6AA');
		
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).css('border', '1px solid #F00');
			}
		});

		validation.addField('weight', "Weight", 'integer|required');
		validation.addField('price', "Price", 'float|required');
		return validation.validate();
	}
	
	function add_price(){
		var weight = jQuery.trim($('#weight').val());
		var price = jQuery.trim($('#price').val());
		
		if(!validatePrice())
			return false;
			
		var found = false;
		$('.hidden_weight').each(function(i){
			var hidden_val = jQuery.trim($(this).val());
			if(weight == hidden_val)
				found = true;
		});
		if(found)
		{
			$('#weight, #price').val('');
			return false;
		}
		var last_id = $('tr:last', '#table_prices').attr('id');
		var next_id;
		if(last_id != undefined)
			next_id = parseInt(last_id) + 1;
		else
			next_id = 1;
		var row_class = (next_id % 2)?'light':'dark';
		$('#table_prices').append('<tr class="'+row_class+'" id="'+next_id+'"><td><input type="hidden" name="weight[]" value="'+weight+'" class="hidden_weight" />'+weight+'g</td><td><input type="hidden" name="price[]" value="'+price+'" class="hidden_price" />'+price+'$</td><td class="right"><a href="#" class="remove_row"><img src="<?=$config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="Remove" /></a></td></tr>');
		$('.remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
		$('#weight, #price').val('');
		return false;
	}
/* ]]> */
</script>

<h1>Edit Shipping Area</h1>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editArea&amp;act=save">
	<input type="hidden" name="area_id" value="<?=$area['id'] ?>"/>
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Prices</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>				
				<input type="text" id="name" name="name" value="<?=$area['name'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="over_weight_unit">Additional weight unit (grams)</label>				
				<input type="text" id="over_weight_unit" name="over_weight_unit" value="<?=$area['over_weight_unit'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="over_price">Price per additional weight unit ($)</label>				
				<input type="text" id="over_price" name="over_price" value="<?=$area['over_price'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="free_shipping">Free shipping minimum order amount ($)</label>				
				<input type="text" id="free_shipping" name="free_shipping" value="<?=$area['free_shipping'] ?>" />
			</div>
		</div>
		<div id="tabs-2">
		
			<div class="form-field clearfix">
				<label for="weight">Weight (grams)</label>				
				<input type="text" id="weight" class="text" name="weight" value="" />
			</div>
		
			<div class="form-field clearfix">
				<label for="price">Price ($)</label>				
				<input type="text" id="price" class="text" name="price" value="" /> 
				<a class="button button-small" style="margin-left: 10px;" href="#" id="add"><span style="width: auto; padding: 7px 15px 3px;">Add</span></a><br /><br />
			</div>
			<table class="values nocheck" id="table_prices">
				<tr>
					<th>Weight</th>
					<th>Price</th>
					<th style="width:75px;">&nbsp;</th>
				</tr>
				<?
					foreach($area['prices'] as $row)
					{	
						$next_id++;
						if($class=="light")
							$class="dark";
						else
							$class="light";
							
						echo "
						<tr class=\"$class\" id=\"$next_id\">
							<td>
								<input type=\"hidden\" name=\"saved_weight[]\" value=\"{$row['weight']}\" class=\"hidden_weight\" /> {$row['weight']}
							</td>
							<td>
								<input type=\"hidden\" name=\"saved_price[]\" value=\"{$row['price']}\" class=\"hidden_price\" /> {$row['price']}
							</td>
							<td class=\"right\">
								<a href=\"#\" class=\"remove_row\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></a>
							</td>
						</tr>";
					}
				?>
			</table>
		</div>
		
	</div>


	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
			<input type="hidden" name="area_id" value="<?=$area['id'] ?>" />
		</span>
	</div>
</form>
