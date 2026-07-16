<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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
        $whatsappNumber = Setting::get('whatsapp_number', '1234567890');

        return view('admin.dashboard', compact('stats', 'whatsappNumber'));
    }
}
