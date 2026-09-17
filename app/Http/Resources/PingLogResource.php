<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PingLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'is_success' => $this->is_success, 'status_code' => $this->status_code, 'latency_ms' => $this->latency_ms, 'error_message' => $this->error_message, 'checked_at' => $this->checked_at];
    }
}
