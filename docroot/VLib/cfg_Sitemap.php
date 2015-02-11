<?
	$sitemap_file = 'sitemap.xml';
	
	$exclude = array();
	//$exclude[] = 'news/news-item';
	
	$manual = array();
/*	
	$manual['jobs/apply'] = array(
			'last_mod'=>'2008-06-21T19:37:25+00:00'
			,'changefreq'=>'hourly'
			,'priority'=>0.5
		);
		last_mod: W3C Datetime format. See http://www.w3.org/TR/NOTE-datetime
		changefreq: always, hourly, daily, weekly, monthly, yearly, never
		priority: valid values range from 0.0 to 1.0
*/		
	//$manual['news'] = array('changefreq'=>'daily', 'last_mod'=>gmdate('Y-m-d', $last_mod).'T'.gmdate('H:i:s', $last_mod).'+00:00');
	
	$manual[''] = array('priority'=>'1.0');
	//$manual['about'] = array('priority'=>'0.9');
	//$manual['our-expertise/qa-qc-and-validation'] = array('priority'=>'0.8');
?>
