<?
	$count = intval($_POST['count']);
	$length = intval($_POST['length']);
	
	if($count <= 0)
		$count = 1;
	if($length <= 0 || $length > 32)
		$length = 15;
	
	$codes = array();
	for($index=0;$index<$count;$index++)
	{
		do
		{
			$code = "";
			for($i=0;$i<$length;$i++)
			{
				$type = rand(0, 2);
				switch($type)
				{
					case 0:	//number
						$code .= chr(rand(48, 57));
						break;
					case 1:	//UPPERCASE
						$code .= chr(rand(65, 90));
						break;			
					case 2:	//lowercase
						$code .= chr(rand(97, 122));
						break;			
				}
			}
			$code = strtoupper($code);
			$results=$db->Execute(
				$sql = sprintf("
					SELECT
						id
					FROM
						shop_promotional_codes
					WHERE
						code = %s
					"
					,$db->Quote($code)
				)
			);
		}
		while($results->RecordCount());
		
		$codes[] = $code;
	}
	
	$sql = array();
	foreach($codes as $code)
		$sql[] = sprintf("
			(%s, %f, %f, %s, %s, %u, %u)
		"
			, $db->Quote($code)
			, $_POST['value']
			, $_POST['min_order']
			, $db->Quote((strtolower($_POST['value_type']) == 'percent')?'percent':'fixed')
			, $db->Quote(implode('-', array_reverse(explode('/', trim($_POST['expiry_date'])))))
			, $_POST['use_count']
			, $_POST['all_users']?1:0
		);
	
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	if(count($sql))
		$db->Execute(
			$sql = sprintf("
				INSERT INTO
					shop_promotional_codes
				(
					code
					,value
					,min_order
					,value_type
					,expiry_date
					,use_count
					,all_users
				)
				VALUES
				%s
			"
				,implode(',', $sql)
			)
		);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the discount codes, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>