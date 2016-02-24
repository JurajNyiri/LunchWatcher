<?php
class Kolkovna { 
	private $link = "http://www.kolkovna.cz/sk/kolkovna-eurovea-19/denne-menu";
	private $saveLocation = "data/kolkovna";
    public function __construct()
    {
    }


    public function getFood()
    {
    	$currentDay = date("w", time());
    	if(!file_exists($this->saveLocation) or ($currentDay !== date("w",filemtime($this->saveLocation))))
    	{ 
    		// download only once a day maximum
    		$data = new stdClass();
	    	$data->day = array();
	    	$data->dayFood = array();
	    	$pageData = @file_get_contents($this->link);
	    	if($pageData !== false)
	    	{
	    		$dom = new DOMDocument('1.0', 'utf-8');
	            @$dom->loadHTML($pageData);
	            $xpath = new DOMXPath($dom);

	            $query = "//div[@class='dailyMenuWeek']/section/h2";
	            $entries = $xpath->query($query);
	            
	            foreach ($entries as $node) {
	            	array_push($data->day,$node->nodeValue);
	            }

	            
	            $i = -1; //monday is the 0
	            $nextDayIncoming = "Polievka";

	            $items = array();

	            $query = "//div[@class='dailyMenuWeek']/section/table/tr/td[1]";
	            $entries = $xpath->query($query);
	            foreach ($entries as $node) 
	            {
	            	//this needs to be inside the cycle
	            	$itemData = new stdClass();
		            $itemData->name = "";
		            $itemData->alergens = "";
		            $itemData->size = "";
		            $itemData->price = "";
	            	if($node->nodeValue == $nextDayIncoming)
	            	{
	            		$i++;
	            	}
	            	if(!isset($items[$i]))
	            	{
	            		$items[$i] = array();
	            	}
	            	array_push($items[$i],$itemData);
	            }

	            $query = "//div[@class='dailyMenuWeek']/section/table/tr/td[2]";
	            $entries = $xpath->query($query);
	            $i = 0;
	            $j = 0;
	            foreach ($entries as $node) 
	            {
	            	if(!isset($items[$i][$j]))
	            	{
	            		$j = 0;
	            		$i++;
	            	}
	            	$itemData = explode(" ", $node->nodeValue);
	            	$itemDataC = count($itemData);
	            	$items[$i][$j]->size = $itemData[0];

					if(preg_match('/^[0-9 ,()]/', $itemData[$itemDataC-1]))
					{
						$items[$i][$j]->alergens = str_replace(")","",str_replace("(","",$itemData[$itemDataC-1]));
						for($k = 1; $k < ($itemDataC-1); $k++)
    					{
    						$items[$i][$j]->name .= $itemData[$k] . " ";
    					}
    					$items[$i][$j]->name = trim($items[$i][$j]->name);
					}
					else
					{
						$items[$i][$j]->alergens = false;
						for($k = 1; $k < ($itemDataC); $k++)
    					{
    						$items[$i][$j]->name .= $itemData[$k] . " ";
    					}
    					$items[$i][$j]->name = trim($items[$i][$j]->name);
					}
	            	$j++;
	            }

	            $query = "//div[@class='dailyMenuWeek']/section/table/tr/td[3]";
	            $entries = $xpath->query($query);
	            $i = 0;
	            $j = 0;
	            foreach ($entries as $node) 
	            {
	            	if(!isset($items[$i][$j]))
	            	{
	            		$j = 0;
	            		$i++;
	            	}
	            	$items[$i][$j]->price = trim($node->nodeValue);
	            	$j++;
	            }
	            $data->dayFood = $items;
	            file_put_contents($this->saveLocation, json_encode($data));
	    	}
    	}
    	else
    	{
    		$data = json_decode(file_get_contents($this->saveLocation));
    	}
    	return $data;
    }
}
?>