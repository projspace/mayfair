<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			INSERT INTO
				admin_acl_groups
			SET
				name=%s
				,description=%s
		"
			,$db->Quote($_POST['name'])
			,$db->Quote($_POST['description'])
		)
	);
	$group_id=$db->Insert_ID();
	
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
				,$group_id
				,$action_id
			)
		);
	}
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the ACL group, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>