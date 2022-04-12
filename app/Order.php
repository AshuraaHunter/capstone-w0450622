<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    public $table = 'order_info';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function soldItem() {
        return $this->hasMany('\App\SoldItem','order_id', 'id')->orderBy('name','ASC');
    }
}
