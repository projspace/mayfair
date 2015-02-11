<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$("#approved_students").click(function(){
			var url = '<?=$config['dir'] ?>index.php?fuseaction=admin.assignDiscountCode&code_id=<?=$_REQUEST['code_id'] ?>';
			if($(this).is(':checked'))
				url += '&approved_students=1';
			window.location = url;
		});
	});
/* ]]> */
</script>

<h1>Assign Discount Code</h1><hr />

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.assignDiscountCode&amp;act=save">

<input type="hidden" name="code_id" value="<?=$_REQUEST['code_id'] ?>" />

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="account_ids">Approved students only</label>
				<input type="checkbox" id="approved_students" <? if($_REQUEST['approved_students']+0):?>checked="checked"<? endif; ?> />
			</div>
			<div class="form-field clearfix">
				<label for="account_ids">Assign To</label>
				<select id="account_ids" name="account_ids[]" multiple="multiple">
				<?
					while($row = $accounts->FetchRow())
						echo '<option value="'.$row['id'].'">'.$row['email'].'</option>';
				?>
				</select>
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