<?php

namespace App\Services;

class LinkService
{
	public function getWantedTags():array{
		return ["laravel","vue", "vue.js", "php" , "api"];
	}
	
	public function checkIfTagExists(array $tags):bool {
		$wantedTags = $this->getWantedTags();
		$intersection = array_intersect($wantedTags, $tags);
		if (!empty($intersection)) {
			// At least one tags matches an element
			return true;
		} else {
			// No matching elements found
			return false;
		}
	}
}