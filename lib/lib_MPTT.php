<?
	if(defined('LIB_MPTT'))
		return;
		
	define("LIB_MPTT", 1);
	
	require_once('lib_DBTree.php');
	
	class MPTT extends DBTree
	{
		var $_siteid;

		function MPTT(&$db,$table,$siteid)
		{
			parent::DBTree($db, $table);
			$this->_siteid=$siteid;
		}

		/********************************************************************************
		 * Add Page
		 *
		 * Adds a node into a MPTT tree structure
		 *******************************************************************************/

		function addPage($id,$parent_id)
		{
			parent::addPage(
				$id
				,$parent_id
				,array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
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
			parent::removePage(
				$id
				,array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
				)
			);
		}

		/********************************************************************************
		 * Move up/down
		 *
		 * Handle moving page order within branch without requiring full rebuild
		 *******************************************************************************/

		function swap($id1,$id2)
		{
			parent::swap(
				$id1
				,$id2
				,array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
				)
			);
		}

		/********************************************************************************
		 * Rebuild Tree
		 *
		 * Completely rebuild the MPTT structure from scratch
		 *******************************************************************************/

		function rebuildTree($parent_id,$lft)
		{
			return parent::rebuildTree(
				$parent_id
				,$lft
				,array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
				)
			);
		}

		/********************************************************************************
		 * Make Tree
		 *
		 * Return a multi-dimensional representation of the tree
		 *******************************************************************************/

		function makeTree()
		{
			return parent::makeTree(
				array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
				)
			);
		}

		/********************************************************************************
		 * Make DTree
		 *
		 * Return javascript code to instantiate and draw a dhtml tree
		 *******************************************************************************/

		function makeDTree()
		{
			return parent::makeDTree(
				array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
				)
			);
		}

		/********************************************************************************
		 * Display Tree
		 *
		 * Return nested ordered lists representing the tree
		 *******************************************************************************/

		function displayTree()
		{
			return parent::displayTree(
				array(
					sprintf("siteid=%u", $this->_siteid)
					,sprintf("deleted=0")
				)
			);
		}
	}
?>