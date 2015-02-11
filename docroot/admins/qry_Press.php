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
    if(!$_GET['press_id']){
        $page = $_REQUEST['page']+0;
        if($page <= 0)
            $page = 1;
        $items_per_page = 20;

        $conditions = array();
        if($_GET['type']){
            $conditions[] = "type = ".$db->Quote($_GET['type']);
        }
        if($_GET['keyword']){
            $conditions[] = "title LIKE ".$db->Quote('%'.$_GET['keyword'].'%');
        }

        $pressList = $db->Execute(
            sprintf("
                SELECT
                    *
                FROM
                    cms_press
                    %s
                ORDER BY
                    date
                DESC
                LIMIT %u, %u
            "
                ,($conditions ? ' WHERE '.implode(' AND ', $conditions) : '')
                ,($page - 1)*$items_per_page
                ,$items_per_page
            )
        );

        $item_count = array_shift($db->Execute(
            "
                SELECT
                    COUNT(1)
                FROM
                    cms_press
            "
        )->FetchRow());
    }else{
        $press = $db->Execute(
            sprintf("
                SELECT
                    *
                FROM
                    cms_press
                WHERE
                    id = %u
            "
                ,$_GET['press_id']
            )
        )->FetchRow();

        $images = $db->Execute(
            sprintf("
                SELECT
                    *
                FROM
                    cms_press_images
                WHERE
                    press_id = %u
            "
                ,$_GET['press_id']
            )
        );

        $relations = $db->Execute("SELECT id, title FROM cms_press WHERE type = '{$press['type']}' AND id != {$press['id']}")->GetRows();
        $selectedRelations = explode(',',array_shift($db->Execute("SELECT GROUP_CONCAT(related_id) FROM cms_press_related WHERE press_id = {$press['id']}")->FetchRow()));
    }
