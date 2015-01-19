<div id="content-wrapper" class="yui3-g">
	<aside id="sidebar" class="yui3-u">
		<ul id="cats">
		<?
			foreach($ranges as $row)
				echo '
					<li class="'.$row['class'].'">
						<a href="'.category_url($row['id'], $row['name']).'" >'.$row['name'].' <br />shop the range</a>
					</li>';
		?>
		</ul>
	</aside>
	<div id="content" class="yui3-u home">
		<article id="slideshow" class="content-box">
		<? 
			if($banner_type == 'multiple')
			{
				echo '<section>';
				while($row = $home_banners->FetchRow())
					echo '<img src="'.$config['dir'].'images/home_banners/'.$row['id'].'.'.$row['image_type'].'" alt="'.htmlentities($row['description'], ENT_COMPAT, 'UTF-8').'" width="733" height="765" data-button-text="'.htmlentities($row['label'], ENT_COMPAT, 'UTF-8').'" data-button-url="'.$row['url'].'">';
				echo '</section>';
				echo '<footer><a href="#" class="btn-green">Text here</a></footer>';
			}
			else
			{
				$banner = '<img src="'.$config['dir'].'images/home_banner.'.$home_banner['image_type'].'" width="733" height="823" alt="">';
				if(trim($home_banner['url']) != '')
					echo '<a href="'.$home_banner['url'].'">'.$banner.'</a>';
				else
					echo $banner;
			}
		?>
		</article>
	</div>
</div>