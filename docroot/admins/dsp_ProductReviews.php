<form id="postback" method="post" action="none"></form>
<h1>Reviews</h1>
<table class="values nocheck">
	<tr>
		<th>Name/ Email</th>
		<th>Description</th>
		<th>Rating</th>
		<th><div style="width:175px;"></div></th>
		<th><div style="width:175px;"></div></th>
	</tr>
<?
	while($row=$reviews->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
		if($row['status'] == 'pending')
			$class = 'changed';
		if($row['status'] == 'rejected')
			$class = 'removed';

		echo "<tr class=\"$class\">
			<td><strong>{$row['name']}</strong><div>{$row['email']}</div></td>
			<td>
				<div>".truncate($row['description'],100)."...</div><br/>
				<div><strong>Status:</strong> {$row['status']}</div>
				<div style=\"white-space:nowrap;\"><strong>Posted:</strong> ".date("d/m/Y H:i", strtotime($row['posted']))."</div>
			</td>
			<td>{$row['rating']}</td>
			<td class=\"right\">";
		if($acl->check("rejectProductReview") && $row['status']=='pending')
			echo "<a href=\"\" class=\"button button-grey\" title=\"Reject\" onclick=\"return postbackConf(
				this
				,'rejectProductReview'
				,['review_id','product_id','category_id']
				,[{$row['id']},{$_REQUEST['product_id']},{$_REQUEST['category_id']}]
				,'reject'
				,'review')\"><span>Reject</span></a>\n";
		if($acl->check("approveProductReview") && $row['status']=='pending')
			echo "<a class=\"button button-grey\" href=\"\" title=\"Approve\" onclick=\"return postbackConf(
				this
				,'approveProductReview'
				,['review_id','product_id','category_id']
				,[{$row['id']},{$_REQUEST['product_id']},{$_REQUEST['category_id']}]
				,'approve'
				,'review')\"><span>Approve</span></a>\n";
		echo '</td><td>';
		if($acl->check("removeProductReview"))
			echo "<a class=\"button button-grey\" title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeProductReview'
				,['review_id','product_id','category_id']
				,[{$row['id']},{$_REQUEST['product_id']},{$_REQUEST['category_id']}]
				,'remove'
				,'review')\"><span>Remove</span></a>\n";
		if($acl->check("editProductReview"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editProductReview&amp;review_id={$row['id']}&amp;product_id={$_REQUEST['product_id']}&amp;category_id={$_REQUEST['category_id']}\"><span>Edit</span></a>\n";
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' reviews<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.productReviews';
			$results_page[] = 'category_id='.$_REQUEST['category_id'];
			$results_page[] = 'product_id='.$_REQUEST['product_id'];
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

<? if($acl->check("addProductReview")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addProductReview&product_id=<?=$_REQUEST['product_id'] ?>&category_id=<?=$_REQUEST['category_id'] ?>"><span>Add Review</span></a>
</div>
<? endif; ?>