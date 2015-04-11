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
	$group=$db->Execute(
		sprintf("
			SELECT
				*
			FROM
				admin_acl_groups
			WHERE
				id=%u
		"
			,$_REQUEST['group_id']
		)
	);
	$group = $group->FetchRow();

	$actions=$db->Execute(
		sprintf("
			SELECT
				admin_acl_group_action.id AS group_action_id
				,admin_acl_categories.id AS category_id
				,admin_acl_categories.name AS category_name
				,admin_acl_actions.*
			FROM
				admin_acl_actions
			LEFT JOIN
				admin_acl_group_action
			ON
				admin_acl_group_action.action_id=admin_acl_actions.id
			AND
				admin_acl_group_action.group_id=%u
			LEFT JOIN
				admin_acl_categories
			ON
				admin_acl_categories.id=admin_acl_actions.category_id
			ORDER BY
				admin_acl_categories.id ASC
		"
			,$_REQUEST['group_id']
		)
	);
?>