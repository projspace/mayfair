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
	class Email
	{
		var $mail;
		var $config;

		function Email($config)
		{
			include($config['path']."lib/phpmailer/class.phpmailer.php");
			$this->mail=new PHPMailer();
			$this->mail->SetLanguage("en",$config['path']."lib/phpmailer/language/");
			$this->config=$config;
		}

		function sendMessage($vars,$layout,$email,$name,$attach=false)
		{
			global $db;
			include($this->config['path']."layout/email/cfg_".$layout.".php");

			ob_start();
			include($this->config['path']."layout/email/lay_".$layout."text.php");
			$text=ob_get_contents();
			ob_end_clean();
			ob_start();
			include($this->config['path']."layout/email/lay_".$layout."html.php");
			$html=ob_get_contents();
			ob_end_clean();

			if(!defined('FROM'))
			{
				$from=$db->Execute(
					sprintf("
						SELECT
							*
						FROM
							shop_variables
						WHERE
							name = 'from'
					"
					)
				);
				$from = $from->FetchRow();
				define('FROM', $from['value']);
			}
			
			$this->mail->ClearAllRecipients();
			$this->mail->ClearAttachments();
			$this->mail->IsHTML(true);
			//$this->mail->From=$this->config['mail']['fromaddress'];
			$this->mail->From=FROM;
			$this->mail->FromName=$this->config['mail']['fromname'];
			$this->mail->Host=$this->config['mail']['server'];
			if($this->config['mail']['auth'] == true)
			{
				$this->mail->SMTPAuth = true;
				$this->mail->Username = $this->config['mail']['token'];
				$this->mail->Password = $this->config['mail']['token'];
			}
			$this->mail->Mailer=$this->config['mail']['method'];

			if(is_array($embed))
			{
				foreach($embed as $item)
				{
					if(strstr($item,"/"))
						$cid=substr($item,strrpos($item,"/")+1);
					else
						$cid=$item;
					$this->mail->AddEmbeddedImage($this->config['path'].$item,$cid,"","base64",$this->_getType($cid));
				}
			}
			if(is_array($attach))
			{
				foreach($attach as $item)
					$this->mail->AddAttachment($item);
			}

			$this->mail->AltBody=$text;
			$this->mail->Body=$html;
			$this->mail->Priority=1;
			$this->mail->Subject=$subject;
			$this->mail->AddAddress($email,$name);

			$ret = $this->mail->Send();
            if(!$ret)
            {
                if ($handle = fopen($config['path'].'debug.txt', 'a'))
                {
                    fwrite($handle, "\n".'===== Sending Email Failed =====');
                    fwrite($handle, "\n".'reason: '.var_export($this->mail->ErrorInfo, true));
                    fwrite($handle, "\n".'vars: '.var_export($vars, true));
                    fwrite($handle, "\n".'layout: '.var_export($layout, true));
                    fwrite($handle, "\n".'email: '.var_export($email, true));
                    fwrite($handle, "\n".'name: '.var_export($name, true));
                    fwrite($handle, "\n".'attach: '.var_export($attach, true));
                    fclose($handle);
                }
            }
            return $ret;
		}

		function _getType($filename)
		{
			$filename=strtolower($filename);
			if(strstr($filename,"css"))
				return "text/css";
			else if(strstr($filename,"gif"))
				return "image/gif";
			else if(strstr($filename,"jpg"))
				return "image/jpeg";
			else if(strstr($filename,"jpeg"))
				return "image/jpeg";
			else if(strstr($filename,"png"))
				return "image/png";
			else if(strstr($filename,"pdf"))
				return "application/pdf";
			else if(strstr($filename,"doc"))
				return "application/word";
			else if(strstr($filename,"xls"))
				return "application/excel";
			else
				return "application/octet-stream";
		}
	}

	$mail=new Email($config);
?>