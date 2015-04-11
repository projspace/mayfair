<form id="postback" method="post" action="none"></form>
<h1>Page Images</h1>
<table class="values nocheck">
	<tr>
		<? if($acl->check("orderPageImage")): ?><th></th><? endif; ?>
		<th>Image</th>
		<th style="width:100px;">&nbsp;</th>
	</tr>
<?
	while($row=$images->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">";
		if($acl->check("orderPageImage"))
			echo "<td class=\"fit\" style=\"width:37px;\">
					<a href=\"none\" title=\"Move Up\" onclick=\"return postback(
							this
							,'orderPageImage'
							,['image_id','pageid','parent_id','dir']
							,[{$row['id']},{$_REQUEST['pageid']},{$_REQUEST['parent_id']},'up'])\"><img src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
					<a href=\"none\" title=\"Move Down\" onclick=\"return postback(
							this
							,'orderPageImage'
							,['image_id','pageid','parent_id','dir']
							,[{$row['id']},{$_REQUEST['pageid']},{$_REQUEST['parent_id']},'down'])\"><img src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"\\/\" /></a>
				</td>";
		echo "<td><img src=\"{$config['dir']}images/page/thumb/image_{$row['id']}.{$row['image_type']}?t=".time()."\" width=\"100\"/></td>
			<td class=\"right\">";
		if($acl->check("removePageImage"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removePageImage'
				,['image_id','pageid','parent_id']
				,[{$row['id']},{$_REQUEST['pageid']},{$_REQUEST['parent_id']}]
				,'remove'
				,'image')\"/></span>\n";
        if($row['pageid'] == 1)
            echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editPageImage&amp;pageid={$row['pageid']}&amp;parent_id={$_REQUEST['parent_id']}&amp;image_id={$row['id']}\"><span>Edit</span></a>\n";
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
			$results_page[] = 'fuseaction=admin.pageImages';
			$results_page[] = 'pageid='.$_REQUEST['pageid'];
			$results_page[] = 'parent_id='.$_REQUEST['parent_id'];
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

<? if($acl->check("addPageImage")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addPageImage&amp;pageid=<?=$_REQUEST['pageid'] ?>&amp;parent_id=<?=$_REQUEST['parent_id'] ?>"><span>Add Image</span></a>
</div>
<? endif; ?>