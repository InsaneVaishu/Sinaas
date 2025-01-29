<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    use HttpResponses;

    protected $tabel='customer';

    public function login(LoginUserRequest $request)
    {
       /*$request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }*/       

        $user_info = array();
        $customer = Customer::where('email', $request->email)->first();

        if(!$customer) {
            //return $this->error('', 'User not found!', 401);
            //echo $request->email; exit;
            $customer = Customer::create([
                'email' => $request->email
            ]);

            $user_info['id'] = $customer->id;
            $user_info['email'] = (string)$customer->email;
            $user_info['type'] = "2"; //If new USER
            $user_info['token'] = $customer->createToken('API Token')->plainTextToken;
            return $this->success([
                'user' => $user_info
            ]);
        }
        
        $user_info['id'] = $customer->id;
        $user_info['first_name'] = $customer->name;
        $user_info['email'] = $customer->email;
        $user_info['status'] = (string)$customer->status;
        $user_info['type'] = "1"; //If old USER
        $user_info['token'] = $customer->createToken('API Token')->plainTextToken;
        
        return $this->success([
            'user' => $user_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function edit(Request $request) //StoreUserRequest $request
    {
        //$request->validated($request->only(['first_name', 'phone', 'email']));
        $customer_id = Auth::user()->id;
        //echo $customer_id; exit;
        $user = Customer::where('id', $customer_id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country_id' => $request->country_id,
                    'latitude' => $request->latitude,
                    'longitude' =>$request->longitude,
                ]);
//exit;
        if($request->image){
            $customer = Customer::where('id', $customer_id)->first();
            $old_image = $customer->image;

            $img =  $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);            
            $filename = uniqid() . '.png';
            $file = storage_path("app/public/profile/"). $filename;        
            if (file_put_contents($file, $data)) {
                if(file_exists(storage_path("app/public/profile/").$old_image)){
                    File::delete(storage_path("app/public/profile/").$old_image);
                }
                $update = Customer::where('id', $customer_id)->update(array('image' => $filename));
            }       
        }

        $customer = Customer::query()->leftjoin("countries", 'countries.id', '=', 'customers.country_id')->select("customers.*","customers.id as id","customers.status as status","countries.name as country_name","countries.phonecode","countries.iso")->where('customers.id', $customer_id)->first();


        if($customer->image){
            $image = env('APP_URL').Storage::url('profile/'.$customer->image);
        }
        else{
            $image = env('APP_URL').Storage::url('no-image.jpg');
        }

        $user_info['id'] = (string)$customer->id;
        $user_info['first_name'] = (string)$customer->first_name;
        $user_info['last_name'] = (string)$customer->last_name;
        $user_info['email'] = $customer->email;
        $user_info['phone'] = (string)$customer->phone;
        $user_info['status'] = (string)$customer->status;
        $user_info['image'] = $image;
        $user_info["country_id"] = (string)$customer->country_id;
        $user_info["country_name"] = (string)$customer->country_name;
        $user_info["country_code"] = (string)$customer->phonecode;
        $user_info["country_flag"] = env('APP_URL').Storage::url('flags/'.strtolower($customer->iso).'.png');
        $user_info["latitude"] = (string)$customer->latitude;
        $user_info["longitude"] = (string)$customer->longitude;

        return $this->success([
            'user' => $user_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function view()
    {
        //$request->validated($request->only(['first_name', 'phone', 'email']));
        $customer_id = Auth::user()->id;
        
        //$customer = Customer::where('id', $customer_id)->first();

        $customer = Customer::query()->leftjoin("countries", 'countries.id', '=', 'customers.country_id')->select("customers.*","customers.id as id","customers.status as status","countries.name as country_name","countries.phonecode","countries.iso")->where('customers.id', $customer_id)->first();

        if($customer->image){
            $image = env('APP_URL').Storage::url('profile/'.$customer->image);
        }
        else{
            $image = env('APP_URL').Storage::url('no-image.jpg');
        }

        $user_info['id'] = $customer->id;
        $user_info['first_name'] = (string)$customer->first_name;
        $user_info['last_name'] = (string)$customer->last_name;
        $user_info['email'] = $customer->email;
        $user_info['phone'] = (string)$customer->phone;
        $user_info['status'] = (string)$customer->status;
        $user_info['image'] = $image;
        $user_info["country_id"] = (string)$customer->country_id;
        $user_info["country_name"] = (string)$customer->country_name;
        $user_info["country_code"] = (string)$customer->phonecode;
        $user_info["country_flag"] = env('APP_URL').Storage::url('flags/'.strtolower($customer->iso).'.png');
        $user_info["latitude"] = (string)$customer->latitude;
        $user_info["longitude"] = (string)$customer->longitude;

        return $this->success([
            'user' => $user_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function register(StoreUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout()
    {
        
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have succesfully been logged out and your token has been removed'
        ]);
    }
}
