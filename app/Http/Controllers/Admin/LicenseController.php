<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Pure HTTP orchestration: validates input and delegates every actual
 * decision (creating, activating, blocking, extending, deleting) to
 * LicenseService. No business logic or direct model queries live here.
 */
class LicenseController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    public function index(): View
    {
        $licenses = $this->licenseService->paginateLicenses();

        return view('admin.licenses.index', compact('licenses'));
    }

    public function create(): View
    {
        return view('admin.licenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'duration_type' => ['required', 'string', 'in:' . implode(',', array_keys(License::DURATION_OPTIONS))],
            'manual_days' => ['required_if:duration_type,' . License::DURATION_MANUAL, 'nullable', 'integer', 'min:1', 'max:3650'],
            'driver_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:32'],
        ]);

        $license = $this->licenseService->createLicense(
            $data['duration_type'],
            $data['driver_name'],
            $data['phone_number'],
            $data['manual_days'] ?? null,
        );

        return redirect()->route('licenses.index')
            ->with('status', 'تم إنشاء كود التفعيل بنجاح: ' . $license->code);
    }

    public function activate(License $license): RedirectResponse
    {
        $this->licenseService->activateAdmin($license);

        return back()->with('status', 'تم تفعيل الكود بنجاح.');
    }

    public function block(License $license): RedirectResponse
    {
        $this->licenseService->block($license);

        return back()->with('status', 'تم حظر الكود بنجاح.');
    }

    public function extend(License $license): RedirectResponse
    {
        $this->licenseService->extend($license);

        return back()->with('status', 'تم تمديد الاشتراك شهرًا واحدًا بنجاح.');
    }

    public function destroy(License $license): RedirectResponse
    {
        $this->licenseService->delete($license);

        return redirect()->route('licenses.index')->with('status', 'تم حذف الكود بنجاح.');
    }
}
