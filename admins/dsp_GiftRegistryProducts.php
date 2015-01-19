<form id="postback" method="post" action="none"></form>
<h1>Gift Registry Products</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>UPC Code</th>
		<th class="right">Bought</th>
		<th class="right">Quantity</th>
		<th class="right">Price</th>
	</tr>
<?
	while($row=$products->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "
			<tr ".$tr_class.">
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class\">{$row['upc_code']}</td>
				<td class=\"$class right\"><strong>".($row['bought']+0)."</strong></td>
				<td class=\"$class right\"><strong>".($row['quantity']+0)."</strong></td>
				<td class=\"$class right\"><strong>".price($row['price'])."</strong></td>
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' products<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.giftRegistryProducts';
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