<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Tag;
use App\Services\LinkService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
	public function __construct(protected LinkService $linkService) {}
	
	public function index(Request $request) {
		$link = Tag::whereIn('name',$this->linkService->getWantedTags())->get();
		return $link->toArray();
	}
	
	public function tags(Request $request, $tagIds) {
		$tagIdsArray = explode(',', $tagIds);
		$links = Link::where(function ($query) use ($tagIdsArray) {
			foreach ($tagIdsArray as $tagId) {
				$query->whereHas('tags', function ($query) use ($tagId) {
					$query->where('tags.id', $tagId);
				});
			}
		})
			->with('tags')
			->get();
		return $links->toArray();
	}
}
