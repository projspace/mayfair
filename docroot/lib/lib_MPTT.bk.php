<?
	class MPTT
	{
		var $_db;
		var $_table;
		var $_siteid;
		var $_result;

		function MPTT(&$db,$table,$siteid)
		{
			$this->_db=$db;
			$this->_table=$table;
			$this->_siteid=$siteid;
		}

		/********************************************************************************
		 * Add Page
		 *
		 * Adds a node into a MPTT tree structure
		 *******************************************************************************/

		function addPage($id,$parentid)
		{
			$res=$this->_db->Execute(
				sprintf("
					SELECT
						MAX(rgt)
					AS
						max
					FROM
						".$this->_table."
					WHERE
						siteid=%u
					AND
						parentid=%u
					AND
						deleted=0
				"
					,$this->_siteid
					,$parentid
				)
			);

			if($parentid==0)
			{
				//Special case
				//If no sibling set to 1, 2
				if($res->fields['max']==0)
				{
					$lft=1;
					$rgt=$lft+1;
				}
				else
				{
					$lft=$res->fields['max']+1;
					$rgt=$lft+1;
				}
			}
			else
			{
				//If no siblings get parent
				if($res->fields['max']==0)
				{
					$parent=$this->_db->Execute(
						sprintf("
							SELECT
								lft
							FROM
								".$this->_table."
							WHERE
								siteid=%u
							AND
								id=%u
							AND
								deleted=0
						"
							,$this->_siteid,$parentid
						)
					);
					$lft=$parent->fields['lft']+1;
					$rgt=$lft+1;
				}
				else
				{
					$lft=$res->fields['max']+1;
					$rgt=$lft+1;
				}

				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							lft=lft+2
						WHERE
							siteid=%u
						AND
							lft>=%u
						AND
							deleted=0
					"
						,$this->_siteid,$lft
					)
				);

				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							rgt=rgt+2
						WHERE
							siteid=%u
						AND
							rgt>=%u
						AND
							deleted=0
					"
						,$this->_siteid,$lft
					)
				);
			}

			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=%u
						,rgt=%u
					WHERE
						siteid=%u
					AND
						id=%u
					AND
						deleted=0
				"
					,$lft
					,$rgt
					,$this->_siteid
					,$id
				)
			);
		}

		/********************************************************************************
		 * Remove Page
		 *
		 * Removes a node from a MPTT structure
		 *******************************************************************************/

		function removePage($id)
		{
			$page=$this->_db->Execute(
				sprintf("
					SELECT
						lft
						,rgt
						,parentid
					FROM
						".$this->_table."
					WHERE
						siteid=%u
					AND
						id=%u
				"
					,$this->_siteid,$id
				)
			);

			$lft=$page->fields['lft'];
			$rgt=$page->fields['rgt'];

			if($rgt-$lft>1)
			{
				//Has child nodes
				//Get current max ord from parent

				$max=$this->_db->Execute(
					sprintf("
						SELECT
							MAX(ord) AS max
						FROM
							".$this->_table."
						WHERE
							parentid=%u
					"
						,$page->fields['parentid']
					)
				);

				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							parentid=%u
							,ord=ord+%u
						WHERE
							siteid=%u
						AND
							parentid=%u
						AND
							deleted=0
					"
						,$page->fields['parentid']
						,$max->fields['max']
						,$this->_siteid
						,$id
					)
				);

				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							lft=lft-1
							,rgt=rgt-1
						WHERE
							siteid=%u
						AND
							lft>%u
						AND
							rgt<%u
						AND
							deleted=0
					"
						,$this->_siteid
						,$lft
						,$rgt
					)
				);
			}

			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=lft-2
					WHERE
						siteid=%u
					AND
						lft>%u
					AND
						deleted=0
				"
					,$this->_siteid
					,$rgt
				)
			);

			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						rgt=rgt-2
					WHERE
						siteid=%u
					AND
						rgt>%u
					AND
						deleted=0
				"
					,$this->_siteid
					,$rgt
				)
			);
		}

		/********************************************************************************
		 * Movee up/down
		 *
		 * Handle moving page order within branch without requiring full rebuild
		 *******************************************************************************/

		function swap($id1,$id2)
		{
			//Get left and right valus
			$page1=$this->_db->Execute(
				sprintf("
					SELECT
						lft
						,rgt
					FROM
						".$this->_table."
					WHERE
						id=%u
				"
					,$id1
				)
			);

			$page2=$this->_db->Execute(
				sprintf("
					SELECT
						lft
						,rgt
					FROM
						".$this->_table."
					WHERE
						id=%u
				"
					,$id2
				)
			);
			$rem=$page2->fields['lft']-$page1->fields['lft'];
			$add=$page2->fields['rgt']-$page1->fields['rgt'];

			$token=md5(uniqid(srand(),true));
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=lft-%u
						,rgt=rgt-%u
						,movetoken=%s
					WHERE
						lft>=%u
					AND
						rgt<=%u
					AND
						deleted=0
				"
					,$rem
					,$rem
					,$this->_db->Quote($token)
					,$page2->fields['lft']
					,$page2->fields['rgt']
				)
			);

			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=lft+%u
						,rgt=rgt+%u
					WHERE
						lft>=%u
					AND
						rgt<=%u
					AND
						deleted=0
					AND
						movetoken!=%s
				"
					,$add
					,$add
					,$page1->fields['lft']
					,$page1->fields['rgt']
					,$this->_db->Quote($token)
				)
			);

			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						movetoken=%s
					WHERE
						movetoken=%s
				"
					,$this->_db->Quote("")
					,$this->_db->Quote($token)
				)
			);
		}

		/********************************************************************************
		 * Rebuild Tree
		 *
		 * Completely rebuild the MPTT structure from scratch
		 *******************************************************************************/

		function rebuildTree($parentid,$lft)
		{
			$rgt=$lft+1;
			$result=$this->_db->Execute(
				sprintf("
					SELECT
						id
					FROM
						".$this->_table."
					WHERE
						siteid=%u
					AND
						parentid=%u
					AND
						deleted=0
					ORDER BY
						ord
					ASC
				"
					,$this->_siteid
					,$parentid
				)
			);

			while($row=$result->FetchRow())
			{
				$rgt=$this->rebuildTree($row['id'],$rgt);
			}

			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=%u
						,rgt=%u
					WHERE
						siteid=%u
					AND
						id=%u
					AND
						deleted=0
				"
					,$lft
					,$rgt
					,$this->_siteid
					,$parentid
				)
			);

			return $rgt+1;
		}

		/********************************************************************************
		 * Make Tree
		 *
		 * Return a multi-dimensional representation of the tree
		 *******************************************************************************/

		function makeTree()
		{
			$this->_result=$this->_db->Execute(
				sprintf("
					SELECT
						id
						,parentid
						,name
						,lft
						,rgt
					FROM
						".$this->_table."
					WHERE
						siteid=%u
					AND
						deleted=0
					ORDER BY
						lft
					ASC
				"
					,$this->_siteid
				)
			);

			$ret=array();
			$count=0;
			while($row=$this->_result->FetchRow())
			{
				if($row['rgt']-$row['lft']==1)
				{
					//No children, add to current level
					$ret[$count]=$row;
					$count++;
				}
				else
				{
					//Children, add to current level, add subsequent to next level
					$ret[$count]=$row;
					$ret[$count]['children']=$this->_makeSubTree($row['rgt']);
					$count++;
				}
			}
			return $ret;
		}

		function _makeSubTree($rgt)
		{
			$count=0;
			while($row=$this->_result->FetchRow())
			{
				if($row['rgt']-$row['lft']==1)
				{
					$ret[$count]=$row;
					$count++;
				}
				else
				{
					$ret[$count]=$row;
					$ret[$count]['children']=$this->_makeSubTree($row['rgt']);
					$count++;
				}
				if($row['rgt']+1==$rgt)
					break;
			}
			return $ret;
		}

		/********************************************************************************
		 * Make DTree
		 *
		 * Return javascript code to instantiate and draw a dhtml tree
		 *******************************************************************************/

		function makeDTree()
		{
			$count=0;
			$ret="a = new dTree('a');a.config.useCookies=true;\n";
			$ret.="a.add(0,-1,'Pages','index.php?fuseaction=admin.pages');\n";

			$result=$this->_db->Execute(
				sprintf("
					SELECT
						id
						,parentid
						,name
					FROM
						".$this->_table."
					WHERE
						siteid=%u
					AND
						deleted=0
					ORDER BY
						ord
					ASC
				"
					,$this->_siteid
				)
			);

			while($row=$result->FetchRow())
			{
				$ret.="a.add({$row['id']},{$row['parentid']},'".addslashes($row['name'])."','index.php?fuseaction=admin.pages&parentid={$row['id']}',true);\n";
			}
			$ret.="document.write(a);";
			return $ret;
		}

		/********************************************************************************
		 * Display Tree
		 *
		 * Return nested ordered lists representing the tree
		 *******************************************************************************/

		function displayTree()
		{
			global $config;
			$result=$this->_db->Execute(
				sprintf("
					SELECT
						*
					FROM
						".$this->_table."
					WHERE
						siteid=%u
					AND
						deleted=0
					ORDER BY
						lft
					ASC
				"
					,$this->_siteid
				)
			);

			$ul=array();
			$ret="<ul>";
			while($row=$result->FetchRow())
			{
				if($row['rgt']-$row['lft']==1)
					$ret.="<li><a href=\"{$config['dir']}{$row['url']}\">{$row['name']}</a></li>";
				else
				{
					$ul[]=$row['rgt'];
					$ret.="<li><a href=\"{$config['dir']}{$row['url']}\">{$row['name']}</a><ul>";
				}
				if(count($ul)>0)
				{
					if($ul[count($ul)-1]==($row['rgt']+1))
					{
						array_pop($ul);
						$ret.="</ul></li>";
					}
				}
			}
			$ret.="</ul>";
			return $ret;
		}
	}
?>