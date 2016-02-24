<?php
if($_POST['token'] == "kcEEqgzLBnJ4w2ZqMrWLnLRT" or $_POST['token'] == "c1OlXbdq0sWVgsA1nBGpChph")
{
	$answer = new stdClass();
	$arguments = explode(" ", $_POST['text']);
	if(count($arguments) > 1)
	{
		if(strtoupper($arguments[1]) == "BEVANDA")
		{
			require "arguments/bevanda.php";
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
?>