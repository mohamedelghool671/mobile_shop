<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\PaymentIntent;
use App\Helpers\ApiResponse;
use App\Helpers\FirebaseSendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\FirebaseNotification;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Validator;

class PayController extends Controller
{
    public function pay (Request $request) {
        $validated = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
        ]);

        $validated = $validated->validated();
        $order = Order::find($validated['order_id']);
        if ($order->status === "paid") {
            return ApiResponse::sendResponse("this order aready paid",422);
        }
        $orderItem = OrderItem::where('order_id',$validated['order_id'])->get();
        if ($orderItem->isEmpty()) {
            return response()->json(['message' => 'Order not found.'], 404);
        }
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $amountInCents = ceil($orderItem->sum('total_price')) + ((int) env("shipping")) * 100;
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'EGP',
            'description' => $orderItem->first()->product_name,
            'metadata' => [
                'order_id' => $validated['order_id'],
                'user_id' => $request->user()->id,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }

    public function hook(Request $request) {
     $payload = $request->all();
     $data = $payload['data']['object'];
     $status = $data['status'];
     if ($payload['type'] === "payment_intent.succeeded" && $status === "succeeded") {
        $user_id = $data['metadata']['user_id'];
        $order_id = $data['metadata']['order_id'];
        $order = Order::where([
            ['id',$order_id],
            ['user_id',$user_id],
            ['status','pending']
        ])->first();
        if ($order) {
            $order->update([
                "status" => "paid"
            ]);
            $this->log_info("payment success ✅ : ".$order_id."\n");
        } else {

            $this->log_info("order not found ❌  : \n");
        }
     }
        // $this->log_info("payment failed ❌ : ".$payload['type']."\n");
    }

    public function handle_notification($message,$user) {
         $user->notify(new OrderNotification($message));
        dispatch(new FirebaseNotification($user->getDeviceTokens(),$message['title'],$message['body']));
    }

    protected function log_info($message) {
         Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/my_payment.log'),
        ])->info($message);
    }
}
