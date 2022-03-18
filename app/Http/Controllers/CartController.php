<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use Storage;
use Session;

class CartController extends Controller
{
    public function store($item_id,$session_id,$ip_address,$quantity)
    { 
        //dd(storage_path());;
        //validate the data
        // if fails, defaults to create() passing errors
        //$this->validate($request, ['item_id'=>'required|integer|min:0|exists:items,id',
        //                           'session_id'=>'required|string',
        //                           'ip_address'=>'required|string',
        //                           'quantity'=>'required|integer']); 

        //send to DB (use ELOQUENT)
        $carts = Cart::all();
        # reference: https://stackoverflow.com/questions/43957370/laravel-contains-for-multiple-values
        if ($carts->contains(function ($val, $key) use ($item_id, $session_id, $ip_address) {
            return $val->item_id == $item_id && $val->session_id == $session_id && $val->ip_address == $ip_address;
        })) {
            $cart = Cart::where('item_id','=',$item_id)->where('session_id','=',$session_id)->where('ip_address','=',$ip_address)->first();
        } else {
            $cart = new Cart;
        }
        $cart->item_id = $item_id;
        $cart->session_id = $session_id;
        $cart->ip_address = $ip_address;
        $cart->quantity += $quantity;

        $cart->save(); //saves to DB

        Session::flash('success','Items added to cart.');

        //redirect
        return redirect()->route('frontAlpha');
    }
}
