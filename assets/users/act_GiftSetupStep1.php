<?
	$_SESSION['gift_setup']['type_id'] = $_POST['type_id'];
	$_SESSION['gift_setup']['name'] = $_POST['name'];
	$_SESSION['gift_setup']['date'] = $_POST['date'];
	$ok = true;
	$redirect_url = $config["dir"].'gift-registry/setup?step=2';
?>