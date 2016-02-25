<?php
class generateResponse {
	private $arguments;
	private $path;
	private $config;
    public function __construct($config,$text,$path)
    {
		$this->arguments = explode(" ", $text);
		$this->config = $config;
    }
    public function getResponse()
    {
    	$answer = new stdClass();
    	$config = $this->config;
    	if(count($this->arguments) > 1)
		{
			if(strtoupper($this->arguments[1]) == "BEVANDA")
			{
				require $this->path."arguments/bevanda.php";
			}
			elseif(strtoupper($this->arguments[1]) == "KOLKOVNA")
			{
				require $this->path."arguments/kolkovna.php";
			}
			elseif(strtoupper($this->arguments[1]) == "ABOUT")
			{
				require $this->path."arguments/about.php";
			}
			else
			{
				require $this->path."arguments/usage.php";
			}
		}
		else
		{
			require $this->path."arguments/usage.php";
		}
		return $answer;
    }
}