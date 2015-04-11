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
	class Password
	{
		function generate($lenPassword)
		{
			$vowel="aeiou";
			$consonant="bcdfghjklmnprstv";
			$doubleConsonant="cdfglmnprst";
			$generatePassword="";
			$writeConsonant=false;
			for($i=0;$i<$lenPassword;$i++)
			{
				$nbRnd=rand(0,10);
		    		if($generatePassword<>"" && $writeConsonant==false && $nbRnd<1)
				{
					$tmp=substr($doubleConsonant,rand(0,strlen($doubleConsonant))-1,1);
					$tmp=$tmp.$tmp;
					$writeConsonant=true;
				}
				else
				{
					if($writeConsonant==false && $nbRnd<9)
					{
						$tmp=substr($consonant,rand(0,strlen($consonant))-1,1);
						$writeConsonant=true;
					}
					else
					{
						$tmp=substr($vowel,rand(0,strlen($vowel))-1,1);
						$writeConsonant=false;
					}
				}
				$generatePassword=$generatePassword.$tmp;
				if($i==($lenpassword-1) && strlen($generatePassword)<$lenPassword)
					$i=0;
			}
			if(strlen($generatePassword)>$lenPassword)
				$generatePassword=substr($generatePassword,0,$lenPassword);
			return $generatePassword;
		}
	}
?>
