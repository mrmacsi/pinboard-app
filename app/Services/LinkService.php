<?php

namespace App\Services;

use App\Models\Link;
use App\Models\Tag;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DomCrawler\Crawler;

class LinkService
{
	// Use dependency injection to inject the HTTP client as a service
	public function __construct(
		protected Client $client
	)
	{
	}
	
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
	
	public function startProcessing():int {
		$totalLink = 0;
		// Use the injected client to make the HTTP request
		$response = $this->client->get('https://pinboard.in/u:alasdairw?per_page=120');
		$html = $response->getBody()->getContents();
		
		$crawler = new Crawler($html);
		$crawler->filter('div.bookmark')->each(function (Crawler $tr) use (&$totalLink) {
			// Use the Crawler to filter and query the HTML with link has tag class
			$tags = $tr->filter('a.tag');
			//Check if the link has tags
			if ($tags->count() > 0) {
				$tagsArray = $tags->each(function ($tag) {
					return $tag->text();
				});
				// Check links tags are matching one of the wanted tags
				$match = $this->checkIfTagExists($tagsArray);
				if ($match) {
					// Filter data from html
					$linkElement = $tr->filter('a.bookmark_title');
					$comments = $tr->filter('div.description')->text();
					$title = $linkElement->text();
					$href = $linkElement->attr('href');
					
					$result = $this->createLinksAndAttachToTags($title,$href,$comments,$tagsArray);
					
					if ($result) {
						$totalLink++;
					}
				}
			}
		});
		
		return $totalLink;
	}
	
	public function createLinksAndAttachToTags($title,$href,$comments,$tagsArray): bool{
		$newLink = new Link();
		//Check if link with title is already created
		$checkLinkExists = Link::where('href', $href)
			->where('title',$title)
			->exists();
		if (!$checkLinkExists) {
			// Check if the url is valid
			$validity = $this->isUrlValid($href);
			$newLink->fill([
				'title' => $title,
				'href' => $href,
				'comments' => $comments,
				'valid' => $validity
			]);
			$newLink->save();
			// Save the tags to table and attach to link
			$ids = $this->addTagsAndGetIds($tagsArray);
			$newLink->tags()->attach($ids);
			return true;
		} else {
			return false;
		}
	}
	
	public function checkInternetConnection($host = 'www.example.com', $port = 80, $timeout = 5):bool {
		$fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
		
		if ($fp) {
			fclose($fp);
			return true; // Internet connection is available
		} else {
			return false; // No internet connection
		}
	}
}