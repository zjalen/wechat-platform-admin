<?php


namespace App\Services;


use App\Exceptions\BusinessExceptions\NotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/12/24 10:25
 */
class MediaService
{
    public function upload(string $appId, UploadedFile $file, string $type)
    {
        $check = Storage::exists('media/'.$appId.'/'.$type.'/'.$file->getClientOriginalName());
        if ($check) {
            return null;
        }
        return Storage::putFileAs('media/'.$appId.'/'.$type, $file, $file->getClientOriginalName());
    }

    public function allFiles(string $appId, $type = null): array
    {
        return Storage::allFiles('media/'.$appId.'/'.$type);
    }

    /**
     * @throws NotFoundException
     */
    public function getFileResponse(
        string $appId,
        string $name,
        string $type
    ): \Symfony\Component\HttpFoundation\StreamedResponse {
        if (!Storage::exists('media/'.$appId.'/'.$type.'/'.$name)) {
            throw new NotFoundException('文件不存在');
        }
        return Storage::response('media/'.$appId.'/'.$type.'/'.$name);
    }

    public function getFilePath(string $appId, string $fileName, string $type): string
    {
        return Storage::path('media/'.$appId.'/'.$type.'/'.$fileName);
    }

    public function deleteFiles(string $appId, array $fileNames, string $type): string
    {
        $deletePaths = [];
        foreach ($fileNames as $fileName) {
            $deletePaths[] = 'media/'.$appId.'/'.$type.'/'.$fileName;
        }
        return Storage::delete($deletePaths);
    }
}
