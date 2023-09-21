<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;


class ProductController extends Controller
{
    //
    public function addproduct(){
        $categories = Category::All()->pluck('category_name','category_name');
        // $categories = Category::All();
        return view('admin.addproduct')->with('categories',$categories);
    }

     //
     public function saveproduct(Request $request){
        $this->validate($request, ['product_name' => 'required',
                                    'product_price' => 'required',
                                    
                                    'product_category' => 'required',
                                    'product_image' => 'image|nullable|max:1999'
                                    ]);

        
        if($request->hasFile('product_image')){
            
            // // //1: get file name with exte
            $fileNameWithExt = $request->file('product_image')->getClientOriginalName();
            // // //2: get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // // //3: get just file extension
            $extension = $request->file('product_image')->getClientOriginalExtension();
            // // //4: file name to store
            $fileNametoStore = $fileName.'_'.time().'.'.$extension;

            //upload image
            $path = $request->file('product_image')->storeAs('public/product_images',$fileNametoStore);
            // print($request);
        }
        else{
            $fileNametoStore = 'noimage.jpg';
            // print($request);
        }

        $product = new Product();

        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->product_category = $request->input('product_category');
        $product->product_image = $fileNametoStore;

        $product->save();

        return back()->with('status','The product has been successfully saved!!');
    }

    //
    public function products(){
        $products = Product::All();
        return view('admin.products')->with('products',$products);
    }

    
}
