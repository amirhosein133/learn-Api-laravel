<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'data' => ArticleResource::collection($this->collection),  //$this->>collection == $articles
        'meta' => [
            'count' => count($this->collection)
        ]
      ];
    }
}
