<?php

namespace App\Http\Controllers;

use App\Actions\Wallet\FundWalletAction;
use App\Actions\Wallet\GetWalletBalanceAction;
use App\Actions\Wallet\GetWalletTransactionsAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\FundWalletRequest;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function getBalance(Request $request)
    {
        return (new GetWalletBalanceAction())->execute($request->user());
    }

    public function addFunds(FundWalletRequest $request): JsonResponse
    {
        return (new FundWalletAction())->execute($request->user(), $request->amount);
    }

    public function getWalletTransactions(Request $request)
    {
        return (new GetWalletTransactionsAction())->execute($request->user(), $request->all());
    }
}
