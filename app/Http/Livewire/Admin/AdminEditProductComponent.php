<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Category;
use Livewire\WithFileUploads;

class AdminEditProductComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_descrption;
    public $description;
    public $regular_price;
    public $sale_price;
    public $SKU;
    public $stock_status;
    public $featured;
    public $qty;
    public $image;
    public $category_id;
    public $newimage;
    public $product_id;

    public function mount($product_slug)
    {
        $product = Product::where('slug',$product_slug)->first();

    $this->name = $product->name;
    $this->slug = $product->slug;
    $this->short_descrption = $product->short_descrption;
    $this->description = $product->description;
    $this->regular_price = $product->regular_price;
    $this->sale_price = $product->sale_price;
    $this->SKU = $product->SKU;
    $this->stock_status = $product->stock_status;
    $this->featured = $product->featured;
    $this->qty = $product->qty ;
    $this->image = $product->image;
    $this->category_id = $product->category_id;
    $this->newimage = $product->newimage;
    $this->product_id = $product->id;
        
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name, '-');
    }
    public function updated($fields)
    {
        $this->validateOnly($fields,[
        'name'=>'required',
        'slug'=>'required|unique:products',
        'short_descrption'=>'required',
        'description'=>'required',
        'regular_price'=>'required|numeric',
        'sale_price'=>'numeric',
        'SKU'=>'required',
        'stock_status'=>'required',
        'qty'=>'required|numeric',
        'newimage'=>'required|mimes:jpeg,png',
        'category_id' => 'required'
        ]);
    }

    public function updateProduct()
    {
        $this->validate([
            'name'=>'required',
            'slug'=>'required|unique:products',
            'short_descrption'=>'required',
            'description'=>'required',
            'regular_price'=>'required|numeric',
            'sale_price'=>'numeric',
            'SKU'=>'required',
            'stock_status'=>'required',
            'qty'=>'required|numeric',
            'newimage'=>'required|mimes:jpeg,png',
            'category_id' => 'required'
        
           ]); 
        $product = Product::find($this->product_id);
       $product->name = $this->name;
       $product->slug = $this->slug;
       $product->short_descrption = $this->short_descrption;
       $product->description = $this->description;
       $product->regular_price = $this->regular_price;
       $product->sale_price = $this->sale_price;
       $product->SKU = $this->SKU;
       $product->stock_status = $this->stock_status;
       $product->featured = $this->featured;
       $product->quantity = $this->qty;
       if($this->newimage)
       {
            $imageName = Carbon::now()->timestamp.'-'. $this->newimage->extension();
            $this->newimage->storeAs('products',$imageName);
            $product->image = $imageName;
       }
       
       $product->category_id = $this->category_id;
       $product->save();
       session()->flash('message','Product has been Update successfuly!');

    }
    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-edit-product-component',['categories'=>$categories])->layout('layouts.base');
    }
}