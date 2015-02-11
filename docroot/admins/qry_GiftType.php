<?
	$type=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				gift_types
			WHERE
				id = %u
		"
			,$_REQUEST['type_id']
		)
	);
	$type = $type->FetchRow();
?>