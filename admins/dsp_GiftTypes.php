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
				url: '<?=$config['dir'] ?>index.php?fuseaction=admin.sortGiftType&item_id='+ui.item.find('.drag').attr('item_id')+'&steps='+(index-start_pos),
				type: 'get',
			});
		}
	});
	$( "#sortable tbody" ).disableSelection();
});
</script>

<form id="postback" method="post" action="none"></form>
<h1>
	<? if($acl->check("addGiftType")): ?><a style="float: right;" class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addGiftType"><span>Add Type</span></a><? endif; ?>
	Gift Registry Types
</h1>
<table class="values nocheck" id="sortable">
<thead>
	<tr>
		<? if($acl->check("orderGiftType")): ?><th></th><? endif; ?>
		<th>Name</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
</thead>
<tbody>
<?
	while($row=$types->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">";
		if($acl->check("orderGiftType"))
			echo "<td class=\"fit\" style=\"width: 20px;\">
					<a href=\"none\" class=\"drag\" item_id=\"{$row['id']}\" title=\"Drag & Drop\" onclick=\"return false;\"><img src=\"{$config['dir']}images/admin/sort.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
				</td>";
		echo "<td>{$row['name']}</td>
			<td class=\"right\">";
		if($acl->check("removeGiftType"))
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeGiftType'
				,['type_id']
				,[{$row['id']}]
				,'remove'
				,'type')\"/></span>\n";
		if($acl->check("editGiftType"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editGiftType&amp;type_id={$row['id']}\"><span>Edit</span></a>\n";
		echo "</td>
		</tr>";
	}
?>
</tbody>
</table>

<?
	$nr_pages = ceil($item_count / $items_per_page);
	$max_page_links = 10;
	
	if($nr_pages > 1)
	{
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' blocks<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.giftTypes';
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