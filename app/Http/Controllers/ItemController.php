<?php

# resize routine: https://laracasts.com/discuss/channels/general-discussion/resize-image-with-sample-aspect-ratio

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use Image;
use Storage;
use Session;

class ItemController extends Controller
{
    # controller modified to redirect to predefined auth methods in case something goes wrong
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::orderBy('title','ASC')->paginate(10);
        return view('items.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->sortBy('name');
        return view('items.create')->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        //dd(storage_path());;
        //validate the data
        // if fails, defaults to create() passing errors
        $this->validate($request, ['title'=>'required|string|max:255',
                                   'category_id'=>'required|integer|min:0',
                                   'description'=>'required|string',
                                   'price'=>'required|numeric',
                                   'quantity'=>'required|integer',
                                   'sku'=>'required|string|max:100',
                                   'picture' => 'required|image']); 

        //send to DB (use ELOQUENT)
        $item = new Item;
        $item->title = $request->title;
        $item->category_id = $request->category_id;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->sku = $request->sku;

        //save image
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location ='images/items/' . $filename;
            $tn_location = 'images/items/tn_' . $filename;
            $lg_location = 'images/items/lrg_' . $filename;

            $image = Image::make($image);
            Storage::disk('public')->put($location, (string) $image->encode());
            $item->picture = $filename;

            # finds an appropriate aspect ratio to resize to,
            # then pastes image onto a blank canvas (transparent by default!)
            # this prevents stretching / cropping with other methods
            $image->resize(4096,4096,function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas = Image::canvas(4096,4096);
            $canvas->insert($image,'center');
            Storage::disk('public')->put($lg_location, (string) $canvas->encode());

            $image->resize(140,140,function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas = Image::canvas(140,140);
            $canvas->insert($image,'center');
            Storage::disk('public')->put($tn_location, (string) $canvas->encode());
        }

        $item->save(); //saves to DB

        Session::flash('success','The item has been added');

        //redirect
        return redirect()->route('items.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Item::find($id);
        $categories = Category::all()->sortBy('name');
        return view('items.edit')->with('item',$item)->with('categories',$categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate the data
        // if fails, defaults to create() passing errors
        $item = Item::find($id);
        $this->validate($request, ['title'=>'required|string|max:255',
                                   'category_id'=>'required|integer|min:0',
                                   'description'=>'required|string',
                                   'price'=>'required|numeric',
                                   'quantity'=>'required|integer',
                                   'sku'=>'required|string|max:100',
                                   'picture' => 'sometimes|image']);             

        //send to DB (use ELOQUENT)
        $item->title = $request->title;
        $item->category_id = $request->category_id;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->sku = $request->sku;

        //save image
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location ='images/items/' . $filename;
            $tn_location = 'images/items/tn_' . $filename;
            $lg_location = 'images/items/lrg_' . $filename;

            $image = Image::make($image);
            $image->backup();
            Storage::disk('public')->put($location, (string) $image->encode());

            $image->resize(140,140,function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas = Image::canvas(140,140);
            $canvas->insert($image,'center');
            Storage::disk('public')->put($tn_location, (string) $canvas->encode());

            $image->reset();
            $image->resize(4096,4096,function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas = Image::canvas(4096,4096);
            $canvas->insert($image,'center');
            Storage::disk('public')->put($lg_location, (string) $canvas->encode());

            # $image = Image::make($image);
            # Storage::disk('public')->put($location, (string) $image->encode());

            if (isset($item->picture)) {
                $oldFilename = $item->picture;
                Storage::delete('public/images/items/'.$oldFilename);  
                Storage::delete('public/images/items/tn_'.$oldFilename);
                Storage::delete('public/images/items/lrg_'.$oldFilename);
            }

            $item->picture = $filename;
        }

        $item->save(); //saves to DB

        Session::flash('success','The item has been updated');

        //redirect
        return redirect()->route('items.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if (isset($item->picture)) {
            $oldFilename = $item->picture;
            Storage::delete('public/images/items/'.$oldFilename);
            Storage::delete('public/images/items/tn_'.$oldFilename);
            Storage::delete('public/images/items/lrg_'.$oldFilename);
        }
        $item->delete();

        Session::flash('success','The item has been deleted');

        return redirect()->route('items.index');

    }
}
