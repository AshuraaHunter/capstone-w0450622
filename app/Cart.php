<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{

    public $table = 'shopping_cart';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function item() {
        return $this->hasOne('\App\Item','id', 'item_id')->orderBy('name','ASC');
    }
}
