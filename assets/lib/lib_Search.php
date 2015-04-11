<?php
	class Search
	{
		var $_config;
		var $_index;
		
		function Search(&$config)
		{
			$this->_config = &$config;			
			$dir=getcwd();
			chdir($config['path']);
			include("Zend/Search/Lucene.php");
			chdir($dir);
			
			//$this->_index=Zend_Search_Lucene::create($this->_config['path']."cache/search");
			$this->_index=Zend_Search_Lucene::open($this->_config['path']."cache/search");
			
			Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_TextNum_CaseInsensitive());
		}
		
		function add($type, $key_id, $title, $content, $fields=false)
		{		
			$doc=new Zend_Search_Lucene_Document();
			
			$doc->addField(Zend_Search_Lucene_Field::Keyword('type',$type));
			$doc->addField(Zend_Search_Lucene_Field::Keyword('key_id',$key_id));
			$doc->addField(Zend_Search_Lucene_Field::Keyword('search_key',$type.$key_id));
			
			if($fields!==false && is_array($fields))
			{
				foreach($fields as $key=>$value)
				{
					$doc->addField(Zend_Search_Lucene_Field::Text($key,$value));
				}
			}
			
			$doc->addField(Zend_Search_Lucene_Field::Text('title',$title));

			$doc->addField(Zend_Search_Lucene_Field::Unstored('contents',$content));
			
			$doc->addField(Zend_Search_Lucene_Field::UnIndexed('abridged',$this->_abridge($content)));

			$this->_index->addDocument($doc);
		}
		
		function _abridge($content)
		{
			$content=trim($content);
			$content=str_replace("\n"," ",$content);
			$content=str_replace("\r"," ",$content);
			$content=str_replace("\t"," ",$content);
			while(strstr($content,"  "))
				$content=str_replace("  "," ",$content);
			$content=mb_ereg_replace("[^a-zA-Z0-9,\\.?!@\"';: ]*","",$content);
			while(strstr($content,"  "))
				$content=str_replace("  "," ",$content);
			return substr($content,0,500);
		}
		
		function update($type, $key_id, $title, $content, $fields=false)
		{
			$this->remove($type,$key_id);
			$this->add($type, $key_id, $title, $content, $fields);
		}
		
		function remove($type,$key_id)
		{
			$term=new Zend_Search_Lucene_Index_Term($type.$key_id,'search_key');  
			$query=new Zend_Search_Lucene_Search_Query_Term($term);
			$hits=array();  
			$hits=$this->_index->find($query);
			
			foreach($hits as $hit)   
			{    
				$this->_index->delete($hit->id);
			}  
			$this->_index->commit();
		}
		
		function find($term,$operand_or=false)
		{
			$term = preg_replace("/[^a-zA-Z0-9s ]/", " ", $term); // remove non-alphanumeric and non-space characters
			
			$term = explode(' ', $term);
			foreach($term as $index=>$value)
				if(trim($value) == '' || strtolower(trim($value)) == 'and' || strtolower(trim($value)) == 'or')
					unset($term[$index]);
					
			if($operand_or)
				$term = implode(' OR ', $term);
			else
				$term = implode(' AND ', $term);
				
			Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
			$query=Zend_Search_Lucene_Search_QueryParser::parse($term);
			return $this->_index->find($query);
		}
		
		function get($type,$key_id)
		{
			$term=new Zend_Search_Lucene_Index_Term($type.$key_id,'search_key');  
			$query=new Zend_Search_Lucene_Search_Query_Term($term);
			return $this->_index->find($query);
		}
	}
?>
