<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#add_value').click(add_value);
		$('#value').keydown(function(event){
			if(event.keyCode == 13)
			{
				add_value();
				return false;
			}
		});
		$('#table_values .remove_row').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function validateValue()
	{
		$('#value').css('border', '1px solid #A7A6AA');
		
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).css('border', '1px solid #F00');
			}
		});

		validation.addField('value', "Value", 'required');
		return validation.validate();
	}
	
	function add_value(){
		var value = jQuery.trim($('#value').val());
		
		if(!validateValue())
			return false;
			
		var found = false;
		$('.hidden_value').each(function(i){
			var hidden_val = jQuery.trim($(this).val());
			if(value == hidden_val)
				found = true;
		});
		if(found)
		{
			alert('Duplicate value. Please check the form and try again.');
			$('#value').val('');
			return false;
		}
		var last_id = $.trim($('tr:last', '#table_values').attr('id'));
		var next_id;
		if(last_id != '')
			next_id = parseInt(last_id) + 1;
		else
			next_id = 1;
		var row_class = (next_id % 2)?'light':'dark';
		$('#table_values').append('<tr class="'+row_class+'" id="'+next_id+'"><td><a href="#" title="Move Up" class="move_up"><img src="<?=$config['dir'] ?>images/admin/up.png" width="16" height="16" alt="/\\" /></a><a href="#" title="Move Down" class="move_down"><img src="<?=$config['dir'] ?>images/admin/down.png" width="16" height="16" alt="\\/" /></a></td><td><input type="hidden" name="value[]" value="'+value+'" class="hidden_value" />'+value+'</td><td class="right"><a href="#" class="remove_row"><img src="<?=$config['dir'] ?>images/admin/delete.png" width="16" height="16" alt="Remove" /></a></td></tr>');
		$('#table_values .remove_row').unbind('click').click(function(){
			$(this).parent().parent().remove();
			return false;
		});
		$('#table_values .move_up').unbind('click').click(function(){
			var row = $(this).parent().parent();
			var prev = row.prev('tr');
			if(prev.get(0) == $('#table_values tr:first').get(0))
				return false;
			row.clone(true).insertBefore(prev);
			row.remove();
			return false;
		});
		$('#table_values .move_down').unbind('click').click(function(){
			var row = $(this).parent().parent();
			var next = row.next('tr');
			if(!next.length)
				return false;
			row.clone(true).insertAfter(next);
			row.remove();
			return false;
		});
		$('#value').val('');
		return false;
	}
/* ]]> */
</script>

<form id="postback" method="post" action="none"></form>
<h1>New Filter</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addCategoryFilter&amp;category_id=<?=$_REQUEST['category_id'] ?>&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Values</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="" />
			</div>
			<div class="form-field clearfix">
				<label for="type">Type</label>
				<select id="type" name="type">
					<option value="single">Single</option>
					<option value="multiple">Multiple</option>
				</select>
			</div>
		</div>
		<div id="tabs-2">
			<div class="form-field clearfix">
				<label for="value">Value</label>
				<span><input type="text" class="text" id="value" name="value" value="" /></span> 
				<a class="button button-small" style="margin-left: 10px;" href="#" id="add_value"><span style="width: auto; padding: 7px 15px 3px;">Add</span></a><br /><br />
			</div>
			<table class="values nocheck" id="table_values">
				<tr>
					<th style="width:37px;"></th>
					<th>Value</th>
					<th>&nbsp;</th>
				</tr>
			</table>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.categoryBoxes&category_id=<?=$_REQUEST['category_id'] ?>"><span>Cancel</span></a>
	</div>
</form>