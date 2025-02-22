<?php 

namespace App\Services\ServicePayment\TvSubscriptionService;

interface TvSubscriptionServiceInterface
{
    public function getUniqueRequiredFields(): mixed;

    public function getSmartCardDetails($cardNumber): mixed;

    public function getVariations(): mixed;

    public function pay($data): mixed;

    public function generateRequestId(): mixed;

    public function getCurrencyCode(): string;
}