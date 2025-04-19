<?php
declare(strict_types=1);

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Setting\Models\Setting;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SettingController extends BaseControllerApi
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => 'required|string',
            'key' => 'required|string|unique:settings,key',
            'label' => 'required|string',
            'value' => 'nullable|string',
            'type' => 'required|in:text,image,timezone',
        ]);

        $setting = Setting::create($data);

        return response()->json($setting, 201);
    }

    public function index()
    {
        return $this->successResponse(Setting::all()->toArray());
    }

    public function uploadImage(Request $request, Setting $setting)
    {
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $path = $request->file('image')->store('settings', 'public');
            $setting->update(['value' => $path]);

            return $this->successResponse(['path' => asset("storage/{$path}"), 'message' => 'Upload image successfully!']);
        }

        return $this->errorResponse('No file uploaded', ResponseAlias::HTTP_BAD_REQUEST);
    }

    public function updateAll(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:settings,id',
            'settings.*.value' => 'nullable|string',
        ]);

        foreach ($request->settings as $settingData) {
            Setting::where('id', $settingData['id'])->update(['value' => $settingData['value']]);
        }

        return $this->successResponse([], 'Update settings successfully!');
    }
}
