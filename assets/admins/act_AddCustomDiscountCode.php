<?
	$code = strtoupper($_POST['code']);
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
	if($results->RecordCount())
	{
		error("This discount code already exists. PLease choose another one","Error");
		return;
	}
	
	$sql = array();
	$sql[] = sprintf("
		(%s, %f, %f, %s, %s, %u, %u, %u)
	"
		, $db->Quote($code)
		, $_POST['value']
		, $_POST['min_order']
		, $db->Quote((strtolower($_POST['value_type']) == 'percent')?'percent':'fixed')
		, $db->Quote(implode('-', array_reverse(explode('/', trim($_POST['expiry_date'])))))
		, $_POST['use_count']
		, $_POST['all_users']?1:0
		, $_POST['gift_list_id']
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
					,gift_list_id
				)
				VALUES
				%s
			"
				,implode(',', $sql)
			)
		);
	
	$ok=$db->CompleteTrans();
	if(!$ok)
		error("There was a problem whilst adding the custom discount code, please try again.  If this problem persists please contact your designated support contact","Database Error");
?>