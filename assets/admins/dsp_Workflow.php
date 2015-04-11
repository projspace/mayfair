<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#all').click(function(){
			if($(this).is(':checked'))
				$('.order').attr('checked','checked');
			else
				$('.order').removeAttr('checked');
		}).click().attr('checked','checked');
		
		$('.order').click(function(){
			if($('.order:checked').length == 0)
				$('#all').removeAttr('checked');
			if($('.order:checked').length == $('.order').length)
				$('#all').attr('checked','checked');
		});
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#submit_shipping').click(function(){
			$('#hidden_shipping').val('1');
			$('#hidden_process').val('0');
		});
		
		$('#submit_process').click(function(){
			$('#hidden_shipping').val('0');
			$('#hidden_process').val('1');
		});
		
		$('#submit_shipping_process').click(function(){
			$('#hidden_shipping').val('1');
			$('#hidden_process').val('1');
		});
		
		$('#submit_dispatch').click(function(){
			$('#hidden_shipping').val('0');
			$('#hidden_process').val('0');
			$('#frmWorkflow').attr('action','<?=$config['dir'] ?>index.php?fuseaction=admin.workflow&act=dispatch');
		});
	});
/* ]]> */
</script>

<form id="postback" method="post" action="none"></form>
<? if($_REQUEST['records']+0): ?>
<h1>Pick and Pack List</h1>
<? else: ?>
<h1>Order Processing Workflow</h1>
<? endif; ?>

<div class="filters clearfix">
	<form method="get" action="<?=$config['dir'] ?>index.php">
		<input type="hidden" name="fuseaction" value="admin.workflow"/>
		<? if($_REQUEST['records']+0): ?><input type="hidden" name="records" value="1"/><? endif; ?>
		
		<div class="legend">Date Interval</div><br />
		<div class="form">
			<label for="start_date">Start Date</label>
			<input type="text" id="start_date" class="calendar" name="start_date" value="<?=$_REQUEST['start_date'] ?>" />

			<label for="end_date">End Date</label>
			<input type="text" id="end_date" class="calendar" name="end_date" value="<?=$_REQUEST['end_date'] ?>" />
			<span class="button button-grey right">
				<input class="submit" type="submit" value="Submit" />
			</span>
			
		</div>
	</form>
</div>
<br />

<form method="post" action="<?=$config['dir'] ?>admins/shipping.php" id="frmWorkflow">
	<input type="hidden" id="hidden_shipping" name="shipping" value="0"/>
	<input type="hidden" id="hidden_process" name="process" value="0"/>
	<? if($_REQUEST['records']+0): ?><input type="hidden" name="records" value="1"/><? endif; ?>

<div class="filters clearfix">
	<span class="button button-grey" style="margin-right: 15px;">
		<input class="submit" type="submit" id="submit_shipping" value="Shipping" />
	</span>
	<? if($acl->check("processOrder") && $_REQUEST['records']+0 == 0): ?>
	<span class="button button-grey" style="margin-right: 15px;">
		<input class="submit" type="submit" id="submit_process" value="Process" />
	</span>
	<span class="button button-grey">
		<input class="submit" type="submit" id="submit_shipping_process" value="Shipping & Process" />
	</span>
	<? endif; ?>
	<? if($_REQUEST['records']+0): ?>
	<span class="button button-grey" style="margin-right: 15px;">
		<input class="submit" type="submit" id="submit_dispatch" value="Dispatch" />
	</span>
	<? endif; ?>
</div>
<br />

<table class="values nocheck">
	<tr>
		<th><input type="checkbox" id="all" checked="checked"/></th>
		<th>ID</th>
		<th>Time</th>
		<th>Name</th>
		<th>Email</th>
		<th class="right">Total</th>
		<th class="right">Shipping</th>
		<th class="right">Packing</th>
		<th class="right">Paid</th>
	</tr>
<?
	$keys=$orders->GetKeys();
	while($row=$orders->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "
			<tr>
				<td class=\"$class\"><input type=\"checkbox\" class=\"order\" name=\"order_ids[]\" value=\"{$row['id']}\"/></td>
				<td class=\"$class\"><strong>{$row['id']}</strong></td>
				<td class=\"$class\">".date("H:i d/m/Y",$row['time'])."</td>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\"><a href=\"mailto:{$row['email']}\">{$row['email']}</a></td>
				<td class=\"$class right\"><strong>$".price($row['total'])."</strong></td>
				<td class=\"$class right\"><strong>$".price($row['shipping'])."</strong></td>
				<td class=\"$class right\"><strong>$".price($row['packing'])."</strong></td>
				<td class=\"$class right\"><strong>$".price($row['paid'])."</strong></td>
			</tr>\n";
	}
?>
</table>
</form>