<?php
$answer->text = "*Lunch Watcher Bot ".$config['version']."*".PHP_EOL.PHP_EOL;
$answer->text .= "*Usage:*".PHP_EOL;
$answer->text .= "!lunch *help* - shows usage".PHP_EOL.PHP_EOL;

$answer->text .= "!lunch *bevanda* - shows daily menu for Bevanda restaurant".PHP_EOL;
$answer->text .= "!lunch *kolkovna* - shows daily menu for Kolkovna restaurant".PHP_EOL;

foreach($config['zomato_supported'] as $name => $zomato_supported)
{
	$answer->text .= "!lunch *".$name."* - shows daily menu for ".ucfirst($name)." restaurant".PHP_EOL;
}

$answer->text .= PHP_EOL."!lunch *about* - shows author and licencing information";
?>