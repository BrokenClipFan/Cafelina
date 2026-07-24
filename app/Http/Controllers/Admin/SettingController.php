<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index() {
        $settings = Setting::pluck('value', 'name');

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'tax' => 'required|numeric|max:100|min:1',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255'
        ]);

        $validated['tax'] = $validated['tax'] / 100;

        foreach($validated as $name => $value) {
            Setting::updateOrCreate(
                ['name' => $name],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings');
    }
}
