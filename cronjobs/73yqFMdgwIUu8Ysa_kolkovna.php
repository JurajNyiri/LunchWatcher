<?php
if (isset($_GET['npp1yaIqgkvrXPkk']) or $argv[1] == "npp1yaIqgkvrXPkk") //valid cron job
{
	require "../classes/kolkovna.php";
	$foodSrc = new Kolkovna(true,"../");
	$foodSrc->getFood(); //download new data
}
?>