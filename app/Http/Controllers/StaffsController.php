<?php

namespace App\Http\Controllers;

use App\Models\Staffs;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StaffsController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {       
        $business_id = $request->business_id;
        $results = Staffs::where('business_id', $business_id)->get();
        $staffs = [];
        foreach($results as $result){

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }

            $staffs[] = ["staff_id" => $result->id,
                "business_id" => $result->id,
                "name"  => $result->name,
                "address"  => $result->address,
                "email"  => $result->email,
                "phone" => $result->phone,
                "billing_address" => $result->billing_address,
                "shipping_address" => $result->shipping_address,
                "time_zone" => $result->time_zone,
                "currency" => (string)$result->currency,
                //"opening_hours" => $result->opening_hours,
                //"delivery_hours" => $result->delivery_hours,
                "website" => $result->website,
                "image" => $image,
                "tax_id" => (string)$result->tax_id,
                "latitude" => (string)$result->latitude,
                "longitude" => (string)$result->longitude,
                "default" => (string)$result->default_business,
                "status" => (string)$result->status];
        }
        

        return $this->success([
            'businesses' => $businesses,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}
