<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferralPayoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        self::$wrap = false;

        if (isset($this->error)) {
            return ['errors' => [$this->error->message]];
        }
        return [
            'message' => $this->resource->message,
            'status' => $this->resource->status,
            'payoutId' => $this->resource->payoutId,
        ];
    }
}
