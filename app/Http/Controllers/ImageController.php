<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

use App\Http\Requests\ImageStoreRequest;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function imageStore(ImageStoreRequest $request)
    {
        /*
        $validatedData = $request->validated();        
        $image_path = $request->file('image')->store('image', 'public');
        */

        $business = Business::where('id', $request->business_id)->first();


        $old_image = $business->image;
        $img =  $request->image;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        //$url = env('APP_URL');
        //echo '|';
        $image_path = $filename = uniqid() . '.png';
        $file = storage_path("app/public/business/"). $filename;
       
        if (file_put_contents($file, $data)) {
            if(file_exists(storage_path("app/public/business/").$old_image)){
                File::delete(storage_path("app/public/business/").$old_image);
                echo '1'; 
               //Storage::delete('business/'.$old_image);

               //Storage::delete('app/public/business/'.$old_image);

               //Storage::disk('public')->delete($old_image);
            }echo '2'; 
        } 

        $data = Business::where('id', $request->business_id)->update(array('image' => $image_path));

        echo '4'; exit;

        return response($data, Response::HTTP_CREATED);
    }
}