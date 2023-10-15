<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\cart;
use Session;

class ClientController extends Controller
{
    //
    public function home(){
        $sliders = Slider::All()->where('status', 1);
        $products = Product::All()->where('status', 1);
        
        return view('client.home')->with('sliders',$sliders)->with('products',$products);
    }
    //
    public function shop(){
        $categories = Category::All();
        $products = Product::All()->where('status', 1);
        
        return view('client.shop')->with('categories',$categories)->with('products',$products);
   }

    //
    public function addtocart($id){

        $product = Product::find($id);

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        //return redirect::to('/shop');
        return back();

    }

    //
    public function cart(){
        if(!Session::has('cart')){
            return view('client.cart');
        }

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        return view('client.cart', ['products' => $cart->items]);
    }

    //
    public function update_qty(Request $request ,$id){
        //print('the product id is '.$request->id.' And the product qty is '.$request->quantity);
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->updateQty($id, $request->quantity);
        Session::put('cart', $cart);

        //dd(Session::get('cart'));
        return redirect('/cart');
    }

    public function remove_from_cart($id){
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
       
        if(count($cart->items) > 0){
            Session::put('cart', $cart);
        }
        else{
            Session::forget('cart');
        }

        //dd(Session::get('cart'));
        return back();
    }
    //
    public function checkout(){
        if (!Session::has('client')) {
            return view('client.login');
        }
        return view('client.checkout');
    }
    //
    public function login(){
        return view('client.login');
    }
     //
     public function signup(){
        return view('client.signup');
    }
    ////
    public function orders(){
        return view('admin.orders');
    }
}
