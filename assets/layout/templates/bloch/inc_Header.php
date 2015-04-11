<header id="page-header">
	<h1 id="logo"><a href="<?=$config['dir']//$config['site_url'] ?>">bloch - since 1932</a></h1>
	<nav>
		<ul>
		<?
			$menu = $elems->qry_Menu();
			foreach($menu['categories'] as $row)
			{
				echo '<li><a class="off" href="'.category_url($row['id'], $row['name']).'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a>';
				if(count($row['children']))
				{
					echo '<div class="sub"><div class="inner clearfix">';
					$main_category = false;
					foreach($row['children'] as $child)
						if($child['main_category'])
							$main_category = true;
							
					if($main_category)
					{
						foreach($row['children'] as $child)
						{
							echo '<ul>';
							echo '<li class="title">'.$child['name'].'</li>';
							if(!$child['hidden_new_products'])
								echo '<li><a href="'.category_url($child['id'], $child['name']).'/new">New Products</a></li>';
							foreach($child['children'] as $subchild)
								echo '<li><a href="'.category_url($subchild['link_category_id']?$subchild['link_category_id']:$subchild['id'], $subchild['name']).'">'.htmlentities($subchild['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
							if(!$child['hidden_clearance'])
								echo '<li><a href="'.category_url($child['id'], $child['name']).'/special">Special/Clearance</a></li>';
							echo '</ul>';
						}
					}
					else
					{
						echo '<ul>';
						if(!$row['hidden_new_products'])
							echo '<li><a href="'.category_url($row['id'], $row['name']).'/new">New Products</a></li>';
						foreach($row['children'] as $child)
							echo '<li><a href="'.category_url($child['link_category_id']?$child['link_category_id']:$child['id'], $child['name']).'">'.htmlentities($child['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
						if(!$row['hidden_clearance'])
							echo '<li><a href="'.category_url($row['id'], $row['name']).'/special">Special/Clearance</a></li>';
						echo '</ul>';
					}
					echo '</div></div>';
				}
				echo '</li>';
			}
			foreach($menu['pages'] as $row)
			  {
				echo '<li><a class="off" href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
				if($row["id"]==12)
				  echo '<li><a class="off" href="'.$config['dir'].'zip-search">Store Locator</a></li>';
			  }
		?>
		</ul>
	</nav>
	<!-- country-selector -->
	<div id="country-selector">
		<label for="country">Choose country</label>
		<select name="country" id="country">
			<option value="USA">USA</option>
			<option value="UK">UK</option>
			<option value="AU">Australia</option>
		</select>
	</div>
	<!-- /country-selector -->
	<form method="get" action="<?=$config['dir'] ?>search" id="search">
		<label for="keywords">
			<span>Site Search</span>
		</label>
		<input type="text" name="keyword" id="keywords">
		<button type="submit"><em>send</em></button>
	</form>
	<p id="signupin" class="yui3-g">
	<? if($user_session->check()): ?>
		<a class="yui3-u-1 hello" href="<?=$config['dir'] ?>account">Hello: <?=$user_session->session->fields['firstname']?></a>
	<? else: ?>
		<a href="<?=$config['dir'] ?>login?ajax=1" class="in yui3-u-1-2">sign in</a>
		<a href="<?=$config['dir'] ?>register?ajax=1" class="up yui3-u-1-2">register</a>
	<? endif; ?>
	</p>
	<section id="cart">
	<?
		$cart = $elems->qry_Cart();
		$ul_contents = '';
		$total = 0;
		$nitems = 0;
		foreach($cart as $row)
		{
			if($row['image_type'])
				$image = $config['dir'].'images/product/medium/'.$row['image_id'].'.'.$row['image_type'];
			else
				$image = $config['dir'].'images/product/medium/placeholder.jpg';
				
			$options = array();
			if(trim($row['size']) != '')
				$options[] = 'Size '.$row['size'];
			if(trim($row['width']) != '')
				$options[] = 'Width '.$row['width'];
			if(trim($row['color']) != '')
				$options[] = 'Colour('.$row['color'].')';
			$options[] = 'Qty '.$row['cart_quantity'];
				
			$ul_contents .= '
				<li>
					<div class="vertical-img h128"><span class="middle-img"><img src="'.$image.'" alt="" width="128"/></span></div>
					<a href="#" class="btn-remove" cart_id="'.$row['cart_id'].'">remove</a>
					<h2>
						<strong>'.price($row['cart_price']).'</strong>
						<a href="'.product_url($row['id'], $row['guid']).'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a>
					</h2>
					<p>'.implode('<br />', $options).'</p>
				</li>';
				
			$total += $row['cart_quantity']*$row['cart_price'];
			$nitems += $row['cart_quantity'];
		}
	?>
		<div class="display"><a href="<?=$config['dir'] ?>cart" class="display">basket (<span class="cart_count"><?=$nitems ?></span>) <strong class="cart_total"><?=price($total) ?></strong></a></div>
		<div class="contents">
			<a href="#" class="prev">prev</a>
			<ul><?=$ul_contents ?></ul>
			<a href="#" class="next">next</a>
			<p class="buttons">
				<a href="<?=$config['dir'] ?>cart" class="btn-gray">View Cart</a>
				<?php /* ?><a href="<?=$config['dir'] ?>checkout" class="btn-red">Pay Securely Now</a><?php */?>
			</p>
		</div>
	</section>
</header>