<form id="postback" method="post" action="none"></form>
<form method="get" action="<?=$config['dir'] ?>index.php">
	<input type="hidden" name="fuseaction" value="admin.discountCodes" />
<h1>
	<span style="float: right; margin-top: 5px;">
		<label style="width: auto; margin-right: 10px;">Teacher/Shop</label>
		<input type="text" name="keyword" value="<?=$_GET['keyword'] ?>"/>
	</span>
	Discount Codes
</h1>
</form>
<table class="values nocheck">
	<tr>
	<?
		$sort_dir = ($sort_dir == 'DESC')?'asc':'desc';
		
		$append_url = array();
		$append_url[] = 'dir='.$sort_dir;
		if(trim($_GET['page']) != '')
			$append_url[] = 'page='.trim($_GET['page']);
		
		if(count($append_url))
			$append_url = '&amp;'.implode('&amp;', $append_url);
		else
			$append_url = '';
	?>
		<th>Code</th>
		<th class="sortable"><a class="sort <? if($sort == 'value') echo ($sort_dir == 'asc')?'desc':'asc' ?>" href="<?=$config['dir']?>index.php?fuseaction=admin.discountCodes&sort=value<?=$append_url?>">Value</a></th>
		<th class="sortable"><a class="sort <? if($sort == 'expiry_date') echo ($sort_dir == 'asc')?'desc':'asc' ?>" href="<?=$config['dir']?>index.php?fuseaction=admin.discountCodes&sort=date<?=$append_url?>">Expiry Date</a></th>
		<th>Min Order Value</th>
		<th>All Users</th>
		<th>Assigned</th>
		<th class="sortable"><a class="sort <? if($sort == 'used') echo ($sort_dir == 'asc')?'desc':'asc' ?>" href="<?=$config['dir']?>index.php?fuseaction=admin.discountCodes&sort=used<?=$append_url?>">Used</a></th>
		<th>Use Count</th>
		<th>Turned off</th>
		<th>Teacher/Shop</th>
		<th style="width:230px;">&nbsp;</th>
	</tr>
<?
	while($row=$discount_codes->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		if($row['value_type'] == 'percent')
			$value = number_format($row['value'], 2, '.', '').'%';
		else
			$value = '$'.number_format($row['value'], 2, '.', '');
			
		if(isDateValid($row['expiry_date']))
			$expiry_date = date('d/m/Y', strtotime($row['expiry_date']));
		else
			$expiry_date = '-';
			
		if($row['used'])
			$used = $row['used'];
		else
			$used = 'No';
			
		if($row['assigned'])
			$assigned = '<img src="'.$config['dir'].'images/admin/tick.gif" width="16" height="16" alt="Yes"/>';
		else
			$assigned = '';
			
		if($row['all_users'])
			$all_users = '<img src="'.$config['dir'].'images/admin/tick.gif" width="16" height="16" alt="Yes"/>';
		else
			$all_users = '';
			
		if($row['suspended'])
			$suspended = '<img src="'.$config['dir'].'images/admin/tick.gif" width="16" height="16" alt="Yes"/>';
		else
			$suspended = '';
			
		echo "<tr class=\"$class\">";
		echo "<td>{$row['code']}</td>
			<td>{$value}</td>
			<td>{$expiry_date}</td>
			<td>".price($row['min_order'])."</td>
			<td>{$all_users}</td>
			<td>{$assigned}</td>
			<td>{$used}</td>
			<td>{$row['use_count']}</td>
			<td>{$suspended}</td>
			<td>{$row['firstname']} {$row['lastname']}</td>
			<td class=\"right\" style=\"padding: 10px;\">";
		if($acl->check("removeDiscountCode"))
		{
			echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeDiscountCode'
				,['code_id']
				,[{$row['id']}]
				,'remove'
				,'code')\" /></span>\n";
			
			if(!$row['suspended'])
				echo "<span class=\"button button-grey\"><input type=\"button\" value=\"Suspend\" onclick=\"return postbackConf(
					this
					,'suspendDiscountCode'
					,['code_id']
					,[{$row['id']}]
					,'suspend'
					,'code')\" /></span>\n";
		}
		if($acl->check("assignDiscountCode") && ($row['assigned']+$row['used']) == 0 && !$row['all_users'] && !$row['suspended'])
			echo "<a title=\"Assign\" class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.assignDiscountCode&amp;code_id={$row['id']}\"><span>Assign</span></a>\n";
		//if($acl->check("assignDiscountCode") && $row['all_users'] && !$row['suspended'] && $row['shop_account_id']+$row['teacher_account_id'] == 0)
			//echo "<a title=\"Assign\" class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.assignCommissionDiscountCode&amp;code_id={$row['id']}\"><span>Assign</span></a>\n";
		if($acl->check("editDiscountCode"))
			echo "<a title=\"Edit\" class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editDiscountCode&amp;code_id={$row['id']}\"><span>Edit</span></a>\n";
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
			$results_page[] = 'fuseaction=admin.discountCodes';
			if(trim($_REQUEST['sort']) != '')
				$results_page[] = 'sort='.urlencode(trim($_REQUEST['sort']));
			if(trim($_REQUEST['dir']) != '')
				$results_page[] = 'dir='.urlencode(trim($_REQUEST['dir']));
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

<div class="clearfix">
	<? if($acl->check("addDiscountCode")): ?>
		<a class="button button-small-add add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addDiscountCode"><span>Add Code(s)</span></a> 
		<a class="button button-small-add add right" style="margin-right: 5px;" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addCustomDiscountCode"><span>Add Custom Code</span></a> 
	<? endif; ?>
	<? if($acl->check("removeDiscountCode")): ?>
		<span class="button button-grey"><input type="button" onclick="return postbackConf2(this, 'removeDiscountCode',['type'],['all'],'remove','codes','all')" value="Delete All" /></span>
		<span class="button button-grey"><input type="button" onclick="return postbackConf2(this, 'removeDiscountCode',['type'],['expired'],'remove','expired codes','all')" value="Delete Expired" /></span>
	<? endif; ?>
	<? if($acl->check("downloadDiscountCodes")): ?>
	<div class="right" style="float: right;">
		<span class="button button-grey"><input type="button" onclick="window.location='<?=$config['dir'] ?>admins/csv_download_discount_codes.php';" value="Download CSV" /></span>
	</div>
	<? endif; ?>
</div>







