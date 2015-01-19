<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		window.print();
	});
/* ]]> */
</script>
<style type="text/css" media="print">
/* <![CDATA[ */
	#header, #footer { display: none; }
/* ]]> */
</style>

<h1 class="pageTitle">Past Orders</h1>

<table class="values nocheck">
	<tr>
		<th>ID</th>
		<th>Time</th>
		<th>Name</th>
		<th class="right">Paid</th>
		<th class="right" width="70">Shipped</th>
	</tr>
<?
	while($row=$orders->FetchRow())
	{
		if($class=="light")
		{
			$class="dark";
			$linkclass="white";
		}
		else
		{
			$class="light";
			$linkclass="a";
		}
		
		if($row['refunded'])
		{
			if($row['refunded'] != $row['paid'])
				$tr_class = 'class="refund-partial"';
			else
				$tr_class = 'class="refund"';
		}
		else
			$tr_class = '';

		if($row['dispatched'])
			$dispatched = '<img src="'.$config['dir'].'images/admin/tick.gif" width="16" height="16" alt="shipped"/>';
		else
			$dispatched = '';
			
		echo "
			<tr ".$tr_class.">
				<td class=\"$class\"><strong>{$row['id']}</strong></td>
				<td class=\"$class\">".date("H:i d/m/Y",$row['time'])."</td>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class right\"><strong>".price($row['paid'])."</strong></td>
				<td class=\"$class right\">".$dispatched."</strong></td>
			</tr>\n";
	}
?>
</table>