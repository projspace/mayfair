<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$("#shop_account_id").change(function(){
			$('#teacher_account_id').val('');
			$('#teacher_commission').val('');
		});
		
		$("#teacher_account_id").change(function(){
			$('#shop_account_id').val('');
			$('#shop_commission').val('');
		});
	});
/* ]]> */
</script>

<h1>Assign Discount Code</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.assignCommissionDiscountCode&amp;act=save">

<input type="hidden" name="code_id" value="<?=$_REQUEST['code_id'] ?>" />

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="shop_account_id">Shop</label>
				<select id="shop_account_id" name="shop_account_id">
					<option value="">Please select</option>
				<?
					while($row = $shops->FetchRow())
						echo '<option value="'.$row['id'].'">'.$row['email'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="shop_commission">Shop Commission</label>
				<input type="input" id="shop_commission" name="shop_commission" value="" />%
			</div>
			
			<div class="form-field clearfix">
				<label for="teacher_account_id">Teacher</label>
				<select id="teacher_account_id" name="teacher_account_id">
					<option value="">Please select</option>
				<?
					while($row = $teachers->FetchRow())
						echo '<option value="'.$row['id'].'">'.$row['email'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="teacher_commission">Teacher Commission</label>
				<input type="input" id="teacher_commission" name="teacher_commission" value="" />%
			</div>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.discountCodes"><span>Cancel</span></a>
	</div>

</form>