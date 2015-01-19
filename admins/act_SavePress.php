<?
	$db->SetTransactionMode("SERIALIZABLE");
	$db->StartTrans();

    $data = array(
        'type' => $_POST['type'],
        'title' => $_POST['title'],
        'date' => strtotime(str_replace('/', '-', $_POST['date'])),
        'release_title' => $_POST['release_title'],
        'summary' => $_POST['summary'],
        'content' => $_POST['content'][0],
        'link' => $_POST['link']
    );

    foreach($data as $key => $val)
        $data[$key] = $db->Quote($val);

    if(!$_GET['press_id']){
        $db->Execute("INSERT INTO cms_press(".implode(',', array_keys($data)).") VALUES(".implode(',', array_values($data)).")");
        $id = $db->_insertid();
    }else{
        $tmp = array();
        foreach($data as $key => $val){
            $tmp[] = $key . ' = ' . $val;
        }

        $db->Execute("UPDATE cms_press SET ".implode(', ', $tmp)." WHERE id = ".$_GET['press_id']);
        $id = $_GET['press_id'];
    }

    if($_FILES['image']['error'] == UPLOAD_ERR_OK){
        $ext = array_pop(explode('.', $_FILES['image']['name']));
        $dir = $config['path'].'images/press/list/';
        $name = $id.'.'.$ext;
        $destination = $dir.$name;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)){
            require_once('lib_WsImage.php');

            $t1 = new Ws_Image($destination);
            $t1->resize(207, 294);
            $t1->save();

            $db->Execute("UPDATE cms_press SET imagetype = '{$ext}' WHERE id = {$id}");
        }
    }

    foreach($_FILES['images']['name'] as $index => $name){
        if($img['error'] == UPLOAD_ERR_OK){
            $ext = array_pop(explode('.', $name));
            $dir = $config['path'].'images/press/';

            $db->Execute("INSERT INTO cms_press_images VALUES(NULL, {$id}, '{$ext}')");
            $img_id = $db->_insertid();

            $name = $img_id.'.'.$ext;
            $destination = $dir.$name;

            if(move_uploaded_file($_FILES['images']['tmp_name'][$index], $destination)){
                require_once('lib_WsImage.php');

                copy($destination, $dir.'big/'.$name);

                $t1 = new Ws_Image($dir.'big/'.$name);
                $t1->resize(null, 385);
                $t1->save();
            }
        }
    }

    $db->Execute("DELETE FROM cms_press_related WHERE press_id = {$id}");
    if($related = $_POST['related']){
        foreach((array)$related as $related_id){
            $db->Execute("INSERT INTO cms_press_related VALUES(NULL, {$id}, {$related_id})");
        }
    }

    $ok=$db->CompleteTrans();

    header('location: index.php?fuseaction=admin.press');
    die;
