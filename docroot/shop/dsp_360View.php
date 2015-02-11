<script src="<?=$config['layout_dir'] ?>js/magic360/magic360.js"></script>
<?
	$url = array();
	while($row = $images->FetchRow())
		$url[] = $config['dir'].'images/product/360_view/'.$row['id'].'.'.$row['image_type'];
?>
<img class="Magic360" src="<?=$url[0] ?>" rel="<?=implode('*', $url) ?>"/>