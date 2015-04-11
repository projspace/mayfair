<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#frmFilter :checkbox, #frmFilter :radio').click(function(){
			$('#frmFilter').submit();
		});
		
		$('#date_custom').change(function(){
			$('#date_from, #date_to').val('');
			$('#frmFilter').submit();
		});
		
		$('#date_from, #date_to').change(function(){
			$('#date_custom').val('');
			$('#frmFilter').submit();
		});
	});
/* ]]> */
</script>


<?
	$url = array();
	$url[] = $config['dir'].'index.php?fuseaction=admin.sales_reports';
	if(isset($_REQUEST['date']))
	{
		$url[] = 'date[custom]='.urlencode($_REQUEST['date']['custom']);
		$url[] = 'date[from]='.urlencode($_REQUEST['date']['from']);
		$url[] = 'date[to]='.urlencode($_REQUEST['date']['to']);
	}
	$src_url = $url;
	$url  = implode('&amp;', $url);
?>

<h1 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=export';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Export" href="<?=$export_url ?>"><span>Export</span></a>
	Sales Reports
</h1>

<div class="filters dashboard-filters clearfix">
	<form id="frmFilter" action="<?= $config['dir'] ?>index.php" method="get">
		<input type="hidden" name="fuseaction" value="admin.sales_reports" />
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
		<div class="label">
			<span>Total sales: <?=price($sales['total']) ?></span>
		</div>
	</form>
</div>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Code" href="<?=$url ?>&sort=code&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'code') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Code</a>
			</th>
			<th class="sortable">
				<a title="Sort on Name" href="<?=$url ?>&sort=name&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'name') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Name</a>
			</th>
			<th class="sortable">
				<a title="Sort on Average Price" href="<?=$url ?>&sort=price&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'price') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Average Price($)</a>
			</th>
			<th class="sortable">
				<a title="Sort on Sold" href="<?=$url ?>&sort=quantity&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'quantity') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Sold</a>
			</th>
			<th class="sortable">
				<a title="Sort on Total Value" href="<?=$url ?>&sort=total&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'total') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Total($)</a>
			</th>
			<th class="sortable">
				<a title="Sort on Average Discount" href="<?=$url ?>&sort=avg_discount&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'avg_discount') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Average Discount($)</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Discount" href="<?=$url ?>&sort=total_discount&sort_dir=<?=($sort_dir == 'asc')?'desc':'asc' ?>" class="sort <? if($sort_field == 'total_discount') echo ($sort_dir == 'asc')?'desc':'asc' ?>">Discount($)</a>
			</th>
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $products->FetchRow())
			echo '
				<tr class="nocheck">
					<td>'.$row['code'].'</td>
					<td class="name">'.$row['name'].'</td>
					<td>'.number_format($row['price'], 2, '.', ',').'</td>
					<td>'.$row['quantity'].'</td>
					<td>'.number_format($row['total'], 2, '.', ',').'</td>
					<td>'.number_format($row['avg_discount'], 2, '.', ',').'</td>
					<td>'.number_format($row['total_discount'], 2, '.', ',').'</td>
				</tr>';
	?>
	</tbody>
</table>