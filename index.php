<?php
require "classes/zomato.php";
$zomato = new Zomato(true,"","https://www.zomato.com/sk/bratislava/brasserie-anjou-star%C3%A9-mesto-bratislava-i/menu");

$weekMeals = $zomato->getFood();
print_r($weekMeals);
die("index die");
$currentDay = date( "w", time());
if(isset($_GET['api']))
{
	echo json_encode($weekMeals);
}
else
{
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>LunchWatcher - <?php echo $weekMeals->day[$currentDay]; ?></title>
	</head>

	<body>
		<?php
		if($currentDay > 0 && $currentDay < 6)
		{
			echo "<h1>" . $weekMeals->day[$currentDay] . "</h1>";
			echo "<table><tr><th>Size</th><th>Name</th><th>Price</th></tr>";
			foreach($weekMeals->dayFood[$currentDay] as $meal)
			{
				echo "<tr><td>" . $meal->size . "</td><td>" . $meal->name . "</td><td>" . $meal->price . "</td></tr>";
			}
			echo "</table>";

			echo "<h1>" . $weekMeals->day[0] . "</h1>";
			echo "<table><tr><th>Size</th><th>Name</th><th>Price</th></tr>";
			foreach($weekMeals->dayFood[0] as $meal)
			{
				echo "<tr><td>" . $meal->size . "</td><td>" . $meal->name . "</td><td>" . $meal->price . "</td></tr>";
			}
			echo "</table>";
		}
		else
		{
			echo "It is closed today :(";
		}
	?>
	</body>

	</html>
	<?php
}




?>