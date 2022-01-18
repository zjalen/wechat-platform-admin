<?php


namespace App\Http\Controllers;


use App\Exceptions\BusinessExceptions\NotAllowedException;
use App\Exceptions\BusinessExceptions\ParamsErrorException;
use App\Exceptions\BusinessExceptions\UnavailableException;
use App\Services\MediaService;
use Illuminate\Support\Str;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2022/1/14 18:34
 */
class ResourceController extends Controller
{

    /**
     * 查看服务器上的所有资源
     *
     * @return array|array[]
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function index(): array
    {
        $mediaService = new MediaService();
        $appId = request()->route('appId');
        $type = request()->query('type');
        $list = $mediaService->allFiles($appId, $type);
        $resultArray = [
            'image' => [],
            'video' => [],
            'voice' => []
        ];
        $key = config('custom.media_token_cache_prefix').$appId;
        $token = cache()->get($key);
        if (!$token) {
            $token = Str::random();
            cache()->set($key, $token, config('custom.media_token_cache_ttl') * 60);
        }
        foreach ($list as $item) {
            $itemNameArray = explode('/', $item);
            $type = $itemNameArray[count($itemNameArray) - 2];
            $name = $itemNameArray[count($itemNameArray) - 1];
            $url = route("resources.show",
                ['appId' => $appId, 'token' => $token])."?type=$type&fileName=$name";
            $resultArray[$type][] = ['url' => $url, 'name' => $name];
        }
        return $resultArray;
    }

    /**
     * 查看资源详情（图片、视频、音频）
     *
     * @param $appId
     * @param $token
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws NotAllowedException
     * @throws \App\Exceptions\BusinessExceptions\NotFoundException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    public function show($appId, $token): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $key = config('custom.media_token_cache_prefix').$appId;
        if (cache()->get($key) != $token) {
            throw new NotAllowedException('token 无效');
        }
        $fileName = request()->query('fileName');
        $type = request()->query('type');
        $mediaService = new MediaService();
        return $mediaService->getFileResponse($appId, $fileName, $type);
    }

    /**
     * 上传资源到服务器
     *
     * @return array
     * @throws ParamsErrorException
     * @throws UnavailableException
     */
    public function store(): array
    {
        $appId = request()->route('appId');
        $files = request()->allFiles();
        $type = request()->input('type');
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $mediaService = new MediaService();
        $count = 0;
        foreach ($files as $file) {
            $result = $mediaService->upload($appId, $file, $type);
            if ($result) {
                $count++;
            }
        }
        if ($count == 0) {
            throw new UnavailableException('文件上传失败');
        }
        return ['success' => $count];
    }

    /**
     * 删除服务器上的资源
     *
     * @return void
     * @throws ParamsErrorException
     */
    public function destroy($type)
    {
        $appId = request()->route('appId');
        $fileNames = request()->input('fileNames');
        if (!in_array($type, ['image', 'video', 'voice'])) {
            throw new ParamsErrorException();
        }
        $mediaService = new MediaService();
        $mediaService->deleteFiles($appId, $fileNames, $type);
    }
}
