<?php
	require "classes/bevanda.php";
	$bevanda = new Bevanda();

	$answer = new stdClass();
	$answer->text = "";

	$weekDays = array("Daily meals","monday","tuesday","wednesday","thursday","friday");

	$weekMeals = $bevanda->getFood();
	$currentDay = date( "w", time());
	if(isset($_GET['api']))
	{
		echo json_encode($weekMeals);
	}
	else
	{
		if($currentDay > 0 && $currentDay < 6)
		{
			$answer->text .= "*Lunch in Bevanda for " . $weekDays[$currentDay] . ":*".PHP_EOL;
			foreach($weekMeals->dayFood[$currentDay] as $meal)
			{
				$answer->text .= $meal->size . " - " . $meal->name . " - " . $meal->price . PHP_EOL;
			}

			$answer->text .= PHP_EOL.PHP_EOL . "*" . $weekDays[0] . "*" . PHP_EOL;
			foreach($weekMeals->dayFood[0] as $meal)
			{
				$answer->text .= $meal->size . " - " . $meal->name . " - " . $meal->price . PHP_EOL;
			}
		}
		else
		{
			$answer->text .= "*Bevanda is closed today.*";
		}
	}
?>