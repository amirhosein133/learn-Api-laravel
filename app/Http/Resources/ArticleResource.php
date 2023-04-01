<?php

namespace App\Http\Resources;

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
         'title' => $this->title,   // $this == $article
         'description' => $this->description,
         'user' => $this->user_id,
         'image_url' => $this->image
       ];
    }
}
