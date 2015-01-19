<?
	$page404 = array();
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = '404_content'
		"
		)
	);
	$page404['content'] = $result->FetchRow();
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = '404_title'
		"
		)
	);
	$page404['title'] = $result->FetchRow();
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = '404_keywords'
		"
		)
	);
	$page404['keywords'] = $result->FetchRow();
	
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = '404_description'
		"
		)
	);
	$page404['description'] = $result->FetchRow();
?>