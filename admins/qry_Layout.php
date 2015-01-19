<?
	if(!isset($layoutid))
		$layoutid=$_REQUEST['layoutid'];
	$layout=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_layouts
			WHERE
				id=%u
		"
			,$layoutid
		)
	);
	$layout = $layout->FetchRow();
?>