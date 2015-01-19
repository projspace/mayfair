<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();
	
	$db->Execute(
		$sql = sprintf("
			INSERT INTO
				shop_user_accounts
			SET
				email=%s
				,password=%s
				,title=%s
				,firstname=%s
				,lastname=%s
				,primary_phone=%s
				,info=%s
				,additional_payment_label=%s
				,additional_payment_session_id=%s
				,dob=%s
				,student=%u
				,teacher=%u
				,shop=%u
				,newsletter=%u
				,created = NOW()
		"
			,$db->Quote(safe($_REQUEST['email']))
			,$db->Quote(safe($_REQUEST['password']))
			,$db->Quote(safe($_REQUEST['title']))
			,$db->Quote(safe($_REQUEST['firstname']))
			,$db->Quote(safe($_REQUEST['lastname']))
			,$db->Quote(safe($_REQUEST['phone']))
			,$db->Quote(safe($_REQUEST['info']))
			,$db->Quote($_REQUEST['additional_payment']?safe($_REQUEST['additional_payment_label']):'')
			,$db->Quote($_REQUEST['additional_payment']?safe($_REQUEST['additional_payment_session_id']):'')
            ,$db->Quote(safe(get_date($_REQUEST['dob'])))
			,$_REQUEST['student']?1:0
			,$_REQUEST['teacher']?1:0
			,$_REQUEST['shop']?1:0
			,$_REQUEST['newsletter']?1:0
		)
	);
	$user_id=$db->Insert_ID();

	if($user_id)
	{
		if($order_address)
		{
			$vars = array('name','email','phone','line1','line2','line3','line4','postcode','country_id');
			
			$empty = true;
			foreach($vars as $var)
			{
				$value = $order_address['billing_'.$var];
				if(trim($value) != '' || $value+0 != 0)
				{
					$empty = false;
					break;
				}
			}
			if(!$empty)
			{
				$db->Execute(
					sprintf("
						INSERT INTO
							shop_user_addresses
						SET
							account_id=%u
							,name=%s
							,email=%s
							,phone=%s
							,line1=%s
							,line2=%s
							,line3=%s
							,line4=%s
							,postcode=%s
							,country_id=%u
							,billing=1
							,delivery=0
					"
						,$user_id
						,$db->Quote(safe($order_address['billing_name']))
						,$db->Quote(safe($order_address['billing_email']))
						,$db->Quote(safe($order_address['billing_phone']))
						,$db->Quote(safe($order_address['billing_line1']))
						,$db->Quote(safe($order_address['billing_line2']))
						,$db->Quote(safe($order_address['billing_line3']))
						,$db->Quote(safe($order_address['billing_line4']))
						,$db->Quote(safe($order_address['billing_postcode']))
						,safe($order_address['billing_country_id'])
					)
				);
			}
			
			$empty = true;
			foreach($vars as $var)
			{
				$value = $order_address['delivery_'.$var];
				if(trim($value) != '' || $value+0 != 0)
				{
					$empty = false;
					break;
				}
			}
			if(!$empty)
			{
				$same = true;
				foreach($vars as $var)
				{
					if($order_address['billing_'.$var] != $order_address['delivery_'.$var])
					{
						$same = false;
						break;
					}
				}
				
				if(!$same)
					$db->Execute(
						sprintf("
							INSERT INTO
								shop_user_addresses
							SET
								account_id=%u
								,name=%s
								,email=%s
								,phone=%s
								,line1=%s
								,line2=%s
								,line3=%s
								,line4=%s
								,postcode=%s
								,country_id=%u
								,billing=0
								,delivery=1
						"
							,$user_id
							,$db->Quote(safe($order_address['delivery_name']))
							,$db->Quote(safe($order_address['delivery_email']))
							,$db->Quote(safe($order_address['delivery_phone']))
							,$db->Quote(safe($order_address['delivery_line1']))
							,$db->Quote(safe($order_address['delivery_line2']))
							,$db->Quote(safe($order_address['delivery_line3']))
							,$db->Quote(safe($order_address['delivery_line4']))
							,$db->Quote(safe($order_address['delivery_postcode']))
							,safe($order_address['delivery_country_id'])
						)
					);
			}
		}
		
		if($_REQUEST['order_id']+0)
		{
			$db->Execute(
				$sql = sprintf("
					INSERT INTO 
						shop_user_orders
					SET
						account_id = %u
						,order_id = %u
				"
					,$user_id
					,$_REQUEST['order_id']
				)
			);
		}
	}
	
	$ok=$db->CompleteTrans();
	//if($ok)
	//	$sent=$mail->sendMessage(array(),"AccountCreated",$_REQUEST['email'],'');
?>