<form id="postback" method="post" action="none"></form>
<h1 class="pageTitle">
	<? if($acl->check("reviews")): ?><a class="button button-grey" title="Back" href="<?=$config['dir'] ?>index.php?fuseaction=admin.reviews" rel="import-container"><span>Back</span></a><? endif; ?>
	Reviews (cancelled)
</h1>
<table class="values nocheck">
	<tr>
		<th>Product</th>
		<th>Rating</th>
		<th>Description</th>
		<th>Posted</th>
		<th style="width:200px;">&nbsp;</th>
	</tr>
<?
	while($row=$reviews->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['product']}</td>
			<td>{$row['rating']}</td>
			<td>".truncate($row['description'],100)."...</td>
			<td>".date("d/m/Y H:i", strtotime($row['posted']))."</td>
			<td class=\"right\">";
		if($acl->check("removeProductReview"))
			echo "<a class=\"button button-grey\" title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeProductReview'
				,['review_id','product_id','category_id','return']
				,[{$row['id']},{$row['product_id']},{$row['category_id']},'rejected']
				,'remove'
				,'review')\"><span>Remove</span></a>\n";
		if($acl->check("editProductReview"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editProductReview&amp;review_id={$row['id']}&amp;product_id={$row['product_id']}&amp;category_id={$row['category_id']}&amp;return=rejected\"><span>Edit</span></a>\n";
		echo "</td>
		</tr>";
	}
?>
</table>

<?
	$nr_pages = ceil($item_count / $items_per_page);
	$max_page_links = 10;
	
	if($nr_pages > 1)
	{
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' rejected reviews<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.rejectedReviews';
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