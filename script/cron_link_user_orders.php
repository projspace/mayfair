<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	
	$db->Execute(
		sprintf("
			UPDATE
				shop_user_orders
				,shop_orders
			SET
				shop_user_orders.order_id = shop_orders.id
			WHERE
				shop_user_orders.session_id = shop_orders.session_id
			AND
				shop_user_orders.session_id != ''
			AND
				shop_user_orders.order_id = 0
		"
		)
	);
	echo 'Finished';
?>