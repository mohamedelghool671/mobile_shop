<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Api\OrderRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\OrderService as orderServ;

class OrderController extends Controller
{

    public function __construct(protected orderServ $orderService)
    {
        $this->middleware('admin')->only(['index', 'update']);
    }

    public function index(Request $request)
    {
        $data = $this->orderService->index($request->limit ?? 10);
        return $data ? ApiResponse::sendResponse("list of orders", 200, $data) :
        ApiResponse::sendResponse("no orders");
    }

    public function store(OrderRequest $request)
    {
        $data =  $this->orderService->store(fluent($request->validated()));
        return $data ? ApiResponse::sendResponse("order placed successfully", 200) :
        ApiResponse::sendResponse("Cart is Empty", 422);
    }

    public function show(Request $request)
    {
        $data = $this->orderService->showUserOrders();
        return $data ?  ApiResponse::sendResponse("return order success", 200, OrderResource::collection($data)):
         ApiResponse::sendResponse("no orders", 422);
    }

    public function update($id, Request $request)
    {
        $data = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,canceled,packing,shipped,out for delivery,delivered'],
        ]);
        if ($data->fails()) {
            return ApiResponse::sendResponse("validation error", 422, $data->errors());
        }
        $data = $data->validated();
        $return_data = $this->orderService->updateStatus($id,$data['status']);
        return $return_data ? ApiResponse::sendResponse("order status updated successfully", 200,new OrderResource($return_data)):
        ApiResponse::sendResponse("record not found", 422);
    }

    public function destroy($id)
    {
        $data = $this->orderService->cancel($id);
        return $data ?  ApiResponse::sendResponse("order canceled successfully", 200) :
         ApiResponse::sendResponse("order not found or aready shopped", 422);
    }

}
