<form id="postback" method="post" action="none"></form>
<h1>Meta Tags</h1><hr />
<table class="values">
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$meta_tags->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['name']}</td>
			<td>".truncate($row['description'],100)."...</td>
			<td class=\"right\">";
		if($acl->check("removeMetaTag"))
			echo "<button title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeMetaTag'
				,['tag_id']
				,[{$row['id']}]
				,'remove'
				,'meta tag')\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Remove\" /></button>\n";
		if($acl->check("editMetaTag"))
			echo "<a href=\"{$config['dir']}index.php?fuseaction=admin.editMetaTag&amp;tag_id={$row['id']}\"><img src=\"{$config['dir']}images/admin/edit.png\" width=\"16\" height=\"16\" alt=\"Edit\" /></a>\n";
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' tags<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.meta_tags';
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

<? if($acl->check("addMetaTag")): ?>
<div class="right">
	<button class="add" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.addMetaTag'; return false;">Add Meta tag</button>
</div>
<? endif; ?>