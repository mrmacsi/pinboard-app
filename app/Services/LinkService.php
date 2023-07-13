<?php

namespace App\Services;

use App\Models\Tag;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class LinkService
{
	public function isUrlValid($url):bool
	{
		$client = new Client([
			RequestOptions::VERIFY => false,
			RequestOptions::TIMEOUT => 10,
			RequestOptions::CONNECT_TIMEOUT => 10,
		]);
		
		try {
			$response = $client->get($url);
			$status = $response->getStatusCode();
			
			if ($status >= 200 && $status <= 302) {
				return true;
			}
		} catch (GuzzleException $e) {
			//echo 'Failed to retrieve HTML content. Error: ' . $e->getMessage();
		}
		
		return false;
	}
	
	public function addTagsAndGetIds(array $names): array
	{
		$ids = [];
		
		foreach ($names as $name) {
			$existingName = Tag::firstOrCreate(['name' => $name]);
			$ids[] = $existingName->id;
		}
		
		return $ids;
	}
	
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