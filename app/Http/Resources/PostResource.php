<?php

namespace App\Http\Resources;

use App\Models\AccountSavePost;
use App\Models\HagTag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'categoryName' => $this->category->name,
            'categorySlug' => $this->category->slug,
            'creator' => $this->creator->accountProfile->getFullName(),
            'creatorCode' => $this->creator->code,
            'is_approved' => $this->is_approved,
            'created_at' => Carbon::create($this->created_at)->format('Y-m-d H:i'),
            'updated_at' => Carbon::create($this->updated_at)->format('Y-m-d H:i'),
        ];
    }
}
