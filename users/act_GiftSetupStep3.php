<?
	$_SESSION['gift_setup']['address1'] = $_POST['address1'];
	$_SESSION['gift_setup']['address2'] = $_POST['address2'];
	$_SESSION['gift_setup']['address3'] = $_POST['address3'];
	$_SESSION['gift_setup']['postcode'] = $_POST['postcode'];
	$_SESSION['gift_setup']['area_id'] = $_POST['area_id'];
	$_SESSION['gift_setup']['country_id'] = $_POST['country_id'];
	$_SESSION['gift_setup']['delivery_after'] = $_POST['delivery_after'];
	$ok = true;
	$redirect_url = $config["dir"].'gift-registry/setup?step=4';
?>