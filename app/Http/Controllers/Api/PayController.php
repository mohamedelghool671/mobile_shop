<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PayController extends Controller
{
    public function pay (Request $request) {
        $validated = Validator::make($request->all(),[
            'order_id' => 'required|exists:orders,id',
        ]);

        $validated = $validated->validated();

        $order = OrderItem::where('order_id',$validated['order_id'])->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $amountInCents = ceil($order->total_price);

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'EGP',
            'description' => $order->product_name,
            'metadata' => [
                'orderitem_id' => $order->id,
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
        Log::info('Stripe Webhook Received:', $request->toArray());
    }
}
