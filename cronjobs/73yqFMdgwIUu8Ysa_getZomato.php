<?php
ob_end_clean();
ignore_user_abort();
ob_start();
header("Connection: close");
header("Content-Length: " . ob_get_length());
ob_end_flush();
flush();
if ((isset($_GET['restaurant'])) && (isset($_GET['npp1yaIqgkvrXPkk']) or $argv[1] == "npp1yaIqgkvrXPkk")) //valid cron job
{
	require "../classes/zomato.php";
	require "../config.php";
	foreach($config['zomato_supported'] as $name => $zomato_supported)
	{
		if(strtoupper($_GET['restaurant']) == strtoupper($name))
		{
			$foodSrc = new Zomato(true,"../",$zomato_supported,$config);
			$foodSrc->getFood(); //download new data
			break;
		}
	}
}
exit;
?>