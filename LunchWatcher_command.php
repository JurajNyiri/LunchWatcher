<?php
ignore_user_abort(true);
set_time_limit(0);

ob_start();
// do initial processing here
echo ""; // send the response
header('Connection: close');
header('Content-Length: '.ob_get_length());
ob_end_flush();
ob_flush();
flush();


$token = (isset($_POST['token']) ? $_POST['token'] : $_GET['token']);
$text = "/lunch " . (isset($_POST['text']) ? $_POST['text'] : $_GET['text']);

if($token == "hBfo4Y55dsIBd0QHDd88eUxu")
{
	require "config.php";
	require "classes/generateResponse.php";
	$generateResponse = new generateResponse($config,$text,"");
	$answer = $generateResponse->getResponse()->text;

	if(isset($_POST['response_url']))
	{
		$url = $_POST['response_url'];

		$data = "payload=" . json_encode(array(
	                "text"          =>  $answer
	            ));
		
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	   	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		curl_exec($ch);


		//close connection
		curl_close($ch);
	}
}
?>