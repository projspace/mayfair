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
	//Category information
	$details=$db->Execute(
		sprintf("
			SELECT
				id
				,name
				,imagetype
				,imageon
				,content
				,trail
				,childord
				,productord
				,vars
				,custom_search
			FROM
				shop_categories
			WHERE
				id=%u
		"
			,$category_id
		)
	);
	$details->fields['vars']=explode("\n",$details->fields['vars']);
	unset($search_params);
	if($details->fields['custom_search']==1)
	{
		foreach($details->fields['vars'] as $var)
		{
			$temp=$db->Execute(
				sprintf("
					SELECT DISTINCT
						shop_product_vars.value
					FROM
						shop_product_vars
						,shop_products
					WHERE
						shop_product_vars.product_id=shop_products.id
					AND
						shop_products.category_id=%u
					AND
						shop_products.hidden=0
					AND
						shop_product_vars.name=%s
					ORDER BY
						shop_product_vars.value ASC
				"
					,$category_id
					,$db->Quote($var)
				)
			);
			$search_params[$var]=$temp->GetRows();			
		}
		$temp=$db->Execute(
			sprintf("
				SELECT DISTINCT
					shop_brands.name AS value
				FROM
					shop_brands
					,shop_products
				WHERE
					shop_brands.id=shop_products.brand_id
				AND
					shop_products.category_id=%u
				AND
					shop_products.hidden=0
				ORDER BY
					shop_brands.name ASC
			"
				,$category_id
			)
		);
		$search_params['brands']=$temp->GetRows();
	}

	//Child Categories
	$children=$db->Execute(
		sprintf("
			SELECT
				id
				,name
				,imagetype
				,imageon
				,content
			FROM
				shop_categories
			WHERE
				parent_id=%u
			AND
				id NOT IN (
				SELECT
					category_id AS id
				FROM
					shop_category_restrictions
				WHERE
					area_id=%u
			)
			ORDER BY
				%s
			ASC
		"
			,$category_id
			,$session->session->fields['area_id']
			,($details->fields['childord']==1) ? "ord" : "name"
		)
	);

	//Product References
	$refs=$db->Execute(
		sprintf("
			SELECT
				shop_products.id
				,shop_products.name
				,shop_products.price
				,shop_products.description
				,shop_products.imagetype
			FROM
				shop_refs
				,shop_products
			WHERE
				shop_refs.category_id=%u
			AND
				shop_products.id=shop_refs.product_id
			AND
				shop_products.hidden=0
			ORDER BY
				shop_products.name ASC
		"
			,$category_id
		)
	);

	//Pagination
	if($config['display']['products']>0)
	{
		if(!isset($start))
			$start=0;

		if($start<0)
			$start=0;

		$num=$db->Execute(
			sprintf("
				SELECT
					COUNT(id) AS num
				FROM
					shop_products
				WHERE
					category_id=%u
				AND
					hidden=0
			"
				,$category_id
			)
		);

		if($start>$num->fields['num'])
			$start=$num->fields['num']-$config['display']['products'];

		if($start<0)
			$start=0;
	}

	//Products
	$query="SELECT
				shop_brands.id AS brand_id
				,shop_brands.name AS brand_name
				,shop_brands.imagetype AS brand_imagetype
				,shop_products.id
				,shop_products.parent_id
				,shop_products.name
				,shop_products.price
				,shop_products.discount
				,shop_products.weight
				,shop_products.description
				,shop_products.imagetype
				,shop_products.soldout
				,shop_products.stock
				,shop_products.options
				,shop_products.specs
				,shop_products.custom
			FROM
				shop_products
				,shop_brands
			WHERE
				shop_products.category_id=%u
			AND
				shop_brands.id=shop_products.brand_id
			AND
				shop_products.id>1
			AND
				shop_products.hidden=0
			AND
				shop_products.id NOT IN (
					SELECT
						product_id AS id
					FROM
						shop_product_restrictions
					WHERE
						area_id=%u
				)";

	echo "<pre>".print_r($_REQUEST,true)."</pre>";	
	
	$query.=" ORDER BY ";
	
	switch($details->fields['productord'])
	{
		default:
		case "0":
			$query.="shop_products.name ASC";
			break;
		case "1":
			$query.="shop_products.name DESC";
			break;
		case "2":
			$query.="shop_products.price ASC";
			break;
		case "3":
			$query.="shop_products.price DESC";
			break;
		case "4":
			$query.="shop_products.ord ASC";
			break;
	}
	$vars=array();
	$vars[]=$category_id;
	$vars[]=$session->session->fields['area_id'];
	if($config['display']['products']>0)
	{
		$query.="
			LIMIT %u,%u";
		$vars[]=$start;
		$vars[]=$config['display']['products'];
	}
	$products=$db->Execute(vsprintf($query,$vars));
?>
