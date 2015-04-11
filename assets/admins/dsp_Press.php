<form id="postback" method="post" action="none"></form>
<form method="get" action="<?=$config['dir'] ?>index.php" id="frmFilter">
    <input type="hidden" name="fuseaction" value="admin.press" />
    <h1>
	<span style="float: right; margin-top: 5px;">
		<label style="width: auto; margin-right: 10px;">Search</label>
		<input type="text" name="keyword" value="<?=$_GET['keyword'] ?>"/>


		<label style="width: auto; clear: none; margin:0 10px;">Type</label>
		<select name="type" style="width: 150px;">
            <option value="">All</option>
            <option value='press'  <?php print $_GET['type']=='press'?"selected":""?>>Press</option>
            <option value='ads' <?php print $_GET['type']=='ads'?"selected":""?> >Ads</option>
        </select>

        <input type="submit" style="width:60px;margin-left:10px;" />

	</span>
        Press/Ads
    </h1>
<table class="values nocheck">
	<tr>
		<th>Type</th>
		<th>Title</th>
		<th>Date</th>
		<th style="width:200px;">&nbsp;</th>
	</tr>
<?
	while($row=$pressList->FetchRow())
	{
        $fetched = true;
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['type']}</td>
			<td>{$row['title']}</td>
			<td>".date("d/m/Y H:i", $row['date'])."</td>
			<td class=\"right\">";
		if($acl->check("deletePress"))
			echo "<a class=\"button button-grey\" title=\"Remove\" onclick=\"return postbackConf(
				this
				,'deletePress'
				,['press_id', 'return']
				,[{$row['id']}, 'press']
				,'remove'
				,'press')\"><span>Remove</span></a>\n";
		if($acl->check("editPress"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editPress&amp;press_id={$row['id']}&amp;return=reviews\"><span>Edit</span></a>\n";
		echo "</td>
		</tr>";
	}

    if(!$fetched){
        echo '<tr><td colspan="4">No rows found</td></tr>';
    }
?>
</table>

<? if($acl->check("addPress")): ?>
<div class="buttons clearfix">
    <a class="button button-small-add add float-right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.editPress"><span>Add Press/Ad</span></a>
</div>
<? endif; ?>

<?
	$nr_pages = ceil($item_count / $items_per_page);
	$max_page_links = 10;
	
	if($nr_pages > 1)
	{
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' reviews<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.press';
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