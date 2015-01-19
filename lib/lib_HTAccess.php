<?
	class HTAccess
	{
		var $_config;
		var $_path;
		var $_content;

		function HTAccess(&$config,$path)
		{
			$this->_config&=$config;
			$this->_path=$path;
			$this->_load();
		}

		function changeDir($dir)
		{
			$count=count($this->_content);
			for($i=0;$i<$count;$i++)
			{
				if($this->_content[$i]=="RewriteEngine On")
				{
					//Do nothing
				}
				else if(ereg("^RewriteBase.*$",$this->_content[$i]))
					$this->_content[$i]="RewriteBase ".$dir;
				else
					$this->_content[$i]=ereg_replace("^(RewriteRule \\^[^\\$]*\\$ ).*(index.php\\?fuseaction=[a-zA-Z0-9].[a-zA-Z0-9].*)$","\\1".$dir."\\2",$this->_content[$i]);
			}
		}

		function addPage($trail,$pageid)
		{
			$this->_content[]="RewriteRule ^".implode("/",$trail)."$ index.php?fuseaction=home.content&pageid=".$pageid;
		}

		function removePage($pageid)
		{
			$count=count($this->_content);
			for($i=0;$i<$count;$i++)
			{
				if(ereg("^.*pageid=".$pageid."[^0-9]*$",$this->_content[$i]))
				{
					$this->_content[$i]="";
					unset($this->_content[$i]);
					break;
				}
			}
		}

		function updatePage($trail,$pageid)
		{
			$count=count($this->_content);
			for($i=0;$i<$count;$i++)
			{
				if(ereg("^.*pageid=".$pageid."([^0-9]*)$", $this->_content[$i], $regs))
				{
					$this->_content[$i]="RewriteRule ^".implode("/",$trail)."$ index.php?fuseaction=home.content&pageid=".$pageid.$regs[1];
					break;
				}
			}
		}

		function save()
		{
			$fp=fopen($this->_path."/.htaccess","w");
			fwrite($fp,trim(implode("\n",$this->_content)));
			fclose($fp);
		}

		function _load()
		{
			$this->_content=explode("\n",str_replace("\r","",file_get_contents($this->_path."/.htaccess")));
		}
	}
?>
