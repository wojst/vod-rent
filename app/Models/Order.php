<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = ['user_id', 'movie_id', 'rent_start', 'rent_end', 'cost' , 'code'];

    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
