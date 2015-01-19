<script language="javascript" type="text/javascript" src="<?=$config['dir'] ?>lib/flot/excanvas.js"></script>
<script language="javascript" type="text/javascript" src="<?=$config['dir'] ?>lib/flot/jquery.flot.js"></script>
<script id="source" language="javascript" type="text/javascript">
/* <![CDATA[ */
$(function () {
	<?
		$offset = idate(Z);
		$data = array();
		for($i=$date_from;$i<=$date_to;$i+=86400)
		{
			$m = strtotime(gmdate('Y-m-d', $i+$offset));
			$data[$m] = 0;
		}

		while($row = $orders->FetchRow())
			$data[strtotime($row['date'])] = $row['count'];
		
		ksort($data, SORT_NUMERIC);
		
		$values = array();
		foreach($data as $timestamp=>$value)
			$values[] = '['.$timestamp.'*1000,'.$value.']';
	?>
    var values_orders = [<?=implode(',', $values) ?>];

	$.plot(
		$("#placeholder_orders"),
		[ { data: values_orders} ],
		{
			series: {
				lines: { show: true },
				points: { show: true }
			},
			grid: { hoverable: true, clickable: false },
			xaxis: { 
				mode: "time",
                min: <?=$date_from*1000 ?>,
                max: <?=$date_to*1000 ?>,
				tickFormatter:function(val, axis){
					return '';
				} 
			}/*,
			yaxis: { 
				tickSize: 5,
				tickFormatter:function(val, axis){
					return parseInt(val);
				} 
			}*/
		}
	);
	
	<?
		$data = array();
		for($i=$date_from;$i<=$date_to;$i+=86400)
		{
			$m = strtotime(gmdate('Y-m-d', $i+$offset));
			$data[$m] = 0;
		}
		
		while($row = $amounts->FetchRow())
			$data[strtotime($row['date'])] = $row['amount'];
		
		$values = array();
		foreach($data as $timestamp=>$value)
			$values[] = '['.$timestamp.'*1000,'.$value.']';
	?>
    var values_amounts = [<?=implode(',', $values) ?>];

	$.plot(
		$("#placeholder_amounts"),
		[ { data: values_amounts} ],
		{
			series: {
				lines: { show: true },
				points: { show: true }
			},
			grid: { hoverable: true, clickable: false },
			xaxis: { 
				mode: "time",
                min: <?=$date_from*1000 ?>,
                max: <?=$date_to*1000 ?>,
				tickFormatter:function(val, axis){
					return '';
				} 
			}
		}
	);
	
	<?
		$data = array();
		for($i=$date_from;$i<=$date_to;$i+=86400)
			$data[$i.'*1000'] = 0;
		
		while($row = $registrations->FetchRow())
			$data[strtotime($row['date']).'*1000'] = $row['count'];
		
		$values = array();
		foreach($data as $timestamp=>$value)
			$values[] = '['.$timestamp.','.$value.']';
	?>
	var values_registrations = [<?=implode(',', $values) ?>];

	$.plot(
		$("#placeholder_registrations"),
		[ { data: values_registrations} ],
		{
			series: {
				lines: { show: true },
				points: { show: true }
			},
			grid: { hoverable: true, clickable: false },
			xaxis: { 
				mode: "time",
                min: <?=$date_from*1000 ?>,
                max: <?=$date_to*1000 ?>,
				tickFormatter:function(val, axis){
					return '';
				} 
			}
		}
	);

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y - 30,
            left: x - 20,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#placeholder_orders, #placeholder_amounts, #placeholder_registrations").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

		if (item) {
			if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
				
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
				
				//showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
				var date = new Date(parseInt(x));
				var day = date.getDate();
				if(day < 10)
					day = '0'+day;
				var month = date.getMonth()+1;
				if(month < 10)
					month = '0'+month;
				var year = date.getFullYear();
				
				var msg = '';
				switch ($(this).attr('id')){
					case 'placeholder_orders': 
						msg = parseInt(y)+' <?=($display == 'abandoned')?'abandoned cart(s)':'order(s)' ?>';
						break;
					case 'placeholder_amounts':  
						msg = '$'+parseInt(y);
						break;
					case 'placeholder_registrations':  
						msg = parseInt(y)+' registration(s)';
						break;
				}

				
				showTooltip(item.pageX, item.pageY, msg+' on '+day+'/'+month+'/'+year);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;            
		}
    });
});
/* ]]> */
</script>
<style type="text/css">
/* <![CDATA[ */
div.legend { font-size: 10px; font-weight: bold; padding: 4px 10px 4px 5px; }
div.form { border: 1px solid #CACACA; }
.placeholder div.legend { background-color: transparent; color: #545454; font-size: medium; font-weight: normal; padding: 0; }
.placeholder table { width: auto; padding: 0; margin: 0; }
/* ]]> */
</style>

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

<h1 class="pageTitle">
	<a class="button button-help button-slide" title="Help for this page" href="#" rel="help-container"><span>Help</span></a>
	<? if($acl->check("uploadStock")): ?><a class="button button-grey button-slide" title="Import Stock CSV" href="#" rel="import-container"><span>Import Stock CSV</span></a><? endif; ?>
	<? if($acl->check("orders") && 0): ?><a class="button button-grey" title="Export Web Orders" href="<?=$config['dir'] ?>index.php?fuseaction=admin.webOrders" rel="import-container"><span>Export Web Orders</span></a><? endif; ?>
	<? if($acl->check("products")): ?><a class="button button-grey" title="Export Stock CSV" href="<?=$config['dir'] ?>index.php?fuseaction=admin.webStock" rel="import-container"><span>Export Stock CSV</span></a><? endif; ?>
	<? if($acl->check("users")): ?><a class="button button-grey" title="Export Customers" href="<?=$config['dir'] ?>index.php?fuseaction=admin.webUsers" rel="import-container"><span>Export Customers</span></a><? endif; ?>
	Dashboard
</h1>

<div class="help-container" id="help-container">
	<div class="help-title">Welcome to your Dashboard</div>
	<div class="help-content">
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempus aliquam feugiat. Donec ac justo orci. Pellentesque tincidunt tempus facilisis. Suspendisse facilisis iaculis justo at dapibus. Sed dictum vehicula dictum. Fusce feugiat quam ac risus molestie congue. Mauris porttitor porttitor orci, vel convallis lectus venenatis sed. Maecenas tincidunt fringilla ornare. Proin at leo in elit consectetur facilisis ac eget nisl. Sed sagittis condimentum tincidunt. Ut id placerat sem. </p>
	</div>
</div>
<div class="help-container" id="import-container">
	<div class="help-title">Import Stock CSV</div>
	<div class="help-content">
		<form enctype="multipart/form-data" action="<?= $config['dir'] ?>index.php?fuseaction=admin.uploadStock" method="post">
			<div class="form-field clearfix">
				<label for="image">Stock File</label>
				<input type="file" id="stock" name="stock" /><br />
			</div>
			<div class="tab-panel-buttons clearfix" style="width: 100%;">
				<label style="width: auto;"><a href="<?=$config['dir'] ?>downloads/example-stock.csv" target="_blank">Please see example CSV here</a>.</label>
				<span class="button button-small submit">
					<input class="submit" type="submit" value="Import" />
				</span>
			</div>
			<br clear="all"/>
		</form>
	</div>
</div>


<div id="col-right">
	
	<div class="filters dashboard-filters clearfix">
	<form id="frmFilter" action="<?= $config['dir'] ?>index.php" method="get">
		<input type="hidden" name="fuseaction" value="admin.start" />
		<label>
			Date range
			<select name="date[custom]" id="date_custom">
				<option value="">Please Select</option>
				<option value="all" <? if($_REQUEST['date']['custom'] == 'all'): ?>selected="selected"<? endif; ?>>All</option>
				<option value="7_days" <? if($_REQUEST['date']['custom'] == '7_days'): ?>selected="selected"<? endif; ?>>Last 7 Days</option>
				<option value="30_days" <? if($_REQUEST['date']['custom'] == '30_days'): ?>selected="selected"<? endif; ?>>Last 30 Days</option>
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
			<span>Show:</span>
			<label><input type="radio" name="display" value="orders" <? if($display == 'orders'): ?>checked="checked"<? endif; ?> /> Paid Orders</label>
			<label><input type="radio" name="display" value="abandoned" <? if($display == 'abandoned'): ?>checked="checked"<? endif; ?> /> Abandoned Carts</label>
		</div>
	</form>
	</div>
	
	
	<div id="orders-tabs" class="inner-tabs">
		<ul>
			<li><a href="#tab-orders">Orders</a></li>
			<li><a href="#tab-amounts">Amounts</a></li>
			<li><a href="#tab-new-registrations">New Registrations</a></li>
		</ul>
		<div id="tab-orders">
			<div style="position:relative;">
				<div class="placeholder" id="placeholder_orders" style="width: 747px; height: 180px;"></div>
			</div>
		</div>
		<div id="tab-amounts">
			<div style="position:relative;">
				<div class="placeholder" id="placeholder_amounts" style="width: 747px; height: 180px;"></div>
			</div>
		</div>
		<div id="tab-new-registrations">
			<div style="position:relative;">
				<div class="placeholder" id="placeholder_registrations" style="width: 747px; height: 180px;"></div>
			</div>
		</div>
	</div>
	
	
	
	<div id="sales-summary-tabs" class="inner-tabs">
		<ul>
			<li><a href="#tab-10-bestsellers">10 Bestsellers</a></li>
			<li><a href="#tab-most-viewd-products">Most Viewed Products</a></li>
			<li><a href="#tab-countries">Delivery Countries</a></li>
		</ul>
		<div id="tab-10-bestsellers">
			<div style="position:relative;">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
					<thead>
						<tr class="nocheck">
							<th class="sortable first">
								<a title="Sort on Name" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=bestsellers&sort=name&sort_dir=<?=($sort['bestsellers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['bestsellers']['field'] == 'name') echo ($sort['bestsellers']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
							</th>
							<th class="sortable">
								<a title="Sort on Price" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=bestsellers&sort=price&sort_dir=<?=($sort['bestsellers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['bestsellers']['field'] == 'price') echo ($sort['bestsellers']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
							</th>
							<th class="sortable last">
								<a title="Sort on Quantity" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=bestsellers&sort=count&sort_dir=<?=($sort['bestsellers']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['bestsellers']['field'] == 'count') echo ($sort['bestsellers']['dir'] == 'asc')?'desc':'asc' ?>">Quantity</a>
							</th>	
						</tr>
					</thead>
					<tbody>
					<?
						while($row = $bestsellers->FetchRow())
							echo '
								<tr class="nocheck">
									<td class="name">
										<a href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&category_id='.$row['category_id'].'&product_id='.$row['id'].'">View</a>
										'.$row['name'].'
									</td>
									<td>'.number_format($row['price'], 2, '.', ',').'</td>
									<td>'.$row['count'].'</td>
								</tr>';
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div id="tab-most-viewd-products">
			<div style="position:relative;">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
					<thead>
						<tr class="nocheck">
							<th class="sortable first">
								<a title="Sort on Name" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=most_viewed&sort=name&sort_dir=<?=($sort['most_viewed']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['most_viewed']['field'] == 'name') echo ($sort['most_viewed']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
							</th>
							<th class="sortable">
								<a title="Sort on Price" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=most_viewed&sort=price&sort_dir=<?=($sort['most_viewed']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['most_viewed']['field'] == 'price') echo ($sort['most_viewed']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
							</th>
							<th class="sortable last">
								<a title="Sort on Quantity" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=most_viewed&sort=count&sort_dir=<?=($sort['most_viewed']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['most_viewed']['field'] == 'count') echo ($sort['most_viewed']['dir'] == 'asc')?'desc':'asc' ?>">Quantity</a>
							</th>	
						</tr>
					</thead>
					<tbody>
					<?
						while($row = $most_viewed->FetchRow())
							echo '
								<tr class="nocheck">
									<td class="name">
										<a href="'.$config['dir'].'index.php?fuseaction=admin.editProduct&category_id='.$row['category_id'].'&product_id='.$row['id'].'">View</a>
										'.$row['name'].'
									</td>
									<td>'.number_format($row['price'], 2, '.', ',').'</td>
									<td>'.$row['count'].'</td>
								</tr>';
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div id="tab-countries">
			<div style="position:relative;">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="values">
					<thead>
						<tr class="nocheck">
							<th class="sortable first">
								<a title="Sort on Name" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=countries&sort=name&sort_dir=<?=($sort['countries']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['countries']['field'] == 'name') echo ($sort['countries']['dir'] == 'asc')?'desc':'asc' ?>">Name</a>
							</th>
							<th class="sortable">
								<a title="Sort on Price" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=countries&sort=price&sort_dir=<?=($sort['countries']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['countries']['field'] == 'price') echo ($sort['countries']['dir'] == 'asc')?'desc':'asc' ?>">Price($)</a>
							</th>
							<th class="sortable last">
								<a title="Sort on Quantity" href="<?=$config['dir'] ?>index.php?fuseaction=admin.start&sort_table=countries&sort=count&sort_dir=<?=($sort['countries']['dir'] == 'asc')?'desc':'asc' ?>" class="sort <? if($sort['countries']['field'] == 'count') echo ($sort['countries']['dir'] == 'asc')?'desc':'asc' ?>">Quantity</a>
							</th>	
						</tr>
					</thead>
					<tbody>
					<?
						while($row = $countries->FetchRow())
							echo '
								<tr class="nocheck">
									<td class="name">
										'.$row['name'].'
									</td>
									<td>'.number_format($row['price'], 2, '.', ',').'</td>
									<td>'.$row['count'].'</td>
								</tr>';
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		 $(function(){
			$('#orders-tabs').tabs();
			$('#sales-summary-tabs').tabs();
			$("th.sortable a").hover(
				function(){$(this).addClass($(this).hasClass("desc") ? "asc1" : "desc1")},
				function(){$(this).removeClass("asc1").removeClass("desc1");}
			);
			<?
				switch($sort_table)
				{
					case 'most_viewed':
						$tab_index = 1;
						break;
					case 'countries':
						$tab_index = 2;
						break;
					case 'bestsellers':
					default:
						$tab_index = 0;
						break;
				}
			?>
			$('#sales-summary-tabs').tabs('select', <?=$tab_index ?>);
		});
	</script>
	
	
	
	
	
</div>

<div id="col-left">
	
	<div class="widget">
		<h3><?=$new_orders ?> New Order(s)</h3>
		<div class="widget-content">
			<a href="<?=$config['dir'] ?>index.php?fuseaction=admin.orders">Process</a>
		</div>
	</div>
	
	<div class="widget">
		<h3>Lifetime Sales:</h3>
		<div class="widget-content">
			<table>
				<tr>
					<td style="width: 80px;">Value</td>
					<td><?=price($sales['total']) ?></td>
				</tr>
				<tr>
					<td style="width: 80px;">Item(s)</td>
					<td><?=number_format($items['total'], 0, '.', ',') ?></td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="widget">
		<h3>Average Order:</h3>
		<div class="widget-content">
			<table>
				<tr>
					<td style="width: 80px;">Value</td>
					<td><?=price($sales['average']) ?></td>
				</tr>
				<tr>
					<td style="width: 80px;">Item(s)</td>
					<td><?=number_format($items['average'], 2, '.', ',') ?></td>
				</tr>
			</table>
		</div>
	</div>
	
	<? if($last_order): ?>
	<div class="widget">
		<h3>Last Order:</h3>
		<div class="widget-content">
			<table>
				<tr>
					<td style="width: 80px;">Value</td>
					<td><?=price($last_order['paid']) ?></td>
				</tr>
				<tr>
					<td style="width: 80px;">Item(s)</td>
					<td><?=number_format($last_order['count'], 0, '.', ',') ?></td>
				</tr>
			</table>
			<a href="<?=$config['dir'] ?>index.php?fuseaction=admin.viewOrder&order_id=<?=$last_order['id'] ?>">View</a>
		</div>
	</div>
	<? endif; ?>
</div>