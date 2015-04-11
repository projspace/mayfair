<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	class ACL
	{
		var $_allowed;
		var $_session;
		var $_db;
		var $_config;

		function ACL(&$db,&$session,&$config)
		{
			$this->_session =& $session;
			$this->_db =& $db;
			$this->_config =& $config;
			$this->_retrieve();
		}

		function _retrieve()
		{
			$acl =& $this->_db->Execute(
				sprintf("
					SELECT
						admin_acl_actions.name
					FROM
						admin_acl_group_action
						,admin_acl_actions
						,admin_accounts
					WHERE
						admin_accounts.id=%u
					AND
						admin_acl_group_action.group_id=admin_accounts.group_id
					AND
						admin_acl_actions.id=admin_acl_group_action.action_id
					ORDER BY
						admin_acl_actions.name
					ASC
				"
					,$this->_session->account_id
				)
			);
			$vals =& $acl->GetRows();

			//Setup defaults
			$this->_allowed=Array();
			array_push($this->_allowed,"password");

			foreach($vals as $val)
				array_push($this->_allowed,trim($val['name']));
		}

		function check($action)
		{
			return in_array(trim($action),$this->_allowed);
		}

		function allowed($action)
		{
			$ret=false;
			if(!in_array(trim($action),$this->_allowed))
				error("You have not been given access to this resource, please contact the shop Administrator if you feel you are receiving this message in error.","Insufficient Privileges");
			else
				$ret=true;
			return $ret;
		}
	}

	$acl=new ACL($db,$session,$config);
?>