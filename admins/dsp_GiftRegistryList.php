<form id="postback" method="post" action="none"></form>
<h1>Gift Registry Orders</h1>
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
		<th class="right">Processed</th>
		<th style="width:200px">&nbsp;</th>
	</tr>
<?
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
				<td class=\"$class right\">".($row['processed']?'Yes':'No')."</td>
				<td class=\"$class right\">";
			if(!$row['processed'] && $acl->check("processOrder"))
				echo "<a href=\"#\" title=\"Process Order\" class=\"button button-grey\" onclick=\"return postbackConf(
						this
						,'processOrder'
						,['order_id']
						,[{$row['id']}]
						,'process'
						,'order');return false;\"><span>Process</span></a>";
			if($acl->check("viewOrder"))
				echo "<a href=\"{$config['dir']}index.php?fuseaction=admin.viewOrder&amp;order_id={$row['id']}\" class=\"button button-grey\"><span>View</span></a>";
		echo "		</td>
			</tr>\n";
	}
?>
</table>
<br />

<?
	$nr_pages = ceil($item_count / $items_per_page);
	$max_page_links = 10;
	
	if($nr_pages > 1)
	{
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' orders<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.giftRegistryList';
			$results_page[] = 'list_id='.$_REQUEST['list_id'];
			$results_page = $config['dir'].'index.php?'.implode('&amp;', $results_page).'&amp;page=';
				
			echo '<li><a href="'.$results_page.'1">&lt;&lt; First</a></li>';
				
			if($page == 1)
				echo '<li class="next"><a href="#">&lt; Back</a></li>';
			else
				echo '<li class="next"><a href="'.$results_page.($page - 1).'">&lt; Back</a></li>';
			
			for($i = $page - floor($max_page_links/2); $i < $page + ceil($max_page_links/2); $i++)
				if(($i > 0)&&($i <= $nr_pages))
				{
					if($i == $page)
						echo '<li><span>'.$i.'</span></li>';
					else
						echo '<li><a href="'.$results_page.$i.'">'.$i.'</a></li>';
				}
			
			if($page == $nr_pages)
				echo '<li class="prev"><a href="#">Next &gt;</a></li>';
			else
				echo '<li class="prev"><a href="'.$results_page.($page + 1).'">Next &gt;</a></li>';
				
			echo '<li><a href="'.$results_page.$nr_pages.'">Last &gt;&gt;</a></li>';
		
			echo '</ul></div>';
		echo '</div>';
	}
?>

<div class="filters clearfix">
	<form method="get" action="<?=$config['dir'] ?>index.php">
		<input type="hidden" name="fuseaction" value="admin.workflow"/>
		
		<div class="legend">Order Processing Workflow</div><br />
		<div class="form">
			<label for="start_date">Start Date</label>
			<input type="text" id="start_date" class="calendar" name="start_date" value="<?=$min_time?date('d/m/Y', $min_time):'' ?>" />

			<label for="end_date">End Date</label>
			<input type="text" id="end_date" class="calendar" name="end_date" value="<?=$max_time?date('d/m/Y', $max_time):'' ?>" />
			<span class="button button-grey right">
				<input class="submit" type="submit" value="Submit" />
			</span>
			
		</div>
	</form>
</div>