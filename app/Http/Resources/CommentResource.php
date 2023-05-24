<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'content' => $this->content,
            'commentator' => $this->account->accountProfile->getFullName(),
            'commentator_code' => $this->account->code,
            'commentator_image' => $this->account->accountProfile->image,
            'is_creator' => $this->account->userable_type == 'Creator',
            'created_at' => Carbon::create($this->created_at)->format('Y-m-d H:i'),
        ];
    }
}
