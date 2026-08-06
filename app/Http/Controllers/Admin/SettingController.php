<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{

    public function setting()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.setting.index', compact('settings'));
    }


    public function updateSetting(Request $request)
    {
        $request->validate([
            'website_name' => 'required',
            'home_page_float_text' => 'required',
            'khaiwal_name' => 'required',
            'whatsapp_number' => 'required',
        ]);


        $settings = [
            'website_name' => $request->website_name,
            'home_page_float_text' => $request->home_page_float_text,
            'khaiwal_name' => $request->khaiwal_name,
            'whatsapp_number' => $request->whatsapp_number,
            'disclaimer' => $request->disclaimer,
            'secondary_page_float_text' => $request->secondary_page_float_text,
            'owner_number' => $request->owner_number,
            'owner_name' => $request->owner_name,
        ];


        foreach ($settings as $key => $value) {

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully');
    }
}
