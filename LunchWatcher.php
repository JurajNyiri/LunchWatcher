<?php
//if($_POST['token'] == "kcEEqgzLBnJ4w2ZqMrWLnLRT" or $_POST['token'] == "c1OlXbdq0sWVgsA1nBGpChph")
//{
$text = (isset($_POST['text']) ? $_POST['text'] : $_GET['text']);
if(!empty($text))
{
	require "config.php";
	$answer = new stdClass();
	$arguments = explode(" ", $text);
	if(count($arguments) > 1)
	{
		if(strtoupper($arguments[1]) == "BEVANDA")
		{
			require "arguments/bevanda.php";
		}
		elseif(strtoupper($arguments[1]) == "KOLKOVNA")
		{
			require "arguments/kolkovna.php";
		}
		elseif(strtoupper($arguments[1]) == "ABOUT")
		{
			require "arguments/about.php";
		}
		else
		{
			require "arguments/usage.php";
		}
	}
	else
	{
		require "arguments/usage.php";
	}
	echo json_encode($answer);
}
	
//}
?>