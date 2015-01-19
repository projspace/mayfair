<?
	class Placeholder
	{
		protected $content = array();
        protected $lock = false;

		function captureStart()
		{
            if($this->lock)
                throw new Exception('Cannot nest placeholder captures for the same placeholder');
            $this->lock = true;
			ob_start();
		}

		function captureEnd()
		{
			$this->content[] = ob_get_clean();
            $this->lock = false;
		}

        function getContent()
        {
            return implode("\n", $this->content);
        }

        function addContent($content)
        {
            $this->content[] = $content;
        }
	}
?>