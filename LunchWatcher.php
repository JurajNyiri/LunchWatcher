<?php
//if($_POST['token'] == "kcEEqgzLBnJ4w2ZqMrWLnLRT" or $_POST['token'] == "c1OlXbdq0sWVgsA1nBGpChph")
//{
$text = (isset($_POST['text']) ? $_POST['text'] : $_GET['text']);
if(!empty($text))
{
	require "config.php";
	require "classes/generateResponse.php";
	$generateResponse = new generateResponse($config,$text,"");
	$answer = $generateResponse->getResponse();
	echo json_encode($answer);
}
	
//}
?>