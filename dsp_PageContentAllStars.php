<div id="content-wrapper" class="yui3-g">
	<aside id="sidebar" class="yui3-u">
		<ul class="pages">
		<?
			while($row = $sidebar->FetchRow())
			{
				if($row['id'] == $page['id'])
					$class = 'class="on"';
				else
					$class = '';
					
				echo '<li><a '.$class.' href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></li>';
			}
		?>
		</ul>
	</aside>
	<div id="content" class="yui3-u home">
		<article id="all-stars">
			<div class="content-box">
				<ul class="results">
				<?
					while($row = $children->FetchRow())
					{
						if($row['ratio']+0)
						{
							$min_height = round($config['size']['page']['thumb']['x'] / $row['ratio']) - 50;
							$image = $config['dir'].'images/page/thumb/image_'.$row['image_id'].'.'.$row['image_type'];
						}
						else
						{
							$min_height = 100;
							$image = $config['dir'].'images/page/thumb/placeholder.gif';
						}

						echo '
							<li class="star" style="min-height: '.$min_height.'px;">
								<a href="'.$config['dir'].$row['url'].'" class="pic"><img src="'.$image.'" alt="" width="100" /></a>
								<h2><a href="'.$config['dir'].$row['url'].'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a></h2>
								<div>'.$row['description'].'</div>
							</li>';
					}
				?>
				</ul>

			</div>
		</article>
	</div>
</div>
