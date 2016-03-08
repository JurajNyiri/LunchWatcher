<?php
if (isset($_GET['npp1yaIqgkvrXPkk']) or $argv[1] == "npp1yaIqgkvrXPkk") //valid cron job
{
	require "../classes/zomato.php";
	require "../config.php";
	$execLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$execLink = substr($execLink, 0, strrpos($execLink, '/'))."/73yqFMdgwIUu8Ysa_getZomato.php?npp1yaIqgkvrXPkk";

	foreach($config['zomato_supported'] as $name => $zomato_supported)
	{
		get_headers($execLink . "&restaurant=".$name);
	}

	
}
?>