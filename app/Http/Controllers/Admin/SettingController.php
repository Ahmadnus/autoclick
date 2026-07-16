<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
        ]);

        Setting::set('whatsapp_number', $validated['whatsapp_number']);

        return back()->with('status', 'تم تحديث رقم واتساب الدعم بنجاح.');
    }
}
