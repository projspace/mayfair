<?
	if(defined('LIB_DBTree'))
		return;
		
	define("LIB_DBTree", 1);
	
	class DBTree
	{
		var $_db;
		var $_table;
		var $_result;

		function DBTree(&$db,$table)
		{
			$this->_db=$db;
			$this->_table=$table;
		}

		/********************************************************************************
		 * Add Page
		 *
		 * Adds a node into a DBTree tree structure
		 *******************************************************************************/

		function addPage($id,$parent_id, $sql_conditions=null)
		{
			$sql_where = array();
			$sql_where[] = sprintf("parent_id=%u", $parent_id);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
					
			$res=$this->_db->Execute(
				sprintf("
					SELECT
						MAX(rgt)
					AS
						max
					FROM
						".$this->_table."
					WHERE
						%s
				"
					,implode(" AND ", $sql_where)
				)
			);

			if($parent_id==0)
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
					$sql_where = array();
					$sql_where[] = sprintf("id=%u", $parent_id);
					if(is_array($sql_conditions))
						foreach($sql_conditions as $condition)
							$sql_where[] = $condition;
							
					$parent=$this->_db->Execute(
						sprintf("
							SELECT
								lft
							FROM
								".$this->_table."
							WHERE
								%s
						"
							,implode(" AND ", $sql_where)
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

				$sql_where = array();
				$sql_where[] = sprintf("lft>=%u", $lft);
				if(is_array($sql_conditions))
					foreach($sql_conditions as $condition)
						$sql_where[] = $condition;
							
				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							lft=lft+2
						WHERE
							%s
					"
						,implode(" AND ", $sql_where)
					)
				);

				$sql_where = array();
				$sql_where[] = sprintf("rgt>=%u", $lft);
				if(is_array($sql_conditions))
					foreach($sql_conditions as $condition)
						$sql_where[] = $condition;
				
				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							rgt=rgt+2
						WHERE
							%s
					"
						,implode(" AND ", $sql_where)
					)
				);
			}

			$sql_where = array();
			$sql_where[] = sprintf("id=%u", $id);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=%u
						,rgt=%u
					WHERE
						%s
				"
					,$lft
					,$rgt
					,implode(" AND ", $sql_where)
				)
			);
		}

		/********************************************************************************
		 * Remove Page
		 *
		 * Removes a node from a DBTree structure
		 *******************************************************************************/

		function removePage($id, $sql_conditions=null)
		{
			$sql_where = array();
			$sql_where[] = sprintf("id=%u", $id);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
					
			$page=$this->_db->Execute(
				sprintf("
					SELECT
						lft
						,rgt
						,parent_id
					FROM
						".$this->_table."
					WHERE
						%s
				"
					,implode(" AND ", $sql_where)
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
							parent_id=%u
					"
						,$page->fields['parent_id']
					)
				);

				$sql_where = array();
				$sql_where[] = sprintf("parent_id=%u", $id);
				if(is_array($sql_conditions))
					foreach($sql_conditions as $condition)
						$sql_where[] = $condition;
				
				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							parent_id=%u
							,ord=ord+%u
						WHERE
							%s
					"
						,$page->fields['parent_id']
						,$max->fields['max']
						,implode(" AND ", $sql_where)
					)
				);

				$sql_where = array();
				$sql_where[] = sprintf("lft>%u", $lft);
				$sql_where[] = sprintf("rgt<%u", $rgt);
				if(is_array($sql_conditions))
					foreach($sql_conditions as $condition)
						$sql_where[] = $condition;
				
				$this->_db->Execute(
					sprintf("
						UPDATE
							".$this->_table."
						SET
							lft=lft-1
							,rgt=rgt-1
						WHERE
							%s
					"
						,implode(" AND ", $sql_where)
					)
				);
			}

			$sql_where = array();
			$sql_where[] = sprintf("lft>%u", $rgt);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=lft-2
					WHERE
						%s
				"
					,implode(" AND ", $sql_where)
				)
			);

			$sql_where = array();
			$sql_where[] = sprintf("rgt>%u", $rgt);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						rgt=rgt-2
					WHERE
						%s
				"
					,implode(" AND ", $sql_where)
				)
			);
		}

		/********************************************************************************
		 * Move up/down
		 *
		 * Handle moving page order within branch without requiring full rebuild
		 *******************************************************************************/

		function swap($id1,$id2, $sql_conditions=null)
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
			
			$sql_where = array();
			$sql_where[] = sprintf("lft>=%u", $page2->fields['lft']);
			$sql_where[] = sprintf("rgt<=%u", $page2->fields['rgt']);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=lft-%u
						,rgt=rgt-%u
						,movetoken=%s
					WHERE
						%s
				"
					,$rem
					,$rem
					,$this->_db->Quote($token)
					,implode(" AND ", $sql_where)
				)
			);

			$sql_where = array();
			$sql_where[] = sprintf("lft>=%u", $page1->fields['lft']);
			$sql_where[] = sprintf("rgt<=%u", $page1->fields['rgt']);
			$sql_where[] = sprintf("movetoken!=%s", $this->_db->Quote($token));
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=lft+%u
						,rgt=rgt+%u
					WHERE
						%s
				"
					,$add
					,$add
					,implode(" AND ", $sql_where)
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
		 * Completely rebuild the DBTree structure from scratch
		 *******************************************************************************/

		function rebuildTree($parent_id,$lft, $sql_conditions=null)
		{
			$rgt=$lft+1;
			
			$sql_where = array();
			$sql_where[] = sprintf("parent_id=%u", $parent_id);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$result=$this->_db->Execute(
				sprintf("
					SELECT
						id
					FROM
						".$this->_table."
					WHERE
						%s
					ORDER BY
						ord
					ASC
				"
					,implode(" AND ", $sql_where)
				)
			);

			while($row=$result->FetchRow())
			{
				$rgt=$this->rebuildTree($row['id'],$rgt, $sql_conditions);
			}

			$sql_where = array();
			$sql_where[] = sprintf("id=%u", $parent_id);
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			
			$this->_db->Execute(
				sprintf("
					UPDATE
						".$this->_table."
					SET
						lft=%u
						,rgt=%u
					WHERE
						%s
				"
					,$lft
					,$rgt
					,implode(" AND ", $sql_where)
				)
			);

			return $rgt+1;
		}

		/********************************************************************************
		 * Make Tree
		 *
		 * Return a multi-dimensional representation of the tree
		 *******************************************************************************/

		function makeTree($sql_conditions=null)
		{
			$sql_where = array();
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			if(count($sql_where) == 0)
				$sql_where[] = '1';
					
			$this->_result=$this->_db->Execute(
				sprintf("
					SELECT
						id
						,parent_id
						,name
						,lft
						,rgt
					FROM
						".$this->_table."
					WHERE
						%s
					ORDER BY
						lft
					ASC
				"
					,implode(" AND ", $sql_where)
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

		function makeDTree($sql_conditions=null)
		{
			$count=0;
			$ret="a = new dTree('a');a.config.useCookies=true;\n";
			$ret.="a.add(0,-1,'Pages','index.php?fuseaction=admin.pages');\n";

			$sql_where = array();
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			if(count($sql_where) == 0)
				$sql_where[] = '1';
			
			$result=$this->_db->Execute(
				sprintf("
					SELECT
						id
						,parent_id
						,name
					FROM
						".$this->_table."
					WHERE
						%s
					ORDER BY
						ord
					ASC
				"
					,implode(" AND ", $sql_where)
				)
			);

			while($row=$result->FetchRow())
			{
				$ret.="a.add({$row['id']},{$row['parent_id']},'".addslashes($row['name'])."','index.php?fuseaction=admin.pages&parent_id={$row['id']}',true);\n";
			}
			$ret.="document.write(a);";
			return $ret;
		}

		/********************************************************************************
		 * Display Tree
		 *
		 * Return nested ordered lists representing the tree
		 *******************************************************************************/

		function displayTree($sql_conditions=null)
		{
			global $config;
			
			$sql_where = array();
			if(is_array($sql_conditions))
				foreach($sql_conditions as $condition)
					$sql_where[] = $condition;
			if(count($sql_where) == 0)
				$sql_where[] = '1';
			
			$result=$this->_db->Execute(
				sprintf("
					SELECT
						*
					FROM
						".$this->_table."
					WHERE
						%s
					ORDER BY
						lft
					ASC
				"
					,implode(" AND ", $sql_where)
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