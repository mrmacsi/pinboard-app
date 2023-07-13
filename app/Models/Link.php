<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
	use HasFactory;
	public $timestamps = false;
	protected $fillable = ['title','href','comments','valid'];
	
	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'link_tag_pivot');
	}
}
