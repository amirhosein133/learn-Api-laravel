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
        return cart::get($article , true);
        if (cart::has($article)) {
            cart::update($article , 1);
        }else{
            Cart::put([
                'quantity' => 1,
                'price' => $article->user_id
            ], $article);
        }
        return 'ok';
    }
}
