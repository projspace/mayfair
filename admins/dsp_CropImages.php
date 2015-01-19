<?
	$files = unserialize($_REQUEST['data']);
?>

<!-- Skin CSS file -->
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/assets/skins/sam/resize.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/assets/skins/sam/imagecropper.css">
<!-- Utility Dependencies -->
<script src="http://yui.yahooapis.com/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script src="http://yui.yahooapis.com/2.8.0r4/build/dragdrop/dragdrop-min.js"></script> 
<script src="http://yui.yahooapis.com/2.8.0r4/build/element/element-min.js"></script> 
<!-- Source file for the Resize Utility -->
<script src="http://yui.yahooapis.com/2.8.0r4/build/resize/resize-min.js"></script>
<!-- Source file for the ImageCropper Control -->
<script src="http://yui.yahooapis.com/2.8.0r4/build/imagecropper/imagecropper-min.js"></script>

<script type="text/javascript">
/* <![CDATA[ */
	var image_crop = false;
	var thumbnail_crop = false;
	
	$(document).ready(function() {
		$('body').addClass("yui-skin-sam");
		$('#frmCrop').submit(function(){
			var coords = false;
			if(image_crop)
			{
				coords = image_crop.getCropCoords();
				$('#image_x').val(coords.left);
				$('#image_y').val(coords.top);
				$('#image_width').val(coords.width);
				$('#image_height').val(coords.height);
			}
			
			if(thumbnail_crop)
			{
				coords = thumbnail_crop.getCropCoords();
				$('#thumbnail_x').val(coords.left);
				$('#thumbnail_y').val(coords.top);
				$('#thumbnail_width').val(coords.width);
				$('#thumbnail_height').val(coords.height);
			}
		});
	});
/* ]]> */	
</script>

<h1>Crop Images</h1><hr />

<form method="post" id="frmCrop" action="<?= $config['dir'] ?>index.php?fuseaction=admin.cropImages&amp;act=doCrop">

<input type="hidden" name="data" value="<?=urlencode(serialize($data)) ?>" />
<? $index=0; ?>
<? foreach((array)$data['images'] as $key=>$item): ?>

	<? if(isset($item['image'])): ?>

	<div class="legend"><!--Image <?=$index+1 ?>--><?=$item['image']['description'] ?> - ratio <?=$item['image']['width'] ?> x <?=$item['image']['height'] ?></div>
	<div class="form">
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				image_<?=$key ?>_crop = new YAHOO.widget.ImageCropper('image_<?=$key ?>_crop', {
					initialXY: [0, 0]
					,initWidth: <?=isset($item['image']['min_width'])?$item['image']['min_width']+0:$item['image']['width']+0 ?>
					,initHeight: <?=isset($item['image']['min_height'])?$item['image']['min_height']+0:$item['image']['height']+0 ?>
					,minWidth: <?=isset($item['image']['min_width'])?$item['image']['min_width']+0:$item['image']['width']+0 ?>
					,minHeight: <?=isset($item['image']['min_height'])?$item['image']['min_height']+0:$item['image']['height']+0 ?>
					<? if($item['image']['height']+0): ?>,ratio: <?=$item['image']['width']+0 ?>/<?=$item['image']['height']+0 ?><? endif; ?>
				});
				
				$('#frmCrop').submit(function(){
					coords = image_<?=$key ?>_crop.getCropCoords();
					$('#image_<?=$key ?>_x').val(coords.left);
					$('#image_<?=$key ?>_y').val(coords.top);
					$('#image_<?=$key ?>_width').val(coords.width);
					$('#image_<?=$key ?>_height').val(coords.height);
				});
				
			});
		/* ]]> */	
		</script>
		
		<input type="hidden" id="image_<?=$key ?>_x" name="image[<?=$key ?>][x]" value="" />
		<input type="hidden" id="image_<?=$key ?>_y" name="image[<?=$key ?>][y]" value="" />
		<input type="hidden" id="image_<?=$key ?>_width" name="image[<?=$key ?>][width]" value="" />
		<input type="hidden" id="image_<?=$key ?>_height" name="image[<?=$key ?>][height]" value="" />

		<? 
			$size = getimagesize($item['image']['src_file']); 
			$image_url = str_replace($config['path'], $config['dir'], $item['image']['src_file']);
		?>
		<img id="image_<?=$key ?>_crop" src="<?=$image_url ?>?time=<?=time() ?>" width="<?=$size[0] ?>" height="<?=$size[1] ?>" alt=""/><br />
	</div>

	<? endif; ?>

	<? if(isset($item['thumbnail'])): ?>

	<div class="legend">Thumbnail <?=$index+1 ?> - ratio <?=$item['thumbnail']['width'] ?> x <?=$item['thumbnail']['height'] ?></div>
	<div class="form">
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				thumbnail_<?=$key ?>_crop = new YAHOO.widget.ImageCropper('thumbnail_<?=$key ?>_crop', {
					initialXY: [0, 0]
					,initWidth: <?=$item['thumbnail']['width']+0 ?>
					,initHeight: <?=$item['thumbnail']['height']+0 ?>
					,minWidth: <?=$item['thumbnail']['width']+0 ?>
					,minHeight: <?=$item['thumbnail']['height']+0 ?>
					,ratio: <?=$item['thumbnail']['width']+0 ?>/<?=$item['thumbnail']['height']+0 ?>
				});
				
				$('#frmCrop').submit(function(){
					coords = thumbnail_<?=$key ?>_crop.getCropCoords();
					$('#thumbnail_<?=$key ?>_x').val(coords.left);
					$('#thumbnail_<?=$key ?>_y').val(coords.top);
					$('#thumbnail_<?=$key ?>_width').val(coords.width);
					$('#thumbnail_<?=$key ?>_height').val(coords.height);
				});
			});
		/* ]]> */	
		</script>
		
		<input type="hidden" id="thumbnail_<?=$key ?>_x" name="thumbnail[<?=$key ?>][x]" value="" />
		<input type="hidden" id="thumbnail_<?=$key ?>_y" name="thumbnail[<?=$key ?>][y]" value="" />
		<input type="hidden" id="thumbnail_<?=$key ?>_width" name="thumbnail[<?=$key ?>][width]" value="" />
		<input type="hidden" id="thumbnail_<?=$key ?>_height" name="thumbnail[<?=$key ?>][height]" value="" />

		<? 
			$size = getimagesize($item['thumbnail']['src_file']); 
			$thumbnail_url = str_replace($config['path'], $config['dir'], $item['thumbnail']['src_file']);
		?>
		<img id="thumbnail_<?=$key ?>_crop" src="<?=$thumbnail_url ?>?time=<?=time() ?>" width="<?=$size[0] ?>" height="<?=$size[1] ?>" alt=""/><br />
	</div>

	<? endif; ?>
	<? $index++; ?>
<? endforeach; ?>

<div class="formRight">
	<input class="submit" type="submit" value="Continue" />
	<button class="finished" onclick="window.location='<?= $data['cancel_url'] ?>'; return false;">Cancel</button>
</div>

</form>