<?php
class Zomato { 
	private $link;
	private $saveLocation = "data/";
	private $online = false;
	private $path = "";
    public function __construct($online = false,$path = "",$link)
    {
    	$this->link = $link;
    	$this->saveLocation .= "zomato_" . md5($link);
    	$this->online = $online;
    	$this->path = $path;
    }

    private function processZomatoItem($str)
    {
    	$finalItemData = new stdClass();
    	$itemData = explode(" ", trim(preg_replace('/[ ]{2,}|[\t]/', ' ', $str)));

    	//remove weird chars from zomato
    	for($i = 0; $i < count($itemData); $i++)
    	{
    		if(empty($itemData[$i]) or trim(str_replace("<br />","",nl2br($itemData[$i]))) == "")
    		{
    			unset($itemData[$i]);
    		}
    		else
    		{
    			$itemData[$i] = trim(str_replace("<br />","",nl2br($itemData[$i])));
    		}

    	}
    	
    	$itemData = array_values($itemData);
    	$itemDataC = count($itemData);
    	//remove weird chars from zomato
    	if(strlen($itemData[($itemDataC-2)]) == 1 && empty(preg_replace("/[^a-zA-Z0-9\/,.\-]+/", "", $itemData[($itemDataC-2)]))) 
    	{
    		unset($itemData[($itemDataC-2)]);
    	}
    	$itemData = array_values($itemData);
    	$itemDataC = count($itemData);

    	$finalItemData->name = "";
		if(strpos($itemData[$itemDataC-1],"€") !== false)
	    {
	    	$finalItemData->price = $itemData[$itemDataC-1];
	    	$finalItemData->size = $itemData[$itemDataC-2];
	    	for($i = 0; $i < $itemDataC-2; $i++)
	    	{
				$finalItemData->name .= $itemData[$i] . " ";
	    	}
	    }
	    else
	    {
	    	$finalItemData->price = "0 €";
	    	$finalItemData->size = $itemData[$itemDataC-1];
	    	for($i = 0; $i < $itemDataC-1; $i++)
	    	{
				$finalItemData->name .= $itemData[$i] . " ";
	    	}
	    }
	    $finalItemData->name = trim($finalItemData->name);
	    $finalItemData->alergens = "";

	    return $finalItemData;
    }

    public function getFood($tries = 0)
    {
    	$currentDay = date("w", time());
    	if($this->online)
    	{
    		$data = new stdClass();
	    	$data->day = array();
	    	$data->dayFood = array();
	    	$pageData = @file_get_contents($this->link);
	    	if($pageData !== false)
	    	{
	    		
	    		$dom = new DOMDocument('1.0', 'utf-8');
	            @$dom->loadHTML($pageData);
	            $xpath = new DOMXPath($dom);


	            $query = "//div[@id='menu-preview']/div[@class='tmi-groups']/div[@class='tmi-group ']/div[@class='tmi-group-name']";
	            $entries = $xpath->query($query);
	            
	            foreach ($entries as $node) {
	            	array_push($data->day,$node->nodeValue);
	            	break;
	            }

	            $query = "//div[@id='menu-preview']/div[@class='tmi-groups']/div[@class='tmi-group tmi-group-long ']/div[@class='tmi-menu-name  bold ']";
	            $entries = $xpath->query($query);
	            
	            foreach ($entries as $node) {
	            	array_push($data->day,trim(str_replace("<br />","",nl2br(trim(preg_replace('/[ ]{2,}|[\t]/', ' ', $node->nodeValue))))));
	            	break;
	            }

	            $query = "//div[@id='menu-preview']/div[@class='tmi-groups']/div[@class='tmi-group '][1]/div[@class='tmi tmi-daily  ']";
	            $entries = $xpath->query($query);
	            $items = array();
	            $items[0] = array();
	            foreach ($entries as $node) {
	            	$finalItemData = $this->processZomatoItem($node->nodeValue);
					array_push($items[0],$finalItemData);
	            }


	            $query = "//div[@id='menu-preview']/div[@class='tmi-groups']/div[@class='tmi-group tmi-group-long ']/div[@class='tmi tmi-long  ']";
	            $entries = $xpath->query($query);
	            $items[1] = array();
	            foreach ($entries as $node) {
	            	$finalItemData = $this->processZomatoItem($node->nodeValue);
					array_push($items[1],$finalItemData);
	            }
	            $data->dayFood = $items;
	            file_put_contents($this->path.$this->saveLocation, json_encode($data));
	    	}
	    	else
	    	{
	    		if($tries < 10)
	    		{
	    			sleep(5);
	    			$this->getFood();
	    		}
	    	}
    	}
    	else
    	{
    		$data = json_decode(file_get_contents($this->path.$this->saveLocation));
    	}
    	
    	return $data;
    }
}
?>