<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profile)
    {

    }
    public function show(Request $request)
    {
        return $this->profile->show($request);
    }

    public function update(ProfileRequest $request)
    {
        return $this->profile->update($request->validated(),$request->user());
    }

    public function destroy(Request $request)
    {
        return $this->profile->destroy($request,$request->user());
    }
}
