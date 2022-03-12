<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Item;

class DetailsController extends Controller
{
    public function index($id) {
        $item = Item::find($id);
        return view('details')->with('item',$item);
    }
}
