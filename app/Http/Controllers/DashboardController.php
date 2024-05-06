<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\DashboardTotalSummaryRequest;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function __construct(private DashboardRepository $dashboardRepository)
    {

    }

    public function getTotalSummary(DashboardTotalSummaryRequest $request) {
        return $this->formatApiResponse(Response::HTTP_OK, __('Data retrieved successfully'), $this->dashboardRepository->totalSummaryData($request->currency));
    }

    public function spendByCurrency() {
        return $this->formatApiResponse(Response::HTTP_OK, __('Data retrieved successfully'), $this->dashboardRepository->spendByCurrencyData());
    }

    public function spendByCategory() {
        return $this->formatApiResponse(Response::HTTP_OK, __('Data retrieved successfully'), $this->dashboardRepository->spendByCategoryData());
    }

    public function expiringSoon() {
        return $this->formatApiResponse(Response::HTTP_OK, __('Data retrieved successfully'), $this->dashboardRepository->expirySoonData());
    }
}
