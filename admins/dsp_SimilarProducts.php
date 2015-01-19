<form id="postback" method="post" action="none"></form>
<h1>'You might like' products</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$similar_products->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['name']}</td>
			<td>".truncate($row['description'],100)."...</td>
			<td class=\"right\">";
		if($acl->check("removeSimilarProduct"))
			echo "<a href=\"#\" class=\"button button-grey right\" title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeSimilarProduct'
				,['similar_product_id','product_id','category_id']
				,[{$row['id']},{$_REQUEST['product_id']},{$_REQUEST['category_id']}]
				,'remove'
				,'product')\"><span>Remove</span></a>\n";
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' products<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.similarProducts';
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


<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.editProduct&product_id=<?=$_REQUEST['product_id']?>&category_id=<?=$_REQUEST['category_id'] ?>"><span>Cancel</span></a>

<? if($acl->check("addSimilarProduct")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addSimilarProduct&amp;product_id=<?=$_REQUEST['product_id'] ?>&amp;category_id=<?=$_REQUEST['category_id'] ?>"><span>Add Product</span></a>
</div>
<!--
<div class="right">
	<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addSimilarProduct&amp;act=add&amp;product_id=<?=$_REQUEST['product_id'] ?>&amp;category_id=<?=$_REQUEST['category_id'] ?>">
	
		<select id="similar_product_id" name="similar_product_id" class="custom-skin" style="margin-right:5px;">
		<?
			while($row = $all_products->FetchRow())
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		?>
		</select>
		<span class="button button-small-add add"><input class="submit" type="submit" value="Add" /></span>
	</form>
</div>
-->
<? endif; ?>