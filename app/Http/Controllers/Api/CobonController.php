<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckCobonRequest;
use App\Http\Requests\Api\StoreCobonRequest;
use App\Http\Requests\Api\UpdateCobonRequest;
use App\Http\Resources\CobonResource;
use App\Models\Cobon;
use App\Services\CobonService;
use Illuminate\Http\Request;

class CobonController extends Controller
{

    public function __construct(public CobonService $cobon) {

    }

    public function index()
    {
        $data = $this->cobon->all();
        return $data ? ApiResponse::sendResponse('cobons retrived success',200,CobonResource::collection($data)) :
        ApiResponse::sendResponse('no cobons ',204);
    }

    public function store(StoreCobonRequest $request)
    {
        $data = fluent($request->validated());
        $return_data = $this->cobon->store($data);
        return $return_data ? ApiResponse::sendResponse('cobon created success',201,new CobonResource($return_data)) :
        ApiResponse::sendResponse('failed to create this cobon try again',422);
    }

    public function update(UpdateCobonRequest $request, string $id)
    {
         $data = fluent($request->validated());
        $return_data = $this->cobon->update($data,$id);
        return $return_data ? ApiResponse::sendResponse('cobon updated success',200,new CobonResource($return_data)) :
        ApiResponse::sendResponse('this cobon not found',422);
    }

    public function destroy(string $id)
    {
        $return_data = $this->cobon->delete($id);
        return $return_data ? ApiResponse::sendResponse('cobon deleted success',200) :
        ApiResponse::sendResponse('this cobon not found',422);
    }

    public function check(CheckCobonRequest $request) {
        $cobon = $request->validated()['cobon'];
        $data =  $this->cobon->check($cobon);
        return $data ? ApiResponse::sendResponse('cobon retrived success',200,new CobonResource($data))
        : ApiResponse::sendResponse('cart is empty or copon n\'t correct or already used',422);
    }
}
