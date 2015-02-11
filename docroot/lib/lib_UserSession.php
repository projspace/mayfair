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
	//class for dealing with admin sessions
	//creates, updates, retrieves and whatnot sessions in the db

	class UserSession
	{
		var $session_id;
		var $account_id;
		var $session;
		var $db;
		var $config;

		function UserSession()
		{
			global $db,$config;
			$this->db=$db;
			$this->config=$config;
			$this->session_id=trim($_COOKIE[$this->config['user']['session_id']]);
			$this->account_id=trim($_COOKIE[$this->config['user']['account_id']]);
		}

		function start($account_id)
		{
			//$this->_remove($account_id);
			$this->session_id=time().md5(microtime().uniqid("shop",true));
			$this->account_id=$account_id;

			header('P3P: '.$this->config['p3p']);
			setcookie($this->config['user']['session_id'],$this->session_id,0,'/');
			setcookie($this->config['user']['account_id'],$this->account_id,0,'/');

			$this->db->Execute(
				sprintf("
					INSERT INTO shop_user_sessions (
						account_id
						,session_id
						,lastaccess
					) VALUES (
						%u
						,%s
						,%u
					)
				"
					,$this->account_id
					,$this->db->Quote($this->session_id)
					,time()
				)
			);
		}

		function check()
		{
			$this->session=$this->db->Execute(
				sprintf("
					SELECT
						shop_user_sessions.id
						,shop_user_accounts.*
					FROM
						shop_user_sessions
						,shop_user_accounts
					WHERE
						shop_user_accounts.id=shop_user_sessions.account_id
					AND
						shop_user_sessions.account_id=%u
					AND
						shop_user_sessions.session_id=%s
					AND
						shop_user_sessions.lastaccess>%u
				"
					,$this->account_id
					,$this->db->Quote($this->session_id)
					,time()-$this->config['user']['timeout']
				)
			);
			if(trim($this->db->ErrorMsg())!="")
				return false;
			else if($this->session->EOF)
				return false;
			else
			{
				//if($this->_consecutive()>1)
				//	alert("Another user is already logged in to ".PRODUCT_NAME." with this account (".$this->session->fields['username'].")","Warning");
				return true;
			}
		}

		function update()
		{
			$this->db->Execute(
				sprintf("
					UPDATE
						shop_user_sessions
					SET
						lastAccess=%u
					WHERE
						id=%u
				"
					,time()
					,$this->session->fields[0]
				)
			);
		}

		function end()
		{
			$this->db->Execute(
				sprintf("
					DELETE FROM
						shop_user_sessions
					WHERE
						account_id=%u
					AND
						session_id=%s
				"
					,$this->account_id
					,$this->db->Quote($this->session_id)
				)
			);
			header('P3P: '.$this->config['p3p']);
			setcookie($this->config['user']['session_id'],"",0,'/');
			setcookie($this->config['user']['account_id'],"",0,'/');
		}

		function _remove($account_id)
		{
			$this->db->Execute(
				sprintf("
					DELETE FROM
						shop_user_sessions
					WHERE
						account_id=%u
				"
					,$account_id
				)
			);
		}

		function _consecutive()
		{
			$ret=$this->db->Execute(
				sprintf("
					SELECT
						COUNT(id) AS num
					FROM
						shop_user_sessions
					WHERE
						account_id=%u
					AND
						shop_user_sessions.lastaccess>%u
				"
					,$this->account_id
					,time()-$this->config['admin']['timeout']
				)
			);
			return $ret->fields['num'];
		}
	}

	$user_session =& new UserSession();
?>
