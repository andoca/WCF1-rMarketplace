<?php

/**
 * Contains Gmap Api related functions
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class GmapUtil {

	/**
	 * returns the google api key for the current domain
	 *
	 * @param string $keyString
	 * @return string $key
	 */
	public static function getApiKey($keyString) {
		// parses the given string (explodes by newline and :)
		$array = OptionUtil::parseSelectOptions($keyString);
		if (count($array) == 1) {
			// return the fist element of the array
			foreach ($array as $domain=>$key) {
				return $key;
			}
		}
		
		foreach ($array as $domain=>$key) {
			if ($domain == $_SERVER ['HTTP_HOST'])
				return $key;
		}
		
		return '';
	}

	/**
	 * Uses google to reverse geocode the given address
	 * this takes addresses and returns lat/lng
	 *
	 * @param array $addr array with elements of the address
	 * @param string $key gmap api key to use. can be an option list with multiple key for multiple hosts
	 * @param boolean $diffuse make coordinates unaccurate if they are lower than acc 5 to avoid markers overlay
	 * @return array coordinates of the entry
	 */
	public static function reverseGeocode($addr, $key = null, $diffuse = true) {
		if (!$key) {
			if (defined('GMAP_API_KEY') && GMAP_API_KEY != '')
				$key = GMAP_API_KEY;
			else
				$key = MP_GMAP_KEY;
		}
		$key = GmapUtil::getApiKey($key);
		
		$qstr = implode(', ', $addr);
		if (defined('CHARSET')) {
			if (CHARSET != 'UTF-8')
				$qstr = StringUtil::convertEncoding(CHARSET, 'UTF-8', $qstr);
		}
		$qstr = urlencode($qstr);
		
		if (isset($addr [1]) && isset($addr [0]))
			$qstr = "&address=&components=country:" . $addr [1] . "|postal_code:" . $addr [0];
		else
			$qstr = "&address=" . $qstr;
		
		$url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false" . $qstr;
		
		try {
			$json = json_decode(file_get_contents(FileUtil::downloadFileFromHttp($url, 'rgeocoder')));
			if (!count($json))
				throw new Exception();
		} catch (Exception $e) {
			throw new NamedUserException('Error contacting google geocoding service');
		}
		
		// handle errors from google
		if (!isset($json->status))
			throw new NamedUserException('Could not connect to the reverse geocoding servers.');
		if ($json->status == 'INVALID_REQUEST')
			throw new NamedUserException('Geocoding service error! Response: Invalid Request');
		if ($json->status == 'OVER_QUERY_LIMIT')
			throw new NamedUserException('Geocoding service error! Response: Over Query Limit');
		if ($json->status == 'REQUEST_DENIED')
			throw new NamedUserException('Geocoding service error! Response: Request denied');
		if ($json->status == 'ZERO_RESULTS')
			throw new NamedUserException('Sorry, I could not find the given address on the map! Please enter a valid zip code and country - if you did so, try a zip code close to yours.');
		
		if ($json->status != 'OK' || !isset($json->results [0]))
			throw new NamedUserException('Could not read the geocoding service response!');
		
		$coord = array ();
		$coord ['lat'] = $json->results [0]->geometry->location->lat;
		$coord ['lng'] = $json->results [0]->geometry->location->lng;
		$coord ['accr'] = $json->results [0]->geometry->location_type;
		
		if ($coord ['accr'] != 'ROOFTOP' && $diffuse) {
			$earthRadius = 6367449; // radius of earth in m --> spherical model
			$latDeg = 110900; // 1 lat deg in m
			$latRad = $coord ['lat'] * pi() / 180;
			$lngDeg = cos($latRad) * pi() * $earthRadius / 180;
			
			$latM = 1 / $latDeg;
			$lngM = 1 / $lngDeg;
			
			$radius = 400; // radius in wich we want our position to be diffused (m)
			$latDiff = rand(0, $radius) - ($radius / 2);
			$lngDiff = rand(0, $radius) - ($radius / 2);
			
			$coord ['lat'] = $coord ['lat'] + $latM * $latDiff;
			$coord ['lng'] = $coord ['lng'] + $lngM * $lngDiff;
		}
		
		return $coord;
	}
}
?>