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
	$url[] = $config['dir'].'index.php?fuseaction=admin.reports';
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
	Reports
</h1>

<div class="filters dashboard-filters clearfix">
	<form id="frmFilter" action="<?= $config['dir'] ?>index.php" method="get">
		<input type="hidden" name="fuseaction" value="admin.reports" />
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

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=bsq';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Best Sellers by Quantity
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Name" href="<?=$url ?>&sort_table=best_sellers_quantity&sort=name&sort_dir=<?=($sort['best_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_sellers_quantity']['field'] == 'name') echo ($sort['best_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
			</th>
			<th class="sortable">
				<a title="Sort on Price" href="<?=$url ?>&sort_table=best_sellers_quantity&sort=price&sort_dir=<?=($sort['best_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_sellers_quantity']['field'] == 'price') echo ($sort['best_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Quantity" href="<?=$url ?>&sort_table=best_sellers_quantity&sort=count&sort_dir=<?=($sort['best_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_sellers_quantity']['field'] == 'count') echo ($sort['best_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>">Quantity</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $best_sellers_quantity->FetchRow())
			echo '
				<tr class="nocheck">
					<td class="name">
						<a style="float: right;" href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&category_id='.$row['category_id'].'&product_id='.$row['id'].'">View</a>
						'.$row['name'].'
					</td>
					<td>'.number_format($row['price'], 2, '.', ',').'</td>
					<td>'.$row['count'].'</td>
				</tr>';
	?>
	</tbody>
</table>

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=bsv';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Best Sellers by Value
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Name" href="<?=$url ?>&sort_table=best_sellers_value&sort=name&sort_dir=<?=($sort['best_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_sellers_value']['field'] == 'name') echo ($sort['best_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
			</th>
			<th class="sortable">
				<a title="Sort on Price" href="<?=$url ?>&sort_table=best_sellers_value&sort=price&sort_dir=<?=($sort['best_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_sellers_value']['field'] == 'price') echo ($sort['best_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Value" href="<?=$url ?>&sort_table=best_sellers_value&sort=value&sort_dir=<?=($sort['best_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_sellers_value']['field'] == 'value') echo ($sort['best_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>">Value($)</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $best_sellers_value->FetchRow())
			echo '
				<tr class="nocheck">
					<td class="name">
						<a style="float: right;" href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&category_id='.$row['category_id'].'&product_id='.$row['id'].'">View</a>
						'.$row['name'].'
					</td>
					<td>'.number_format($row['price'], 2, '.', ',').'</td>
					<td>'.number_format($row['value'], 2, '.', ',').'</td>
				</tr>';
	?>
	</tbody>
</table>

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=wsq';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Worst Sellers by Quantity
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Name" href="<?=$url ?>&sort_table=worst_sellers_quantity&sort=name&sort_dir=<?=($sort['worst_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_sellers_quantity']['field'] == 'name') echo ($sort['worst_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
			</th>
			<th class="sortable">
				<a title="Sort on Price" href="<?=$url ?>&sort_table=worst_sellers_quantity&sort=price&sort_dir=<?=($sort['worst_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_sellers_quantity']['field'] == 'price') echo ($sort['worst_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Quantity" href="<?=$url ?>&sort_table=worst_sellers_quantity&sort=count&sort_dir=<?=($sort['worst_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_sellers_quantity']['field'] == 'count') echo ($sort['worst_sellers_quantity']['dir'] == 'asc')?'desc':'asc' ?>">Quantity</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $worst_sellers_quantity->FetchRow())
			echo '
				<tr class="nocheck">
					<td class="name">
						<a style="float: right;" href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&category_id='.$row['category_id'].'&product_id='.$row['id'].'">View</a>
						'.$row['name'].'
					</td>
					<td>'.number_format($row['price'], 2, '.', ',').'</td>
					<td>'.$row['count'].'</td>
				</tr>';
	?>
	</tbody>
</table>

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=wsv';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Worst Sellers by Value
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Name" href="<?=$url ?>&sort_table=worst_sellers_value&sort=name&sort_dir=<?=($sort['worst_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_sellers_value']['field'] == 'name') echo ($sort['worst_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
			</th>
			<th class="sortable">
				<a title="Sort on Price" href="<?=$url ?>&sort_table=worst_sellers_value&sort=price&sort_dir=<?=($sort['worst_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_sellers_value']['field'] == 'price') echo ($sort['worst_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Value" href="<?=$url ?>&sort_table=worst_sellers_value&sort=value&sort_dir=<?=($sort['worst_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_sellers_value']['field'] == 'value') echo ($sort['worst_sellers_value']['dir'] == 'asc')?'desc':'asc' ?>">Value($)</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $worst_sellers_value->FetchRow())
			echo '
				<tr class="nocheck">
					<td class="name">
						<a style="float: right;" href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&category_id='.$row['category_id'].'&product_id='.$row['id'].'">View</a>
						'.$row['name'].'
					</td>
					<td>'.number_format($row['price'], 2, '.', ',').'</td>
					<td>'.number_format($row['value'], 2, '.', ',').'</td>
				</tr>';
	?>
	</tbody>
</table>

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=bc';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Most Active Customers
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Email" href="<?=$url ?>&sort_table=best_customers&sort=name&sort_dir=<?=($sort['best_customers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_customers']['field'] == 'name') echo ($sort['best_customers']['dir'] == 'asc')?'desc':'asc' ?>">Email</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Value" href="<?=$url ?>&sort_table=best_customers&sort=value&sort_dir=<?=($sort['best_customers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['best_customers']['field'] == 'value') echo ($sort['best_customers']['dir'] == 'asc')?'desc':'asc' ?>">Value($)</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $best_customers->FetchRow())
			echo '
				<tr class="nocheck">
					<td>'.$row['email'].'</td>
					<td>'.number_format($row['value'], 2, '.', ',').'</td>
				</tr>';
	?>
	</tbody>
</table>

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=wc';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Least Active Customers
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Email" href="<?=$url ?>&sort_table=worst_customers&sort=name&sort_dir=<?=($sort['worst_customers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_customers']['field'] == 'name') echo ($sort['worst_customers']['dir'] == 'asc')?'desc':'asc' ?>">Email</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Value" href="<?=$url ?>&sort_table=worst_customers&sort=value&sort_dir=<?=($sort['worst_customers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['worst_customers']['field'] == 'value') echo ($sort['worst_customers']['dir'] == 'asc')?'desc':'asc' ?>">Value($)</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $worst_customers->FetchRow())
			echo '
				<tr class="nocheck">
					<td>'.$row['email'].'</td>
					<td>'.number_format($row['value'], 2, '.', ',').'</td>
				</tr>';
	?>
	</tbody>
</table>

<h2 class="pageTitle">
	<?
		$export_url = $src_url;
		$export_url[] = 'act=full_export';
		$export_url[] = 'src=idle';
		$export_url  = implode('&amp;', $export_url);
	?>
	<a class="button button-grey button-slide" title="Full Export" href="<?=$export_url ?>"><span>Full Export</span></a>
	Customers who have not purchased in over a year
</h2>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
	<thead>
		<tr class="nocheck">
			<th class="sortable first">
				<a title="Sort on Email" href="<?=$url ?>&sort_table=idle_customers&sort=name&sort_dir=<?=($sort['idle_customers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['idle_customers']['field'] == 'name') echo ($sort['idle_customers']['dir'] == 'asc')?'desc':'asc' ?>">Email</a>
			</th>
			<th class="sortable last">
				<a title="Sort on Value" href="<?=$url ?>&sort_table=idle_customers&sort=value&sort_dir=<?=($sort['idle_customers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['idle_customers']['field'] == 'value') echo ($sort['idle_customers']['dir'] == 'asc')?'desc':'asc' ?>">Value($)</a>
			</th>	
		</tr>
	</thead>
	<tbody>
	<?
		while($row = $idle_customers->FetchRow())
			echo '
				<tr class="nocheck">
					<td>'.$row['email'].'</td>
					<td>'.number_format($row['value'], 2, '.', ',').'</td>
				</tr>';
	?>
	</tbody>
</table>