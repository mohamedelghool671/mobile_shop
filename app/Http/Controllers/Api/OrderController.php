<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        return $this->orderService->index($request->limit ?? 10);
    }

    public function store(OrderRequest $request)
    {
        return $this->orderService->store($request->validated(), $request->user());
    }

    public function show(Request $request)
    {
        return $this->orderService->showUserOrders($request->user());
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
        return $this->orderService->updateStatus($id,$data['status']);
    }

    public function destroy($id)
    {
        return $this->orderService->cancel($id);
    }
}
