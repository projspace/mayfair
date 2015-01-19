<style type="text/css">
.center-align-float-wrapper { display: none; }
</style>


<div id="login">
	<div id="loginBody">
		<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.doLogin" class="clearfix">
			<label>
				<span>Username</span>

				<input class="input-text input-large" type="text" id="username" name="username" tabindex="1"  />
			</label>
			<label class="clearfix">
				<span>Password</span>
				<input class="input-text input-large" type="password" id="password" name="password" tabindex="2" />
			</label>

			<div class="buttons clearfix">
				<span class="button">
					<input class="submit" type="submit" value="Login" tabindex="3"/>
				</span>
				<a class="forgot-password" href="<?= $config['dir'] ?>index.php?fuseaction=admin.sendPassword">Forgotten Password</a>
			</div>
		</form>
	</div>
</div>
