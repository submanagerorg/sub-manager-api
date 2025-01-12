<?php 

namespace App\Parsers;

use App\Dto\PayResponseDto;

class PayResponseParser
{
    public function __construct(public readonly mixed $payResponse)
    {
        
    }

    public function parse() {
        return new PayResponseDto(
            status: $this->getStatus(),
            requestId: $this->getRequestId(),
        );
    }

    private function getStatus() {
        return $this->payResponse['content']['transactions']['status'] ?? null;
    }

    private function getRequestId() {
        return $this->payResponse['requestId'] ?? null;
    }
}