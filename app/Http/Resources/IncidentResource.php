<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'status' => $this->status, 'started_at' => $this->started_at, 'resolved_at' => $this->resolved_at, 'failure_reason' => $this->failure_reason];
    }
}
