<?php

namespace App\Console\Commands;

use App\Services\LinkService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class FetchPinboardLinks extends Command
{
    protected $signature = 'app:fetch-links';

    protected $description = 'Command description';
	
	// Use dependency injection to inject the HTTP client as a service, inject link service
	public function __construct(
		protected Client $client,
		protected LinkService $linkService
	)
	{
		parent::__construct();
	}
	
	public function handle()
	{
		// Use the injected client to make the HTTP request
		$response = $this->client->get('https://pinboard.in/u:alasdairw?per_page=120');
		$html = $response->getBody()->getContents();
		
		$crawler = new Crawler($html);
		$crawler->filter('div.bookmark')->each(function (Crawler $tr){
			// Use the Crawler to filter and query the HTML with link has tag class
			$tags = $tr->filter('a.tag');
			//Check if the link has tags
			if ($tags->count() > 0) {
				$tagsArray = $tags->each(function ($tag) {
					return $tag->text();
				});
				// Check links tags are matching one of the wanted tags
				$match = $this->linkService->checkIfTagExists($tagsArray);
				if ($match) {
					// Filter data from html
				}
			}
		});
		
		$this->info('Link processing finished');
	}
}
