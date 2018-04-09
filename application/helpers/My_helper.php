<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getLatitudeLongitudeFromAddress'))
{
    function getLatitudeLongitudeFromAddress($fields = null)
    {
    
		//fields is an array containing the address fields
		//the function adds lat / lng values to the array
		
		$addressLine1 = $fields['AddressLine1'];
		$addressLine2 = $fields['AddressLine2'];
		$addressLine3 = $fields['AddressLine3'];		
		$city = $fields['City'];
		
		if(isset($fields['Zip']) && $fields['Zip'] != "")
		{
			$zip = ", Zip code: ".$fields['Zip'];
		}
		else
		{
			$zip = "";
		}
		
		$data = $addressLine1." ".$addressLine2." ".$addressLine3." ".$city.$zip;
		
		$data = str_replace(' ', '%20', $data);
		$data = str_replace('#', '', $data);
		
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$data.'&sensor=false';
		
		
		//$fields['BranchName'] = $url;
		//$fields['ATMName'] = $url;
		
		
		//echo "url<br />";
		//var_dump($url);
		
		
		
		$response = file_get_contents($url);
		//var_dump($response);
		
		
		$response = json_decode($response);
		
		
		$status = $response->status;
		log_message('error', $status);
		
		
		if($status == "OK")
		{
			
			$results = $response->results;
		
			//var_dump($results);
			
			//results object is an array, and there is only one object in that array
			$results = $results[0];
			
			$geometry = $results->geometry;
			$formatted_address = $results->formatted_address;
			$partial_match = $results->partial_match;
			
			
			//check google geocoding api documentation for description of location_type
			$location_type = $geometry->location_type;
			
			$location = $geometry->location;
			
			
			$latitude = $location->lat;
			$longitude = $location->lng;
			
			/*echo $latitude."<br />";
			echo $longitude."<br />";
			echo $formatted_address."<br />";
			echo $partial_match."<br />";
			echo $location_type."<br />";*/
		}
		
		else
		{
			$latitude = 100;
			$longitude = 100;
		}
		
		$fields['Latitude'] = $latitude;
		$fields['Longitude'] = $longitude;
		
		return $fields;
	
	
    }   
}



?>