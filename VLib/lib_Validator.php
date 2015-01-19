<?
	class Validator
	{
		protected $session_id;
		protected $session;
		protected $db;
		protected $config;

		function __construct()
		{
			require("cfg_Validator.php");
			$this->config = $vcfg;
			
			try 
			{
				$this->db = new PDO($this->config['db']['driver'].':host='.$this->config['db']['server'].';dbname='.$this->config['db']['database'], $this->config['db']['username'], $this->config['db']['password']);
			} 
			catch (PDOException $e) 
			{
				throw new Exception('Validator: Cannot connect to database');
			}
			
			$this->session_id = trim($_COOKIE[$this->config['cookie']['session_id']]);
			if($this->session_id != "")
			{
				$this->session = $this->_getSession();
				if(!$this->session)
					$this->session = $this->_createSession();
			}
			else
				$this->session = $this->_createSession();

			if(!$this->session)
				throw new Exception('Validator: Error instantiating session');
		}

		protected function _getSession()
		{
			return $this->db->query(
				sprintf("
					SELECT
						*
					FROM
						validator_sessions
					WHERE
						session_id = %s
				"
					,$this->db->Quote($this->session_id)
				)
			)->fetch(PDO::FETCH_ASSOC);
		}

		protected function _createSession()
		{
			$this->session_id = time().md5(microtime().uniqid("validator",true));
			
			header('P3P: '.$this->config['p3p']);
			setcookie($this->config['cookie']['session_id'],$this->session_id,0,$this->config['cookie']['path']);
			
			$this->db->exec(
				$sql = sprintf("
					INSERT INTO 
						validator_sessions
					SET
						session_id = %s
						,ip = %s
						,port = %s
						,user_agent = %s
						,last_access = NOW()

				"
					,$this->db->Quote($this->session_id)
					,$this->db->Quote($_SERVER['REMOTE_ADDR'])
					,$this->db->Quote($_SERVER['REMOTE_PORT'])
					,$this->db->Quote($_SERVER['HTTP_USER_AGENT'])
				)
			);
			$session_id = $this->db->lastInsertId() + 0;
			if(!$session_id)
				throw new Exception('Validator: Error creating session');
			
			return $this->_getSession();
		}

		protected function _request()
		{
			$token = sha1(uniqid(mt_rand().$this->config['token'], true));
			$sha_token = sha1(uniqid(mt_rand().$this->config['sha_token'], true));
			
			$this->db->exec(
				$sql = sprintf("
					INSERT INTO 
						validator_session_requests
					SET
						session_id = %s
						,token = %s
						,sha_token = %s
						,`time` = NOW()

				"
					,$this->session['id']
					,$this->db->Quote($token)
					,$this->db->Quote($sha_token)
				)
			);
			$request_id = $this->db->lastInsertId() + 0;
			if(!$request_id)
				throw new Exception('Validator: Error creating request');
				
			$this->_update();
		}

		protected function _update()
		{
			$this->db->exec(
				sprintf("
					UPDATE
						validator_sessions
					SET
						last_access = NOW()
					WHERE
						id = %u
				"
					,$this->session['id']
				)
			);
		}

		protected function _checkRequest()
		{
			$check = $this->db->query(
				sprintf("
					SELECT
						validator_session_requests.*
					FROM
						validator_sessions
						,validator_session_requests
					WHERE
						validator_sessions.id = validator_session_requests.session_id
					AND
						validator_sessions.session_id = %s
					AND
						validator_sessions.ip = %s
					AND
						validator_sessions.port = %s
					AND
						validator_sessions.user_agent = %s
					AND
						UNIX_TIMESTAMP(validator_session_requests.`time`) > UNIX_TIMESTAMP() - %u
				"
					,$this->db->Quote($this->session_id)
					,$this->db->Quote($_SERVER['REMOTE_ADDR'])
					,$this->db->Quote($_SERVER['REMOTE_PORT'])
					,$this->db->Quote($_SERVER['HTTP_USER_AGENT'])
					,$this->config['timeout']
				)
			)->fetch(PDO::FETCH_ASSOC);
			if(!$check)
				return false;
			if(strtolower(trim(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST))) != strtolower(trim($_SERVER['SERVER_NAME'])))
				return false;
			return true;
		}
		
		protected function _checkToken($check, $request)
		{
			return true;
		}

		public function getSessionID()
		{
			return $this->session_id;
		}

		public function getSession()
		{
			return $this->session;
		}
		
		public function validate($request)
		{
			if(!($check = $this->_checkRequest() && $this->_checkToken($check, $request))) // possible attack
			{
				if($vcfg['contact'] != '')
				{
					$subject = 'Possible attack on '.$_SERVER['SERVER_NAME'];
					$content = array();
					$content[] = "====request====\n".var_export($request, true);
					$content[] = "====_REQUEST====\n".var_export($_REQUEST, true);
					$content[] = "====_POST====\n".var_export($_POST, true);
					$content[] = "====_GET====\n".var_export($_GET, true);
					$content[] = "====_COOKIE====\n".var_export($_COOKIE, true);
					$content[] = "====session====\n".var_export($this->_getSession(), true);
					$content[] = "====time====\n".date('d/m/Y H:i:s');
					
					mail($vcfg['contact'], $subject, implode("\n\n", $content));
				}
				return false;
			}
		}
	}

	$validator = new Validator();
	//$validator->_request();
	var_dump(parse_url('http://globe.dev4.clientproof.co.uk/index.php?fuseaction=admin.validator', PHP_URL_HOST), $_SERVER);exit;
?>