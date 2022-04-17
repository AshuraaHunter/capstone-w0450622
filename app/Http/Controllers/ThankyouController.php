<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Item;
use App\SoldItem;

class ThankyouController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id)
    {
        $id = Crypt::decryptString($id);
        $id = intval($id);
        $orderItems = SoldItem::all()->where('order_id',$id);
        $items = Item::all();
        return view('thankyou')->with('orderItems',$orderItems)->with('items',$items);
    }
}
