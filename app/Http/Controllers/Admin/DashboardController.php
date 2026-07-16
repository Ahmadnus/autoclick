<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    public function index(): View
    {
        $stats = $this->licenseService->getDashboardStats();

        return view('admin.dashboard', compact('stats'));
    }
}
