<?php 

namespace App\Interfaces;

interface PayForServiceInterface
{
    public function payDstv(array $paymentData): mixed;

    public function getWalletBalance(): mixed;
}