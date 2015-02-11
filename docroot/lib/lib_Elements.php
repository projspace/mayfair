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
	class Elements
	{
		var $db;
		var $smarty;
		var $config;
		var $session_id;
		var $meta;
		var $title;
		
		var $content;
		var $contents;
		var $page;
		var $site;

		function Elements(&$db,&$smarty,&$config,&$session_id)
		{
			$this->db =& $db;
			$this->smarty =& $smarty;
			$this->config =& $config;
			$this->session_id =& $session_id;
			$this->setSite();
			$this->setMeta();
		}
		
		function setSite()
		{
			$this->site=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						cms_sites
					WHERE
						id=%u
				"
					,$this->config['siteid']
				)
			);
		}
		
		function setMeta()
		{
			$results=$this->db->Execute(
				sprintf("
					SELECT
						*
					FROM
						shop_variables
					WHERE
						name IN ('meta_title','meta_keywords','meta_description')
				"
				)
			);
			while($row = $results->FetchRow())
				$this->meta[str_replace('meta_', '', $row['name'])] = $row['value'];
		}
		
		function setPage(&$page,&$content)
		{
			$this->page=$page;
			$this->content=$content;
			$this->contents=explode("<!--[#content#]-->",$this->content->fields['content']);
		}

		function title()
		{
			return $this->title;
		}

		function sid()
		{
			if(!USECOOKIE && !SEARCHENGINE)
				return "?".$this->config['shop']['sessionid']."=".$_REQUEST[$this->config['shop']['sessionid']];
		}

		function sid_amp()
		{
			if(!USECOOKIE && !SEARCHENGINE)
				return "&amp;".$this->config['shop']['sessionid']."=".$_REQUEST[$this->config['shop']['sessionid']];
		}

		function meta($name)
		{
			if(isset($this->content) && trim($this->content->fields['meta_'.$name])!="")
				return $this->content->fields['meta_'.$name];
			else
			if(trim($this->meta[$name])!="")
				return $this->meta[$name];
			else
				return $this->config['meta'][$name];
				//return $this->site->fields['meta_'.$name];
		}

		function content($id=0)
		{
			global $Fusebox;
			if(!isset($this->contents))
			{
				echo add_sessionid($Fusebox['layout']);
			}
			else
				echo trim($this->contents[$id]);
		}

		function search($type=false)
		{
			if($type===false)
				$this->smarty->display($this->config['template']."/elems/search.tpl.php");
			else
				$this->smarty->display($type."/elems/search.tpl.php");
		}

		function navigation($type=false)
		{
			$pages=$this->db->Execute("
				SELECT
					url
					,name
				FROM
					cms_pages
				WHERE
					parentid = 0
				AND
					hidden = 0
				AND
					deleted = 0
				ORDER BY
					ord
				ASC
			");
			$this->smarty->assign("pages",$pages->GetRows());
			if($type===false)
				$this->smarty->display($this->config['template']."/elems/nav.tpl.php");
			else
				$this->smarty->display($type."/elems/nav.tpl.php");
		}

		function offers($category_id=1,$type=false)
		{
			$offers=$this->db->Execute(
				sprintf("
					SELECT
						shop_products.id
						,shop_products.category_id
						,shop_products.name
						,shop_products.price
						,shop_products.description
						,shop_products.imagetype
						,shop_products.specs
					FROM
						shop_products
						,shop_refs
					WHERE
						shop_products.id=shop_refs.product_id
					AND
						shop_refs.category_id=%u
				"
					,$category_id
				)
			);
			if($offers->RecordCount()==0 && $category_id>1)
				$this->offers(1,$type);
			else
			{
				$this->smarty->assign("offers",$offers->GetRows());
				if($type===false)
					$this->smarty->display($this->config['template']."/elems/offers.tpl.php");
				else
					$this->smarty->display("$type/elems/offers.tpl.php");
			}
		}

		function trailHome($type=false)
		{
			global $Fusebox,$page;
			$trail[0]['name']="Home";
			$trail[0]['url']="index.php";
			if($page!="" && $page!="home")
			{
				$getpage=$this->db->Execute(
					sprintf("
						SELECT
							name
						FROM
							cms_website
						WHERE
							page=%s
					"
						,$this->db->Quote($page)
					)
				);
				$trail[1]['name']=$getpage->fields[0];
				$trail[1]['url']="index.php/fuseaction/home.content/page/".$page;
			}
			$this->smarty->assign("home",true);
			$this->smarty->assign("trail",$trail);
			if($type===false)
				$this->smarty->display($this->config['template']."/elems/breadcrumb.tpl.php");
			else
				$this->smarty->display("$type/elems/breadcrumb.tpl.php");
		}

		function trailShop($category_id=1,$type=false)
		{
			global $Fusebox;
			if($Fusebox['fuseaction']=="category" || $Fusebox['fuseaction']=="product")
			{
				$category=$this->db->Execute(
					sprintf("
						SELECT
							trail
						FROM
							shop_categories
						WHERE
							id=%u
					"
						,$category_id
					)
				);
				$trail=unserialize($category->fields[0]);

				//$this->smarty->assign("trail",$trail);
				//Remove top category for partridges
				unset($trail[1]);
				$this->smarty->assign("trail",$trail);
				if($type===false)
					$this->smarty->display($this->config['template']."/elems/breadcrumb.tpl.php");
				else
					$this->smarty->display("$type/elems/breadcrumb.tpl.php");
			}
		}

		function cart($type=false)
		{
			$cart=$this->db->Execute(
				sprintf("
					SELECT
						SUM(quantity) AS items
						,SUM(quantity*price) AS total
					FROM
						shop_session_cart
					WHERE
						session_id=%s
				"
					,$this->db->Quote($this->session_id)
				)
			);
			$this->smarty->assign("cart",$cart->fields);
			if($type===false)
				$this->smarty->display($this->config['template']."/elems/cart.tpl.php");
			else
				$this->smarty->display("$type/elems/cart.tpl.php");
		}

		function categories($category_id=1,$type=false)
		{
			global $session;
			$details=$this->db->Execute("
					SELECT
						childord
					FROM
						shop_categories
					WHERE
						id=1
			");

			$siblings=$this->db->Execute(
				sprintf("
					SELECT
						id
						,name
						,imagetype
						,imageon
						,content
						,color
					FROM
						shop_categories
					WHERE
						parent_id=1
					AND
						id NOT IN (
							SELECT
								category_id AS id
							FROM
								shop_category_restrictions
							WHERE
								area_id=%u
						)
					ORDER BY
						%s
					ASC
				"
					,$session->session->fields['area_id']
					,($details->fields['childord']==1) ? "ord" : "name"
				)
			);

			$this->smarty->assign("categories",$siblings->GetRows());
			$this->smarty->assign("category_id",$category_id);
			if($type===false)
				$this->smarty->display($this->config['template']."/elems/categories.tpl.php");
			else
				$this->smarty->display("$type/elems/categories.tpl.php");
		}

		function brands($type=false)
		{
			$brands=$this->db->Execute("
				SELECT
					id
					,imageurl
					,name
					,content
				FROM
					shop_brands
				ORDER BY
					name
				ASC
			");
			$this->smarty->assign("brands",$brands);
			if($type===false)
				$this->smarty->display($this->config['template']."/elems/brands.tpl.php");
			else
				$this->smarty->display("$type/elems/brands.tpl.php");
		}
	}
?>
