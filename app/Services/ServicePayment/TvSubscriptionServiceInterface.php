<?php 

namespace App\Services\ServicePayment;

interface TvSubscriptionServiceInterface
{
    public function getUniqueRequiredFields(): mixed;

    public function getSmartCardDetails($cardNumber): mixed;

    public function getVariations(): mixed;

    public function pay(): mixed;

    public function generateRequestId(): mixed;
}