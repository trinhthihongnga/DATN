<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => Carbon::create($this->created_at)->format('Y-m-d H:i'),
            'updated_at' => Carbon::create($this->updated_at)->format('Y-m-d H:i'),
        ];
    }
}
