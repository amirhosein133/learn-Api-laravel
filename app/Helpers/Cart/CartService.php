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
        $this->cart = request()->session()->get('cart') ?? collect([]); // ایجاد میکنیم در هر دفعه که مطمئن باشیم کارتی که درست کردیم حتما از تایپ کالکشن باشد
    }

    public function put(array $value, $obj = null)
    {
        if (!is_null($obj) && $obj instanceof Model) {
            $value = array_merge($value, [
                'id' => Str::random(10),
                'subject_id' => $obj->id,
                'subject_type' => get_class($obj),

            ]);
        } elseif (!isset($value['id'])) {
            $value = array_merge($value, [
                'id' => Str::random(10)
            ]);
        }
        $this->cart->put($value['id'], $value); // ولیو های ما هم به کمک ایدی ساخته شده به عنوان یک متد جدید به کارت ما اضافه شد ..
        request()->session()->put('cart', $this->cart);
        return $this;

    }

    public function has($key)
    {
        if ($key instanceof Model) {
            return !is_null($this->cart->where('subject_id', $key->id)->where('subject_type', get_class($key))->first());
        }
        return !is_null($this->cart->where('id', $key)->first()); //  داخل کارت که از طریق کش ذخیره میشود جستجو میکنه ببینه ایا همچین ایدی وجود دارد یا ن ؟
    }

    public function get($key, $withReleation)
    {

        $item = $key instanceof Model
            ? $this->cart->where('subject_id', $key->id)->where('subject_type', get_class($key))->first()
            : $this->cart->where('id', $key)->first();

        return $withReleation ? $this->withReleationShipIfExits($item) : $item;

    }

    public function all()
    {
        $cart = $this->cart;
        $cart = $cart->map(function ($item) {
            return $this->withReleationShipIfExits($item);
        });

        return $cart;

    }

    public function update($key, $options)
    {
        $item = collect($this->get($key, false));
        if (is_numeric($options)) {
            $item = $item->merge([
                'quantity' => $item['quantity'] + $options
            ]);
        }
        $this->put($item->toArray());
        return $this;

    }

    public function delete($key)
    {

        if ($this->has($key)) {
            $this->cart = $this->cart->filter(function ($item) use ($key) {  // بر اساس نتیجه شروط (درست بودن یا نبودن )موارد را به کالکشن جدید اضافه میکند . اگر شروط صحیح باشد اضافه میشود و برعکس .
                if ($key instanceof Model) {
                    return (($item['subject_id'] != $key->id));
                }
                return $key != $item['id'];
            });

            request()->session()->put('cart', $this->cart);
            return true;
        }
        return false;
    }

    /**
     * @param $item
     * @return void
     *  در این قسمت ما میخوایم برای محصول و کارت یک رابطه به وجود بیاریم
     */
    protected function withReleationShipIfExits($item)
    {

        if (isset($item['subject_id']) && isset($item['subject_type'])) {
            $class = $item['subject_type'];
            $subject = (new $class())->find($item['subject_id']);
            $item[strtolower(class_basename($class))] = $subject;
            unset($item['subject_id']);
            unset($item['subject_type']);
        }
        return $item;
    }

    public function instance(string $name)
    {
        $this->cart = \session()->get($name) ?? collect([]);
        $this->name = $name;
        return $this;
    }
}
