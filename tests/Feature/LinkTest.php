<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkTest extends TestCase
{
	use RefreshDatabase;
	
	public function setUp(): void
	{
		parent::setUp();
		
		// Create two tags
		Tag::factory(['name'=>'api'])->create();
		Tag::factory(['name'=>'php'])->create();
		
		// Create one link but do not attach
		Link::factory()->create();
	}
	
	public function test_the_application_returns_a_successful_response(): void
	{
		$response = $this->get('/');
		
		$response->assertStatus(200);
	}
	
	public function testTagDataCount()
	{
		$response = $this->get('/api/tags');
		
		$response->assertStatus(200);
		
		$responseData = $response->json(); // Convert the response content to an array or JSON object
		
		$dataCount = count($responseData);
		
		$this->assertEquals(2, $dataCount);
	}
	
	public function testTagSearchDataCount()
	{
		$response = $this->get('/api/tags/1111');
		
		$response->assertStatus(200);
		
		$responseData = $response->json(); // Convert the response content to an array or JSON object

		$dataCount = count($responseData);
		
		$this->assertEquals(0, $dataCount);
	}
}
