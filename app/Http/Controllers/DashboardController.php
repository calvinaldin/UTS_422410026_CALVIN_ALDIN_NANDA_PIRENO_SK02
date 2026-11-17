<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $service) {}

    public function __invoke()
    {
        return view('dashboard', ['data' => $this->service->ringkasan()]);
    }
}
