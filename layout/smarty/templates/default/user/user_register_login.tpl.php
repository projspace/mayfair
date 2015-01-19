<div class="siteTitle">User Login/Registration</div>
<p>If you are already a registered {$config.company} customer please enter your login details below and click the "Login" button to continue the order process.</p>
<p><b>If this is your first order with us, we need you to <a href="{$config.dir}index.php/fuseaction/user.register/return/shop.checkout">register with us</a> to continue shopping.  Please <a href="{$config.dir}index.php/fuseaction/user.register/return/shop.checkout">click here</a> to go to the registration page.</b></p>
<p>If you do not wish to complete registration now, don't worry, we've saved your shopping cart for 24 hours so you can come back and complete this later.</p>
<center>
<table border="0">
	<form method="post" action="{$config.dir}index.php/fuseaction/user.doLogin">
	<input type="hidden" name="return" value="shop.checkout">
	<tr>
		<td class="shopField">Username</td>
		<td><input type="text" name="username"></td>
	</tr>
	<tr>
		<td class="shopField">Password</td>
		<td><input type="password" name="password"></td>
	</tr>
	<tr>
		<td colspan="2"><p align="right"><input class="shopButtonInput" type="Submit" value="Login"></p></td>
	</tr>
	</form>
</table>
</center>