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

<h1 class="pageTitle">Orders</h1>

<table class="values nocheck">
	<tr>
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

		if($row['refunded'])
			$tr_class = 'class="refund"';
		else
			$tr_class = '';
			
		echo "
			<tr ".$tr_class.">
				<td class=\"$class\"><strong>{$row['id']}</strong></td>
				<td class=\"$class\">".date("H:i d/m/Y",$row['time'])."</td>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\"><a href=\"mailto:{$row['email']}\">{$row['email']}</a></td>
				<td class=\"$class right\"><strong>".price($row['total'])."</strong></td>
				<td class=\"$class right\"><strong>".price($row['shipping'])."</strong></td>
				<td class=\"$class right\"><strong>".price($row['packing'])."</strong></td>
				<td class=\"$class right\"><strong>".price($row['paid'])."</strong></td>
			</tr>\n";
	}
?>
</table>