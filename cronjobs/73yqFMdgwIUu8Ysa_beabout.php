<?php
if (isset($_GET['npp1yaIqgkvrXPkk']) or $argv[1] == "npp1yaIqgkvrXPkk") //valid cron job
{
	require "../classes/zomato.php";
	$foodSrc = new Zomato(true,"../","https://www.zomato.com/sk/bratislava/beabout-star%C3%A9-mesto-bratislava-i/menu"); 
	$foodSrc->getFood(); //download new data
}
?>