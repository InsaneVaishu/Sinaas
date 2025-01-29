<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\Inventories;
use Illuminate\Http\Request;
use App\Models\ProductsNames;
use App\Traits\HttpResponses;

use App\Models\InventoryNames;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StocksController extends Controller
{
    use HttpResponses;   
        

    public function list(Request $request)
    {
        $business_id = $request->business_id;

        $results = Stocks::query()->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('stocks.quantity','units.code AS code','inventories.inventory_image as image','inventory_names.name AS name', 'stocks.id as id')->orderBy('inventory_names.name')->get();
        
        $option_stocks = [];
        foreach($results as $result){

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }

            $option_stocks[] = [
                "stock_id" => $result->id,
                "name" => $result->name,
                "stock_image" => $image,
                "quantity" => (string)$result->quantity,
                "unit_code"  => (string)$result->code
            ];
        }

        return $this->success([
            'option_stocks' => $option_stocks
        ]);
    }

    public function add(Request $request)
    {
       // $request->validated(); 
        $user_id = Auth::user()->id;  
        
        /*$name = $request->name;
        $inventory_id = $request->inventory_id;
        if($inventory_id==''){
            $name = InventoryNames::create([      
                'name' => $request->name,
                'name_en' => $request->name,
                'name_es' => $request->name,
            ]);
            $name_id = $name->id;
        }*/

        $stock = Stocks::create([            
            'business_id' => $request->business_id,
            'inventory_id' => $request->inventory_id,
            //'buy_price' => $request->buy_price,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'quantity_alert' => $request->quantity_alert,
        ]);

            $stock_id = $stock->id;

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'products/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Stocks::where('id', $stock_id)->update(array('image' => $filename));
                }
            }       

            $result = Stocks::query()->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('stocks.quantity','units.code AS code','stocks.image as image','inventory_names.name AS name', 'stocks.id as id')->where('stocks.id', $stock->id)->first();

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }                  

            $stock_info = ["stock_id" => (string)$stock_id,
                    "business_id" => (string)$result->business_id,
                    "inventory_id"  => (string)$result->inventory_id,
                    "name"  => (string)$result->name,
                    "quantity"  => (string)$result->quantity,
                    "image" => $image,
                    "unit_id"  => (string)$result->unit_id,
                    "unit_code"  => (string)$result->code,
                    "quantity_alert" => (string)$result->quantity_alert];

            return $this->success([
                'stock_info' => $stock_info
            ],'Stock Added successfully!');        
    }


    public function edit(Request $request)
    {
       // $request->validated(); 
        $user_id = Auth::user()->id; 
        
        $stock_id = $request->stock_id;

        $stock = Stocks::where('id', $stock_id)->update([
            'inventory_id' => $request->inventory_id,
            //'buy_price' => $request->buy_price,
            'unit_id' => $request->unit_id,
            'quantity' => $request->quantity,
            'quantity_alert' => $request->quantity_alert,
        ]);

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'products/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Stocks::where('id', $stock_id)->update(array('image' => $filename));
                }
            }       

            $result = Stocks::query()->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('stocks.quantity','units.code AS code','stocks.image as image','inventory_names.name AS name', 'stocks.id as id')->where('stocks.id', $stock_id)->first();

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }                  

            $stock_info = ["stock_id" => (string)$stock_id,
                    "business_id" => (string)$result->business_id,
                    "inventory_id"  => (string)$result->inventory_id,
                    "name"  => (string)$result->name,
                    "quantity"  => (string)$result->quantity,
                    "image" => $image,
                    "unit_id"  => (string)$result->unit_id,
                    "unit_code"  => (string)$result->code,
                    "quantity_alert" => (string)$result->quantity_alert];

            return $this->success([
                'stock_info' => $stock_info
            ],'Stock Updated successfully!');        
    }  



    public function info(Request $request)
    {
       // $request->validated();
        $user_id = Auth::user()->id;      
        
        $stock_id = $request->stock_id;

        $result = Stocks::query()->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('stocks.quantity', 'stocks.unit_id', 'stocks.inventory_id','units.code AS code','inventories.inventory_image as image','inventory_names.name AS name', 'stocks.id as id')->where('stocks.id', $stock_id)->first();

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }                  

            $stock_info = ["stock_id" => (string)$stock_id,
                    "business_id" => (string)$result->business_id,
                    "inventory_id"  => (string)$result->inventory_id,
                    "name"  => (string)$result->name,
                    "quantity"  => (string)$result->quantity,
                    "image" => $image,
                    "unit_id"  => (string)$result->unit_id,
                    "unit_code"  => (string)$result->code,
                    "quantity_alert" => (string)$result->quantity_alert];

    
        return $this->success([
            'stock_info' => $stock_info
        ]);
        
    }


    public function delete(Request $request)
    {   
        $stock_id = $request->stock_id;       

        Stocks::where('id', $stock_id)->delete();

        return $this->success([
            'stock_id' => (string)$stock_id,
        ],'Stock deleted successfully!');
        
    }

    public function inventory_list(Request $request)
    {
        $business_id = $request->business_id;

        $results = Inventories::query()->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->select('inventory_names.name AS name', 'inventories.id as id')->orderBy('inventory_names.name')->get();
        
        $inventories = [];
        foreach($results as $result){           

            $inventories[] = [
                "inventory_id" => $result->id,
                "name" => $result->name
            ];
        }

        return $this->success([
            'inventories' => $inventories
        ]);
    }

    public function update_quantity(Request $request)
    {   
        $stock_id = $request->stock_id;
        $quantity = $request->quantity;
        if($quantity > 0){
            $quantity = abs($quantity);

            //DB::select("UPDATE stocks where id = '$stock_id'  ");
            $stock = Stocks::where('id',$stock_id)->increment('quantity',$quantity);
            
            //Stocks::where('id', $stock_id)->update(['quantity'=> DB::raw('quantity+1')]);


        }else{
            $quantity = abs($quantity);
            $stock = Stocks::where('id',$stock_id)->decrement('quantity',$quantity);
            //Stocks::where('id', $stock_id)->update(['quantity'=> DB::raw('quantity-1')]);
        }        

        return $this->success([
            'stock_id' => (string)$stock_id,
        ],'Stock quantity updated successfully!');
        
    }
    
}
