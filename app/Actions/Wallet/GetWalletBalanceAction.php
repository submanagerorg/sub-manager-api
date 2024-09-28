<?php

namespace App\Actions\Wallet;

use App\Models\User;
use App\Traits\FormatApiResponse;


class GetWalletBalanceAction
{
    use FormatApiResponse;

    /**
     *
     * @param User $user
     * @return JsonResponse
     */
    public function execute(User $user)
    {
        return $this->formatApiResponse(200, 'Wallet balance retrieved successfuly', ['balance' => $user->wallet->balance]);
    }
}
