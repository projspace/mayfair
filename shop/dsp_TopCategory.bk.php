<div id="content-wrapper" class="yui3-g">
	<aside id="sidebar" class="yui3-u filters">
		<h1>Browse <?=htmlentities($category['name'], ENT_NOQUOTES, 'UTF-8') ?></h1>

		<?
			$main_category = false;
			foreach($subcategories as $row)
				if($row['main_category'])
					$main_category = true;
					
			if($main_category)
			{
				foreach($subcategories as $row)
				{
					echo '<h2>'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</h2><ul class="subcats">';
					if(!$row['hidden_new_products'])
						echo '<li><a href="'.category_url($row['id'], $row['name']).'/new">New Products</a></li>';
					foreach($row['children'] as $child)
						echo '<li><a href="'.category_url($child['link_category_id']?$child['link_category_id']:$child['id'], $child['name']).'">'.$child['name'].'</a></li>';
					if(!$row['hidden_clearance'])
						echo '<li><a href="'.category_url($row['id'], $row['name']).'/special">Special/Clearance</a></li>';
					echo '</ul>';
				}
			}
			else
			{
				echo '<ul class="subcats">';
				if(!$category['hidden_new_products'])
					echo '<li><a href="'.category_url($category['id'], $category['name']).'/new">New Products</a></li>';
				foreach($subcategories as $row)
					echo '<li><a href="'.category_url($row['link_category_id']?$row['link_category_id']:$row['id'], $row['name']).'">'.$row['name'].'</a></li>';
				if(!$category['hidden_clearance'])
					echo '<li><a href="'.category_url($category['id'], $category['name']).'/special">Special/Clearance</a></li>';
				echo '</ul>';
			}
		?>
	</aside>
	<div id="content" class="yui3-u">
		<article id="category-overview">
			<header class="content-box">
				<h1><?=htmlentities($category['name'], ENT_NOQUOTES, 'UTF-8') ?></h1>
			</header>
			<section class="content-box">
				<ul class="grid yui3-g clearfix">
				<?
					while($row = $boxes->FetchRow())
					{
						$size = array();
						switch($row['type'])
						{
							case 'big1':
								$class = 'w2-3 h4';
								$size[0] = 490;
								$size[1] = 430;
								break;
							case 'small':
								$class = 'w1-3 h4';
								$size[0] = 242;
								$size[1] = 430;
								break;
							case 'big2':
								$class = 'w1-2 h3';
								$size[0] = 366;
								$size[1] = 496;
								break;
							case 'small1':
								$class = 'w1-2 h1';
								$size[0] = 366;
								$size[1] = 196;
								break;
							case 'small2':
								$class = 'w1-2 h2';
								$size[0] = 366;
								$size[1] = 296;
								break;
							default:
								$class = '';
								break;
						}
						switch($row['label'])
						{
							case 'new_product':
								$label = '<em class="tag tag-new">New product</em>';
								break;
							case 'best_seller':
								$label = '<em class="tag tag-best">Best seller</em>';
								break;
							case 'on_sale':
								$label = '<em class="tag tag-sale">On Sale</em>';
								break;
							case 'bloch_stars':
								$label = '<em class="tag tag-stars">Bloch stars</em>';
								break;
							case 'none':
							default:
								$label = '';
								break;
						}
						
						if($row['image_type'])
							$image = $config['dir'].'images/box_items/'.$row['id'].'.'.$row['image_type'];
						else
							$image = '';
						
						if($row['link_id']+0)
						{
							switch($row['link_type'])
							{
								case 'product':
									$url = product_url($row['link_id'], $row['product_guid']);
									break;
								case 'category':
									$url = category_url($row['link_id'], $row['category_name']);
									break;
								default:
									$url = '#';
									break;
							}
						}
						else
							$url = '#';
						
						echo '
							<li class="yui3-u '.$class.'">
								'.$label.'
								<a href="'.$url.'" >
									<img src="'.$image.'" width="'.$size[0].'" height="'.$size[1].'" alt="">
									<span class="caption">
										<span class="product-name">'.htmlentities($row['title'], ENT_NOQUOTES, 'UTF-8').'</span>
										<em>'.((trim($row['small_title']) != '')?htmlentities($row['small_title'], ENT_NOQUOTES, 'UTF-8'):'View full product').'</em>
									</span>
								</a>
							</li>';
					}
				?>
				</ul>
			</section>
		</article>
	</div>
</div>