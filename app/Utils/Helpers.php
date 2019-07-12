<?php 

namespace App\Utils;

class Helpers{

	public static function getHref(&$string){
		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
		$matches = array();
		if(preg_match_all("/$regexp/siU", $string, $matches)) {
			
			if(array_key_exists(2, $matches)){
				$total = count($matches[0]);
				$matches = $matches[0];
				for ($i=0; $i < $total; $i++) { 
					
					$matches[$i] = str_replace('"', "", $matches[$i]);
					$matches[$i] = str_replace("'", "", $matches[$i]);

					$string = str_replace("<a", "<a class='custom-link'", $string);
				}
			}
		}
	}
}

