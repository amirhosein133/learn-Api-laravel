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
        if (!cart::has($article)) {
            Cart::put([
                'quantity' => 1,
                'price' => $article->user_id
            ], $article);
        }
        return 'ok';
    }
}
