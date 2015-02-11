<?
	include("../lib/cfg_Config.php");
	include("../lib/adodb/adodb.inc.php");
	include("../lib/act_OpenDB.php");
	include("../lib/lib_Session.php");
	include("../lib/lib_Elements.php");
	include("../lib/lib_CustomElements.php");
	$elems=new CustomElements($db,$smarty,$config,$session->session_id);
	include("../lib/lib_Common.php");
	
	$cart=$elems->qry_Cart();
	
	$vars = array();
	$vars['total']=0;
	$vars['nitems']=0;
	$vars['html']='';
	foreach($cart as $row)
	{
		$vars['nitems']+=$row['cart_quantity'];
		$vars['total']+=$row['cart_quantity']*$row['cart_price'];
		
		if($row['image_type'])
			$image = $config['dir'].'images/product/medium/'.$row['image_id'].'.'.$row['image_type'];
		else
			$image = $config['dir'].'images/product/medium/placeholder.jpg';
			
		$options = array();
		if(trim($row['size']) != '')
			$options[] = 'Size '.$row['size'];
		if(trim($row['width']) != '')
			$options[] = 'Option '.$row['width'];
		if(trim($row['color']) != '')
			$options[] = 'Color('.$row['color'].')';
		$options[] = 'Qty '.$row['cart_quantity'];
		
		$vars['html'] .= '
			<li>
				<div class="vertical-img h128"><span class="middle-img"><img src="'.$image.'" alt="" width="128"/></span></div>
				<a href="#" class="btn-remove" cart_id="'.$row['cart_id'].'">remove</a>
				<h2>
					<strong>'.price($row['cart_price']).'</strong>
					<a href="'.product_url($row['id'], $row['guid']).'">'.htmlentities($row['name'], ENT_NOQUOTES, 'UTF-8').'</a>
				</h2>
				<p>'.implode('<br />', $options).'</p>
			</li>';
	}
	die(json_encode($vars));
?>