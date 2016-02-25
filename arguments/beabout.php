<?php
	require "classes/zomato.php";
	$zomato = new Zomato(false,"","https://www.zomato.com/sk/bratislava/beabout-star%C3%A9-mesto-bratislava-i/menu#daily");

	$answer = new stdClass();
	$answer->text = "";

	$currentDay = date( "w", time());

	$weekMeals = $zomato->getFood();
	if(isset($_GET['api']))
	{
		echo json_encode($weekMeals);
	}
	else
	{
		if($currentDay > 0 && $currentDay < 6)
		{
			$answer->text .= "*Lunch in BeAbout for " . strtolower(date("l",time())) . ":*".PHP_EOL;
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
			$answer->text .= "*BeAbout is closed today.*";
		}
	}
?>