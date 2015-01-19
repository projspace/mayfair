<?
	$_SESSION['gift_setup']['title'] = $_POST['title'];
	$_SESSION['gift_setup']['first_name'] = $_POST['first_name'];
	$_SESSION['gift_setup']['middle_name'] = $_POST['middle_name'];
	$_SESSION['gift_setup']['surname'] = $_POST['surname'];
	$_SESSION['gift_setup']['primary_phone'] = $_POST['primary_phone'];
	$_SESSION['gift_setup']['secondary_phone'] = $_POST['secondary_phone'];
	$_SESSION['gift_setup']['email'] = $_POST['email'];
	$_SESSION['gift_setup']['contact_method'] = $_POST['contact_method'];
	$_SESSION['gift_setup']['newsletter'] = $_POST['newsletter']+0;
	$ok = true;
	$redirect_url = $config["dir"].'gift-registry/setup?step=3';
?>