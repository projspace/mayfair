<form id="postback" method="post" action="none"></form>
<h1>Static Content</h1>
<table class="values nocheck">
	<tr>
		<th>Name</th>
		<th>Content</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$content_areas->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['name']}</td>
			<td>".truncate($row['description'], 100)."</td>
			<td class=\"right\">";
		if($acl->check("removeContentArea"))
			echo "<span class=\"button button-grey\"><input type=\"button\" title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeContentArea'
				,['area_id']
				,[{$row['id']}]
				,'remove'
				,'content')\" value=\"Remove\" /></span>\n";
		if($acl->check("editContentArea"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editContentArea&amp;area_id={$row['id']}\"><span>Edit</span></a>\n";
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' users<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.contentAreas';
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
<? if($acl->check("addContentArea")): ?>
<div class="tab-panel-buttons clearfix">
	<a class="button button-small-add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addContentArea">
		<span>Add Content</span>
	</a>
</div>
<? endif; ?>