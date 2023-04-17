<?php

namespace App\Http\Resources\Api\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "code" => $this->code,
            "designation" => $this->designation,
            "qty" => $this->pivot->qty,
            "prix" => $this->pivot->prix,
            "total" => $this->pivot->total,
        ];

    }
}
