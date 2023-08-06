<?php

namespace App\Helpers\Cart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartService
{
    protected $cart;
    public function __construct()
    {
        $this->cart = cache()->get('cart') ?? collect([]); // ایجاد میکنیم در هر دفعه که مطمئن باشیم کارتی که درست کردیم حتما از تایپ کالکشن باشد
    }
    public function put(array $value , $obj = null)
    {

        if(! is_null($obj) && $obj instanceof Model)
        {
            $value = array_merge($value , [
                'id' => Str::random(10),
                'subject_id' => $obj->id,
                'subject_type' => get_class($obj),

            ]);
        }
        $this->cart->put($value['id'] , $value); // ولیو های ما هم به کمک ایدی ساخته شده به عنوان یک متد جدید به کارت ما اضافه شد ..
        \cache()->put('cart' , $this->cart);
        return $this;

    }

    public function has($key)
    {
        if($key instanceof Model){
            return  ! is_null($this->cart->where('subject_id' , $key->id)->where('subject_type' , get_class($key))->first());
        }
        return ! is_null($this->cart->where('id' , $key)->first()); //  داخل کارت که از طریق کش ذخیره میشود جستجو میکنه ببینه ایا همچین ایدی وجود دارد یا ن ؟
    }

    public function get($key)
    {
        $item = $key instanceof Model
            ? $this->cart->where('subject_id' , $key->id)->where('subject_type' , get_class($key))->first()
            : $this->cart->where('id' , $key)->first();

        return $item;
    }

    public function all()
    {
        return $this->cart;
    }
}
