<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Article $article)
    {

        if (cart::has($article)) {
            cart::update($article, 1); // برای پروژه خودت باید علاوه بر زیاد شدن کم کردن رو هم اضافه کنی به برنامت
        } else {
            Cart::put([
                'quantity' => 1,
                'price' => $article->user_id
            ], $article);
        }
        return 'ok';
    }

    public function deleteFromCart(Article $article)
    {
        cart::delete($article);
        return 'delete cart item is succses';
    }
}
