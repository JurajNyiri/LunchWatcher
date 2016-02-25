<?php
class Bevanda { 
	private $link = "http://tower115.bevanda.sk/";
	private $saveLocation = "data/bevanda";
	private $online = false;
	private $path = "";
    public function __construct($online = false,$path = "")
    {
    	$this->online = $online;
    	$this->path = $path;
    }


    public function getFood()
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

	            $query = "//div[@class='dayly-menu-content']/div[@class='day-menu']/div[@class='day']";
	            $entries = $xpath->query($query);
	            
	            foreach ($entries as $node) {
	            	array_push($data->day,$node->nodeValue);
	            }

	            $query = "//div[@class='dayly-menu-content']/div[@class='day-menu']/div[@class='dayly-menu-list']";
	            $entries = $xpath->query($query);
	            foreach ($entries as $node) {
	            	$temp_items = explode(PHP_EOL,nl2br($node->nodeValue));
	            	$items = array();
	            	foreach($temp_items as $i => $item)
	            	{
	            		$finalItemData = new stdClass();
	            		if(!empty($item))
	            		{
	            			$itemData = explode(" ",preg_replace('/[ ]{2,}|[\t]/', ' ', trim($item)));
	            			$itemDataC = count($itemData);
	            			if($itemDataC > 2)
	            			{
	            				//array_pop($itemData);
	            				$itemData[$itemDataC-2] = str_replace("<br","",$itemData[$itemDataC-2]);
	            				$itemData[0] = trim($itemData[0]);
	            				for($j = 0; $j < $itemDataC; $j++)
	            				{
	            					$itemData[$j] = trim($itemData[$j]);
	            				}
	            				$itemDataC = count($itemData);
	            				$j = $itemDataC;
	            				while($j > 0)
	            				{
	            					$j--;
	            					if(strpos($itemData[$j], "€") === false)
	            					{
	            						unset($itemData[$j]);
	            						
	            					}
	            					else
	            					{
	            						break;
	            					}
	            				}
	            				
								$itemData = array_values($itemData);
	            				$itemDataC = count($itemData);
	            				if($itemData[$itemDataC-1] == "€")
	            				{	
	            					unset($itemData[$itemDataC-1]);
	            					$itemData[$itemDataC-2].="€";
	            				}
	            				$itemData = array_values($itemData);
	            				$itemDataC = count($itemData);

	            				$finalItemData->price = $itemData[$itemDataC-1];
	            				$finalItemData->size = $itemData[0];
	            				if(preg_match('/^[0-9 ,()]/', $itemData[$itemDataC-2]))
	        					{
	        						$finalItemData->alergens = str_replace(")","",str_replace("(","",$itemData[$itemDataC-2]));
	        						$finalItemData->name = "";
	        						for($j = 1; $j < ($itemDataC-2); $j++)
	            					{
	            						$finalItemData->name .= $itemData[$j] . " ";
	            					}
	            					$finalItemData->name = trim($finalItemData->name);
	        					}
	        					else
	        					{
	        						$finalItemData->alergens = false;
	        						$finalItemData->name = "";
	        						for($j = 1; $j < ($itemDataC-1); $j++)
	            					{
	            						$finalItemData->name .= $itemData[$j] . " ";
	            					}
	            					$finalItemData->name = trim($finalItemData->name);
	        					}
	            			}
	            		}
	            		if(isset($finalItemData->name) && isset($finalItemData->alergens) && isset($finalItemData->size) && isset($finalItemData->price))
	            		{
							$items[$i] = $finalItemData;
	            		}
	            	}
	            	unset($items[0]);
	            	$items = array_values($items);
	            	array_push($data->dayFood,$items);
	            }
	            file_put_contents($this->path.$this->saveLocation, json_encode($data));
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