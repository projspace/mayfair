<?
	$areas=$db->Execute("
		SELECT
			*
		FROM
			shop_areas
		ORDER BY
			name
		ASC");
?>
<h1>Please select your region:</h1>
<?= $sid_amp; ?>
<ul>
<?
	while($row=$areas->FetchRow())
	{
		echo "<li><a href=\"{$config['dir']}?area_id={$row['id']}&amp;act=save{$sid_amp}\">{$row['name']}</li>\n";
	}
?>
</ul>