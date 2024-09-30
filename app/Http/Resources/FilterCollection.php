<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilterCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $enabled = $this->collection->get('enabled');
        $disabled = $this->collection->get('disabled');

        // dd($disabled);
        return [
            'data' => $disabled,
            // 'disabled' => $disabled,
            // 'enabled' => $enabled?->flatten() ?? $enabled,
        ];
    }
}
