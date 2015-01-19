<?
	$validator=new Validation("summary");
	$validator->addRequired("name","Name");
	$validator->addRequired("code","Code");
	//$validator->addRequired("hexa","Color");
	$validator->addCustom("code","Code","existingColorCode","This color code already exists. Please choose another one.");
	
	function val_existingColorCode($value)
	{
		global $db;
		
		$ret = $db->Execute(
			sprintf("
				SELECT
					id
				FROM
					shop_colors
				WHERE
					code = %s
				AND
					id != %u
			"
				,$db->Quote($value)
				,$_REQUEST['color_id']
			)
		);
		
		return $ret->FetchRow()?false:true;
	}
?>