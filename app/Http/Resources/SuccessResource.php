<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => 'success',
            'message' => $this['message'],
            'data' => $this['data'] ?? null,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this['status_code'] ?? 200);
    }
}