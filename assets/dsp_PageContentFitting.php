<? if($_REQUEST['ajax']): ?>
<div class="overlay" id='fitting-guide-overlay'>
	<div class="header content-box"><h1><?=htmlentities($page['name'], ENT_NOQUOTES, 'UTF-8') ?></h1></div>
	<div class="content-box">
<? else: ?>
<div id="content-wrapper">
	<article id="fitting-guide">
		<header class="content-box"><h1><?=htmlentities($page['name'], ENT_NOQUOTES, 'UTF-8') ?></h1></header>
		<section class="content-box">
<? endif; ?>
			<div id="fitting-table">
				<?=$content->fields['content'] ?>
				<table>
					<thead>
					<?
						foreach($fitting_guides_rows as $key=>$row)
						{
							if(!$row['heading'])
								break;
							echo '<tr><th>'.$fitting_guides_rows[$key]['row_name'].'</th>';
							foreach($fitting_guides_columns as $j=>$unsed)
								echo '<td>'.$fitting_guides[$key.','.$j]['size'].'</td>';
							echo '</tr>';
						}
					?>
					</thead>
					<tbody>
					<?
						prev($fitting_guides_rows);
						while (list ($key, $row) = each ($fitting_guides_rows))
						{
							echo '<tr><th>'.$fitting_guides_rows[$key]['row_name'].'</th>';
							foreach($fitting_guides_columns as $j=>$unsed)
								echo '<td>'.$fitting_guides[$key.','.$j]['size'].'</td>';
							echo '</tr>';
						}
					?>
					</tbody>
				</table>
			</div>
<? if($_REQUEST['ajax']): ?>
	</div>
</div>
<?
	ob_end_flush();
	exit;
?>
<? else: ?>
		</section>
	</article>
</div>
<? endif; ?>