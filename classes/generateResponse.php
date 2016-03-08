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
				$foundInZomatoSupported = false;
				foreach($config['zomato_supported'] as $name => $zomato_supported)
				{
					if(strtoupper($this->arguments[1]) == strtoupper($name))
					{
						$foundInZomatoSupported = true;
						require $this->path."classes/zomato.php";
						$zomato = new Zomato(false,"",$zomato_supported, $config);

						$answer = new stdClass();
						$answer->text = "";

						$currentDay = date( "w", time());

						$weekMeals = $zomato->getFood(0,$link);
						if(isset($_GET['api']))
						{
							echo json_encode($weekMeals);
							die();
						}
						else
						{
							if($currentDay > 0 && $currentDay < 6)
							{
								$answer->text .= "*Lunch for " . strtolower(date("l",time())) . ":*".PHP_EOL;
								foreach($weekMeals->dayFood[0] as $meal)
								{
									$answer->text .= $meal->size . " - " . $meal->name . " - " . $meal->price . PHP_EOL;
								}

								$answer->text .= PHP_EOL.PHP_EOL . "*​Daily meals*" . PHP_EOL;
								foreach($weekMeals->dayFood[1] as $meal)
								{
									$answer->text .= $meal->size . " - " . $meal->name . " - " . $meal->price . PHP_EOL;
								}
							}
							else
							{
								$answer->text .= "*This is closed today.*";
							}
						}
						break;
					}
				}
				if(!$foundInZomatoSupported)
				{
					require $this->path."arguments/usage.php";
				}
			}
		}
		else
		{
			require $this->path."arguments/usage.php";
		}
		return $answer;
    }
}