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
	//class for dealing with shop sessions
	//creates, updates, retrieves and whatnot sessions in the db

	class Session
	{
		var $session_id;
		var $session;
		var $db;
		var $config;

		function session(&$db,&$config,$session_id=false)
		{
			$this->db=$db;
			$this->config=$config;
			if($session_id==false)
			{
				if(USECOOKIE)
					$this->session_id=trim($_COOKIE[$this->config['shop']['session_id']]);
				else if(isset($_REQUEST[$this->config['shop']['session_id']]))
					$this->session_id=$_REQUEST[$this->config['shop']['session_id']];
			}
			else
				$this->session_id=$session_id;

			if($this->session_id!="")
			{
				$this->session =& $this->_getSession();
				if($this->session->EOF)
					$this->session =& $this->_createSession();
			}
			else
				$this->session =& $this->_createSession();

			if($this->session->EOF)
				die("Error instantiating session");
		}

		function _getSession()
		{
			return $this->db->Execute(
				sprintf("
					SELECT
						id
						,nitems
						,total
						,weight
						,packing
						,shipping
						,area_id
						,last_category_id
						,discount_code
						,discount_code_id
						,delivery_name
						,delivery_email
						,delivery_phone
						,delivery_line1
						,delivery_line2
						,delivery_line3
						,delivery_line4
						,delivery_postcode
						,delivery_country_id
						,billing_name
						,billing_email
						,billing_phone
						,billing_line1
						,billing_line2
						,billing_line3
						,billing_line4
						,billing_postcode
						,billing_country
						,billing_country_id
						,multibuy_discount
						,promotional_discount
						,promotional_discount_type
						,account_id
						,pick_up_date
						,gift_voucher
						,additional_payment
						,delivery_speedtax_status
						,delivery_service_code
						,tax
						,customerProfileId
						,customerAddressId
						,paymentProfileId
						,last_gift_list_id
						,gift_message
						,cvv
					FROM
						shop_sessions
					WHERE
						session_id=%s
				"
					,$this->db->Quote($this->session_id)
				)
			);
		}

		function _createSession()
		{
			//$this->session_id=time().md5(microtime().uniqid("shop",true));
			$this->session_id = substr(time().md5(microtime().uniqid("shop",true)),0,25);
			if(USECOOKIE || TESTCOOKIE)
			{
                header('P3P: '.$this->config['p3p']);
				setcookie($this->config['shop']['session_id'],$this->session_id,0,'/');
			}
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_countries
					WHERE
						`default` = 1
					LIMIT 1
				"
				)
			);
			$result = $result->FetchRow();
			if($result)
				$country_id = $result['id'];
			else
				$country_id = $this->config['defaultcountry_id'];
			
			$this->db->Execute(
				sprintf("
					INSERT INTO shop_sessions (
						session_id
						,lastaccess
						,delivery_country_id
					) VALUES (
						%s
						,%u
						,%u
					)
				"
					,$this->db->Quote($this->session_id)
					,time()
					,$country_id
				)
			);
			return $this->_getSession();
		}

		function getSessionID()
		{
			return $this->session_id;
		}

		function getSession()
		{
			return $this->session;
		}
	}

	$session =& new session($db,$config);
	$session_id=$session->session_id;
?>