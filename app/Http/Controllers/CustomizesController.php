<?php

namespace App\Http\Controllers;

use App\Models\Customize;
use Illuminate\Http\Request;
use App\Models\ProductsNames;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomizesController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        //$business_id = $request->business_id;
        
        $product_id = $request->product_id;
        $results = Customize::query()->leftJoin('stocks', 'stocks.id', '=', 'customizes.stock_id')->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('customizes.*','customizes.id as id','customizes.status as status','units.code AS code','inventory_names.name as name','inventories.inventory_image as image')->where('customizes.product_id', $product_id)->get();
    
        $customizes = [];
        foreach($results as $result){    
            
            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }
            
            $customizes[] = [
                "customize_id" => (string)$result->id,
                "stock_id" => (string)$result->stock_id,
                "stock_name" => (string)$result->name,
                "unit_code"  => (string)$result->code,
                "image" => $image,
                "image_name" => $result->image,
                "default" => $result->default_extra,
                "price" => (string)$result->price,
                "max" => (string)$result->max,
                "stock_deduction" => (string)$result->stock_deduction,
                "status" => (string)$result->status];
        }  

        return $this->success([
            'customizes' => $customizes,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function add_product_customize(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id; 

        if($request->stocks){            
            foreach($request->stocks as $stock){
                //print_r($stock); exit;
                //echo $stock->customize_id; exit;
                if($stock['customize_id']=='0'){
                    $customize = Customize::create([
                        "customize_id" => $stock['customize_id'],
                        "product_id" => $product_id,
                        "business_id" => $request->business_id,
                        "stock_id" => $stock['stock_id'],
                        "stock_deduction" => $stock['quantity_status'],
                        "price" => $stock['price'],
                        "default_extra" => $stock['quantity_status'],
                        "max" => $stock['default_quantity'],
                        "status" => $stock['status'],
                    ]);
                }else{
                   // $customize_id = $stock->customize_id;
                    Customize::where('id', $stock['customize_id'])->update([
                        "stock_deduction" => $stock['quantity_status'],
                        "price" => $stock['price'],
                        "default_extra" => $stock['quantity_status'],
                        "max" => $stock['default_quantity'],
                    ]);
                }
            }
        }

        $product_id = $request->product_id;
        $results = Customize::query()->leftJoin('stocks', 'stocks.id', '=', 'customizes.stock_id')->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('customizes.*','customizes.id as id','customizes.status as status','units.code AS code','inventory_names.name as name','inventories.inventory_image as image')->where('customizes.product_id', $product_id)->get();
    
        $customizes = [];
        foreach($results as $result){    
            
            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }
            
            $customizes[] = [
                "customize_id" => (string)$result->id,
                "stock_id" => (string)$result->stock_id,
                "stock_name" => (string)$result->name,
                "unit_code"  => (string)$result->code,
                "image" => $image,
                "image_name" => $result->image,
                "default" => $result->default_extra,
                "price" => (string)$result->price,
                "max" => (string)$result->max,
                "stock_deduction" => (string)$result->stock_deduction,
                "status" => (string)$result->status];
        }    

        return $this->success([
            'customizes' => $customizes,
            //'token' => $user->createToken('API Token')->plainTextToken
        ],'Customize updated successfully!');


        
        /*return $this->success([
            'customize_id' => (string)$customize_id,
        ],'Customize added successfully!');*/
    }

    public function info(Request $request)
    {
        //$business_id = $request->business_id;
        
        $customize_id = $request->customize_id;
        $result = Customize::query()->leftJoin('stocks', 'stocks.id', '=', 'customizes.stock_id')->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->select('customizes.*','customizes.id as id','customizes.status as status','inventory_names.name as name','inventories.inventory_image as image')->where('customizes.id', $customize_id)->first();
      
            
            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }
            
            $customize_info = [
                "customize_id" => (string)$result->id,
                "stock_id" => (string)$result->stock_id,
                "stock_name" => (string)$result->name,
                "image" => $image,
                "image_name" => $result->image,
                "default" => $result->default_extra,
                "price" => (string)$result->price,
                "max" => (string)$result->max,
                "stock_deduction" => (string)$result->stock_deduction,
                "status" => (string)$result->status];
           

        return $this->success([
            'customize_info' => $customize_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function names(Request $request)
    {
        $language_code = strtolower($request->language_code);

        if(!$language_code){ $language_code = 'en';}

        $results = ProductsNames::query()->select("name_".$language_code." as language_name","id")->get();
    
        $customize_names = [];
        foreach($results as $result){
            $customize_names[] = [
                "name" => $result->language_name,
                "name_id" => $result->id];
        }        

        return $this->success([
            'customize_names' => $customize_names
        ]);
    }


    


    public function edit_product_customize(Request $request)
    {
        $user_id = Auth::user()->id;
        $customize_id = $request->customize_id;   

        $option = Customize::where('id', $customize_id)->update([
            "business_id" => $request->business_id,
            "stock_id" => $request->stock_id,
            "stock_deduction" => $request->stock_deduction,
            "price" => $request->price,
            "default_extra" => $request->default_extra,
            "max" => $request->max,
            "status" => $request->status,
        ]);
        
        return $this->success([
            'customize_id' => (string)$customize_id,
        ],'Customize Option edited successfully!');
    }

    public function delete_product_customize(Request $request)
    {       
        //$user_id = Auth::user()->id;
        $customize_id = $request->customize_id;       

        Customize::where('id', $customize_id)->delete();

        return $this->success([
            'customize_id' => (string)$customize_id,
        ],'Product Customize deleted successfully!');
    }
}
