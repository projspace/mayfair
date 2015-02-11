<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Common.php");
	
	function sort_ord($row1, $row2)
	{
		if ($row1['ord'] == $row2['ord']) 
			return 0;
		return ($row1['ord'] > $row2['ord']) ? 1 : -1;
	}
	
	function sort_name($row1, $row2)
	{
		return strcmp($row1['name'], $row2['name']);
	}
	
	$product_id = $_REQUEST['product_id']+0;
	$size_id = $_REQUEST['size_id']+0;
	$width_id = $_REQUEST['width_id']+0;
	$color_id = $_REQUEST['color_id']+0;

    $results=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				shop_variables
			WHERE
				name IN ('vat','product_options')
		"
		)
	);


	while($row = $results->FetchRow())
	{
		if($row['name'] == 'vat')
			define('VAT', $row['value']);
		if($row['name'] == 'product_options')
			define('PRODUCT_OPTIONS', $row['value']);
	}
    if(!defined('PRODUCT_OPTIONS'))
        define('PRODUCT_OPTIONS', 'upc_only');

	if(PRODUCT_OPTIONS == 'upc_only')
		$sql_options = sprintf("shop_product_options.upc_code IS NOT NULL");
	else
		$sql_options = "1";

	$product=$db->Execute(
		sprintf("
			SELECT
				id
				,alt_size
				,vat
				,price
			FROM
				shop_products
			WHERE
				id = %u
		"
            ,$product_id
		)
	);
	$product = $product->FetchRow();
	
	//find all options
	$options=$db->Execute(
		sprintf("
			SELECT
				shop_product_options.*
				,shop_sizes.name size_name
				,shop_sizes.alt size_alt_name
				,shop_sizes.ord size_ord
				,shop_widths.name width_name
				,shop_widths.ord width_ord
				,shop_colors.name color_name
				,shop_colors.ord color_ord
				,shop_colors.hexa color_hexa
				,shop_colors.image_type
			FROM
				shop_product_options
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
			WHERE
				shop_product_options.product_id = %u
			AND
				%s
		"
			,$product_id
			,$sql_options
		)
	);
	$sizes = array();
	$widths = array();
	$colors = array();
	$quantity = array();
	$qty = 0;
	while($row = $options->FetchRow())
	{
		if($row['size_id'] && !isset($sizes[$row['size_id']+0]))
			$sizes[$row['size_id']+0] = array('id'=>$row['size_id'], 'name'=>($product['alt_size'] && trim($row['size_alt_name'])!='')?$row['size_alt_name']:$row['size_name'], 'ord'=>$row['size_ord'], 'selected'=>false, 'disabled'=>false);
		if($row['width_id'] && !isset($widths[$row['width_id']+0]))
			$widths[$row['width_id']+0] = array('id'=>$row['width_id'], 'name'=>$row['width_name'], 'ord'=>$row['width_ord'], 'selected'=>false, 'disabled'=>false);
		if($row['color_id'] && !isset($colors[$row['color_id']+0]))
			$colors[$row['color_id']+0] = array('id'=>$row['color_id'], 'name'=>$row['color_name'], 'ord'=>$row['color_ord'], 'hexa'=>$row['color_hexa'], 'selected'=>false, 'disabled'=>false, 'image_type'=>$row['image_type']);
		if($row['quantity'] > $qty)
			$qty = $row['quantity'];
        
        if($price = $row['price']+0)
        {
            if($row['size_id']) $sizes[$row['size_id']+0]['prices'][] = $price;
            if($row['width_id']) $widths[$row['width_id']+0]['prices'][] = $price;
            if($row['color_id']) $colors[$row['color_id']+0]['prices'][] = $price;
        }
	}
	
	uasort($sizes, "sort_ord");
	uasort($widths, "sort_ord");
	uasort($colors, "sort_ord");
	
	for($i=1;$i<=$qty;$i++)
		$quantity[] = array('quantity'=>$i, 'selected'=>false, 'disabled'=>false);
	
	//selected options
	$sql_where = array();
	$sql_where[] = sprintf("shop_product_options.product_id = %u", $product_id);
	$sql_where[] = $sql_options;
	
	if($qty = $_REQUEST['quantity']+0)
	{
		$quantity[$qty-1]['selected'] = true;
		$sql_where['quantity'] = sprintf("shop_product_options.quantity >= %u", $qty);
	}
	else
		$sql_where[] = sprintf("shop_product_options.quantity > 0");
	
	//if(trim($_REQUEST['source']) != 'color')
	//{
		if($size_id)
		{
			$sizes[$size_id]['selected'] = true;
			$sql_where['size'] = sprintf("shop_product_options.size_id = %u", $size_id);
		}
			
		if($width_id)
		{
			$widths[$width_id]['selected'] = true;
			$sql_where['width'] = sprintf("shop_product_options.width_id = %u", $width_id);
		}
	//}
	
	/*if(!$color_id)
	{
		$result = reset($colors);
		$color_id = $result['id'];
	}*/
	if($color_id)
	{
		$colors[$color_id]['selected'] = true;
		$sql_where['color'] = sprintf("shop_product_options.color_id = %u", $color_id);
	}
	
	$option_ids = array();
    $option_prices = array();
    $option_price = 0;
	if(count($sql_where) > 1)
	{
        $size_ids = array();
        $width_ids = array();
        $color_ids = array();
        $max_qty = 0;

        $options=$db->Execute(
			sprintf("
				SELECT
					*
				FROM
					shop_product_options
				WHERE
					%s
			"
				,implode(' AND ', $sql_where)
			)
		);
		while($row = $options->FetchRow())
		{
			$option_ids[] = $row['id'];
			/*$size_ids[] = $row['size_id'];
			$width_ids[] = $row['width_id'];
			$color_ids[] = $row['color_id'];
			if($row['quantity'] > $max_qty)
				$max_qty = $row['quantity'];*/
            $option_prices[] = $row['price'];
            $option_price = $row['price'];
		}

        $sql_temp = $sql_where;
        unset($sql_temp['size']);
        $options=$db->Execute(sprintf("SELECT DISTINCT size_id FROM shop_product_options WHERE %s", implode(' AND ', $sql_temp)));
		while($row = $options->FetchRow())
			$size_ids[] = $row['size_id'];

        $sql_temp = $sql_where;
        unset($sql_temp['width']);
        $options=$db->Execute(sprintf("SELECT DISTINCT width_id FROM shop_product_options WHERE %s", implode(' AND ', $sql_temp)));
		while($row = $options->FetchRow())
			$width_ids[] = $row['width_id'];

        $sql_temp = $sql_where;
        unset($sql_temp['color']);
        $options=$db->Execute(sprintf("SELECT DISTINCT color_id FROM shop_product_options WHERE %s", implode(' AND ', $sql_temp)));
		while($row = $options->FetchRow())
			$color_ids[] = $row['color_id'];

        $sql_temp = $sql_where;
        unset($sql_temp['quantity']);
        $options=$db->Execute(sprintf("SELECT MAX(quantity) quantity FROM shop_product_options WHERE %s", implode(' AND ', $sql_temp)));
		$row = $options->FetchRow();
		$max_qty = $row['quantity'];

		if(count($size_ids))
			foreach($sizes as $size_id=>$unused)
				if(!in_array($size_id, $size_ids))
					$sizes[$size_id]['disabled'] = true;
					
		if(count($width_ids))
			foreach($widths as $width_id=>$unused)
				if(!in_array($width_id, $width_ids))
					$widths[$width_id]['disabled'] = true;
					
		if(count($color_ids))
			foreach($colors as $color_id=>$unused)
				if(!in_array($color_id, $color_ids))
					$colors[$color_id]['disabled'] = true;
		
		foreach($quantity as $qty=>$unused)
			if($qty+1 > $max_qty)
				$quantity[$qty]['disabled'] = true;
	}

    $product_price = $product['price']+0;
	if(count($option_ids) == 1)
    {
        echo '<input type="hidden" id="option_id" name="option_id" value="'.$option_ids[0].'"/>';
        $product_price += $option_price;
    }
    else
    {
		if(count($option_prices))
		{
			$min_price  = min($option_prices);
			$max_price  = max($option_prices);
		}
		else
			$min_price  = $max_price  = 0;

        if($min_price == $max_price)
            $product_price += $min_price;
        else
            $product_price = array('min'=>$product_price+$min_price, 'max'=>$product_price+$max_price);
    }

    if(is_array($product_price))
    {
        echo '<input type="hidden" id="product_price_min" name="product_price_min" value="'.$product_price['min'].'"/>';
        echo '<input type="hidden" id="product_price_max" name="product_price_max" value="'.$product_price['max'].'"/>';
    }
    else
    {
        if($product['vat'])
            $product_price = $product_price*(100+VAT)/100;

        echo '<input type="hidden" id="product_price" name="product_price" value="'.$product_price.'"/>';
    }


	if(count($colors))
	{
        echo '<div class="report-box  custom-select text-big">';
		echo '<select name="color_id" class="color styled" title="Select color">';
		echo '<option value="">select color</option>';
		foreach($colors as $color_id=>$row)
		{
			$status = '';
			$additional = '';
            /*if($count = count($row['prices']))
            {
                if($count > 1)
                    $additional .= ' +['.price(min($row['prices'])).' - '.price(max($row['prices'])).']';
                else
                    $additional .= ' +'.price($row['prices'][0]);
            }*/
			if($row['disabled'])
			{
				$status = 'disabled="disabled"';
				$additional .= ' (out of stock)';
			}
			elseif($row['selected'])
				$status = 'selected="selected"';
				
			if($row['image_type'])
				$style='data-background="url('.$config['dir'].'images/colors/'.$color_id.'.'.$row['image_type'].') top left no-repeat"';
			else
				$style = '';
				
			echo '<option value="'.$row['id'].'" data-color="#'.$row['hexa'].'" '.$status.' '.$style.'>'.$row['name'].$additional.'</option>';
		}
		echo '</select>';
		echo '</div>';
	}
	
	if(count($sizes))
	{
        echo '<div class="report-box  custom-select text-big">';
		echo '<select name="size_id" class="styled">';
		echo '<option value="">select size</option>';
		foreach($sizes as $row)
		{
			$status = '';
			$additional = '';
            if($count = count($row['prices']))
            {
                if($count > 1)
                    $additional .= (($min = min($row['prices'])) == ($max = max($row['prices'])))?' +'.price($min):' +['.price($min).' - '.price($max).']';
                else
                    $additional .= ' +'.price($row['prices'][0]);
            }
			if($row['disabled'])
			{
				$status = 'disabled="disabled"';
				$additional .= ' (out of stock)';
			}
			elseif($row['selected'])
				$status = 'selected="selected"';
				
			echo '<option value="'.$row['id'].'" '.$status.'>'.$row['name'].$additional.'</option>';
		}
		echo '</select>';
        echo '</div>';
	}
	
	if(count($widths))
	{
        echo '<div class="report-box  custom-select text-big">';
		echo '<select name="width_id" class="styled">';
		echo '<option value="">select option</option>';
		foreach($widths as $row)
		{
			$status = '';
			$additional = '';
            if($count = count($row['prices']))
            {
                if($count > 1)
                    $additional .= ' +['.price(min($row['prices'])).' - '.price(max($row['prices'])).']';
                else
                    $additional .= ' +'.price($row['prices'][0]);
            }
			if($row['disabled'])
			{
				$status = 'disabled="disabled"';
				$additional .= ' (out of stock)';
			}
			elseif($row['selected'])
				$status = 'selected="selected"';
				
			echo '<option value="'.$row['id'].'" '.$status.'>'.$row['name'].$additional.'</option>';
		}
		echo '</select>';
        echo '</div>';
	}
	
    echo '<div class="report-box  custom-select text-big">';
	echo '<select name="quantity" id="quantity" class="styled">';
	echo '<option value="">select quantity</option>';
	foreach($quantity as $row)
	{
		$status = '';
		$additional = '';
		if($row['disabled'])
		{
			$status = 'disabled="disabled"';
			$additional .= ' (out of stock)';
		}
		elseif($row['selected'])
			$status = 'selected="selected"';
			
		echo '<option value="'.$row['quantity'].'" '.$status.'>'.$row['quantity'].$additional.'</option>';
	}
	echo '</select>';	
    echo '</div>';
    echo '<div class="clear"></div>';
?>