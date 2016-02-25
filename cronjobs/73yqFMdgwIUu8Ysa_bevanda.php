<?php
if (isset($_GET['npp1yaIqgkvrXPkk']) or $argv[1] == "npp1yaIqgkvrXPkk") //valid cron job
{
	require "../classes/bevanda.php";
	$foodSrc = new Bevanda(true,"../"); 
	$foodSrc->getFood(); //download new data
}
?>