<form id="postback" method="post" action="none"></form>
<h1>Home Banners</h1>
<table class="values nocheck">
	<tr>
		<? if($acl->check("orderHomeBanner")): ?><th></th><? endif; ?>
		<th>Image</th>
		<th>Label</th>
		<th>Description</th>
		<th>URL</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$banners->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">";
		if($acl->check("orderHomeBanner"))
			echo "<td class=\"fit\" style=\"width:37px;\">
					<a href=\"none\" title=\"Move Up\" onclick=\"return postback(
							this
							,'orderHomeBanner'
							,['banner_id','dir']
							,[{$row['id']},'up'])\"><img src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
					<a href=\"none\" title=\"Move Down\" onclick=\"return postback(
							this
							,'orderHomeBanner'
							,['banner_id','dir']
								,[{$row['id']},'down'])\"><img src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"\\/\" /></a>
				</td>";
		echo "<td><img src=\"{$config['dir']}images/home_banners/{$row['id']}.{$row['image_type']}?time=".time()."\" width=\"200\" alt=\"\" /></td>
			<td>{$row['label']}</td>
			<td>{$row['description']}</td>
			<td>{$row['url']}</td>
			<td class=\"right\">";
		if($acl->check("removeHomeBanner"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeHomeBanner'
				,['banner_id']
				,[{$row['id']}]
				,'remove'
				,'banner')\"/></span>\n";
		if($acl->check("editHomeBanner"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editHomeBanner&amp;banner_id={$row['id']}\"><span>Edit</span></a>\n";
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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' blocks<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.homeBanners';
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

<? if($acl->check("addHomeBanner")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addHomeBanner"><span>Add Banner</span></a>
</div>
<? endif; ?>