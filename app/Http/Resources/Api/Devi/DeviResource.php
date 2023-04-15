<?php

namespace App\Http\Resources\Api\Devi;

use App\Http\Resources\Api\Article\ArticleResource;
use App\Http\Resources\Api\Client\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviResource extends JsonResource
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
            'code' => $this->code,
            'date' => $this->date,
            'client' => new ClientResource($this->client),
            'articles' => ArticleResource::collection($this->whenLoaded('article')),
        ];

    }
}
