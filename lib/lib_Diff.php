<?
	class Diff
	{
		var $_rev1;
		var $_rev2;
		var $_config;

		function Diff($rev1,$rev2,&$config)
		{
			$this->_rev1=$rev1;
			$this->_rev2=$rev2;
			$this->_config=$config;
		}

		function setRevision1($rev1id)
		{
			$this->_rev1id=$rev1id;
		}

		function setRevision2($rev2id)
		{
			$this->_rev2id=$rev2id;
		}

		/*
		 * Return formatted diff
		 */

		function format()
		{
			//Output temp files for diff program
			$rev1file=$this->_config['tmpdir'].time().md5($this->_rev1);
			$rev2file=$this->_config['tmpdir'].(time()+1).md5($this->_rev2);

			$this->_file_put_contents($rev1file,$this->_rev1);
			$this->_file_put_contents($rev2file,$this->_rev2);

			//Run diff and capture output
			$diff=`{$this->_config['prog']['diff']} -E -b {$rev1file} {$rev2file}`;

			//Delete temp files
			unlink($rev1file);
			unlink($rev2file);

			//Parse diff and start formatting
			$diff=$this->_parse_diff($diff);

			$source=explode("\n",ereg_replace("(</*p>)","\\1&para; ",$this->_rev1));
			$ret="<table class=\"diff\" cellspacing=\"0\">\n";

			//First line, header
			$ret.="\t<tr>\n"
					."\t\t<th width=\"1\">Rev&nbsp;".$this->_rev1id."</th>\n"
					."\t\t<th width=\"1\">Rev&nbsp;".$this->_rev2id."</th>\n"
					."\t\t<th>&nbsp;</th>\n"
				."\t</tr>\n";

			$rev1c=0;
			$rev2c=0;

			//content up to first change
			if($diff[0]["old"]["start"]>1)
				for($i=0;$i<$diff[0]["old"]["start"]-1;$i++)
					$ret.=$this->_row($rev1c,$rev2c,$source[$i],"unchanged");
			for($j=0;$j<count($diff);$j++)
			{
				//Change
				if($diff[$j]["type"]=="c")
				{
					$before=explode("\n",ereg_replace("(</*p>)","\\1&para; ",trim($diff[$j]["content"][0])));
					$after=explode("\n",ereg_replace("(</*p>)","\\1&para; ",trim($diff[$j]["content"][1])));
					foreach($before as $line)
						$ret.=$this->_row_removed($rev1c,$line);
					foreach($after as $line)
						$ret.=$this->_row_added($rev2c,$line);
				}
				//Addition
				else if($diff[$j]["type"]=="a")
				{
					if($rev1c>0)
						$ret.=$this->_row($rev1c,$rev2c,$source[$rev1c]);
					$add=explode("\n",ereg_replace("(</*p>)","\\1&para; ",trim($diff[$j]["content"])));
					foreach($add as $line)
						$ret.=$this->_row_added($rev2c,$line);
				}
				//Show removal
				else if($diff[$j]["type"]=="d")
				{
					for($i=$diff[$j]["old"]["start"]-1;$i<$diff[$j]["old"]["end"];$i++)
						$ret.=$this->_row_removed($rev1c,$source[$i]);
				}

				//Output content until next change
				for($i=$diff[$j]["old"]["end"];$i<$diff[$j+1]["old"]["start"]-1;$i++)
					$ret.=$this->_row($rev1c,$rev2c,$source[$i]);
			}
			//Output from last change to the end

			for($i=$diff[$j-1]["old"]["end"];$i<count($source);$i++)
				$ret.=$this->_row($rev1c,$rev2c,$source[$i]);

			$ret.="</table>";
			return $ret;
		}

		function _file_put_contents($filename,$data)
		{
			$data=str_replace("&nbsp;","",$data);
			$fp=fopen($filename,"w");
			fwrite($fp,$data."\n");
			fclose($fp);
		}

		/*
		 *Parse diff into internal array representation
		 */

		function _parse_diff($diff)
		{
			$diff=explode("\n",$diff);
			$count=-1;
			for($i=0;$i<count($diff);$i++)
			{
				//Check if start of diff block (range command range) or last line in diff
				if(ereg("^([0-9]*),*([0-9]*)([acd])([0-9]*),*([0-9]*)$",trim($diff[$i]),$regs) || $i==count($diff)-1)
				{
					//If this isn't the first diff block, add the statements to the array
					if($count>-1)
					{
						if($diffarr[$count]["type"]=="c")
							$diffarr[$count]["content"]=explode("---",$content);
						else if($diffarr[$count]["type"]=="a")
							$diffarr[$count]["content"]=trim($content);
					}

					if($i==count($diff)-1)
						continue;

					//Initialise the next diff block
					$count++;
					$content="";
					$diffarr[$count]["type"]=$regs[3];
					$diffarr[$count]["old"]["start"]=$regs[1];
					$diffarr[$count]["old"]["end"]=($regs[2]=="") ? $regs[1] : $regs[2];
					$diffarr[$count]["new"]["start"]=$regs[4];
					$diffarr[$count]["new"]["end"]=($regs[5]=="") ? $regs[4] : $regs[5];
				}
				//If not then append line to current diff statement
				else if($diffarr[$count]["type"]=="c" || $diffarr[$count]["type"]=="a")
				{
					if($diff[$i]!="---")
						$content.=substr($diff[$i],2)."\n";
					else
						$content.=$diff[$i];
				}
			}
			return $diffarr;
		}

		function _strip_tags($data)
		{
			$data=ereg_replace("<(/*)h[0-9]>","<\\1strong>",$data);
			$data=str_replace("<li>","___* ",$data);
			$data=ereg_replace("<a[^>]*href=\"([^\"]*)\"[^>]*>","<strong>[link url=\"\\1\"]</strong>",$data);
			$data=str_replace("</a>","<strong>[/link]</strong>",$data);
			$data=ereg_replace("<img[^>]*>","[image]",$data);
			$data=strip_tags($data,'<i><em><strong>');
			return $data;
		}

		/*
		 * Return row
		 */

		function _row_blank()
		{
			return "<tr>\n"
					."\t<th>&nbsp;</th>"
					."\t<th>&nbsp;</th>"
					."\t<td>&nbsp;</td>\n"
					."</tr>\n";
		}

		function _row_blank_changed($class)
		{
			return "<tr>\n"
					."\t<th>&nbsp;</th>"
					."\t<th>&nbsp;</th>"
					."\t<td class=\"$class\">&nbsp;</td>\n"
					."</tr>\n";
		}

		function _row(&$c1,&$c2,$data)
		{
			$c1++;
			$c2++;
			$ret="<tr>\n"
					."\t<th>$c1</th>\n"
					."\t<th>$c2</th>\n"
					."\t<td><code>".$this->_strip_tags($data)."</code></td>\n"
					."</tr>\n";
			if(stristr($data,"&para;"))
				$ret.=$this->_row_blank();
			return $ret;
		}

		/*
		 * Return added row
		 */

		function _row_added(&$c2,$data)
		{
			$c2++;
			$ret="<tr>\n"
					."\t<th>&nbsp;</th>\n"
					."\t<th>$c2</th>\n"
					."\t<td class=\"added\"><code>=> ".$this->_strip_tags($data)."</code></td>\n"
					."</tr>\n";
			if(stristr($data,"&para;"))
				$ret.=$this->_row_blank_changed("added");
			return $ret;
		}

		/*
		 * Return removed row
		 */

		function _row_removed(&$c1,$data)
		{
			$c1++;
			$ret="<tr>\n"
					."\t<th>$c1</th>\n"
					."\t<th>&nbsp;</th>\n"
					."\t<td class=\"removed\"><code><= ".$this->_strip_tags($data)."</code></td>\n"
					."</tr>\n";
			if(stristr($data,"&para;"))
				$ret.=$this->_row_blank_changed("removed");
			return $ret;
		}
	}
?>