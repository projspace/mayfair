<?
	$gift_registry=$db->Execute(
		$sql=sprintf("
			SELECT
				gl.*
			FROM
				gift_lists gl
            ORDER BY
				gl.name ASC
		"
		)
	);
?>