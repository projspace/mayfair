<script src="<?=$config['layout_dir'] ?>js/swfobject.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	var flashvars = { file:'<?=$config['dir'] ?>downloads/product/video/<?=$product['id'].'.'.$product['video_type'] ?>', autostart:'true' };
	var params = { allowfullscreen:'true', allowscriptaccess:'always' };
	var attributes = { id:'player1', name:'player1' };

	swfobject.embedSWF('<?=$config['dir'] ?>player.swf','container','600','400','9.0.115','false', flashvars, params, attributes);

/* ]]> */
</script>
<p id="container">Please install the Flash Plugin</p>
