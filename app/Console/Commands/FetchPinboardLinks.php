<?php

namespace App\Console\Commands;

use App\Services\LinkService;
use Illuminate\Console\Command;

class FetchPinboardLinks extends Command
{
	protected $signature = 'app:fetch-links';
	
	protected $description = 'Command description';
	
	// Use dependency injection to inject link service
	public function __construct(
		protected LinkService $linkService
	) {
		parent::__construct();
	}
	
	public function handle()
	{
		// Check internet connection to avoid misleading validity of the urls
		if (!$this->linkService->checkInternetConnection()) {
			$this->info('Please connect to internet');
			
			return;
		}
		
		$this->info('Link processing started');
		
		$count = $this->linkService->startProcessing();
		
		if ($count > 0) {
			$this->info($count.' Link added');
		} else {
			$this->info('No new Link found');
		}
		
		$this->info('Link processing finished');
	}
}
