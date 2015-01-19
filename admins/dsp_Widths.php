<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script>
var initial_order = [];
$(function() {
	$( "#sortable tbody" ).sortable({
		handle: '.drag'
		,start: function(event, ui) {
            ui.item.data('start_pos', ui.item.index());
        }
		,update: function(event, ui){
			var start_pos = ui.item.data('start_pos');
            var index = ui.item.index();
			$.ajax({
				url: '<?=$config['dir'] ?>index.php?fuseaction=admin.sortWidth&item_id='+ui.item.find('.drag').attr('item_id')+'&steps='+(index-start_pos),
				type: 'get',
			});
		}
	});
	$( "#sortable tbody" ).disableSelection();
});
</script>

<form id="postback" method="post" action="none"></form>
<h1>
	<? if($acl->check("addWidth")): ?><a style="float: right;" class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addWidth"><span>Add Width</span></a><? endif; ?>
	Product Widths
</h1>
<table class="values nocheck" id="sortable">
	<tr>
		<? if($acl->check("orderWidth")): ?><th></th><? endif; ?>
		<th>Name</th>
		<th>Code</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$widths->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">";
		if($acl->check("orderWidth"))
			echo "<td class=\"fit\" style=\"width: 20px;\">
					<a href=\"none\" class=\"drag\" item_id=\"{$row['id']}\" title=\"Drag & Drop\" onclick=\"return false;\"><img src=\"{$config['dir']}images/admin/sort.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
					<!--<a href=\"none\" title=\"Move Up\" onclick=\"return postback(
							this
							,'orderWidth'
							,['width_id','dir']
							,[{$row['id']},'up'])\"><img src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
					<a href=\"none\" title=\"Move Down\" onclick=\"return postback(
							this
							,'orderWidth'
							,['width_id','dir']
								,[{$row['id']},'down'])\"><img src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"\\/\" /></a>-->
				</td>";
		echo "<td>{$row['name']}</td>
			<td>{$row['code']}</td>
			<td class=\"right\">";
		if($acl->check("removeWidth"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeWidth'
				,['width_id']
				,[{$row['id']}]
				,'remove'
				,'width')\"/></span>\n";
		if($acl->check("editWidth"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editWidth&amp;width_id={$row['id']}\"><span>Edit</span></a>\n";
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
			$results_page[] = 'fuseaction=admin.widths';
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