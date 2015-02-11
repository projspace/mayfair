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
	class DataFeed
	{
		var $_db;
		var $_config;
		var $_products;

		function DataFeed(&$db,&$config)
		{
			$this->_db =& $db;
			$this->_config =& $config;
		}

		function retrieve()
		{
			$this->_products=$this->_db->Execute("
				SELECT
					shop_products.*
					,shop_categories.id AS category_id
					,shop_categories.content AS category_content
					,shop_categories.name AS category_name
					,shop_categories.trail AS category_trail
					,shop_categories.meta_title AS category_meta_title
					,shop_categories.meta_description AS category_meta_description
					,shop_categories.meta_keywords AS category_meta_keywords
					,shop_brands.id AS brand_id
					,shop_brands.name AS brand_name
					,shop_brands.url AS brand_url
					,shop_brands.content AS brand_content
				FROM
					shop_products
					,shop_categories
					,shop_brands
				WHERE
					shop_categories.id=shop_products.category_id
				AND
					shop_brands.id=shop_products.brand_id
				AND
					shop_products.id>1
				ORDER BY
					shop_categories.id
			");
		}

		function publish()
		{
			return;
		}
	}
?>