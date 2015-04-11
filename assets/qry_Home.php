<?
	$result=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				cms_variables
			WHERE
				name = 'home_banner_type'
		"
		)
	);
	$banner_type = $result->FetchRow();
	$banner_type = $banner_type['value'];
	
	if($banner_type == 'multiple')
	{
		$home_banners=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					cms_home_banners
				ORDER BY
					ord
				LIMIT 4
			"
			)
		);
	}
	else
	{
		$home_banner = array();
		
		$result=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					cms_variables
				WHERE
					name = 'home_banner_image_type'
			"
			)
		);
		$home_banner['image_type'] = $result->FetchRow();
		$home_banner['image_type'] = $home_banner['image_type']['value'];
		
		$result=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					cms_variables
				WHERE
					name = 'home_banner_url'
			"
			)
		);
		$home_banner['url'] = $result->FetchRow();
		$home_banner['url'] = $home_banner['url']['value'];
	}
	
	$result=$db->Execute(
		sprintf("
				SELECT
					*
				FROM
					cms_pages_images
				WHERE
					pageid = 1
				ORDER BY
					ord ASC
			"
		)
	);
    $slides = array();
    while($row = $result->FetchRow())
    {
        $row['metadata'] = unserialize($row['metadata']);
        $slides[] = $row;
    }

    $featured=$db->Execute(
		sprintf("
				SELECT DISTINCT
                    shop_products.*
                    ,shop_product_images.id image_id
                    ,shop_product_images.imagetype image_type
                FROM
                    shop_products
                LEFT JOIN
                    shop_product_images
                ON
                    shop_product_images.product_id = shop_products.id
                LEFT JOIN
                    shop_product_options
                ON
                    shop_product_options.product_id = shop_products.id
                WHERE
                    shop_products.id > 1
                AND
                    shop_products.hidden = 0
                AND
                    shop_products.featured > 0
                GROUP BY
                    shop_products.id
                ORDER BY
                    shop_products.featured ASC
                LIMIT
                    4
			"
		)
	);
?>
