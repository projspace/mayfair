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
	function getRates()
	{
		$ch=curl_init("http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml");
		ob_start();
		curl_exec($ch);
		$rates=ob_get_contents();
		ob_end_clean();
		curl_close($ch);
		$header="<?\n\t/* Country code to name mapping: Euro is base rate
		EUR Euro
		USD US dollar
		JPY Japanese yen
		DKK Danish krone
		GBP Pound sterling
		SEK Swedish krona
		CHF Swiss franc
		ISK Icelandic krona
		NOK Norwegian krone
		BGN Bulgarian lev
		CYP Cyprus pound
		CZK Czech koruna
		EEK Estonian kroon
		HUF Hungarian forint
		LTL Lithuanian litas
		LVL Latvian lats
		MTL Maltese lira
		PLN Polish zloty
		ROL Romanian leu
		SIT Slovenian tolar
		SKK Slovakian koruna
		TRL Turkish lira
		AUD Australian dollar
		CAD Canadian dollar
		HKD Hong Kong dollar
		NZD New Zealand dollar
		SGD Singapore dollar
		KRW South Korean won
		ZAR South African rand*/\n\t\$config[\"rates\"][\"EUR\"]=1;\n";
		$fp=fopen("lib/rates/cfg_Rates.php","w");
		fwrite($fp,$header);
		$rates=explode("\n",$rates);
		$count=count($rates);
		for($i=0;$i<$count;$i++)
		{
			if(ereg("<Cube currency='([A-Z]{3,})' rate='([0-9\.]*)'/>",$rates[$i],$regs))
			{
				fwrite($fp,"\t\$config[\"rates\"][\"".$regs[1]."\"]=".$regs[2].";\n");
			}
		}
		fwrite($fp,"?>");
		fclose($fp);
	}

	getRates();
?>