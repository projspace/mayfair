<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#status').change(function(){
			$('#frmFilter').submit();
		});
	});
/* ]]> */
</script>
<form id="postback" method="post" action="none"></form>
<h1>Gift Registry</h1>

<div class="filters dashboard-filters clearfix">
	<form id="frmFilter" action="<?= $config['dir'] ?>index.php" method="get">
		<input type="hidden" name="fuseaction" value="admin.giftRegistry" />
		<label>
			Status
			<select name="status" id="status">
				<option value="">Please Select</option>
				<option value="pending" <? if($_REQUEST['status'] == 'pending'): ?>selected="selected"<? endif; ?>>Pending</option>
				<option value="completed" <? if($_REQUEST['status'] == 'completed'): ?>selected="selected"<? endif; ?>>Completed</option>
			</select>
		</label>
	</form>
</div>

<table class="values nocheck" id="sortable">
<thead>
	<tr>
		<th>Name</th>
		<th>Bought<br />Products</th>
		<th>No of<br />Products</th>
		<th>Status</th>
		<th>Date</th>
        <th>Deliver<br />After</th>
        <th>Public</th>
        <th style="width:160px;">&nbsp;</th>
	</tr>
</thead>
<tbody>
<?
	while($row=$lists->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">";
		echo "<td>{$row['name']}</td>
			<td>".($row['bought']+0)."</td>
			<td>".($row['quantity']+0)."</td>
			<td>{$row['status']}</td>
			<td>".date('m/d/Y', strtotime($row['date']))."</td>
			<td>".date('m/d/Y', strtotime($row['delivery_after']))."</td>
			<td>".(($row['public']+0)?'Yes':'No')."</td>
			<td class=\"right\">";

        $options = array();
        $actions = array();

        if($row['bought']+0 == $row['quantity']+0 && $row['status'] == 'pending')
        {
            $options[] = "<option value=\"close\">Close</option>";
            $actions[] = "<a class=\"icon-button close\" title=\"Close\" onclick=\"return postbackConf(
                            this
                            ,'closeGiftRegistryList'
                            ,['list_id']
                            ,[{$row['id']}]
                            ,'close'
                            ,'list')\">Close</a>\n";
        }

		if($row['public']+0)
		{
			$options[] = "<option value=\"delete\">Delete</option>";
			$actions[] = "<a class=\"icon-button delete\" title=\"Delete\" onclick=\"return postbackConf(
							this
							,'removeGiftRegistryList'
							,['list_id']
							,[{$row['id']}]
							,'delete'
							,'gift registry')\">Delete</a>\n";
		}

        $options[] = "<option value=\"note\">Shipping List</option>";
        $actions[] = "<a class=\"note\" title=\"Shipping List\" href=\"{$config['dir']}admins/shipping_note.php?list_id={$row['id']}\">Shipping List</a>\n";

        $options[] = "<option value=\"orders\">Orders</option>";
        $actions[] = "<a class=\"orders\" title=\"Orders\" href=\"{$config['dir']}index.php?fuseaction=admin.giftRegistryList&amp;list_id={$row['id']}\">Orders</a>\n";

        $options[] = "<option value=\"products\">Products</option>";
        $actions[] = "<a class=\"products\" title=\"Products\" href=\"{$config['dir']}index.php?fuseaction=admin.giftRegistryProducts&amp;list_id={$row['id']}\">Products</a>\n";

        $options[] = "<option value=\"view\">View</option>";
        $actions[] = "<a class=\"view\" title=\"View\" href=\"{$config['dir']}index.php?fuseaction=admin.viewGiftRegistry&amp;list_id={$row['id']}\">View</a>\n";

        echo "<select class=\"custom-skin row-actions\"><option value=\"\">Select Action</option>".implode("", $options)."</select>";
        echo "<div style=\"display:none;\">".implode("", $actions)."</div>";

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
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' lists<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.giftRegistry';
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
<script type="text/javascript">
	$(function(){
		$('select.row-actions').each(function(){
			$this = $(this);
			var buttons = $this.parent();
			$this.change(function(){
				if( this.value ) {
					var button = buttons.find('.' + this.value);
					var node = button.get(0);
					if( node.nodeName.toLowerCase() == 'a' && !node.onclick ) {
						window.location = node.href;
					} else {
						button.attr('onclick').call(node);
					}
				}
			});
		});
	});
</script>