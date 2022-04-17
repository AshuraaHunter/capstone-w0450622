<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Cart;
use App\Item;
use App\Order;
use App\SoldItem;
use Storage;
use Session;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('order.index')->with('orders',$orders);
    }
    public function check(Request $request)
    { 
        $request->merge([
            'session_id' => Crypt::decryptString($request->session_id),
            'ip_address' => Crypt::decryptString($request->ip_address),
        ]);
    
        $fname = $request->fname;
        $lname = $request->lname;
        $phone = $request->phone;
        $email = $request->email;
        $session_id = $request->session_id;
        $ip_address = $request->ip_address;
        
        $this->validate($request, ['fname'=>'required|string|max:127',
                                   'lname'=>'required|string|max:127',
                                   'phone'=>'required|string',
                                   'email'=>'required|string|regex:/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/',
                                   'session_id'=>'required|string',
                                   'ip_address'=>'required|string']); 

        $order = new Order;
        $order->fname = $fname;
        $order->lname = $lname;
        $order->phone = $phone;
        $order->email = $email;
        $order->session_id = $session_id;
        $order->ip_address = $ip_address;

        $order->save(); //saves to DB

        $order_id = $order->id;
        $carts = Cart::all();
        if ($carts->contains(function ($val, $key) use ($session_id, $ip_address) {
            return $val->session_id == $session_id && $val->ip_address == $ip_address;
        })) {
            $items = Cart::where('session_id','=',$session_id)->where('ip_address','=',$ip_address)->get();
        } else {
            $items = new Cart;
        }

        foreach ($items as $item) {
            $new_item = new SoldItem;
            $this_item = Item::find($item->item_id);
            $new_item->item_id = $item->item_id;
            $new_item->order_id = $order_id;
            $new_item->price = $this_item->price;
            $new_item->quantity = $item->quantity;
            $this_item->quantity = max(0,($this_item->quantity - $new_item->quantity)); # we don't want negative shop items!
            $new_item->save();
        }

        Session::flash('success','Order processed.');

        //redirect
        return redirect()->route('thankyou',['id' => Crypt::encryptString($order_id)]);
    }
}
