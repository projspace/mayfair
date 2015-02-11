<link href="<?=$config['layout_dir'] ?>css/sitemap.css" media="screen" rel="stylesheet">
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#sitemap span').live('click', function(){
			if($(this).prev('ul').is(':hidden'))
			{
				$(this).prev('ul').show();
				$(this).removeClass('collapsed').addClass('expanded');
			}
			else
			{
				$(this).prev('ul').hide();
				$(this).removeClass('expanded').addClass('collapsed');
			}
		});
		$('#sitemap ul').hide().after('<span class="collapsed"></span>');
	});
/* ]]> */
</script>
<h1>Sitemap</h1>
<ul id="sitemap">
<?
	while($row=$pages->FetchRow())
		echo '<li><a href="'.$config['dir'].$row['url'].'">'.$row['name'].'</a></li>';

	disp_map($shop);
	function disp_map($map)
	{
		global $config;
		for($i=0;$i<count($map);$i++)
		{
			echo "<li>";
			echo "<a href=\"".category_url($map[$i]["id"], $map[$i]["name"])."\"><b>{$map[$i]["name"]}</b></a>";
			if($map[$i]["products"] || $map[$i]["children"])
				echo "\n<ul>";
			if($map[$i]["products"])
				echo "\n".disp_prod($map[$i]["products"])."\n";
			if($map[$i]["children"])
				echo "\n".disp_map($map[$i]["children"])."\n";
			if($map[$i]["products"] || $map[$i]["children"])
				echo "</ul>\n";
			echo "</li>\n";
		}
	}

	function disp_prod($map)
	{
		global $config;
		for($i=0;$i<count($map);$i++)
		{
			echo "<li><a href=\"".product_url($map[$i]["id"], $map[$i]["guid"])."\"><i>{$map[$i]["name"]}</i></a></li>";
		}
	}
?>
</ul>
