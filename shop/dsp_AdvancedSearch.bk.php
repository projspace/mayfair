<script src="<?=$config['layout_dir'] ?>js/libs/jquery-ui.1.8.min.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		var min_left = Math.round(((<?=safe(disp($_REQUEST['min_price'],$lowest_price))+0 ?> - <?=$lowest_price+0 ?>)*$('#range_container').width())/<?=($highest_price - $lowest_price)+0 ?>);
		var max_left = Math.round(((<?=safe(disp($_REQUEST['max_price'],$highest_price))+0 ?> - <?=$lowest_price+0 ?>)*$('#range_container').width())/<?=($highest_price - $lowest_price)+0 ?>) - $('#range_container .max_button').outerWidth();
		var max_right = $('#range_container').width() - (max_left + $('#range_container .max_button').outerWidth());
		
		$('#range_container .min_button').css('left', min_left);
		$('#range_container .slider').css('left', min_left);
		$('#range_container .text_container .min_value').css('left', min_left);
		
		$('#range_container .max_button').css('left', max_left);
		$('#range_container .slider').css('right', max_right);
		$('#range_container .text_container .max_value').css('right', max_right);
		
		$('.range_container .min_button').draggable({
			axis: 'x'
			,containment: 'parent'
			,drag: function(event, ui){
				var min_right = ui.position.left+$(ui.helper).outerWidth();
				var max_left = parseInt($(ui.helper).siblings('.max_button').css('left'));
				if(min_right >= max_left)
					return false;
				
				var highest_value = parseInt($(ui.helper).parent().parent().find('.text_container .highest_value em').text());
				var lowest_value = parseInt($(ui.helper).parent().parent().find('.text_container .lowest_value em').text());
				var distance =  highest_value - lowest_value;
				var price = Math.round((distance * ui.position.left) / $(ui.helper).parent().width()) + lowest_value;
				
				$(ui.helper).siblings('.slider').css('left', ui.position.left);
				$(ui.helper).parent().parent().find('.text_container .min_value em').html(price).parent().css('left', ui.position.left);
				$(ui.helper).parent().siblings('.input_min_value').val(price);
			}
			,stop: function(event, ui){
			
			}
		});
		$('.range_container .max_button').draggable({
			axis: 'x'
			,containment: 'parent'
			,drag: function(event, ui){
				var max_left = ui.position.left;
				var min_right = parseInt($(ui.helper).siblings('.min_button').css('left'))+parseInt($(ui.helper).siblings('.min_button').outerWidth());
				if(min_right >= max_left)
					return false;
			
				var highest_value = parseInt($(ui.helper).parent().parent().find('.text_container .highest_value em').text());
				var lowest_value = parseInt($(ui.helper).parent().parent().find('.text_container .lowest_value em').text());
				var distance =  highest_value - lowest_value;
				var price = Math.round((distance * (ui.position.left+$(ui.helper).outerWidth())) / $(ui.helper).parent().width()) + lowest_value;
				
				var right = $(ui.helper).parent().width()-(ui.position.left+$(ui.helper).outerWidth());
				$(ui.helper).siblings('.slider').css('right', right);
				$(ui.helper).parent().parent().find('.text_container .max_value em').html(price).parent().css('right', right);
				$(ui.helper).parent().siblings('.input_max_value').val(price);
			}
			,stop: function(event, ui){
			
			}
		});
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#advancedSearch').submit(function(){
			var defaults = {
				'keyword':'Text in name / description of product'
				,'tag':'Type of Product'
				,'filter_character':'Related to this Character'
				,'filter_director':'Related to this Director'
				,'filter_actor':'Related to this Actor'
				,'filter_play':'Related to this Play'
			};
			for(var id in defaults)
			{
				if($('#'+id).val() == defaults[id])
					$('#'+id).val('');
			}
		});
	});
/* ]]> */
</script>
<form id="advancedSearch" action="<?=$config['dir'] ?>search" method="get" class="advancedForm" target="_parent">
	<h3>Advanced Search</h3>
	<p>Introduction to search and how you can use it.  Tue commolo rperiuscip eui tincil iuscing euguerci elis augait nullan hent at augiat. At dolore er in ulputpat, sis dit exerat.</p>
	<p><strong>Search for products with the following criteria</strong></p>
	<p class="row"><input type="text" id="keyword" name="keyword" value="<?=(safe($_GET['keyword']) != '')?safe($_GET['keyword']):'Text in name / description of product' ?>" /></p>
	<p class="row"><input type="text" id="tag" name="tag" value="<?=(safe($_GET['tag']) != '')?safe($_GET['tag']):'Type of Product' ?>" /></p>
	<p class="row double"><input type="text" class="split" id="filter_character" name="filters[character]" value="<?=(safe($_GET['filters']['character']) != '')?safe($_GET['filters']['character']):'Related to this Character' ?>"/><input type="text" id="filter_play" name="filters[play]" value="<?=(safe($_GET['filters']['play']) != '')?safe($_GET['filters']['play']):'Related to this Play' ?>"/></p>
	<p class="row double"><input type="text" class="split" id="filter_actor" name="filters[actor]" value="<?=(safe($_GET['filters']['actor']) != '')?safe($_GET['filters']['actor']):'Related to this Actor' ?>"/><input type="text" id="filter_director" name="filters[director]" value="<?=(safe($_GET['filters']['director']) != '')?safe($_GET['filters']['director']):'Related to this Director' ?>"/></p>
	<p><strong>Price range</strong></p>
	<div id="range_container" class="range_container" style="width: 503px; margin-bottom: 8px;">
		<input type="hidden" class="input_min_value" name="min_price" value="<?=safe(disp($_REQUEST['min_price'],$lowest_price)) ?>" />
		<input type="hidden" class="input_max_value" name="max_price" value="<?=safe(disp($_REQUEST['max_price'],$highest_price)) ?>" />
		<div class="slider_container">
			<div class="slider">
				<span style="float:left;">&nbsp;</span>
				<span style="float:right;">&nbsp;</span>
				<br clear="all"/>
			</div>
			<a class="min_button"><img src="<?=$config['layout_dir'] ?>images/placeholders/price_range_min.gif" width="9" height="14" alt="*"/></a>
			<a class="max_button"><img src="<?=$config['layout_dir'] ?>images/placeholders/price_range_max.gif" width="9" height="14" alt="*"/></a>
		</div>
		<div class="text_container">
			<span class="lowest_value">&pound;<em><?=$lowest_price ?></em></span>
			<span class="highest_value">&pound;<em><?=$highest_price ?></em></span>
			<span class="min_value">&pound;<em><?=safe(disp($_REQUEST['min_price'],$lowest_price)) ?></em></span>
			<span class="max_value">&pound;<em><?=safe(disp($_REQUEST['max_price'],$highest_price)) ?></em></span>
		</div>
		<br clear="left"/>
	</div>
	<p class="submit"><input type="submit" value="find it" class="redDoubleArrow" /><input type="button" class="redDoubleArrow ccClose" value="Close"></p>
</form>