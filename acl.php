<?
	include("lib/cfg_Config.php");
	include("lib/adodb/adodb.inc.php");
	include("lib/act_OpenDB.php");

	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

	//Clear ACL tables
	$db->Execute("TRUNCATE TABLE admin_acl_actions");
	$db->Execute("ALTER TABLE admin_acl_actions AUTO_INCREMENT=1");

	$db->Execute("TRUNCATE TABLE admin_acl_categories");
	$db->Execute("ALTER TABLE admin_acl_categories AUTO_INCREMENT=1");

	$db->Execute("TRUNCATE TABLE admin_acl_groups");
	$db->Execute("ALTER TABLE admin_acl_groups AUTO_INCREMENT=1");

	$db->Execute("TRUNCATE TABLE admin_acl_group_action");
	$db->Execute("ALTER TABLE admin_acl_group_action AUTO_INCREMENT=1");

	$db->Execute(
		sprintf("
			INSERT INTO
				admin_acl_groups (
					name
				) VALUES (
					%s
				)
		"
			,$db->Quote("Admin")
		)
	);
	echo $db->Insert_ID();
	echo $db->ErrorMsg()."<br />";

	$acl=file_get_contents("shop_acl.txt");
	$acl=str_replace("\r","",$acl);
	$acl=explode("\n",$acl);

	$category_id=0;
	foreach($acl as $item)
	{
		if(strstr($item,"|"))
		{
			$db->Execute(
				sprintf("
					INSERT INTO
						admin_acl_categories (
							name
						) VALUES (
							%s
						)
				"
					,$db->Quote(trim(str_replace("|","",$item)))
				)
			);
			echo $db->ErrorMsg()."<br />";
			$category_id=$db->Insert_ID();
		}
		else if(trim($item)!="")
		{
			if($category_id==0)
				die("No category first.");
			$item=explode(",",$item);
			$db->Execute(
				sprintf("
					INSERT INTO
						admin_acl_actions (
							category_id
							,name
							,description
						) VALUES (
							%u
							,%s
							,%s
						)
				"
					,$category_id
					,$db->Quote(trim($item[0]))
					,$db->Quote(trim($item[1]))
				)
			);
			echo $db->ErrorMsg()."<br />";
			$action_id=$db->Insert_ID();
			$db->Execute(
				sprintf("
					INSERT INTO
						admin_acl_group_action (
							group_id
							,action_id
						) VALUES (
							%u
							,%u
						)
				"
					,1
					,$action_id
				)
			);
			echo $db->ErrorMsg()."<br />";
		}
	}

	$ok=$db->CompleteTrans();
	if(!$ok)
    	echo "There was a problem whilst updating the database, please try again.  If this persists please notify your designated support contact";
	include("lib/act_CloseDB.php");
?>