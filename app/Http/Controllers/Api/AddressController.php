<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Requests\Api\StoreAddressRequest;
use App\Http\Requests\Api\UpdateAddressRequest;

class AddressController extends Controller
{

    public function __construct(public AddressService $address)
    {
    }

    public function store(StoreAddressRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $return_data = $this->address->store(fluent($data));
        return $return_data ?
        ApiResponse::sendResponse('your address created success ',201,$return_data) :
        ApiResponse::sendResponse('creation failed',422);
    }

    public function index()
    {
        $data = $this->address->show();
        return $data ? ApiResponse::sendResponse('address retrived success',200,AddressResource::collection($data)) :
        ApiResponse::sendResponse('user haven\'t address',422) ;
    }

    public function updateAddress(UpdateAddressRequest $request , string $addres_id)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $return_data = $this->address->update(fluent($data),$addres_id);
        return $return_data ?ApiResponse::sendResponse('address updated success',200,new AddressResource($return_data)) :
        ApiResponse::sendResponse('user haven\'t address',422) ;
    }

    public function deleteAddress(string $addres_id)
    {
        $data = $this->address->delete(auth()->id(),$addres_id);
        return $data ?ApiResponse::sendResponse('address deleted success',200) :
        ApiResponse::sendResponse('user haven\'t address',422) ;
    }
}
