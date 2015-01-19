<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				admin_acl_group_action
			WHERE
				group_id=%u
		"
			,$_POST['group_id']
		)
	);

	foreach($_POST['acl_action'] as $action_id)
	{
		$db->Execute(
			sprintf("
				INSERT INTO
					admin_acl_group_action (
						group_id
						,action_id
					) VALUES (
						%u
						,%u )
			"
				,$_POST['group_id']
				,$action_id
			)
		);
	}

	$db->Execute(
		sprintf("
			UPDATE
				admin_acl_groups
			SET
				name=%s
				,description=%s
			WHERE
				id=%u
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['description'])
			,$_POST['group_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
    	error("There was a problem whilst updating the ACL group, please try again.  If this persists please notify your designated support contact","Database Error");
?>