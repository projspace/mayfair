<?
	$page = $elems->qry_Page(31);
?>
<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateForm()
	{
		$('#frm input, #frm textarea').css('border', '1px solid #B7B3AB');
		
		if($('#description').attr('placeholder') == $('#description').val())
			$('#description').val('');
		
		var validation = new Validator(function(errors){
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).css('border', '1px solid #B3351E');
			}
		});

		validation.addField('title', "Title", 'required');
		validation.addField('author', "Author", 'required');
		validation.addField('description', "Description", 'required');
		validation.addField('stars', "Stars", 'required');
		
		if(!validation.validate())
		{
			if($('#description').val() == '')
				$('#description').val($('#description').attr('placeholder'));
			return false;
		}
		return true;
	}
	
	$(document).ready(function(){
		$('#frm').submit(validateForm);
	});
/* ]]> */
</script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#fancybox-content', parent.document).css('height', ($('#page-wrapper').height()+20)+'px');
		parent.$.fancybox.center(true);
	});
/* ]]> */
</script>
<div class="overlay" id='fitting-guide-overlay'>
	<div class="header content-box">
		<h1>Write a review</h1>
	</div>
	<div class="content-box">
		<div id="review-content" class="clearfix">
			<?=$page['content'] ?>
			<form method="post" action="<?=$config['dir'] ?>addReview/<?=$product['id'] ?>?act=save" class="options revRight" id="frm">
				<?=$validator->displayMessage() ?>
				<p class="ratingStars">
					<select class="selectBox" name='stars' id="stars">
						<option value=''>select star rating</option>
						<option value='1' <?=($_POST['stars'] == 1)?'selected="selected"':'' ?>>1</option>
						<option value='2' <?=($_POST['stars'] == 2)?'selected="selected"':'' ?>>2</option>
						<option value='3' <?=($_POST['stars'] == 3)?'selected="selected"':'' ?>>3</option>
						<option value='4' <?=($_POST['stars'] == 4)?'selected="selected"':'' ?>>4</option>
						<option value='5' <?=($_POST['stars'] == 5)?'selected="selected"':'' ?>>5</option>
					</select>
					<strong><?=date('d/m/Y') ?></strong>
				</p>
				<div class="row">
					<label for="title">Title</label>
					<input type="text" class="text" value="<?=$_POST['title'] ?>" name="title" id="title" style="width: 396px;" />
				</div>
				<div class="row">
					<label for="author">Author</label>
					<input type="text" class="text" value="<?=$_POST['author'] ?>" name="author" id="author" style="width: 396px;" />
				</div>
				<textarea class="clearable" name="description" id="description" cols="30" rows="10" placeholder="Please type your review here..."><?=disp($_POST['description'], 'Please type your review here...') ?></textarea>
				<p class="buttons"><a href="#" class="btn-gray submit">Post your review</a></p>
			</form>
			<div class="revLeft">
			<?
				$name = htmlentities($product['name'], ENT_NOQUOTES, 'UTF-8');
				if(trim($product['code']) != '')
					$name .= ' <em>'.htmlentities($product['code'], ENT_NOQUOTES, 'UTF-8').'</em>';
			?>
				<h1><?=$name ?></h1>
				<p class="price">Price <?=price($product['price']) ?></p>
			</div>
		</div>
	</div>
</div>