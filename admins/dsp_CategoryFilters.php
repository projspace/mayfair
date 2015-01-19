<form id="postback" method="post" action="none"></form>
<h1>Filters</h1>
<table class="values nocheck">
	<tr>
		<? if($acl->check("orderCategoryFilter")): ?><th></th><? endif; ?>
		<th>Name</th>
		<th>Type</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$filters->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";
			
		echo "<tr class=\"$class\">";
		if($acl->check("orderCategoryFilter"))
			echo "<td class=\"fit\" style=\"width:37px;\">
					<a href=\"none\" title=\"Move Up\" onclick=\"return postback(
							this
							,'orderCategoryFilter'
							,['filter_id','category_id','dir']
							,[{$row['id']},{$_REQUEST['category_id']},'up'])\"><img src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
					<a href=\"none\" title=\"Move Down\" onclick=\"return postback(
							this
							,'orderCategoryFilter'
							,['filter_id','category_id','dir']
								,[{$row['id']},{$_REQUEST['category_id']},'down'])\"><img src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"\\/\" /></a>
				</td>";
		echo "<td>{$row['name']}</td>
			<td>{$row['type']}</td>
			<td class=\"right\">";
		if($acl->check("removeCategoryFilter"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeCategoryFilter'
				,['filter_id','category_id']
				,[{$row['id']},{$_REQUEST['category_id']}]
				,'remove'
				,'filter')\"/></span>\n";
		if($acl->check("editCategoryFilter"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editCategoryFilter&amp;filter_id={$row['id']}&amp;category_id={$_REQUEST['category_id']}\"><span>Edit</span></a>\n";
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' filters<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.categoryFilters';
			$results_page[] = 'category_id='.$_REQUEST['category_id'];
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

<? if($acl->check("addCategoryFilter")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addCategoryFilter&category_id=<?=$_REQUEST['category_id'] ?>"><span>Add Filter</span></a>
</div>
<? endif; ?>