<?php
	require "classes/kolkovna.php";
	$kolkovna = new Kolkovna();

	$answer = new stdClass();
	$answer->text = "";

	$weekDays = array("monday","tuesday","wednesday","thursday","friday");

	$weekMeals = $kolkovna->getFood();
	$currentDay = date( "w", time());
	if(isset($_GET['api']))
	{
		echo json_encode($weekMeals);
	}
	else
	{
		if($currentDay > 0 && $currentDay < 6)
		{
			$answer->text .= "*Lunch in Kolkovna for " . $weekDays[$currentDay-1] . ":*".PHP_EOL;
			foreach($weekMeals->dayFood[$currentDay-1] as $meal)
			{
				$answer->text .= $meal->size . " - " . $meal->name . " - " . $meal->price . PHP_EOL;
			}
		}
		else
		{
			$answer->text .= "*Kolkovna is closed today.*";
		}
	}
?>