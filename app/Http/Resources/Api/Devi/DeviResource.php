<?php

namespace App\Http\Resources\API\Devi;

use App\Http\Resources\API\Article\AricleResource;
use App\Http\Resources\API\Client\CleintResource;
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
            'client' => new CleintResource($this->client),
            'articles' => AricleResource::collection($this->whenLoaded('article')),
        ];

    }
}
