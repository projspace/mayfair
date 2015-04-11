<?
	class CreditCard
	{
		function clean_no ($cc_no)
  		{
			return ereg_replace ('[^0-9]+', '', $cc_no);
  		}

  		function identifyCard ($cc_no)
  		{
			if (ereg ('^5[1-5].{14}$', $cc_no))			{return array('Type' => 'Mastercard','Index' => 11, 'CanAccept' => TRUE);}

			if (ereg ('^6334[5-9].{11}$', $cc_no))		{return array('Type' => 'Solo / Maestro','Index' => 16, 'CanAccept' => FALSE);}
			if (ereg ('^6767[0-9].{11}$', $cc_no))		{return array('Type' => 'Solo / Maestro','Index' => 16, 'CanAccept' => FALSE);}

			if (ereg ('^564182[0-9].{9}$', $cc_no))		{return array('Type' => 'Switch / Maestro','Index' => 19, 'CanAccept' => FALSE);}
			if (ereg ('^6333[0-4].{11}$', $cc_no))		{return array('Type' => 'Switch / Maestro','Index' => 19, 'CanAccept' => FALSE);}
			if (ereg ('^6759[0-9].{11}$', $cc_no))		{return array('Type' => 'Switch / Maestro','Index' => 19, 'CanAccept' => FALSE);}

			if (ereg ('^49030[2-9].{10}$', $cc_no))		{return array('Type' => 'Switch','Index' => 18, 'CanAccept' => FALSE);}
			if (ereg ('^49033[5-9].{10}$', $cc_no))		{return array('Type' => 'Switch','Index' => 18, 'CanAccept' => FALSE);}
			if (ereg ('^49110[1-2].{10}$', $cc_no))		{return array('Type' => 'Switch','Index' => 18, 'CanAccept' => FALSE);}
			if (ereg ('^49117[4-9].{10}$', $cc_no))		{return array('Type' => 'Switch','Index' => 18, 'CanAccept' => FALSE);}
			if (ereg ('^49118[0-2].{10}$', $cc_no))		{return array('Type' => 'Switch','Index' => 18, 'CanAccept' => FALSE);}
			if (ereg ('^4936[0-9].{11}$', $cc_no)) 		{return array('Type' => 'Switch','Index' => 18, 'CanAccept' => FALSE);}

			if (ereg ('^6011.{12}$', $cc_no))			{return array('Type' => 'Discover Card','Index' => 23, 'CanAccept' => FALSE);}

			if (ereg ('^6[0-9].{14}$', $cc_no))			{return array('Type' => 'Maestro','Index' => 20, 'CanAccept' => FALSE);}
			if (ereg ('^5[0,6-8].{14}$', $cc_no))		{return array('Type' => 'Maestro','Index' => 20, 'CanAccept' => FALSE);}

			if (ereg ('^450875[0-9].{9}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^48440[6-8].{10}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^48441[1-9].{10}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^4844[2-4].{11}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^48445[0-5].{10}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^4917[3-5].{11}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^491880[0-9].{9}$', $cc_no))		{return array('Type' => 'UK Electron','Index' => 21, 'CanAccept' => FALSE);}

			if (ereg ('^41373[3-7].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^4462[0-9].{11}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^45397[8-9].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^454313[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^45443[2-5].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^454742[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^45672[5-9].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^45673[0-9].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^45674[0-5].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^4658[3-7].{11}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^4659[0-5].{11}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^484409[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^48441[0-9].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^4909[6-7].{11}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^49218[1-2].{10}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}
			if (ereg ('^498824[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa Delta','Index' => 13, 'CanAccept' => TRUE);}

			if (ereg ('^40550[1-4].{10}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^40555[0-4].{10}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^415928[0-4].{9}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^42460[4-5].{10}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^427533[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^4288[0-9].{11}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^443085[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^448[4-6].{12}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^471[5-6].{12}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}
			if (ereg ('^4804[0-9].{11}$', $cc_no))		{return array('Type' => 'Visa Purchasing','Index' => 12, 'CanAccept' => TRUE);}

			if (ereg ('^49030[0-1].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^4903[1-2].{11}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49033[0-4].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^4903[4-9].{11}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49040[0-9].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^490419[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^490451[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^490459[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^490467[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49047[5-8].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^4905[0-9].{11}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^491001[0-9].{9}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49110[3-9].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^4911[1-6].{11}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49117[0-3].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49118[3-9].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^49119[0-9].{10}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^4928[0-9].{11}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}
			if (ereg ('^4987[0-9].{11}$', $cc_no))		{return array('Type' => 'Visa ATM','Index' => 14, 'CanAccept' => FALSE);}


			if (ereg ('^4(.{12}|.{15})$', $cc_no))		{return array('Type' => 'Visa','Index' => 12, 'CanAccept' => TRUE);}

			if (ereg ('^3[4-7].{13}$', $cc_no))	 		{return array('Type' => 'American Express','Index' => 18, 'CanAccept' => FALSE);}

			if (ereg ('^3(0[0-5].{11}|[6].{12}|[8].{12})$', $cc_no))
														{return array('Type' => 'Diners Club/Carte Blanche','Index' => 19, 'CanAccept' => FALSE);}

			if (ereg ('^(3.{15}|(2131|1800).{11})$', $cc_no))
														{return array('Type' => 'JCB','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^(3528[0-9].{11})$', $cc_no))	{return array('Type' => 'JCB','Index' => 21, 'CanAccept' => FALSE);}
			if (ereg ('^(35[3-8].{13})$', $cc_no))		{return array('Type' => 'JCB','Index' => 21, 'CanAccept' => FALSE);}

			if (ereg ('^2(014|149).{11})$', $cc_no))	{return array('Type' => 'enRoute','Index' => 22, 'CanAccept' => FALSE);}
			return array('Type' => 'unknown or invalid','Index' => 0, 'CanAccept' => FALSE);
		}

		function validate($cc_no)
		{
			$cc_no=strrev($cc_no);
			$NoDigits=strlen($cc_no);
			$TestSum=0;
			for($Digit=0;$Digit<$NoDigits;$Digit=$Digit+2)
			{
				$TestSum=$TestSum+($cc_no[$Digit])+CreditCard::SingleDigit(($cc_no[$Digit+1]*2));
			}
			if(floor($TestSum/10)!=($TestSum/10))
				return FALSE;
			else
				return TRUE;
  		}

  		function SingleDigit($iDigit)
  		{
	  		if ($iDigit>=10) {$iDigit=$iDigit-9;}
				return $iDigit;
	  	}

  		function CheckDates($VFrom,$VTo)
  		{
			$ErrorCode['VFrom']=FALSE;
			$ErrorCode['VTo']=FALSE;
			if(isset($VFrom)==TRUE)
			{
				if(strlen($VFrom)==2)
	 			{
		 			if (ereg("^[[:digit:]]{2}$",$VFrom)!=TRUE)
						$ErrorCode['VFrom']=TRUE;
				}
				elseif(strlen($VFrom)==5)
				{
			 		if (ereg("^[[:digit:]/[:digit:]]${5}",$VFrom)!=TRUE)
						$ErrorCode['VFrom']=TRUE;
					else
					{
						$tVFr = explode("/",$VFrom);
		 				if($tVFr[0] <=0 or $tVFr[0]>=13)
			 				$ErrorCode['VFrom']=TRUE;
		 				if ($tVFr[1] > date(y))
			 				$ErrorCode['VFrom']=TRUE;
		 				elseif($tVFr[1] == date(y))
		 				{
			 				if ($tVFr[0]>date(m))
								$ErrorCode['VFrom']=TRUE;
		 				}
					}
	 			}
	 			elseif (strlen($VFrom)>0)
		 			$ErrorCode['VFrom']=TRUE;
			}
			if (isset($VTo)==TRUE)
			{
				if (strlen($VTo)==5)
	 			{
		 			if (ereg("^[[:digit:]/[:digit:]]${5}",$VTo)!=TRUE)
						$ErrorCode['VTo']=TRUE;
					else
					{
						$tVTo = explode("/",$VTo);
		 				if($tVTo[0] <=0 or $tVTo[0]>=13)
			 				$ErrorCode['VTo']=TRUE;
		 				if($tVTo[1] < date(y))
			 				$ErrorCode['VTo']=TRUE;
		 				elseif ($tVTo[1] == date(y))
		 				{
			 				if ($tVTo[0]<date(m))
								$ErrorCode['VTo']=TRUE;
		 				}
					}
	 			}
	 			else
		 			$ErrorCode['VTo']=TRUE;
			}
			return array('VTo'=> $ErrorCode['VTo'],'VFr'=> $ErrorCode['VFrom']);
  		}

		function check($cc_no,$VFr,$VTo)
		{
			$cc_no = CreditCard::clean_no($cc_no);
			$valid = CreditCard::validate ($cc_no);
			$CCData = CreditCard::IdentifyCard ($cc_no);
			$Dates = CreditCard::CheckDates($VFr,$VTo);
			return array ('valid' => $valid, 'canaccept' => $CCData['CanAccept'], 'index'=> $CCData['Index'], 'type'=>$CCData['Type'],'vto' => $Dates['VTo'], 'vfr'=>$Dates['VFr']);
		}
	}
?>
