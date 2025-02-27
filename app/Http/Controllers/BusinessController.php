<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\BusinessBilling;
use App\Models\BusinessWorking;

use App\Models\BusinessDelivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBusinessRequest;

class BusinessController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Business::all(); where('user_id', Auth::user()->id)->get()
        $user = Customer::where('id', Auth::user()->id)->first();
        $username = $user['first_name'];
        
        $results = Business::where('user_id', Auth::user()->id)->get();
        $businesses = [];
        foreach($results as $result){

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }

            $businesses[] = ["business_id" => $result->id,
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
            'user_name' => $username,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBusinessRequest $request)
    {
       // return response()->json('store'); 
        $request->validated();          

        //$image_path = $request->file('image')->store('image', 'public');
        $user_id = Auth::user()->id;
        $business = Business::create([            
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tax_id' => $request->tax_id,  
            'country_id' => $request->country_id,
            'latitude' => $request->latitude,
            'longitude' =>$request->longitude,
            'status' => 1,
            'currency' => 1  
        ]);

        $update = Business::where('id', $business->id)->update(array('user_id' => $user_id));
        
        if($request->image){
            $img =  $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $filename = 'business/' . uniqid() . '.png';
            $file = storage_path("app/public/"). $filename;        
            if (file_put_contents($file, $data)) {
                $update = Business::where('id', $business->id)->update(array('image' => $filename));
            }
        }

        /*return BusinessResource::collection(
            Business::where('id', $business->id)->get()
        );*/

        $result = Business::where('id', $business->id)->first();

        if($result->image){
            $image = env('APP_URL').Storage::url($result->image);
        }
        else{
            $image = env('APP_URL').Storage::url('no-image.jpg');
        }

        $business_info = ["business_id" => $result->id,
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
                "country_id"    =>$result->country_id,
                "website" => $result->website,
                "image" => $image,
                "tax_id" => (string)$result->tax_id,
                "latitude" => (string)$result->latitude,
                "longitude" => (string)$result->longitude,
                "status" => (string)$result->status];


        return $this->success([
            'business_info' => $business_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Business added successfully!');
    }

    public function edit(StoreBusinessRequest $request)
    {       
        $request->validated();
        $user_id = Auth::user()->id;
        
        $update = Business::where('id', $request->business_id)->where('user_id', $user_id)->update([            
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' =>$request->longitude,
            'tax_id' => $request->tax_id]);
        
        if($request->image){
            $img =  $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $filename = 'business/' . uniqid() . '.png';
            $file = storage_path("app/public/"). $filename;        
            if (file_put_contents($file, $data)) {
                $update = Business::where('id', $request->business_id)->update(array('image' => $filename));
            }
        }

        $result = Business::where('id', $request->business_id)->first();

        if($result->image){
            $image = env('APP_URL').Storage::url($result->image);
        }
        else{
            $image = env('APP_URL').Storage::url('no-image.jpg');
        }

        $business = ["business_id" => $result->id,
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
                "country_id"    =>$result->country_id,
                "website" => $result->website,
                "image" => $image,
                "tax_id" => (string)$result->tax_id,
                "latitude" => (string)$result->latitude,
                "longitude" => (string)$result->longitude,
                "status" => (string)$result->status];


        return $this->success([
            'business_info' => $business,
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Business edited successfully!');
    }

    public function info(Request $request)
    {       
       // $request->validated();
        $user_id = Auth::user()->id;               
        //echo $request->business_id; exit;
        $result = Business::query()->leftjoin("countries", 'countries.id', '=', 'businesses.country_id')->select("businesses.*","businesses.id as id","businesses.status as status","businesses.name as name","countries.name as country_name","countries.phonecode","countries.iso")->where('businesses.id', $request->business_id)->first();


        if($result->image){
            $image = env('APP_URL').Storage::url($result->image);
        }
        else{
            $image = env('APP_URL').Storage::url('no-image.jpg');
        }

       // query()->leftJoin('inventories')
        $business = ["business_id" => $result->id,
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
                "country_id" => (string)$result->country_id,
                "country_name" => $result->country_name,
                "country_code" => (string)$result->phonecode,
                "country_flag" => env('APP_URL').Storage::url('flags/'.strtolower($result->iso).'.png'),
                "latitude" => (string)$result->latitude,
                "longitude" => (string)$result->longitude,
                "default" => (string)$result->default_business,
                "status" => (string)$result->status];

    
        return $this->success([
            'business_info' => $business,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
        
    }


    public function update_status(Request $request)
    {       
        //$request->validated();
        $user_id = Auth::user()->id;
         $business_id = $request->business_id;
         $status = $request->status; 
        // echo $status; exit;
        //$results = Business::where('user_id', Auth::user()->id)->get();
       
        
        //Business::where('id', $business_id)->where('user_id', $user_id)->update(['status' => $status]);     
                        
        $update = Business::where('id', $business_id)->where('user_id', $user_id)->update(['status' => $status]);
        
        
        return $this->success([
            'status' => (string)$status,
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Business status updated successfully!');
    }


    public function update_working(Request $request)
    {       
        //$request->validated();
        $user_id = Auth::user()->id;
       
        //$results = Business::where('user_id', Auth::user()->id)->get();        
        $business_id = $request->business_id;
        BusinessWorking::where('business_id', $business_id)->delete();
        foreach($request->working_hours as $day => $day_array){
           // echo $day;
            //$day
            foreach($day_array as $value_array){
                $end_time = $start_time = '';
                foreach($value_array as $key => $value){
                    if($key=='end_time'){ $end_time = $value; }
                    if($key=='start_time'){ $start_time = $value; }                    
                } 
                $BusinessWorking = BusinessWorking::create([            
                    'business_id' => $business_id,
                    'open_from' => $end_time,
                    'open_to' => $start_time,
                    'day' => ucfirst($day),
                ]);
            }                 
        }
                  
        return $this->success([
            'updated' => "1",
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Working hours updated successfully!');
    }


    public function get_working(Request $request)
    {       
        //$request->validated();
        $user_id = Auth::user()->id;

        $business_id = $request->business_id;       
        $results = BusinessWorking::where('business_id', $business_id)->get();        
        $working_hours = [];
        $working_hours['working_hours'] = [];
        $working_hours['business_id'] = $business_id;
        foreach($results as $result){
            $working_hours['working_hours'][$result->day][] = [
                "end_time" => $result->open_to,
                "start_time" => $result->open_from
            ];                 
        }
                  
        return $this->success([
            'working_hours_info' => $working_hours,
        ],'Working hours fetched successfully!');
    }


    public function update_delivery(Request $request)
    {       
        //$request->validated();
        $user_id = Auth::user()->id;
       
        //$results = Business::where('user_id', Auth::user()->id)->get();        
        $business_id = $request->business_id;
        BusinessDelivery::where('business_id', $business_id)->delete();
        foreach($request->delivery_hours as $day => $day_array){
           // echo $day;
            //$day
            foreach($day_array as $value_array){
                $end_time = $start_time = '';
                foreach($value_array as $key => $value){
                    if($key=='end_time'){ $end_time = $value; }
                    if($key=='start_time'){ $start_time = $value; }                    
                } 
                $BusinessDelivery = BusinessDelivery::create([            
                    'business_id' => $business_id,
                    'open_from' => $end_time,
                    'open_to' => $start_time,
                    'day' => ucfirst($day),
                ]);
            }                 
        }
                  
        return $this->success([
            'updated' => "1",
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Delivery hours updated successfully!');
    }


    public function get_delivery(Request $request)
    {       
        //$request->validated();
        $user_id = Auth::user()->id;

        $business_id = $request->business_id;       
        $results = BusinessDelivery::where('business_id', $business_id)->get();        
        $delivery_hours = [];
        $delivery_hours['delivery_hours'] = [];
        $delivery_hours['business_id'] = $business_id;
        foreach($results as $result){
            $delivery_hours['delivery_hours'][$result->day][] = [
                "end_time" => $result->open_to,
                "start_time" => $result->open_from
            ];                 
        }
                  
        return $this->success([
            'delivery_hours_info' => $delivery_hours,
        ],'Delivery hours fetched successfully!');
    }


    public function get_billing(Request $request)
    {       
        //$request->validated();
        $user_id = Auth::user()->id;

        $business_id = $request->business_id;
       
        //$results = BusinessBilling::where('business_id', $business_id)->get();

        $result = BusinessBilling::query()->leftjoin("countries", 'countries.id', '=', 'business_billing.country_id')->select("business_billing.*","business_billing.name as name","countries.name as country_name","countries.phonecode","countries.iso")->where('business_billing.business_id', $request->business_id)->first();
        
        $billing = [];
        if($result){
            $billing['business_id'] = $result->business_id;   
            $billing['name'] = $result->name; 
            $billing['email'] = $result->email; 
            $billing['phone'] = $result->phone; 
            $billing['address'] = (string)$result->address;
            $billing['country_id'] = (string)$result->country_id;
            $billing['tax_id'] = (string)$result->tax_id; 

            $billing['country_name'] = (string)$result->country_name;
            $billing['country_code'] = (string)$result->phonecode;
            $billing['country_flag'] = env('APP_URL').Storage::url('flags/'.strtolower($result->iso).'.png');

            $billing["latitude"] = (string)$result->latitude;
            $billing["longitude"] = (string)$result->longitude;
        }
             
                  
        return $this->success([
            'business_billing_info' => $billing,
        ],'Your request was successfull!');
    }


    public function update_billing(Request $request)
    {       
        $user_id = Auth::user()->id;
        $business_id = $request->business_id;

        BusinessBilling::where('business_id', $business_id)->delete();


        $BusinessWorking = BusinessBilling::create([            
            'business_id' => $business_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'country_id' => $request->country_id,
            'latitude' => $request->latitude,
            'longitude' =>$request->longitude,
            'tax_id' => $request->tax_id,
        ]);


        $result = BusinessBilling::query()->leftjoin("countries", 'countries.id', '=', 'business_billing.country_id')->select("business_billing.*","business_billing.name as name","countries.name as country_name","countries.phonecode","countries.iso")->where('business_billing.business_id', $request->business_id)->first();
        
        $billing = [];
        if($result){
            $billing['business_id'] = $result->business_id;   
            $billing['name'] = $result->name; 
            $billing['email'] = $result->email; 
            $billing['phone'] = $result->phone; 
            $billing['address'] = $result->address;
            $billing['country_id'] = $result->country_id;
            $billing['tax_id'] = (string)$result->tax_id; 

            $billing['country_name'] = $result->country_name;
            $billing['country_code'] = (string)$result->phonecode;
            $billing['country_flag'] = env('APP_URL').Storage::url('flags/'.strtolower($result->iso).'.png');

            $billing["latitude"] = (string)$result->latitude;
            $billing["longitude"] = (string)$result->longitude;
        }
        

        return $this->success([
            'business_billing_info' => $billing,
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Business billing updated successfully!');
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     
    public function edit(string $id)
    {
        
    }*/

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
