<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['id' => $this->id, 'name' => $this->name, 'type' => $this->type, 'url' => $this->url, 'method' => $this->method, 'status' => $this->status, 'interval_seconds' => $this->interval_seconds, 'last_latency_ms' => $this->last_latency_ms, 'consecutive_failures' => $this->consecutive_failures, 'last_checked_at' => $this->last_checked_at, 'next_check_at' => $this->next_check_at];
    }
}
