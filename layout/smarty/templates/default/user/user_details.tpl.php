{if $errorflag neq ""}
	<div class="siteTitle">{$errorflag}</div><br>
{/if}
{if $messageflag neq ""}
	<div class="siteTitle">{$messageflag}</div><br>
{/if}
<table border="0" cellpadding="4" cellspacing="4">
	<tr valign="top">
		<td>
			<table border="0">
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<tr><td colspan="2"><div class="siteTitle">Account Details</div></td></tr>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<tr>
					<td class="shopField">Username</td>
					<td>{$details.username}</td>
				</tr>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<form method="post" action="{$config.dir}index.php/fuseaction/user.details/act/password">
				<tr>
					<td class="shopField">Password</td>
					<td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td class="shopField">Confirm Password</td>
					<td><input type="password" name="confirm"></td>
				</tr>
				<tr>
					<td colspan="2"><p align="right"><input class="shopButtonInput" type="Submit" value="Change"></p></td>
				</tr>
				</form>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<form method="post" action="{$config.dir}index.php/fuseaction/user.details/act/details">
				<tr>
					<td class="shopField">Email</td>
					<td><input type="text" name="email" value="{$details.email}"></td>
				</tr>
				<tr>
					<td class="shopField">Title</td>
					<td><input type="text" name="title" value="{$details.title}"></td>
				</tr>
				<tr>
					<td class="shopField">First Name</td>
					<td><input type="text" name="firstname" value="{$details.firstname}"></td>
				</tr>
				<tr>
					<td class="shopField">Last Name</td>
					<td><input type="text" name="lastname" value="{$details.lastname}"></td>
				</tr>
				<tr>
					<td colspan="2"><p align="right"><input class="shopButtonInput" type="Submit" value="Update"></p></td>
				</tr>
				</form>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
			</table>
		</td>
		<td>
			<table border="0">
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<tr><td colspan="2"><div class="siteTitle">Shipping</div></td></tr>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<form method="post" action="{$config.dir}index.php/fuseaction/user.details/act/shipping">
				<tr>
					<td class="shopField">Address</td>
					<td><textarea style="width: 100%;" rows="5" name="shippingaddress">{$details.shippingaddress}</textarea></td>
				</tr>
				<tr>
					<td class="shopField">Postcode</td>
					<td><input type="text" name="shippingpostcode" value="{$details.shippingpostcode}"></td>
				</tr>
				<tr>
					<td class="shopField">Country</td>
					<td><select name="shippingcountry_id">
					{if $details.shippingcountry_id neq ""}
						{html_options values=$cid output=$cname selected=$details.shippingcountry_id}
					{else}
						{html_options values=$cid output=$cname selected=$config.defaultcountry_id}
					{/if}
					</select></td>
				</tr>
				<tr>
					<td colspan="2"><p align="right"><input type="Submit" class="shopButtonInput" value="Update"></p></td>
				</tr>
				</form>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<tr><td colspan="2"><div class="siteTitle">Billing</div></td></tr>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
				<form method="post" action="{$config.dir}index.php/fuseaction/user.details/act/billing">
				<tr>
					<td class="shopField">Address</td>
					<td><textarea style="width: 100%;" rows="5" name="billingaddress">{$details.billingaddress}</textarea></td>
				</tr>
				<tr>
					<td class="shopField">Postcode</td>
					<td><input type="text" name="billingpostcode" value="{$details.billingpostcode}"></td>
				</tr>
				<tr>
					<td class="shopField">Country</td>
					<td><select name="billingcountry_id">
					{if $details.billingcountry_id neq ""}
						{html_options values=$cid output=$cname selected=$details.billingcountry_id}
					{else}
						{html_options values=$cid output=$cname selected=$config.defaultcountry_id}
					{/if}
					</select></td>
				</tr>
				<tr>
					<td colspan="2"><p align="right"><input type="Submit" class="shopButtonInput" value="Update"></p></td>
				</tr>
				</form>
				<tr><td colspan="2"><img src="{$config.dir}images/dash.gif" width="280" height="9" alt="--------"></td></tr>
			</table>
		</td>
	</tr>
</table>