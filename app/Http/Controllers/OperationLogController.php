<?php

namespace App\Http\Controllers;

use App\Models\OperationLog;
use Illuminate\Support\Facades\Auth;

class OperationLogController extends Controller
{
    public function index(): array
    {
        $id = Auth::id();
        $limit = request('limit', 20);
        $skip = request('skip', 0);
        $orderBy = request('orderBy', 'id');
        $direction = request('desc', 'true') === 'true' ? 'desc' : 'asc';
        $query = OperationLog::query()->where('user_id', $id);
        $total_account = $query->count();
        $query->limit($limit)->skip($skip);
        if ($orderBy) {
            $query = $query->orderBy($orderBy, $direction);
        }
        return ['total_account' => $total_account, 'list' => $query->get()];
    }
}
