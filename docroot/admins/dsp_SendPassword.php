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
<?

	if($reason)
		alertRender( array( array('message' => 'That username/email was not found in the database.  Please check and retry.', 'heading' => 'Error') ) );
?>


<div id="login">
	<div id="loginBody">
		<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.sendPassword&act=send" class="clearfix">
			<label>
				<span style="width:500px;">Username</span>

				<input class="input-text input-large" type="text" id="username" name="username" value="<?= $username ?>" tabindex="1"/>
			</label>
			
			<label>
				<span style="width:500px;">E-mail</span>

				<input class="input-text input-large" type="text" id="email" name="email" value="<?= $email ?>" tabindex="2"/>
			</label>

			<div class="buttons clearfix">
				<span class="button">
					<input class="submit" type="submit" value="Reset password" tabindex="3"/>
				</span>
			</div>
		</form>

	</div>
</div>
