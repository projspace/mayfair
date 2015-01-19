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
    require "lib_Placeholder.php";

	class CustomElements extends Elements
	{
        var $_placeholders = array();
        
		function qry_Page404()
		{
			$page404 = array();
	
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						cms_variables
					WHERE
						name = '404_content'
				"
				)
			);
			$page404['content'] = $result->FetchRow();
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						cms_variables
					WHERE
						name = '404_title'
				"
				)
			);
			$page404['title'] = $result->FetchRow();
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						cms_variables
					WHERE
						name = '404_keywords'
				"
				)
			);
			$page404['keywords'] = $result->FetchRow();
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						cms_variables
					WHERE
						name = '404_description'
				"
				)
			);
			$page404['description'] = $result->FetchRow();
			
			return $page404;
		}
		
		function qry_ContentArea($area_id)
		{
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						cms_content_areas
					WHERE
						id = %u
				"
					,$area_id
				)
			);
			
			return $result->FetchRow();
		}
		
		function qry_Categories($parent_id = 1)
		{
			$parent=$this->db->Execute(
				sprintf("
					SELECT
						id
						,childord
					FROM
						shop_categories
					WHERE
						id=%u
				"
					,$parent_id
				)
			);
			$parent = $parent->FetchRow();
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						id
						,name
						,main_category
						,hidden_new_products
						,hidden_clearance
						,link_category_id
						,box_imagetype
					FROM
						shop_categories
					WHERE
						parent_id = %u
                    AND
                        hidden = 0
					ORDER BY
						%s ASC
				"
					,$parent_id
					,$parent['childord']?'ord':'name'
				)
			);
			return $result->GetRows();
		}
		
		function qry_Menu()
		{
			$menu = $this->qry_Categories(1);
			foreach($menu as $key=>$row)
			{
                if($row)
				$menu[$key]['children'] = $this->qry_Categories($row['id']);
				foreach($menu[$key]['children'] as $key2=>$row2)
					if($row2['main_category'])
						$menu[$key]['children'][$key2]['children'] = $this->qry_Categories($row2['id']);
			}
			
			$menu = array('categories'=>$menu, 'pages'=>array());
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						id
						,name
						,url
					FROM
						cms_pages
					WHERE
						menu = 1
					AND
						hidden = 0
					AND
						deleted=0
					ORDER BY
						lft ASC
				"
				)
			);
			while($row = $result->FetchRow())
				$menu['pages'][] = $row;
			
			return $menu;
		}
		
		function qry_MegaFooter()
		{
			$result=$this->db->Execute(
				sprintf("
					SELECT
						id
						,name
						,hidden_new_products
						,hidden_clearance
						,link_category_id
					FROM
						shop_categories
					WHERE
						main_category = 1
					ORDER BY
						lft ASC
				"
				)
			);
			$footer = array();
			$footer['categories'] = array();
			while($row = $result->FetchRow())
			{
				$row['children'] = $this->qry_Categories($row['id']);
				$footer['categories'][$row['id']] = $row;
			}
			
			$result=$this->db->Execute(
				sprintf("
					SELECT
						id
						,name
						,url
						,lft
						,rgt
					FROM
						cms_pages
					WHERE
						megafooter = 1
					AND
						hidden = 0
					AND
						deleted=0
					ORDER BY
						lft ASC
				"
				)
			);
			$footer['pages'] = array();
			while($row = $result->FetchRow())
			{
				$row['children'] = array();
				$has_parent = false;
				foreach($footer['pages'] as $key=>$unused)
				{
					$key = explode(',', $key);
					$lft = $key[0]+0;
					$rgt = $key[1]+0;
					if($lft < $row['lft']+0 && $row['rgt']+0 < $rgt)
					{
						$has_parent = true;
						break;
					}
				}
				if($has_parent)
					$footer['pages'][$lft.','.$rgt]['children'][] = $row;
				else
					$footer['pages'][$row['lft'].','.$row['rgt']] = $row;
			}
			
			return $footer;
		}
		
		function qry_Footer()
		{
			$result=$this->db->Execute(
				sprintf("
					SELECT
						id
						,name
						,url
					FROM
						cms_pages
					WHERE
						footer = 1
					AND
						hidden = 0
					AND
						deleted=0
					ORDER BY
						lft ASC
				"
				)
			);
			return $result->GetRows();
		}
		
		function qry_Cart()
		{
			global $session;
			
			$cart=$this->db->Execute(
				$sql = sprintf("
					SELECT DISTINCT
						shop_session_cart.id AS cart_id
						,shop_session_cart.price AS cart_price
						,shop_session_cart.discount AS cart_discount
						,shop_session_cart.quantity AS cart_quantity
						,shop_products.id
						,shop_products.name
						,shop_products.code
						,shop_products.guid
						,shop_products.price
						,shop_products.discount
						,shop_products.weight
						,shop_products.description
						,shop_products.soldout
						,shop_products.options
						,shop_products.shipping
						,shop_products.no_shipping
						,shop_products.pick_up_only
						,shop_products.packing
						,shop_products.custom
						,shop_products.gift
						,shop_products.imagetype
						,shop_products.vat
						,shop_session_cart.parent_id
						,shop_sizes.name size
						,shop_widths.name width
						,shop_colors.name color
						,IFNULL(spi.id, shop_product_images.id) image_id
						,IFNULL(spi.imagetype, shop_product_images.imagetype) image_type
					FROM
					(
						shop_products
						,shop_session_cart
						,shop_categories
						,shop_product_options
					)
					LEFT JOIN
						shop_sizes
					ON
						shop_sizes.id = shop_product_options.size_id
					LEFT JOIN
						shop_widths
					ON
						shop_widths.id = shop_product_options.width_id
					LEFT JOIN
						shop_colors
					ON
						shop_colors.id = shop_product_options.color_id
					LEFT JOIN
						shop_product_images
					ON
						shop_product_images.product_id = shop_products.id
					LEFT JOIN
						shop_product_images spi
					ON
						spi.product_id = shop_products.id
					AND
						spi.color_id = shop_product_options.color_id
					WHERE
						shop_products.id=shop_session_cart.product_id
					AND
						shop_categories.id=shop_products.category_id
					AND
						shop_session_cart.option_id = shop_product_options.id
					AND
						shop_session_cart.product_id = shop_product_options.product_id
					AND
						shop_session_cart.session_id=%s
					GROUP BY
						shop_products.id
						,shop_session_cart.option_id
						,shop_session_cart.parent_id
					ORDER BY
						shop_session_cart.time ASC
				"
					,$this->db->Quote($session->session_id)
				)
			);
			return $cart->GetRows();
		}

        function qry_CartSummary()
		{
			global $session;

			$cart=$this->db->Execute(
				$sql = sprintf("
					SELECT
					    SUM(shop_session_cart.quantity) items
					    ,SUM(shop_session_cart.quantity * shop_session_cart.price) total
					FROM
					(
						shop_products
						,shop_session_cart
						,shop_categories
						,shop_product_options
					)
					WHERE
						shop_products.id=shop_session_cart.product_id
					AND
						shop_categories.id=shop_products.category_id
					AND
						shop_session_cart.option_id = shop_product_options.id
					AND
						shop_session_cart.product_id = shop_product_options.product_id
					AND
						shop_session_cart.session_id=%s
				"
					,$this->db->Quote($session->session_id)
				)
			);
			return $cart->FetchRow();
		}
		
		function qry_Page($page_id)
		{
			$page=$this->db->Execute(
				$sql = sprintf("
					SELECT
						cms_pages.*
						,cms_content.content
						,cms_content.description
						,cms_content.meta_title
						,cms_content.meta_keywords
						,cms_content.meta_description
					FROM
						cms_pages
					LEFT JOIN
						cms_content
					ON
						cms_content.pageid = cms_pages.id
					AND
						cms_content.revision = cms_pages.revision
					WHERE
						cms_pages.id = %u
				"
					,$page_id
				)
			);
			return $page->FetchRow();
		}
		
		function qry_ShopVariable($var)
		{
			$result=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_variables
					WHERE
						name = %s
				"
					,$this->db->Quote($var)
				)
			);
			$result = $result->FetchRow();
			switch($var)
			{
				case 'fb_code':
					$request_uri = $_SERVER['REQUEST_URI'];
					if(strpos($request_uri, 'session_id=') === false)
					{
						if(strpos($request_uri, '?') === false)
							$request_uri .= '?session_id='.$session->session_id;
						else
							$request_uri .= '&session_id='.$session->session_id;
					}
					return str_replace('[like_url]', urlencode($this->config['protocol'].$_SERVER['HTTP_HOST'].$request_uri), $result['value']);
					break;
				default:
					return $result['value'];
					break;
			}
		}
		
		function qryPendingGiftListCount()
		{
			global $user_session;
			$result=$this->db->Execute(
				sprintf("
					SELECT
						COUNT(id) count
					FROM
						gift_lists
					WHERE
						account_id = %u
					AND
						status = 'pending'
				"
					,$user_session->account_id
				)
			);
			$result = $result->FetchRow();
			return $result['count']+0;
		}

        function placeholder($key)
        {
            if(!isset($this->_placeholders[$key]))
                $this->_placeholders[$key] = new Placeholder();

            return $this->_placeholders[$key];
        }

        function qry_SubcategoryIDs($parent_id = 1)
		{
            $parent_ids = array($parent_id);
            $return = array($parent_id);
            while(count($parent_ids))
            {
                $result=$this->db->Execute(
                    sprintf("
                        SELECT
                            id
                        FROM
                            shop_categories
                        WHERE
                            parent_id IN (%s)
                    "
                        ,implode(',', $parent_ids)
                    )
                );
                $parent_ids = array();
                while($row = $result->FetchRow())
                {
                    $return[] = $row['id'];
                    $parent_ids[] = $row['id'];
                }
            }
            return $return;
		}

        function qry_TopCategory($category_id)
        {
            $result=$this->db->Execute(
                sprintf("
                    SELECT
                        parent.*
                    FROM
                        shop_categories parent
                    JOIN
                        shop_categories child
                    ON
                        parent.lft < child.lft
                    AND
                        child.rgt < parent.rgt
                    WHERE
                        parent.parent_id = 1
                    AND
                        child.id = %u
                "
                    ,$category_id
                )
            );
            return $result->FetchRow();
        }
	}
?>
