<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	$db->Execute(
		$sql=sprintf("
			UPDATE
				shop_fitting_guide_sizes
			SET
				size = %s
			WHERE
				id = %u
			LIMIT 1
		"
			,$db->Quote($_POST['size'])
			,$_POST['size_id']
		)
	);

	$ok=$db->CompleteTrans();
	if(!$ok)
		die('NOT OK');
	else
		die('OK');
?>