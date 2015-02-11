<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		sprintf("
			DELETE FROM
				admin_acl_groups
			WHERE
				id != 1
			AND
				id=%u
		"
			,$_POST['group_id']
		)
	);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst removing the ACL group, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>