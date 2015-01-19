<?
	$validator=new Validation("summary");
	$validator->addRequired("name","Name");
	$validator->addRequired("code","Code");
	$validator->addCustom("code","Code","existingWidthCode","This width code already exists. Please choose another one.");
	
	function val_existingWidthCode($value)
	{
		global $db;
		
		$ret = $db->Execute(
			sprintf("
				SELECT
					id
				FROM
					shop_widths
				WHERE
					code = %s
				AND
					id != %u
			"
				,$db->Quote($value)
				,$_REQUEST['width_id']
			)
		);
		
		return $ret->FetchRow()?false:true;
	}
?>