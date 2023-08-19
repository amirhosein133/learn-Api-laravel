<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Models\Order;

class PaymentController extends Controller
{
    protected $cart;

    public function __construct()
    {
        $this->cart = request()->session()->get('cart') ?? collect([]);
    }

    public function payment() // این متد اطلاعات ذخیره شده در سشن را بعد از کلیک پرداخت به جدول order انتقال میدهد.
    {

        $cartItem = Cart::instance('cart'); // لیست ذخیره شده در کش را میدهد
        $cart = $cartItem->all();
        if (count($cart)) {
            $price = $cart->sum(function ($item) {

                return $item['article']->user_id * $item['quantity']; // محاسبه کل قیمت
            });
            $orderItem = $cart->mapWithKeys(function ($item) {
                return [$item['article']->id => ['quantity' => $item['quantity']]]; // به وجود اوردن مجموعه کالکشن هایی که بتوانیم جدول واسطه بین اوردر و محصولات را پر کنیم
            });

            $order = Order::create([
                'user_id' => 1,
                'status' => 'unpaid',
                'price' => $price
            ]); // جدول اوردر را پر میکنیم
            $order->articles()->attach($orderItem); // جدول واسطه محصولات و اوردر را پر میکنیم.
            return 'ok';
        }
        return 'no item';
    }
}
