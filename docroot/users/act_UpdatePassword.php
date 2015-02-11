<?
	if($_POST['current_password'] != $account['password'])
	{
		echo '<form class="reviewForm"><h3>Error</h3><p>Please provide the correct password.</p><p class="submit"><input type="button" class="redDoubleArrow ccClose" value="Close"></p></form';
		return;
	}
	if(safe($_POST['new_password']) != safe($_POST['retype_new_password']))
	{
		echo '<form class="reviewForm"><h3>Error</h3><p>Passwords do not match. Please try again.</p><p class="submit"><input type="button" class="redDoubleArrow ccClose" value="Close"></p></form';
		return;
	}
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_user_accounts
			SET
				password=%s
			WHERE
				id = %u
		"
			,$db->Quote(safe($_POST['new_password']))
			,$user_session->account_id
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		echo '<form class="reviewForm"><h3>Error</h3><p>There was a problem whilst updating the password, please try again.</p><p class="submit"><input type="button" class="redDoubleArrow ccClose" value="Close"></p></form';
	else
		echo '<form class="reviewForm"><h3>Success</h3><p>Your password has been successfully updated.</p><p class="submit"><input type="button" class="redDoubleArrow ccCloseReload" value="Close"></p></form>';
?>