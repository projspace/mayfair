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

	class AdminSession
	{
		var $session_id;
		var $account_id;
		var $session;
		var $db;
		var $config;
		var $_data;
		var $_alerted;

		function AdminSession()
		{
			global $db,$config;
			$this->db=$db;
			$this->config=$config;
			$this->session_id=trim($_COOKIE[$this->config['admin']['session_id']]);
			$this->account_id=trim($_COOKIE[$this->config['admin']['account_id']]);
			$this->_data=array();
			$this->_alerted=false;
		}

		function start($account_id)
		{
			//$this->_remove($account_id);
			$this->session_id=time().md5(microtime().uniqid("shop",true));
			$this->account_id=$account_id;

			header('P3P: '.$this->config['p3p']);
			//setcookie($this->config['admin']['session_id'],$this->session_id,0,$this->config['dir']);
			//setcookie($this->config['admin']['account_id'],$this->account_id,0,$this->config['dir']);
			setcookie($this->config['admin']['session_id'],$this->session_id,0,'/');
			setcookie($this->config['admin']['account_id'],$this->account_id,0,'/');

			$this->db->Execute(
				sprintf("
					INSERT INTO admin_sessions (
						account_id
						,session_id
						,remote_addr
						,hostname
						,lastaccess
					) VALUES (
						%u
						,%s
						,%s
						,%s
						,%u
					)
				"
					,$this->account_id
					,$this->db->Quote($this->session_id)
					,$this->db->Quote($_SERVER['REMOTE_ADDR'])
					,$this->db->Quote(gethostbyaddr($_SERVER['REMOTE_ADDR']))
					,time()
				)
			);
		}

		function check()
		{
			$this->session=$this->db->Execute(
				sprintf("
					SELECT
						admin_sessions.id
						,admin_accounts.username
						,admin_sessions.data
					FROM
						admin_sessions
						,admin_accounts
					WHERE
						admin_accounts.id=admin_sessions.account_id
					AND
						admin_sessions.account_id=%u
					AND
						admin_sessions.session_id=%s
					AND
						admin_sessions.lastaccess>%u
				"
					,$this->account_id
					,$this->db->Quote($this->session_id)
					,time()-$this->config['admin']['timeout']
				)
			);
			if(trim($this->db->ErrorMsg())!="")
				return false;
			else if($this->session->EOF)
				return false;
			else
			{
				$this->_data=unserialize($this->session->fields['data']);
				if($this->_consecutive()>1 && !$this->_alerted)
				{
					alert("Another user is already logged in to ".PRODUCT_NAME." with this account (".$this->session->fields['username'].")","Warning");
					$this->_alerted=true;
				}
				return true;
			}
		}

		function update()
		{
			$this->db->Execute(
				sprintf("
					UPDATE
						admin_sessions
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
						admin_sessions
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
			//setcookie($this->config['admin']['session_id'],"",0,$this->config['dir']);
			//setcookie($this->config['admin']['account_id'],"",0,$this->config['dir']);
            setcookie($this->config['admin']['session_id'],"",0,'/');
			setcookie($this->config['admin']['account_id'],"",0,'/');
		}

		function _remove($account_id)
		{
			$this->db->Execute(
				sprintf("
					DELETE FROM
						admin_sessions
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
						admin_sessions
					WHERE
						account_id=%u
					AND
						admin_sessions.lastaccess>%u
				"
					,$this->account_id
					,time()-$this->config['admin']['timeout']
				)
			);
			return $ret->fields['num'];
		}

		function getValue($name)
		{
			return $this->_data[$name];
		}

		function setValue($name,$value)
		{
			$this->_data[$name]=$value;
		}

		function save()
		{
			$this->db->Execute(
				sprintf("
					UPDATE
						admin_sessions
					SET
						data=%s
					WHERE
						session_id=%s
					AND
						account_id=%u
				"
					,$this->db->Quote(serialize($this->_data))
					,$this->db->Quote($this->session_id)
					,$this->account_id
				)
			);
		}
	}

	$session =& new AdminSession();
?>