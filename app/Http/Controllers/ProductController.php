<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;


class ProductController extends Controller
{

        //
    public function products(){
        $products = Product::All();
        // $products = Product::All()->where('status',1);
        return view('admin.products')->with('products',$products);
    }
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
        $product->status = 1;

        $product->save();

        return back()->with('status','The product has been successfully saved!!');
    }



            //
    public function edit_product(Request $request, $id){
         //print($id);
        $categories = Category::All()->pluck('category_name','category_name');
        $product = Product::Find($id);
             return view('admin.edit_product')->with('product',$product)->with('categories',$categories);
    }
    
        //
    public function delete_product($id){
            //print($id);
        $product = Product::Find($id);
        if($product->product_image != 'noimage.jpg'){
            Storage::delete('public/product_images/'.$product->product_image);
        }
        $product->delete();
            return back()->with('status','The product has been succesfully Deleted !!');
    }
    
    
        public function updateproduct(Request $request){
        $this->validate($request, ['product_name' => 'required',
                                   'product_price' => 'required',
                                   'product_category' => 'required',
                                    'product_image' => 'image|nullable|max:1999'
                                  ]);
            $product = Product::find($request->input('id'));

            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_category = $request->input('product_category');

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

                //in case of image change delete the image from storage
                if($product->product_image != 'noimage.jpg'){
                    Storage::delete('public/product_images/'.$product->product_image);
                }
                $product->product_image = $fileNametoStore;
            }


            $product->update();
    
            return redirect('/products')->with('status','The product has been successfully updated !!');
    
        }

        public function activateproduct($id){

            $product = Product::find($id);

            $product->status = 1;
            $product->update();

            return back()->with('status','The product has been succesfully active !!');
    }

        public function unactivateproduct($id){

            $product = Product::find($id);

            $product->status = 0;
            $product->update();

            return back()->with('status','The product has been succesfully Unactive !!');
  }
    
    public function view_product_by_category($category_name){

        $products = Product::All()->where('product_category',$category_name)->where('status',1);
        $categories=Category::All();

        return view('client.shop')->with('products',$products)->with('categories',$categories);
    }
}
