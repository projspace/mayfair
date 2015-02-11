<?
	die('STOP');
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	
	if (!$handle = fopen($config['path'].'repair_products.sql', 'r')) 
		die('Cannot open file');
		
	$count = 0;
	while(($data = fgetcsv($handle, 0, ',', "'")) !== false)
	{
		$db->Execute(
			$sql=sprintf("
				UPDATE
					shop_products
				SET
					category_id=%u
					,brand_id=%u
					,parent_id=%u
					,hidden=%u
					,name=%s
					,code=%s
					,meta_title=%s
					,meta_description=%s
					,meta_keywords=%s
					,price=%f
					,price_old=%f
					,start_from=%f
					,discount=%f
					,weight=%f
					,packing=%f
					,shipping=%f
					,vat=%u
					,gift=%u
					,description=%s
					,imagetype=%s
					,soldout=%u
					,stock=%u
					,`trigger`=%u
					,low_stock_trigger=%u
					,hide_stock_trigger=%u
					,options=%s
					,optionslayout=%u
					,specs=%s
					,filename=%s
					,downloads=%u
					,custom=%s
					,buy_1_get_1_free=%u
					,recent_productions=%u
					,home_slider=%u
					,slider_title = %s
					,slider_description = %s
					,slider_image_type = %s
					,360_view=%u
					,product_search=%u
					,audio_type=%s
					,video_type=%s
					,`inserted`=%s
					,`updated`=%s
					,ord=%u
				WHERE
					id=%u
			"
				,$data[1]
				,$data[2]
				,$data[3]
				,$data[4]
				,"'".$data[5]."'"
				,"'".$data[6]."'"
				,"'".$data[7]."'"
				,"'".$data[8]."'"
				,"'".$data[9]."'"
				,$data[10]
				,$data[11]
				,$data[12]
				,$data[13]
				,$data[14]
				,$data[15]
				,$data[16]
				,$data[17]
				,$data[18]
				,"'".$data[19]."'"
				,"'".$data[20]."'"
				,$data[21]
				,$data[22]
				,$data[23]
				,$data[24]
				,$data[25]
				,"'".$data[26]."'"
				,$data[27]
				,"'".$data[28]."'"
				,"'".$data[29]."'"
				,$data[30]
				,"'".$data[31]."'"
				,$data[32]
				,$data[33]
				,$data[34]
				,"'".$data[35]."'"
				,"'".$data[36]."'"
				,"'".$data[37]."'"
				,$data[38]
				,$data[39]
				,"'".$data[40]."'"
				,"'".$data[41]."'"
				,"'".$data[42]."'"
				,"'".$data[43]."'"
				,$data[44]
				,$data[0]
			)
		);
		if($db->ErrorNo())
			var_dump($sql, $db->ErrorMsg());
	}
	echo 'DONE';
	fclose($handle);
?>