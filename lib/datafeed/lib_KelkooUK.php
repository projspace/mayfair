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
	class KelkooUK extends DataFeed
	{
		function publish()
		{
			ob_start();
			echo "<?xml version=\"1.0\"?>\n"
				."<Products xmlns=\"http://www.kelkoo.co.uk\"\n"
				."xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n"
				."xsi:schemaLocation=\"http://www.kelkoo.co.uk kelkoo.xsd\">\n";
			while($row=$this->_products->FetchRow())
			{
				$custom=unserialize($row['custom']);
				if($custom["kelkoo_category"]!="0" && $custom["kelkoo_category"]!="")
				{
					$desc=$row["description"];
					//Cleanup Description
					$desc=str_replace(">","> ",$desc);
					$desc=str_replace("**","",$desc);
					$desc=str_replace("!!","",$desc);
					$desc=str_replace("..","",$desc);
					$desc=strip_tags($desc);
					$desc=html_entity_decode($desc,ENT_COMPAT);//F-8");
					$desc=htmlentities($desc,ENT_COMPAT,"UTF-8");
					$desc=str_replace("&pound;","GBP ",$desc);
					$desc=str_replace("&nbsp;"," ",$desc);
					$desc=str_replace("&deg;"," degrees ",$desc);
					$desc=str_replace("&copy;"," copyright ",$desc);
					$desc=trim($desc);
					while(strstr($desc,"  "))
						$desc=str_replace("  "," ",$desc);
					$desc=utf8_encode($desc);
					echo "\t<Product>\n";
					echo "\t\t<Category>".str_replace("[AND]","&amp;",$custom["kelkoo_category"])."</Category>\n"
						."\t\t<Type>{$custom["kelkoo_type"]}</Type>\n"
						."\t\t<FieldC>{$custom["kelkoo_field_c"]}</FieldC>\n"
						."\t\t<FieldD>{$custom["kelkoo_field_d"]}</FieldD>\n"
						."\t\t<FieldE>{$custom["kelkoo_field_e"]}</FieldE>\n"
						."\t\t<FieldF>{$custom["kelkoo_field_f"]}</FieldF>\n"
						."\t\t<FieldG>{$custom["kelkoo_field_g"]}</FieldG>\n"
						."\t\t<FieldH>{$custom["kelkoo_field_h"]}</FieldH>\n"
						."\t\t<FieldI>{$custom["kelkoo_field_i"]}</FieldI>\n"
						."\t\t<FieldJ>{$custom["kelkoo_field_j"]}</FieldJ>\n"
						."\t\t<FieldK>{$custom["kelkoo_field_k"]}</FieldK>\n"
						."\t\t<UniqueCode>{$row["id"]}</UniqueCode>\n"
						."\t\t<Description>".$desc."</Description>\n"
						."\t\t<Promotion></Promotion>\n"
						."\t\t<Image>http://www.carncomarketing.co.uk/{$row["imageurl"]}</Image>\n"
						."\t\t<LinkToProduct>http://www.carncomarketing.co.uk/index.php/fuseaction/shop.product/category_id/{$row["category_id"]}/product_id/{$row["id"]}</LinkToProduct>\n"
						."\t\t<Price>{$row["price"]}</Price>\n"
						."\t\t<DeliveryCost>{$custom["kelkoo_delivery_cost"]}</DeliveryCost>\n"
						."\t\t<DeliveryTime>{$custom["kelkoo_delivery_time"]}</DeliveryTime>\n"
						."\t\t<Availability>{$custom["kelkoo_availability"]}</Availability>\n"
						."\t\t<Warranty>{$custom["kelkoo_warranty"]}</Warranty>\n"
						."\t\t<Condition>{$custom["kelkoo_condition"]}</Condition>\n"
						."\t\t<OfferType>{$custom["kelkoo_offer_type"]}</OfferType>\n"
						."\t\t<Bid></Bid>\n";
					echo "\t</Product>\n";
				}
			}
			echo "</Products>\n";
			$feed=ob_get_contents();
			ob_end_clean();
			$fp=fopen($this->_config['path']."kelkoo.xml","w");
			fwrite($fp,$feed);
			fclose($fp);
			return "Feed Created Successfully as ".$this->_config['protocol'].$this->_config['url'].$this->_config['dir']."kelkoo.xml";
		}
	}
?>