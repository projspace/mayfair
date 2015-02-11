<?
	$table = 'cms_pages_images';
	$post_var = 'image_id';
	$second_table_var = 'pageid';
	$second_post_var = 'pageid';
	$second_post_var_type = 'int';
	
	if($second_post_var_type == 'string')
		$second_var = $db->Quote($_POST[$second_post_var]);
	else
		$second_var = $_POST[$second_post_var];
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Get current ord and note the min ord is 1

	$ord=$db->Execute(
		sprintf("
			SELECT 
				t.id
				,t.ord
			FROM `%s` t
			WHERE t.ord >= (SELECT t2.ord FROM `%s` t2 WHERE t2.id = %u AND `%s`=%s) AND `%s`=%s
			ORDER BY t.ord ASC
			LIMIT 2 
		"
			,$table
			,$table
			,$_POST[$post_var]
			,$second_table_var
			,$second_var
			,$second_table_var
			,$second_var
		)
	);
	if(($ord->RecordCount() < 0)||($ord->RecordCount() > 2))
		$db->FailTrans();

	if($ord->RecordCount() == 2)
	{
		$row_src=$ord->FetchRow();
		$row_dest=$ord->FetchRow();
		
		$db->Execute(
			sprintf("
				UPDATE
					`%s` t
				SET
					t.ord = %u
				WHERE
					t.id = %u
			"
				,$table
				,$row_dest["ord"]
				,$row_src["id"]
			)
		);
		
		$db->Execute(
			sprintf("
				UPDATE
					`%s` t
				SET
					t.ord = %u
				WHERE
					t.id = %u
			"
				,$table
				,$row_src["ord"]
				,$row_dest["id"]
			)
		);
	}

	$ok=$db->CompleteTrans();
?>