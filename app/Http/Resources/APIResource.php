<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class APIResource extends JsonResource
{
    protected string $message = 'Success';
    protected bool $status = true;
    protected int $httpCode = 200;

    public function withMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function withStatus(bool $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function withHttpCode(int $code): static
    {
        $this->httpCode = $code;
        return $this;
    }

    public function with($request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->httpCode);
    }
}
