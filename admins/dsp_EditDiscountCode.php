<h1>Edit Discount Code</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editDiscountCode&code_id=<?=$_REQUEST['code_id'] ?>&amp;act=save">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<? if($discount_code['all_users']): ?><li><a href="#tabs-2">Assign</a></li><? endif; ?>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="code">Code</label>
				<input type="text" id="code" name="code" value="<?=$discount_code['code'] ?>" disabled="disabled" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="value">Value</label>
				<input type="text" id="value" name="value" value="<?=round($discount_code['value'], 2) ?>" disabled="disabled" />
				<select name="value_type" style="width:100px;" disabled="disabled">
					<option value="fixed" <? if($discount_code['value_type'] == 'fixed'): ?>selected="selected"<? endif; ?>>$</option>
					<option value="percent" <? if($discount_code['value_type'] == 'percent'): ?>selected="selected"<? endif; ?>>%</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="expiry_date">Expiry Date</label>
				<input type="text" class="calendar" id="expiry_date" name="expiry_date" value="<?=isDateValid($discount_code['expiry_date'])?date('d/m/Y', strtotime($discount_code['expiry_date'])):'' ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="min_order">Minimum Order Value</label>
				<input type="text" id="min_order" name="min_order" value="<?=round($discount_code['min_order'], 2) ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="use_count">Use Count</label>
				<input type="text" id="use_count" name="use_count" value="<?=$discount_code['use_count'] ?>" /><br />
			</div>
			<div class="form-field clearfix">
				<label for="all_users">Usable by all users</label>
				<input type="checkbox" id="all_users" name="all_users" value="1" <? if($discount_code['all_users']): ?>checked="checked"<? endif; ?> /><br />
			</div>
			<div class="form-field clearfix">
				<label for="gift_list_id">Gift Registry</label>
				<select name="gift_list_id">
					<option value="">None</option>
					<? while($row = $gift_registry->FetchRow()): ?>
					<option value="<?= $row['id'] ?>" <?=($row['id'] == $discount_code['gift_list_id'])?'selected="selected"':'' ?>><?= $row['code'] ?> - <?= $row['name'] ?></option>
					<? endwhile; ?>
				</select>
			</div>
		</div>
		<? if($discount_code['all_users']): ?>
		<!--<script language="javascript" type="text/javascript">
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
		</script>-->
		<div id="tabs-2">
			<div class="form-field clearfix">
				<label for="shop_account_id">Shop</label>
				<select id="shop_account_id" name="shop_account_id">
					<option value="">Please select</option>
				<?
					$found = false;
					while($row = $shops->FetchRow())
						if($row['id'] == $discount_code['shop_account_id'])
						{
							echo '<option value="'.$row['id'].'" selected="selected">'.$row['email'].'</option>';
							$found = true;
						}
						else
							echo '<option value="'.$row['id'].'">'.$row['email'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="shop_commission">Shop Commission</label>
				<input type="input" id="shop_commission" name="shop_commission" value="<?=$found?$discount_code['shop_commission']:'' ?>" />%
			</div>
			
			<div class="form-field clearfix">
				<label for="teacher_account_id">Teacher</label>
				<select id="teacher_account_id" name="teacher_account_id">
					<option value="">Please select</option>
				<?
					$found = false;
					while($row = $teachers->FetchRow())
						if($row['id'] == $discount_code['teacher_account_id'])
						{
							echo '<option value="'.$row['id'].'" selected="selected">'.$row['email'].'</option>';
							$found = true;
						}
						else
							echo '<option value="'.$row['id'].'">'.$row['email'].'</option>';
				?>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="teacher_commission">Teacher Commission</label>
				<input type="input" id="teacher_commission" name="teacher_commission" value="<?=$found?$discount_code['teacher_commission']:'' ?>" />%
			</div>
		</div>
		<? endif; ?>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.discountCodes"><span>Cancel</span></a>
	</div>

</form>