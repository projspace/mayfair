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
<h1>Add Telesale Account</h1><hr>
<div class="center">
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addTelesaleAccount&act=add">
<table border="0" cellpadding="3" cellspacing="0" width="622">
	<tr>
		<td class="heading">Telesale Account Details</td>
	</tr>
<?
	if($reason)
		echo "<tr>
				<td class=\"login\"><strong>Error:</strong> {$reason}</td>
			</tr>";
?>
	<tr>
		<td class="border">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="field">Username</td>
					<td><input type="text" name="username" value="<?= $username ?>"></td>
				</tr>
				<tr>
					<td class="field">Email</td>
					<td><input type="text" name="email" value="<?= $email ?>"></td>
				</tr>
				<tr>
					<td class="field">Title</td>
					<td><select name="title">
						<option>Mr.</option>
						<option>Miss</option>
						<option>Mrs.</option>
						<option>Dr.</option>
					</select></td>
				</tr>
				<tr>
					<td class="field">First Name</td>
					<td><input type="text" name="firstname" value="<?= $firstname ?>"></td>
				</tr>
				<tr>
					<td class="field">Last Name</td>
					<td><input type="text" name="lastname" value="<?= $lastname ?>"></td>
				</tr>
				<tr>
					<td class="field">Generate Password</td>
					<td><input class="checkbox" type="checkbox" name="generate" checked></td>
				</tr>
				<tr>
					<td class="field">Specify Password</td>
					<td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td class="field">Confirm</td>
					<td><input type="password" name="confirm"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="border"><div class="right"><input class="submit" type="Submit" value="Continue"></div></td>
	</tr>
</table>
</form>
</div>