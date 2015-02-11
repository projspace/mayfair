<?php
if($press['imagetype']){
    if($_GET['id']){
        $db->Execute("DELETE FROM cms_press_images WHERE id = {$_GET['id']}");
        header('location: index.php?fuseaction=admin.editPress&press_id='.$press['id'].'#tabs-4');
    }else{
        @unlink($config['path'].'images/press/list/'.$press['id'].'.'.$press['imagetype']);
        $db->Execute("UPDATE cms_press SET imagetype='' WHERE id = {$press['id']}");
        header('location: index.php?fuseaction=admin.editPress&press_id='.$press['id'].'#tabs-3');
    }

    die;
}