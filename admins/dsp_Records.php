<?
	$url = array();
	if(isset($_REQUEST['date']))
	{
		$url[] = 'date[custom]='.urlencode($_REQUEST['date']['custom']);
		$url[] = 'date[from]='.urlencode($_REQUEST['date']['from']);
		$url[] = 'date[to]='.urlencode($_REQUEST['date']['to']);
	}
	if(isset($_REQUEST['keyword']))
		$url[] = 'keyword='.urlencode($_REQUEST['keyword']);
?>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#date_custom').change(function(){
			$('#date_from, #date_to').val('');
			$('#frmFilter').submit();
		});
		
		$('#date_from, #date_to').change(function(){
			$('#date_custom').val('');
			$('#frmFilter').submit();
		});
		
		$('#keyword').keydown(function(event){
			if(event.which == 13)
			{
				$('#frmFilter').submit();
				return false;
			}
		});
	});
/* ]]> */
</script>
<form id="postback" method="post" action="none"></form>

<h1 class="pageTitle">
	<a class="button button-grey button-slide" title="Print" href="<?=$config['dir'] ?>index.php?fuseaction=admin.records&act=print" target="_blank"><span>Print</span></a>
	<?
		$export_url = $url;
		$export_url[] = 'act=export';
		$export_url = $config['dir'].'index.php?fuseaction=admin.records&amp;'.implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Export" href="<?=$export_url ?>"><span>Export Orders</span></a>
	<?
		$export_url = $url;
		$export_url[] = 'act=export_products';
		$export_url = $config['dir'].'index.php?fuseaction=admin.records&amp;'.implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Export" href="<?=$export_url ?>"><span>Export Products</span></a>
	Past Orders
</h1>

<div class="filters dashboard-filters clearfix">
	<form id="frmFilter" action="<?= $config['dir'] ?>index.php" method="get">
		<input type="hidden" name="fuseaction" value="admin.records" />
		<label>
			Date range
			<select name="date[custom]" id="date_custom">
				<option value="">Please Select</option>
				<option value="all" <? if($_REQUEST['date']['custom'] == 'all'): ?>selected="selected"<? endif; ?>>All</option>
				<option value="7_days" <? if($_REQUEST['date']['custom'] == '7_days'): ?>selected="selected"<? endif; ?>>Last 7 Days</option>
				<option value="this_month" <? if($_REQUEST['date']['custom'] == 'this_month'): ?>selected="selected"<? endif; ?>>Current Month</option>
				<option value="previous_month" <? if($_REQUEST['date']['custom'] == 'previous_month'): ?>selected="selected"<? endif; ?>>Previous Month</option>
			</select>
		</label>
		<label>
			Or From:
			<input type="text" class="text medium calendar" value="<?=$_REQUEST['date']['from'] ?>" name="date[from]" id="date_from" />
		</label>
		<label>
			To:
			<input type="text" class="text medium calendar" value="<?=$_REQUEST['date']['to'] ?>" name="date[to]" id="date_to" />
		</label>
		<label style="float: right;">
			ID / Name:
			<input type="text" class="text medium" value="<?=$_REQUEST['keyword'] ?>" name="keyword" id="keyword" />
		</label>
	</form>
</div>

<table class="values nocheck">
	<tr>
		<th>ID</th>
		<th>Time</th>
		<th>Name</th>
		<th class="right">Paid</th>
		<th class="right" width="70">Shipped</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$orders->FetchRow())
	{
		if($class=="light")
		{
			$class="dark";
			$linkclass="white";
		}
		else
		{
			$class="light";
			$linkclass="a";
		}
		
		if($row['refunded'])
		{
			if($row['refunded'] != $row['paid'])
				$tr_class = 'class="refund-partial"';
			else
				$tr_class = 'class="refund"';
		}
		else
			$tr_class = '';

		if($row['dispatched'])
			$dispatched = '<img src="'.$config['dir'].'images/admin/tick.gif" width="16" height="16" alt="shipped"/>';
		else
			$dispatched = '';
			
		echo "
			<tr ".$tr_class.">
				<td class=\"$class\"><strong>{$row['id']}</strong></td>
				<td class=\"$class\">".date("H:i d/m/Y",$row['time'])."</td>
				<td class=\"$class\">{$row['name']}</td>
				<td class=\"$class right\"><strong>".price($row['paid'])."</strong></td>
				<td class=\"$class right\">".$dispatched."</strong></td>
				<td class=\"$class right\">";
		if($acl->check("viewRecord"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.viewRecord&order_id={$row['id']}\"><span>View</span/></a>";
		
		if($acl->check("dispatchOrder") && !$row['dispatched'])
			echo "<a href=\"#\" title=\"Dispatch Order\" class=\"button button-grey\" onclick=\"return postbackPrompt(
						this
						,'dispatchOrder'
						,['order_id']
						,[{$row['id']}]
						,'Please enter the shipping number'
						,'shipping_number');return false;\"><span>Ship</span></a>";
			
		echo "
				</td>
			</tr>\n";
	}
?>
</table>

<?
	$nr_pages = ceil($item_count / $items_per_page);
	$max_page_links = 10;
	
	if($nr_pages > 1)
	{
		echo '<div style="float: left; width: 100%; text-align: center; margin-bottom: 10px;"><br/>'.$item_count.' past orders<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.records';
			foreach($url as $row)
				$results_page[] = $row;
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
<br />
<div class="filters clearfix">
	<form method="get" action="<?=$config['dir'] ?>index.php">
		<input type="hidden" name="fuseaction" value="admin.workflow"/>
		<input type="hidden" name="records" value="1"/>
		
		<div class="legend">Pick and Pack List</div><br />
		<div class="form">
			<label for="start_date">Start Date</label>
			<input type="text" id="start_date" class="calendar" name="start_date" value="<?=$min_time?date('d/m/Y', $min_time):'' ?>" />

			<label for="end_date">End Date</label>
			<input type="text" id="end_date" class="calendar" name="end_date" value="<?=$max_time?date('d/m/Y', $max_time):'' ?>" />
			<span class="button button-grey right">
				<input class="submit" type="submit" value="Submit" />
			</span>
			
		</div>
	</form>
</div>