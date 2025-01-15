<?php 

namespace App\Dto;

class PayResponseDto
{
    public function __construct(
        public readonly string $status = '',
        public readonly int $amount = 0,
        public readonly string $requestId = '',
    )
    {
        
    }
}