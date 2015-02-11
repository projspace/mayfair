<?
	$category_id = $_REQUEST['category_id']+0;
	$category=$db->Execute(
		sprintf("
			SELECT
				shop_categories.*
			FROM
				shop_categories
			WHERE
				shop_categories.id=%u
		"
			,$category_id
		)
	);
	$category = $category->FetchRow();
	if(!$category)
	{
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		header("Status: 404 Not Found");
		header("location: ".$config['dir']);
		exit;
	}
	
	$tmp = str_replace($config['protocol'].$config['url'], '', category_url($category['id'], $category['name']));
	if(!in_array($_SERVER['REDIRECT_URL'], array($tmp, $tmp.'/new', $tmp.'/special')))
	{
		/*if(strpos($_SERVER['REDIRECT_URL'], '/new') == strlen($_SERVER['REDIRECT_URL'])-strlen('/new'))
			$tmp .= '/new';
		if(strpos($_SERVER['REDIRECT_URL'], '/special') == strlen($_SERVER['REDIRECT_URL'])-strlen('/special'))
			$tmp .= '/special';
		*/
		header($_SERVER["SERVER_PROTOCOL"]." 301 Moved Permanently");
		header("Status: 301 Moved Permanently");
		header("location: ".$tmp);
		exit;
	}
	
    // meta tags
	if($category['meta_title']!="")
		$elems->meta['title']=$category['meta_title'];
	else
		$elems->meta['title']=$category['name'].' / '.$config['meta']['title'];

	if($category['meta_description']!="")
		$elems->meta['description']=$category['meta_description'];
	else
		$elems->meta['description']=trim(strip_tags($category['content']));

	if($category['meta_keywords']!="")
		$elems->meta['keywords']=$category['meta_keywords'];
		
	$subcategories = $elems->qry_Categories($category['id']);

	/*$old_pageview = $_REQUEST['pageview'];
	$_REQUEST['pageview'] = 'ALL';
	include("qry_CategoryMobile.php");
	$_REQUEST['pageview'] = $old_pageview;*/
?>
