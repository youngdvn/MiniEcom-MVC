<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'email',
        'phone',
        'address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
