<?php
if($_POST['press_id']){
	$db->Execute("DELETE FROM cms_press WHERE id = {$_POST['press_id']}");
}

header('location: index.php?fuseaction=admin.press');die;