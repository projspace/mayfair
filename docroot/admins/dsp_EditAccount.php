<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<h1>Edit Account</h1>
<?
	if($reason)
		error($reason,"Error");
?>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editAccount&amp;act=save">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="username">Username</label>
				<span id="username"><?= $account['username']; ?></span>
			</div>
			<div class="form-field clearfix">
				<label for="email">Email</label>
				<input id="email" type="text" class="text" name="email" value="<?=disp($_POST['email'], $account['email']) ?>" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="password">Specify New Password</label>
				<input id="password" type="password" class="text" name="password" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="confirm">Confirm</label>
				<input id="confirm" type="password" class="text" name="confirm" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="aclgroup">Access Group</label>
				<select name="group_id" id="group_id">
					<?
						while($row=$groups->FetchRow())
							if($row['id'] == disp($_POST['group_id'], $account['group_id']))
								echo "<option value=\"{$row['id']}\" selected=\"selected\">{$row['name']}</option>";
							else
								echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
					?>
				</select>
			</div>
		</div>
	</div>
	
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
			<input type="hidden" name="account_id" value="<?= $account['id'] ?>" />
		</span>
	</div>

</form>