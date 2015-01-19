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
	/* [DATABASE] */
	$config['db']['driver']="mysql";
	$config['db']['server']="localhost";
	$config['db']['username']="mayfair_house";
	$config['db']['password']="~#;(8Pw0Q$9T";
	$config['db']['database']="mayfair_may";

	/*$config['db']['driver']="mysql";
	$config['db']['server']="localhost";
	$config['db']['username']="marian";
	$config['db']['password']="pa55word";
	$config['db']['database']="marian_mayfair";*/

	/* [META] */
	$config['meta']['title']="Mayfair House";
	$config['meta']['keywords']="META Keyworks";
	$config['meta']['description']="META Description";

	/* [COMPANY] */
	$config['company']="Mayfair House";
	$config['slogan']="It Isn't Our Job, It's Our Life";
	$config['companyshort']="MAYFAIR";

	/* [LOCATION] */
	$config['defaultcountry']="United Kingdom";
	$config['defaultcountry_id']=1;
	$config['defaultarea']="British Isles";

	/* [INVOICE] */
	$config['invoice']['companyname']='Mayfair House';
	$config['invoice']['address1']="21 New Globe Walk";
	$config['invoice']['address2']="London SE1 9DT";
	$config['invoice']['address3']="";
	$config['invoice']['address4']="";
	$config['invoice']['tel']="+44 (0) 0207 902 1588";
	$config['invoice']['fax']="+44 (0) 0207 902 1515";
	$config['invoice']['email']="info@mayfair.com";
	$config['invoice']['footer_left']="Mayfair House, Registered Company Number: XXXXXXXX";
	$config['invoice']['footer_right']="Mayfair House � ".date('Y');

	/* [NAVIMG] */
	$config['navimg']['font']="vera_sans";
	$config['navimg']['size']=10;
	$config['navimg']['padding']=10;
	$config['navimg']['height']=20;
	$config['navimg']['y']=15;
	$config['navimg']['color']['bg']['r']=55;
	$config['navimg']['color']['bg']['g']=95;
	$config['navimg']['color']['bg']['b']=134;
	$config['navimg']['color']['fg']['r']=255;
	$config['navimg']['color']['fg']['g']=255;
	$config['navimg']['color']['fg']['b']=255;

	/* [PATHS] */
	$config['prog']['mysqldump']="/usr/bin/mysqldump";
	$config['prog']['mogrify']="/usr/bin/convert";
	$config['prog']['diff']="/usr/bin/diff";
	$config['prog']['aspell']="/usr/bin/aspell";
	$config['tmpdir']="/tmp/";

	/* [PSP] */
	$config['psp']['driver']="Authorize";//"SecureHosting";

	/* [IMAGES] */
	$config['admin']['resize']="ImageMagick";

	$config['size']['brand']['thumb']['x']=88;
	$config['size']['brand']['thumb']['y']=88;
	$config['size']['brand']['medium']['x']=150;
	$config['size']['brand']['medium']['y']=150;
	$config['size']['brand']['image']['x']=281;
	$config['size']['brand']['image']['y']=281;

	$config['size']['product']['small']['x']=80;
	$config['size']['product']['small']['y']=80;
	$config['size']['product']['small']['description']='Thumbnail on product page';
	$config['size']['product']['small']['type']='all-products';
	/*$config['size']['product']['thumb']['x']=90;
	$config['size']['product']['thumb']['y']=90;
	$config['size']['product']['thumb']['description']='Search listing';
	$config['size']['product']['thumb']['type']='all-products';*/
	$config['size']['product']['medium']['x']=124;
	$config['size']['product']['medium']['y']=124;
	$config['size']['product']['medium']['description']='Related products thumbnail';
	$config['size']['product']['medium']['type']='all-products';
	$config['size']['product']['image']['x']=282;
	$config['size']['product']['image']['y']=282;
	$config['size']['product']['image']['description']='Listing image';
	$config['size']['product']['image']['type']='all-products';
	/*$config['size']['product']['large_thumb']['x']=80;
	$config['size']['product']['large_thumb']['y']=112;
	$config['size']['product']['large_thumb']['description']='Portrait product thumbnail';
	$config['size']['product']['large_thumb']['type']='portrait';*/
	$config['size']['product']['view']['x']=550;
	$config['size']['product']['view']['y']=0;
	$config['size']['product']['view']['min_y']=258;
	$config['size']['product']['view']['description']='Landscape main image';
	$config['size']['product']['view']['type']='all-products';
	$config['size']['product']['zoom']['x']=900;
	$config['size']['product']['zoom']['y']=0;
	$config['size']['product']['zoom']['min_y']=387;
	$config['size']['product']['zoom']['description']='Main image';
	$config['size']['product']['zoom']['type']='all-products';
	/*$config['size']['product']['large']['x']=440;
	$config['size']['product']['large']['y']=254;
	//$config['size']['product']['large']['min_y']=380;
	$config['size']['product']['large']['description']='Preview image';
	$config['size']['product']['large']['type']='all-products';
	$config['size']['product']['large_zoom']['x']=840;
	$config['size']['product']['large_zoom']['y']=1180;
	//$config['size']['product']['large_zoom']['min_y']=760;
	$config['size']['product']['large_zoom']['description']='Portrait main image';
	$config['size']['product']['large_zoom']['type']='portrait';
	$config['size']['product']['listing_horizontal']['x']=215;
	$config['size']['product']['listing_horizontal']['y']=137;
	$config['size']['product']['listing_horizontal']['description']='Landscape product listing';
	$config['size']['product']['listing_horizontal']['type']='landscape';
	$config['size']['product']['listing_vertical']['x']=215;
	$config['size']['product']['listing_vertical']['y']=274;
	$config['size']['product']['listing_vertical']['description']='Portrait product listing';
	$config['size']['product']['listing_vertical']['type']='portrait';*/
	$config['size']['product']['original']['x']=0;
	$config['size']['product']['original']['y']=0;
	$config['size']['product']['original']['description']='';

	$config['size']['category']['thumb']['x']=88;
	$config['size']['category']['thumb']['y']=88;
	$config['size']['category']['medium']['x']=150;
	$config['size']['category']['medium']['y']=150;
	$config['size']['category']['image']['x']=373;
	$config['size']['category']['image']['y']=0;
	
	$config['size']['page']['thumb']['x']=100;
	$config['size']['page']['thumb']['y']=0;
	$config['size']['page']['image']['x']=215;
	$config['size']['page']['image']['y']=0;
	$config['size']['page']['view']['x']=984;
	$config['size']['page']['view']['y']=545;
	$config['size']['page']['original']['x']=0;
	$config['size']['page']['original']['y']=0;

	/* [MAIL] */
	$config['mail']['fromaddress']="info@mayfairhouse.com";
	$config['mail']['fromname']="Mayfair House";
	$config['mail']['method']="smtp";
	$config['mail']['token']="6c42c86c-a882-49a3-8646-0f1b4b98008f";
	$config['mail']['server']="smtp.postmarkapp.com";
	$config['mail']['auth']=true;
	$config['mail']['notify']="info@mayfairhouse.com";

	/* [SESSIONS] */
	$config['p3p']="CP=\"NOI NID ADMa OUR IND UNI COM NAV\"";

	$config['shop']['timeout']=0;
	$config['shop']['session_id']="session_id";

	$config['user']['timeout']=3600;
	$config['user']['session_id']="user_session_id";
	$config['user']['account_id']="user_account_id";

	$config['admin']['timeout']=3600;
	$config['admin']['session_id']="admin_session_id";
	$config['admin']['account_id']="admin_account_id";

	$config['telesale']['sidtimeout']=3600;

	/* [SITE] */
	$config['siteid']=1;
	if(isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == 'on')
		$config['protocol']="https://";
	else
		$config['protocol']="http://";
	$config['url']="mayfairhouse.com";
	$config['dir']=$config['protocol'].$config['url']."/";
	$config['path']="/home/mayfair/public_html/";
	

	/* [WYSIWYG] */
	$config['admin']['editor']="tinymce";

	/* [CUSTOMVARS] */
	//$config['admin']['product']['customvars'][0]="";

	/* [APPEARANCE] */
	$config['layout']="mayfair";
	$config['template']="default";
	$config['display']['children']=4;
	$config['display']['products']=10;
	$config['display']['refs']=4;
	
    if(isset($_REQUEST['lay']))
        $config['layout']=$_REQUEST['lay'];
	
	$config['layout_dir']=$config['dir'].'layout/templates/'.$config['layout'].'/';
	$config['layout_path']=$config['path'].'layout/templates/'.$config['layout'].'/';
	
	
	$config['main_site']='http://mayfairhouse.com';
	
	$config['development'] = false;
	
	/* [SPEEDTAX] */
	$config['speedtax']['address1'] = '8844 Beverly Blvd';
	$config['speedtax']['address2'] = 'Los Angeles, CA 90048';
	
	/* [FULL CIRCLE] */
	$config['full_circle']['company_number'] = '20';
	
	/* [UPS] */
	$config['ups']['access_key'] = '3C9B45F3E8717303';
	$config['ups']['username'] = 'blochretail';
	$config['ups']['password'] = 'F8MZqdRXz';
	$config['ups']['address']['name'] = 'Bloch';
	$config['ups']['address']['shipper_number'] = '';
	$config['ups']['address']['line1'] = '1170 Trademark Drive, Suite 112';
	$config['ups']['address']['city'] = 'Reno';
	$config['ups']['address']['state'] = 'NV';
	$config['ups']['address']['postcode'] = '89521';
	$config['ups']['test_mode'] = true;

	/* [MAILCHIMP]
	$config['mailchimp']['datacenter']="us5";
	$config['mailchimp']['api_key']="b1071c4608989f065f6af160e5745d10-us5";
	$config['mailchimp']['list_id']="17d6e4bf84";*/
    	$config['mailchimp']['url'] = 'http://us5.api.mailchimp.com/1.3/';
	$config['mailchimp']['apikey'] = 'b1071c4608989f065f6af160e5745d10-us5';
	$config['mailchimp']['list'] = 'be484a0ffc';

    $config['catalogues'] = array(
        '2012 Dance shoes catalogue' => 'PR1417_2012DANCESHOE_website2.pdf',
        '2012 Bloch Basics apparel catalogue' => 'PR1413_BLOCH Basics_USA_2012_HR nocrop.pdf',
        'Bloch Spring / Summer 2013 catalogue' => 'PR1459_Bloch SS13_v4_REVISED_LR.pdf',
        'Bloch Holiday 2012 catalogue' => 'PR1442_BLOCH_HOL_12_HR nocrop.pdf',
        'Bloch Fall / Winter 2012 catalogue' => 'PR1419_Bloch FW12_HR_nocrop.pdf',
    );

	set_include_path($config['path']."lib");
?>
