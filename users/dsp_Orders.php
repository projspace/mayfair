<script type="text/javascript">
	(function($){
		$(document).ready(function() {
			// launch colorbox
			$('.orders .order').click(function(){
				var href = $(this).attr('href');
				$.colorbox({
					href: href,
					opacity: 0.3,
					width:550,
					height:400,
					close:false,
					iframe: true
				});
				return false;
			});
		});
	})(this.jQuery);
</script>

<style type="text/css">
	table.orders { width: 100%; }
	table.orders th { background-color: #636466; color: #fff; padding: 3px 7px; }
	table.orders td { padding: 3px 7px; }
	table.orders th.right { text-align: right; padding-right: 15px; }
	table.orders td.right { text-align: right; padding-right: 15px; }
	table.orders tr.light td { background-color: #F3F3F4; }
	table.orders tr.dark td { background-color: #b3b5b9; }
</style>

<article id="innerShop">
	<p>Introdutcio to basket an hent at augiat. At dolore er in ulputpat, sis dit exerat. Rud tincilla aute moluptat. Per sequam quat utatie dolut alisit adiam, quip ea coreetue volum vel euisis nissenim exe.</p>
</article>
<article id="shopContent">
	<div class="splitTwo"><h3>Your Orders</h3></div>

	<table class="orders">
		<tr>
			<th>Order Date</th>
			<th>Order Number</th>
			<th class="right">Amount</th>
			<th>Status</th>
		</tr>
	<?
		while($row=$orders->FetchRow())
		{
			if($class=="light")
				$class="dark";
			else
				$class="light";
				
			if($row['time'])
				$time = date('d/m/Y', $row['time']);
			else
				$time = '';

			if($row['dispatched'])
				$status = 'Shipped';
			elseif($row['processed'])
				$status = 'Processed';
			elseif($row['id'])
				$status = 'In Progress';
			else
				$status = 'Pending';
				
			if($row['paid']+0)
				$paid = price($row['paid']);
			else
				$paid = '';
				
			echo '<tr class="'.$class.'">
				<td><a class="order" href="'.$config['dir'].'account/order/'.$row['id'].'">'.$time.'</td>
				<td>'.$row['id'].'</td>
				<td class="right">'.$paid.'</td>
				<td>'.$status.'</td>
			</tr>';
		}
	?>
	</table>
	<br />
	<p class="catPager">
	<?
		$nr_pages = ceil($item_count / $items_per_page);
		$max_page_links = 10;
		
		if($nr_pages > 1)
		{
			$results_page = array();
			$results_page = $config['dir'].'account/orders?'.implode('&', $results_page).'&page=';
				
			if($nr_pages > $max_page_links)
				echo '<a class="prev" href="'.$results_page.'1">First</a> ';
				
			if($page == 1)
				echo '<a class="prev" href="#">Previous</a> ';
			else
				echo '<a class="prev" href="'.$results_page.($page - 1).'">Previous</a> ';
			
			for($i = $page - floor($max_page_links/2); $i < $page + ceil($max_page_links/2); $i++)
				if(($i > 0)&&($i <= $nr_pages))
				{
					if($i == $page)
						echo '<a class="on" href="'.$results_page.$i.'">'.$i.'</a> ';
					else
						echo '<a href="'.$results_page.$i.'">'.$i.'</a> ';
				}
			
			if($page == $nr_pages)
				echo '<a class="next" href="#">Next</a> ';
			else
				echo '<a class="next" href="'.$results_page.($page + 1).'">Next</a> ';
				
			if($nr_pages > $max_page_links)
				echo '<a class="next" href="'.$results_page.$nr_pages.'">Last</a>';
		}
	?>
	</p>
	<br/>
	<br/>
	<br/>
	<br/>
</article>
