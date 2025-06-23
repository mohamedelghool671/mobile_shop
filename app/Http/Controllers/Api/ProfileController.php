<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profile)
    {

    }
    public function show(Request $request)
    {
        $profile = $this->profile->show();
        return $profile ?  ApiResponse::sendResponse('profile data retrived success',200,[
            "name" => $profile->name ?? null,
            "email" => $profile->email ?? null,
            "phone" => $profile->phone ?? null,
            "image" => $profile?->image ? asset('storage/'.$profile->image) : null,
            "address" => $profile->address ?? null
        ]) : ApiResponse::sendResponse('user haven\'t profile',422 );
    }

    public function update(ProfileRequest $request)
    {
        $return_data = $this->profile->update(fluent($request->validated()));
        return $return_data ? ApiResponse::sendResponse('profile update success', 200,$return_data) :
        ApiResponse::sendResponse('invalid password', 422);
    }

    public function destroy(Request $request)
    {
        return $this->profile->destroy() ?  ApiResponse::sendResponse('acount deleted success',200) :
         ApiResponse::sendResponse('user haven\'t prfile',422);
    }
}
