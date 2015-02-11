<?
	/**
	 * e-Commerce System Data Feed/Export Plugin
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	class FroogleUK extends DataFeed
	{
		function publish()
		{		
			$data="description\texpiration_date\tid\timage_link\tlabel\tprice\ttitle\tbrand\tprice_type\tlink\n";
			
			/*description
			expiration_date
			id
			image_link
			label
			price
			title
			
			brand
			price_type = starting
			link*/
			
			while($row=$this->_products->FetchRow())
			{
				//Set vars
				$description=substr(str_replace("\n","",str_replace("\t","",str_replace("\r","",strip_tags($row['description'])))),0,65535);
				$expiration_date=date("Y-m-d",time()+(86400*7));
				$id=$row['id'];
				if(trim($row['imagetype'])!="")
					$image_link=$this->_config['protocol'].$this->_config['url'].$this->_config['dir']."images/product/".$row['id'].".".$row['imagetype'];
				else
					$image_link="";
				$label="electronics";
				$price=price($row['price']);
				$title=substr($row['name'],0,80);
				$brand=$row['brand_name'];
				$price_type="starting";
				$link=$this->_config['protocol'].$this->_config['url'].$this->_config['dir']."index.php/fuseaction/shop.product/category_id/".$row['category_id']."/product_id/".$row['id'];
				
				$data.=$description."\t".$expiration_date."\t".$id."\t".$image_link."\t".$label."\t".$price."\t".$title."\t".$brand."\t".$price_type."\t".$link."\r\n";
			}
			$file=$this->_config['tmpdir']."/".md5(uniqid($this->_config['company'],true));
			$fp=fopen($file,"w");
			fwrite($fp,$data);
			fclose($fp);

			$conn=ftp_connect($this->_config['froogle']['ftp_server']);

			if(@ftp_login($conn,$this->_config['froogle']['username'],$this->_config['froogle']['password']))
			{
				if(ftp_put($conn,$file,$this->_config['froogle']['remote_filename'],FTP_ASCII))
					$message=="Feed uploaded successfully";
				else
					$message="There was a problem while uploading to froogle, please try again.";
			}
			else
				$message="Failed to login to froogle server, please check username/password and try again";

			ftp_close($conn);
			unlink($file);
			return $message;
		}
	}
?>