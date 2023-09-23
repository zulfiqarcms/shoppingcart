<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Slider;

class SliderController extends Controller
{
    //
    public function addslider(){
        return view('admin.addslider');
    }

    //
    public function sliders(){
        $sliders = Slider::All();
        // $sliders = Slider::All()->where('status',1);
        return view('admin.sliders')->with('sliders',$sliders);
    }

    //
    public function saveslider(Request $request){
        $this->validate($request, ['description1' => 'required',
                                    'description2' => 'required',
                                    'slider_image' => 'image|required|max:1999'
                                    ]);

        
        if($request->hasFile('slider_image')){
            
            // // //1: get file name with exte
            $fileNameWithExt = $request->file('slider_image')->getClientOriginalName();
            // // //2: get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // // //3: get just file extension
            $extension = $request->file('slider_image')->getClientOriginalExtension();
            // // //4: file name to store
            $fileNametoStore = $fileName.'_'.time().'.'.$extension;

            //upload image
            $path = $request->file('slider_image')->storeAs('public/slider_images',$fileNametoStore);
            // print($request);
        }
        else{
            $fileNametoStore = 'noimage.jpg';
            // print($request);
        }

        $slider = new Slider();

        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        
        $slider->slider_image = $fileNametoStore;
        $slider->status = 1;

        $slider->save();

        return back()->with('status','The Slider has been successfully saved!!');
    }

    //
    public function edit_slider(Request $request, $id){
        //print($id);
        
        $slider = Slider::Find($id);
            return view('admin.edit_slider')->with('slider',$slider);
    }


        //
        public function delete_slider($id){
            //print($id);
        $slider = Slider::Find($id);
        if($slider->slider_image != 'noimage.jpg'){
            Storage::delete('public/slider_images/'.$slider->slider_image);
        }
        $slider->delete();
            return back()->with('status','The slider has been succesfully Deleted !!');
    }


    public function updateslider(Request $request){
        $this->validate($request, ['description1' => 'required',
                                    'description2' => 'required',
                                    'slider_image' => 'image|nullable|max:1999'
                                        ]);

            $slider = Slider::find($request->input('id'));
            $slider->description1 = $request->input('description1');
            $slider->description2 = $request->input('description2');
            

                         
            

            
            if($request->hasFile('slider_image')){
            
                // // //1: get file name with exte
                $fileNameWithExt = $request->file('slider_image')->getClientOriginalName();
                // // //2: get just file name
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // // //3: get just file extension
                $extension = $request->file('slider_image')->getClientOriginalExtension();
                // // //4: file name to store
                $fileNametoStore = $fileName.'_'.time().'.'.$extension;

                //upload image
                $path = $request->file('slider_image')->storeAs('public/slider_images',$fileNametoStore);
                // print($request);

                //in case of image change delete the image from storage
                if($slider->slider_image != 'noimage.jpg'){
                    Storage::delete('public/slider_images/'.$slider->slider_image);
                }
                $slider->slider_image = $fileNametoStore;
            }


            $slider->update();

            return redirect('/sliders')->with('status','The SLider has been successfully updated !!');

        }

    public function activateslider($id){

        $slider = slider::find($id);

        $slider->status = 1;
        $slider->update();

        return back()->with('status','The slider has been succesfully active !!');
    }

        public function unactivateslider($id){

            $slider = slider::find($id);

            $slider->status = 0;
            $slider->update();

            return back()->with('status','The slider has been succesfully Unactive !!');
  }
}
