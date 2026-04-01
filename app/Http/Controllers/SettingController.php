<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            $key = $setting->key;
            if ($setting->type === 'boolean') {
                $value = $request->has("settings.{$key}") ? '1' : '0';
            } else {
                $value = $request->input("settings.{$key}", $setting->value);
            }

            if ($setting->value !== $value) {
                $setting->update(['value' => $value]);
                \Illuminate\Support\Facades\Cache::forget("setting.{$key}");
            }
        }

        return redirect()->route('settings.index')->with('success', 'Configuración actualizada correctamente.');
    }
}
