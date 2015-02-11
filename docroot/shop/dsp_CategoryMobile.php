<?
	$category_url = category_url($category['id'], $category['name']);
	
	if(trim($_REQUEST['pageview']) != '')
		$pageview = $_REQUEST['pageview'];
	else
		$pageview = 12;
		
	$src_url = array();
	$src_url['sortby'] = $ajax['sortby']['selected'];
	$src_url['pageview'] = $pageview;
	if(isset($ajax['filters']))
	foreach($ajax['filters'] as $index=>$filter)
	{
		$src_url['filters['.$index.'][name]'] = $filter['name'];
		if(in_array($filter['type'], array('single','multi')))
			foreach($filter['options'] as $row)
				if($row['on'])
					$src_url['filters['.$index.'][value][]'][] = $row['value'];
		if($filter['type'] == 'range')
		{
			$src_url['filters['.$index.'][value][]'][] = $filter['value'][0];
			$src_url['filters['.$index.'][value][]'][] = $filter['value'][1];
		}
	}
?>
<div id="content-wrapper" class="yui3-g">
	<aside id="sidebar" class="yui3-u filters">
		<h1>Refine selection...</h1>

		<? if(trim($ajax['sidebar']) == ''): ?>
		<section id="filters">
		<?
			foreach((array)$ajax['filters'] as $index=>$filter)
			{
				$tmp_url = $src_url;
				unset($tmp_url['filters['.$index.'][value][]']);
				$clear_all_url = array();
				foreach($tmp_url as $key=>$value)
					if(is_array($value))
					{
						foreach($value as $sub_value)
							$clear_all_url[] = urlencode($key).'='.urlencode($sub_value);
					}
					else
						$clear_all_url[] = urlencode($key).'='.urlencode($value);
						
				$szFilter = '';
				switch($filter['type'])
				{
					case 'single':
					case 'multi':
						$szFilter = '<div class="filter">';
						$on = false;
						$ul = true;
						$on_display = '';
						foreach($filter['options'] as $row)
							if($row['on'])
							{
								$on = true;
								$on_display = $row['display'];
								break;
							}
						if($filter['type'] == 'single' && trim($filter['display']) != '' && !$on)
							$szFilter .= '<h2>'.$filter['display'].'</h2>';
							
						if($filter['type'] == 'multi' && trim($filter['display']) != '') 
						{
							if($on)
								$szFilter .= '<h2><a href="'.$category_url.'?'.implode('&', $clear_all_url).'" class="clear-all" title="Clear filter">Clear all</a>'.$filter['display'].'</h2>';
							else
								$szFilter .= '<h2>'.$filter['display'].'</h2>';
						}
						elseif($on)
						{
							$szFilter .= '<h2><a href="'.$category_url.'?'.implode('&', $clear_all_url).'" class="clear-all" title="Clear filter">Clear all</a>'.$on_display.'</h2>';
							$ul = false;
						}
						
						if($ul)
						{
							$szFilter .= '<ul class="'.$filter['type'].'">';
							foreach($filter['options'] as $row)
							{
								$classes = array();
								$attrs = array();
								$filter_url = $src_url;
								
								if($row['on'])
								{
									$classes[] = 'on';
									$attrs[] = 'title="Clear selection"';
									
									foreach($filter_url['filters['.$index.'][value][]'] as $key=>$value)
										if($value == $row['value'])
											unset($filter_url['filters['.$index.'][value][]'][$key]);
								}
								else
								{
									if($filter['type'] == 'multi')
										$filter_url['filters['.$index.'][value][]'][] = $row['value'];
									else
										$filter_url['filters['.$index.'][value][]'] = array($row['value']);
								}
								
								if($row['disabled'])
								{
									$classes[] = 'disabled';
									$attrs[] = 'disabled="disabled"';
								}
								
								if(count($classes))
									$attrs[] = 'class="'.implode(' ', $classes).'"';
								
								$url = array();
								foreach($filter_url as $key=>$value)
									if(is_array($value))
									{
										foreach($value as $sub_value)
											$url[] = urlencode($key).'='.urlencode($sub_value);
									}
									else
										$url[] = urlencode($key).'='.urlencode($value);
								
								$szFilter .= '<li><a href="'.$category_url.'?'.implode('&', $url).'" '.implode(' ', $attrs).'><em></em>'.$row['display'].'</a></li>';
							}
							$szFilter .= '</ul>';
						}
						
						$szFilter .= '</div>';
						break;
					case 'range':
						$szFilter = '<div class="filter">';
						$szFilter .= '<form action="'.$category_url.'" method="get">';
						
						$filter_url = $src_url;
						unset($filter_url['filters['.$index.'][value][]']);
						foreach($filter_url as $key=>$value)
							if(is_array($value))
							{
								foreach($value as $sub_value)
									$szFilter .= '<input type="hidden" name="'.$key.'" value="'.$sub_value.'"/>';
							}
							else
								$szFilter .= '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
						
						
						if(trim($filter['display']) != '')
						{
							if($filter['value'][0] != $filter['min'] || $filter['value'][1] != $filter['max'] )
								$szFilter .= '<h2>'.$filter['display'].'<a href="'.$category_url.'?'.implode('&', $clear_all_url).'" class="clear-all" title="Clear filter">Clear all</a></h2>';
							else
								$szFilter .= '<h2>'.$filter['display'].'</h2>';
						}
						
						$szFilter .= '<select name="filters['.$index.'][value][]" style="float: left;">';
						for($i=$filter['min'];$i<=$filter['max'];$i++)
							if($i == $filter['value'][0])
								$szFilter .= '<option value="'.$i.'" selected="selected">$'.$i.'</option>';
							else
								$szFilter .= '<option value="'.$i.'">$'.$i.'</option>';
						$szFilter .= '</select>';
						$szFilter .= '<select name="filters['.$index.'][value][]" style="float: right;">';
						for($i=$filter['min'];$i<=$filter['max'];$i++)
							if($i == $filter['value'][1])
								$szFilter .= '<option value="'.$i.'" selected="selected">$'.$i.'</option>';
							else
								$szFilter .= '<option value="'.$i.'">$'.$i.'</option>';
						$szFilter .= '</select>';
						$szFilter .= '<br clear="all"/>';
						$szFilter .= '<input type="submit" class="btn-red" style="width: 100%; margin-top: 5px;" value="Filter price">';
						$szFilter .= '</form>';
						$szFilter .= '</div>';
						break;
				}
				echo $szFilter;
			}
		?>
		</section>
		<? else: ?>
		<div id="sidebar_subcats"><?=$ajax['sidebar'] ?></div>
		<? endif; ?>
		<?
			if($category['fitting_guide'])
			{
				if($category['fitting_pdf'] && $category['fitting_pdf_visible'])
					echo '<a href="'.$config['dir'].'downloads/fitting_pdf/'.$category['id'].'.pdf" target="_blank">Fitting guide</a>';
				else
					echo '<a href="'.$config['dir'].'fitting-guide/category/'.$category['id'].'?ajax=1" id="fitting-guide-link">Fitting guide</a>';
			}
		?>
	</aside>
	<div id="content" class="yui3-u">
		<article id="category-overview">
			<header class="content-box">
				<h1 id="page_title"><?=$ajax['title'] ?></h1>
				<section id="view-options" class="clearfix">
					<p class="page-view fr">
						page view
					<?
						$views = array(12, 24, 'all');
						$aViews = array();
						foreach($views as $view)
						{
							$view_url = $src_url;
							$view_url['pageview'] = $view;
							
							$url = array();
							foreach($view_url as $key=>$value)
								if(is_array($value))
								{
									foreach($value as $sub_value)
										$url[] = urlencode($key).'='.urlencode($sub_value);
								}
								else
									$url[] = urlencode($key).'='.urlencode($value);
							
							$aViews[] = '<a href="'.$category_url.'?'.implode('&', $url).'" '.(($pageview == $view)?'class="on"':'').'>'.strtoupper($view).'</a>';
						}
						echo implode(' / ', $aViews);
					?>
					</p>
					<div class="sorting selectbox-wrapper">
						<form action="<?=$category_url ?>" method="get">
						<?
							$sort_url = $src_url;
							unset($sort_url['sortby']);
							foreach($sort_url as $key=>$value)
								if(is_array($value))
								{
									foreach($value as $sub_value)
										echo '<input type="hidden" name="'.$key.'" value="'.$sub_value.'"/>';
								}
								else
									echo '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
						?>
							<label for="sortby">Sort by:</label>
							<select id="sortby" name="sortby" style="display: block; float: left;">
							<?
								foreach($ajax['sortby']['options'] as $key=>$value)
									if($key == $ajax['sortby']['selected'])
										echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
									else
										echo '<option value="'.$key.'">'.$value.'</option>';
							?>
							</select>
							<input type="submit" class="btn-red" style="margin-left: 5px; height: 20px; padding: 0 5px;" value="Sort">
						</form>
					</div>
				</section>
			</header>
			<section class="content-box">
				<ul class="product-listing yui3-g"><?=$ajax['products'] ?></ul>
				<div id="pagination" class="pagination">
					<? if($ajax['paging']['current'] > 1): ?><a href="#" class="prev">Previous Page</a><? endif; ?>
					<p>
					<?
						for($i=1;$i<=$ajax['paging']['total'];$i++)
						{
							$pag_url = $src_url;
							$pag_url['page'] = $i;
							
							$url = array();
							foreach($pag_url as $key=>$value)
								if(is_array($value))
								{
									foreach($value as $sub_value)
										$url[] = urlencode($key).'='.urlencode($sub_value);
								}
								else
									$url[] = urlencode($key).'='.urlencode($value);
								
							if($i == $ajax['paging']['current'])
								echo '<a href="'.$category_url.'?'.implode('&', $url).'" class="on">'.$i.'</a>';
							else
								echo '<a href="'.$category_url.'?'.implode('&', $url).'">'.$i.'</a>';
						}
					?>
					</p>
					<? if($ajax['paging']['current'] < $ajax['paging']['total']): ?><a href="#" class="next">Next Page</a><? endif; ?>
				</div>
			</section>
			<?
				if($category['content_visible'] && trim($category['content']) != '')
					echo '<section class="cat-desc word-limit" data-word-limit="50">'.$category['content'].'</section>';
			?>
		</article>
	</div>
</div>