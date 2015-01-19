<footer id="page-footer" class="clearfix">
	<nav>
		<ul class="yui3-g">
		<?
			$footer = $elems->qry_MegaFooter();
			$slots = array(
				array(7)
				,array(6)
				,array(5,4)
				,array(3)
			);
			foreach($slots as $ids)
			{
				echo '<li class="yui3-u">';
				foreach($ids as $id)
				{
					$row = $footer['categories'][$id];
					echo '<h1><a href="'.category_url($row['id'], $row['name']).'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h1><ul>';
					if(!$row['hidden_new_products'])
						echo '<li><a href="'.category_url($row['id'], $row['name']).'/new">New Products</a></li>';
					foreach((array)$row['children'] as $child)
						echo '<li><a href="'.category_url($child['link_category_id']?$child['link_category_id']:$child['id'], $child['name']).'">'.htmlentities($child['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
					if(!$row['hidden_clearance'])
						echo '<li><a href="'.category_url($row['id'], $row['name']).'/special">Special/Clearance</a></li>';
					echo '</ul>';
				}
				echo '</li>';
			}
			
			echo '
				<li class="yui3-u">
					<span style="font-weight: bold; color: #48433C;">Follow us on</span><br />
					<a href="http://www.facebook.com/BlochUSA" target="_blank" style="width: 25px; height: 25px; display: inline-block; margin: 0.75em 0;"><img src="'.$config['layout_dir'].'images/facebook-small-logo.jpg" width="25" height="25" alt=""/></a>
					<a href="https://twitter.com/BlochDance_USA" target="_blank" style="width: 25px; height: 25px; display: inline-block; margin: 0.75em 0;"><img src="'.$config['layout_dir'].'images/twitter-small-logo.jpg" width="25" height="25" alt=""/></a><br />
					<span style="width: 105px; float: left;">for all the latest BLOCH news and offers.</span>
				</li>';
		?>
		<?
			$rows = array();
			foreach($footer['pages'] as $row)
				if(count($row['children']))
					$rows[$row['id']] = 1 + count($row['children']);
				else
					$rows[$row['id']] = 1;
			$middle = array_sum($rows)/2;
			$first_column = array();
			$second_column = array();
			$sum = 0;
			foreach($rows as $id=>$count)
			{
				if($sum < $middle)
					$first_column[$id] = 1;
				else
					$second_column[$id] = 1;
					
				$sum += $count;
			}
		?>
			<li class="yui3-u">
			<?
				foreach($footer['pages'] as $row)
				{
					if(!$first_column[$row['id']])
						continue;
					echo '<h1><a href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h1>';
					if(count($row['children']))
					{
						echo '<ul>';
						foreach($row['children'] as $child)
							echo '<li><a href="'.$config['dir'].$child['url'].'">'.htmlentities($child['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
						echo '</ul>';
					}
				}
			?>
			</li>
			<li class="yui3-u">
			<?
				foreach($footer['pages'] as $row)
				{
					if(!$second_column[$row['id']])
						continue;
					echo '<h1><a href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h1>';
					if(count($row['children']))
					{
						echo '<ul>';
						foreach($row['children'] as $child)
							echo '<li><a href="'.$config['dir'].$child['url'].'">'.htmlentities($child['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
						echo '</ul>';
					}
				}
				echo '<h1><a href="'.$config['dir'].'zip-search">Store Locator</a></h1>';
			?>
			</li>
		</ul>
	</nav>
	<p>
		&copy;<?=date('Y') ?> Bloch Ltd
		<?
			$footer = $elems->qry_Footer();
			foreach($footer as $row)
				echo '<a href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a>';
		?>

		<span>
			<!--<img src="<?=$config['layout_dir'] ?>images/paypall.gif" alt="paypall" width="30" height="20">-->
			<img src="<?=$config['layout_dir'] ?>images/visa.gif" alt="visa" width="30" height="20">
			<img src="<?=$config['layout_dir'] ?>images/mastercard.gif" alt="mastercard" width="30" height="20">
		</span>
		<a href="http://webstarsltd.com/" title="webstars - exceptional digital" class="webstars">webstars</a>
	</p>
</footer>