<div class="siteTitle">User Registration</div><br><br>
<p>Registering as a {$config.company} user is quick and easy.  Simply enter your details in the form below and press the "continue" button to start.</p>
<p>Once you have registered you will start to benefit from our membership benefits such as full order history tracking, a simple re-order facility and automatic address insertion when you checkout.</p>
<center>
{if $reason neq ""}
	<b>Error:</b> {$reason}
{/if}
<table border="0">
	<form method="post" action="{$config.dir}index.php/fuseaction/user.register/act/register">
	{if $return neq ""}
	<input type="hidden" name="return" value="{$return}">
	{/if}
	<tr>
		<td class="shopField">Desired Username</td>
		<td><input type="text" name="username" value="{$params.username}"></td>
	</tr>
	<tr>
		<td class="shopField">Password</td>
		<td><input type="password" name="password" value="{$params.password}"></td>
	</tr>
	<tr>
		<td class="shopField">Confirm Password</td>
		<td><input type="password" name="confirm" value="{$params.confirm}"></td>
	</tr>
	<tr>
		<td class="shopField">Email Address</td>
		<td><input type="text" name="email" value="{$params.email}"></td>
	</tr>
	<tr>
		<td class="shopField">Title</td>
		<td><input type="text" name="title" value="{$params.title}"></td>
	</tr>
	<tr>
		<td class="shopField">First Name</td>
		<td><input type="text" name="firstname" value="{$params.firstname}"></td>
	</tr>
	<tr>
		<td class="shopField">Last Name</td>
		<td><input type="text" name="lastname" value="{$params.lastname}"></td>
	</tr>
	<tr>
		<td colspan="2"><p align="right"><input type="Submit" class="shopButtonInput" value="Continue"></p></td>
	</tr>
</table>
</center>