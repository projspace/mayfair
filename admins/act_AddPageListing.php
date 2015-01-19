<?
	$db->Execute(
		sprintf("
			INSERT INTO
				cms_pages_listings (
					pageid
					,posted
					,title
					,content
				) VALUES (
					%u
					,%s
					,%s
					,%s
				)
		"
			,$_POST['pageid']
			,$db->DBTimeStamp(time())
			,$db->Quote($_POST['title'])
			,$db->Quote($_POST['content'])
		)
	);
?>