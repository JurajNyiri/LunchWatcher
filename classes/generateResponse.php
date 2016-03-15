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
    	if($this->arguments[0] == "!joke")
    	{
    		if($this->arguments[1] == "lamer")
			{
				$ch = curl_init();
				$timeout = 10;
				curl_setopt($ch, CURLOPT_URL, "http://www.lamer.cz/quote/random");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$data = curl_exec($ch);
				curl_close($ch);

				$dom = new DOMDocument('1.0', 'utf-8');
	            @$dom->loadHTML($data);
	            $xpath = new DOMXPath($dom);

	            $query = "//div[@id='quotes']/div[@class='quote first']/p[@class='text']";
				$answer->text = "";
	            $entries = $xpath->query($query);
	            
	            foreach ($entries as $node) {
	            	$answer->text = $node->nodeValue;
	            }
	            include "funny_animals.php";
	            $randomAnimal = rand(0,(count($funny_animals)-1));
	            $answer->username = $funny_animals[$randomAnimal]["name"];
	            $answer->icon_url = $funny_animals[$randomAnimal]["image"];
			}
			else if($this->arguments[1] == "random")
			{
				$ch = curl_init();
				$timeout = 10;
				curl_setopt($ch, CURLOPT_URL, "http://www.srandy.sk/index.php/component/vtipy/random");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$data = curl_exec($ch);
				curl_close($ch);

				$dom = new DOMDocument('1.0', 'utf-8');
	            @$dom->loadHTML($data);
	            $xpath = new DOMXPath($dom);

	            $query = "//div[@id='obraz_stred']/div/table/tr[2]/td";
				$answer->text = "";
	            $entries = $xpath->query($query);
	            foreach ($entries as $node) {
	            	$answer->text = $node->nodeValue;
	            }
	            include "funny_animals.php";
	            $randomAnimal = rand(0,(count($funny_animals)-1));
	            $answer->username = $funny_animals[$randomAnimal]["name"];
	            $answer->icon_url = $funny_animals[$randomAnimal]["image"];
			}
				///http://www.srandy.sk/index.php/component/vtipy/random
    		//http://www.nejvtipy.cz/nahodne-vtipy_sk
    		
    	}
    	else if((count($this->arguments) > 1) && $this->arguments[0] == "!lunch")
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

								if(isset($weekMeals->dayFood[1]) && !empty($weekMeals->dayFood[1]))
								{
									$answer->text .= PHP_EOL.PHP_EOL . "*​Daily meals*" . PHP_EOL;
									foreach($weekMeals->dayFood[1] as $meal)
									{
										$answer->text .= $meal->size . " - " . $meal->name . " - " . $meal->price . PHP_EOL;
									}
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