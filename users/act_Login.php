<?
	$user=$db->Execute(
		$sql = sprintf("
			SELECT
				id
			FROM
				shop_user_accounts
			WHERE
				email=%s
			AND
				password=%s
		"
			,$db->Quote($_POST['email'])
			,$db->Quote($_POST['password'])
		)
	);
	$user = $user->FetchRow();
	if($user)
	{
		$user_session->start($user['id']);
		
		$return_url = safe($_POST['return_url']);
		if(isset($_REQUEST['ajax']))
		{
			echo '<script language="javascript" type="text/javascript">/* <![CDATA[ */';
			if($return_url != '')
				echo 'parent.window.location = "'.addcslashes($return_url, '"').'";';
			else
				echo 'parent.window.location.reload(true);';
			echo '/* ]]> */</script>';
		}
		else
		{
			if($return_url != '')
				$url = $return_url;
			else
				$url = $config["dir"];
			header("Location: ".$url);
		}
		exit;
	}
	else
	{
		$validator->_valid = false;
		$validator->_errorMsg[] = 'Login failed. Please check your details and try again.';
	}
?>