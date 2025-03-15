<?php

namespace App\Actions\Wallet;

use App\Models\User;
use App\Models\Wallet;
use App\Traits\FormatApiResponse;


class GetWalletTransactionsAction
{
    use FormatApiResponse;

    /**
     *
     * @param User $user
     * @param array $data
     * @return JsonResponse
     */
    public function execute(User $user, array $data)
    {
        $walletTransactions = $user->wallet->wallet_transactions()->orderBy('id', 'desc');

        $walletTransactions = $walletTransactions->paginate($data['length'] ?? 20);

        return $this->formatApiResponse(200, 'Wallet transactions retrieved successfuly', $walletTransactions);
    }
}
