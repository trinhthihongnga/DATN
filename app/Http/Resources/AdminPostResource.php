<?php

namespace App\Http\Resources;

use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPostResource extends JsonResource
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
            'description' => $this->description,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $this->image,
            'categoryName' => $this->category->name,
            'categorySlug' => $this->category->slug,
            'is_approved' => $this->is_approved,
            'status' => $this->status ? true : false,
            'is_popular' => $this->is_popular ? "1" : "0",
            'created_at' => Carbon::create($this->created_at)->format('Y-m-d H:i'),
            'updated_at' => Carbon::create($this->updated_at)->format('Y-m-d H:i'),
            'creator' => $this->creator->accountProfile->getFullName(),
            'updater' => $this->creator->accountProfile->getFullName(),
        ];
    }
}
