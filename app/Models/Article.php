<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'image'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);   // برای پیگیری هر سفارش باید بدانیم هر محصول در کدام اوردر بوده
    }
}
