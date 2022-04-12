<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Cart;
use App\Item;
use Storage;
use Session;

class CartController extends Controller
{
    public function store(Request $request)
    { 
        //dd(storage_path());;
        //validate the data
        // if fails, defaults to create() passing errors
        $request->merge([
            'item_id' => intval(Crypt::decryptString($request->item_id)),
            'session_id' => Crypt::decryptString($request->session_id),
            'ip_address' => Crypt::decryptString($request->ip_address),
        ]);
    
        $item_id = $request->item_id;
        $session_id = $request->session_id;
        $ip_address = $request->ip_address;
        $quantity = $request->quantity;

        
        $this->validate($request, ['item_id'=>'required|integer|min:0|exists:items,id',
                                   'session_id'=>'required|string',
                                   'ip_address'=>'required|string',
                                   'quantity'=>'required|integer']); 
        

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
        return redirect()->route('show_cart',['sid' => Crypt::encryptString($session_id), 'ipaddr' => Crypt::encryptString($ip_address)]);
    }
    public function show($session_id, $ip_address)
    {
        $session_id = Crypt::decryptString($session_id);
        $ip_address = Crypt::decryptString($ip_address);

        $userCart = Cart::all()->where('session_id','=',$session_id)->where('ip_address','=',$ip_address);
        $items = Item::all();
        return view('cart.show')->with('userCart', $userCart)->with('items', $items);
    }
    public function update(Request $request)
    { 
        //dd(storage_path());;
        //validate the data
        // if fails, defaults to create() passing errors
        $request->merge([
            'item_id' => intval(Crypt::decryptString($request->item_id)),
            'session_id' => Crypt::decryptString($request->session_id),
            'ip_address' => Crypt::decryptString($request->ip_address),
            'quantity' => $request->{"quantity".Crypt::decryptString($request->id)},
        ]);
    
        $item_id = $request->item_id;
        $session_id = $request->session_id;
        $ip_address = $request->ip_address;
        $quantity = $request->quantity;

        
        $this->validate($request, ['item_id'=>'required|integer|min:0|exists:items,id',
                                   'session_id'=>'required|string',
                                   'ip_address'=>'required|string',
                                   'quantity'=>'required|integer']); 
        

        //send to DB (use ELOQUENT)
        $carts = Cart::all();
        $item = Item::find($item_id);
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
        if ($quantity > $item->quantity) {
            $cart->quantity = $item->quantity;
        } else {
            $cart->quantity = $quantity;
        }

        $cart->save(); //saves to DB

        Session::flash('success','Item successfully updated within cart.');

        //redirect
        return redirect()->route('show_cart',['sid' => Crypt::encryptString($session_id), 'ipaddr' => Crypt::encryptString($ip_address)]);
    }

    public function delete(Request $request)
    { 
        //dd(storage_path());;
        //validate the data
        // if fails, defaults to create() passing errors
        $request->merge([
            'item_id' => intval(Crypt::decryptString($request->item_id)),
            'session_id' => Crypt::decryptString($request->session_id),
            'ip_address' => Crypt::decryptString($request->ip_address)
        ]);
    
        $item_id = $request->item_id;
        $session_id = $request->session_id;
        $ip_address = $request->ip_address;

        
        $this->validate($request, ['item_id'=>'required|integer|min:0|exists:items,id',
                                   'session_id'=>'required|string',
                                   'ip_address'=>'required|string']); 
        

        //send to DB (use ELOQUENT)
        $carts = Cart::all();
        # reference: https://stackoverflow.com/questions/43957370/laravel-contains-for-multiple-values
        if ($carts->contains(function ($val, $key) use ($item_id, $session_id, $ip_address) {
            return $val->item_id == $item_id && $val->session_id == $session_id && $val->ip_address == $ip_address;
        })) {
            $cart = Cart::where('item_id','=',$item_id)->where('session_id','=',$session_id)->where('ip_address','=',$ip_address)->first();
        }

        $cart->delete(); //saves to DB

        Session::flash('success','Item successfully deleted.');

        $carts = Cart::all();

        if ($carts->contains(function ($val, $key) use ($session_id, $ip_address) {
            return $val->session_id == $session_id && $val->ip_address == $ip_address;
        })) {
            return redirect()->route('show_cart',['sid' => Crypt::encryptString($session_id), 'ipaddr' => Crypt::encryptString($ip_address)]);
        } else {
            return redirect()->route('frontAlpha');
        }
    }
}
