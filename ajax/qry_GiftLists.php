<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Common.php");
	include("../lib/lib_UserSession.php");
	
	try
	{
		if(!$user_session->check())
			throw new Exception('You are no longer logged in. Please login again.', 10000);
		
		$lists=$db->Execute(
			sprintf("
				SELECT
					id
					,name
				FROM
					gift_lists
				WHERE
					account_id = %u
				AND
					status = 'pending'
				ORDER BY
					name ASC
			"
				,$user_session->account_id
			)
		);

        $msg = '<div class="report-box  custom-select text-big">';
		$msg .=  '<select name="gift_list_id" id="gift_list_id">';
		$msg .= '<option value="">select list</option>';
		while($row = $lists->FetchRow())
			$msg .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		$msg .= '</select>';
        $msg .= '</div>';
        $msg .= '<div class="clear"></div>';
			
		die(json_encode(array('status'=>true, 'message'=>$msg)));
	}
	catch(Exception $e)
	{
		if($e->getCode() >= 10000)
			$msg = $e->getMessage();
		else
			$msg = 'There was a problem whilst processing your request, please try again.';
			
		if($e->getCode() == 10000)
			$status = 'login';
		else
			$status = false;
			
		die(json_encode(array('status'=>$status, 'message'=>$msg)));
	}
?>