<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Services\MediaService;
use Illuminate\Support\Str;

class PlatformController extends Controller
{
    public function index()
    {
        return Platform::query()->orderByDesc('created_at')->get();
    }

    public function store(): Platform
    {
        $params = request()->input();
        $newPlatform = new Platform();
        $newPlatform->name = $params['name'];
        $newPlatform->app_id = $params['app_id'];
        $newPlatform->app_secret = $params['app_secret'];
        $newPlatform->token = $params['token'];
        $newPlatform->aes_key = $params['aes_key'];
        $newPlatform->description = $params['description'];
        $newPlatform->is_open = $params['is_open'];
        $newPlatform->type = $params['type'];
        $newPlatform->slug = Str::random();
        $newPlatform->save();
        return $newPlatform;
    }

    public function show($id)
    {
        return Platform::query()->find($id);
    }

    public function update($id)
    {
        $params = request()->input();
        $platform = Platform::query()->find($id);
        $platform->name = $params['name'];
        $platform->app_id = $params['app_id'];
        $platform->app_secret = $params['app_secret'];
        $platform->token = $params['token'];
        $platform->aes_key = $params['aes_key'];
        $platform->description = $params['description'];
        $platform->is_open = $params['is_open'];
        $platform->type = $params['type'];
        $platform->save();
        return $platform;
    }

    public function destroy($id): int
    {
        return Platform::destroy($id);
    }


    public function getLocalMedia($appId, $type, $token, $fileName): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $mediaService = new MediaService();
        return $mediaService->getFileResponse($appId, $fileName, $type);
    }
}
