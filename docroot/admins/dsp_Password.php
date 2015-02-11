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
<h1>Change Password</h1><hr />
<? if($reason)
	error($reason);
?>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.password&amp;act=save">
<div class="legend">Password Details</div>
<div class="form">
	<label for="password">Current Password</label>
	<input type="password" id="password" name="password" value="<?= make_safe($_POST['password']) ?>" autocomplete="off" /><br />

	<label for="newpassword">New Password</label>
	<input type="password" id="newpassword" name="newpassword" value="<?= make_safe($_POST['newpassword']) ?>" autocomplete="off" /><br />

	<label for="confirmpassword">Confirm New Password</label>
	<input type="password" id="confirmpassword" name="confirmpassword" value="<?= make_safe($_POST['confirmpassword']) ?>" autocomplete="off" /><br />
</div>
<div class="formRight">
	<input class="submit" type="submit" value="Save" />
</div>
</form>