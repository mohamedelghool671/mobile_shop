<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function all(Request $request) {
    $user = $request->user();
       return ApiResponse::sendResponse("notification retrived success",200, NotificationResource::collection($user->notifications));
    }

    public function clear(Request $request) {
        $user = $request->user();
        $user->notifications()->delete();
        return ApiResponse::sendResponse("notification deleted success");
    }
}
