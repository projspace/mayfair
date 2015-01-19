<?php
$type = $_GET['type'];
$types = array('press' => 'Press', 'ads' => 'Advertising');

if($id = $_GET['id']){
    $press = $db->Execute("SELECT * FROM cms_press WHERE id = ".$db->Quote($id))->FetchRow();
    $images = $db->Execute("SELECT * FROM cms_press_images WHERE press_id = {$press['id']}");
    $related = $db->Execute("SELECT p.* FROM cms_press p LEFT JOIN cms_press_related r ON r.related_id = p.id WHERE r.press_id = {$press['id']} LIMIT 3")->GetRows();
    $elems->meta['title'] = $press['title'] . ' / Press';
}else{
    $elems->meta['title'] = $types[$type];

    $page = max((int)$_GET['page'], 1);
    $perPage = 8;

    $list = $db->Execute("SELECT * FROM cms_press WHERE type='{$type}' ORDER BY date DESC LIMIT ".(($page-1)*$perPage).",".$perPage);
    $total = array_shift($db->Execute("SELECT COUNT(1) FROM cms_press WHERE type='{$type}'")->FetchRow());
    $pages = ceil($total/$perPage);
}