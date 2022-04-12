<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoldItem extends Model
{

    public $table = 'items_sold';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function order() {
        return $this->hasOne('\App\Order','id', 'order_id')->orderBy('name','ASC');
    }
}
