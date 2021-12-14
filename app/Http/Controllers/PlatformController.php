<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends BaseController
{
    public function index()
    {
        return Platform::query()->orderByDesc('created_at')->get();
    }
}
