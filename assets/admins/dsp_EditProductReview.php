<script src="<?=$config['dir'] ?>VLib/js/validator.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function validateFRM()
	{
		$('#frmValidate input:text').removeClass('error').next('label.error').hide();
		var validation = new Validator(function(errors){
			var error = '';
			for(i=0;i<errors.length;i++)
			{
				$(errors[i].dom).addClass('error');
				var label = $(errors[i].dom).next('label.error');
				if(label.length)
					label.text(errors[i].errorMsg).show();
				else
					$(errors[i].dom).after('<label class="error">'+errors[i].errorMsg+'</label>');
			}
		});

		validation.addField('title','Title','required');
		validation.addField('author','Author','required');
		validation.addField('description','Description','required');
		
		if(!validation.validate())
			return false;

		return true;
	}
	
	$(document).ready(function(){
		$('#frmValidate').submit(validateFRM);
	});
/* ]]> */
</script>

<?= $validator->clientValidate(); ?>
<?= $validator->displayMessage(); ?>

<form id="postback" method="post" action="none"></form>
<h1>Edit Review</h1>

<form method="post" id="frmValidate" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editProductReview&amp;act=add&amp;review_id=<?=$review['id'] ?>&amp;product_id=<?=$_REQUEST['product_id'] ?>&amp;category_id=<?=$_REQUEST['category_id'] ?>&amp;return=<?=$_REQUEST['return'] ?>">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="title">Title</label>
				<span><input type="text" class="text" id="title" name="title" value="<?=disp($_POST['title'], $review['title']) ?>" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="author">Author</label>
				<span><input type="text" class="text" id="author" name="author" value="<?=disp($_POST['author'], $review['author']) ?>" /></span>
			</div>
			<div class="form-field clearfix">
				<label for="description">Description</label>
				<span><textarea id="description" name="description"><?=disp($_POST['description'], $review['description']) ?></textarea></span>
			</div>
			<div class="form-field clearfix">
				<label for="rating">Rating</label>
				<select id="rating" name="rating">
				<?
					for($i=1;$i<=5;$i++)
						if($i == disp($_POST['rating'], $review['rating']))
							echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
						else
							echo '<option value="'.$i.'">'.$i.'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="status">Status</label>
				<select id="status" name="status">
					<option value="pending" <? if(disp($_POST['status'], $review['status']) == 'pending'): ?>selected="selected"<? endif; ?>>Pending</option>
					<option value="approved" <? if(disp($_POST['status'], $review['status']) == 'approved'): ?>selected="selected"<? endif; ?>>Approved</option>
					<option value="rejected" <? if(disp($_POST['status'], $review['status']) == 'rejected'): ?>selected="selected"<? endif; ?>>Rejected</option>
				</select>
			</div>
		</div>
		<div class="tab-panel-buttons clearfix">
			<span class="button button-small submit">
				<input class="submit" type="submit" value="Continue" />
			</span>
			<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.reviews"><span>Cancel</span></a>
		</div>
	</div>

</form>
