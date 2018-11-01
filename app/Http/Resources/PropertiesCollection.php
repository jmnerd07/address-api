<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PropertiesCollection extends ResourceCollection
{
    public $collects = 'App\Http\Resources\Property';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'first' => $this->resource->url(1),
                'last' => $this->resource->url($this->resource->lastPage()),
                'next' => $this->resource->nextPageUrl(),
                'previous' => $this->resource->previousPageUrl()

            ],
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'from' => $this->resource->firstItem(),
                'to' => $this->resource->lastItem(),
                'total_per_page'=> $this->resource->count(),
                'total_records' => $this->resource->total()
            ]
        ];
    }
}
