<?php

namespace App\Http\Controllers;


use App\Models\Stocks;

use App\Models\Options;
use App\Models\OptionStock;
use Illuminate\Http\Request;
use App\Models\ProductsNames;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OptionsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $product_id = $request->product_id;
        $results = Options::query()->leftjoin("products_names", 'products_names.id', '=', 'options.optionname_id')->select("options.*","options.id as id","options.status as status","products_names.name as name")->where('product_id', $product_id)->get();
  
        
        $options = [];
        foreach($results as $result){                    

            $option_id = $result->id;
//echo $option_id; exit;
            $stocks = [];
            $stocks_list = OptionStock::query()->leftJoin('stocks', 'stocks.id', '=', 'option_stocks.stock_id')->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->select("option_stocks.*","option_stocks.id as id","option_stocks.status as status","inventory_names.name as name","inventories.inventory_image as image")->where('option_stocks.option_id', $option_id)->get();

            foreach($stocks_list as $stock){

                if($stock->image){
                    $image = env('APP_URL').Storage::url($stock->image);
                }
                else{
                    $image = env('APP_URL').Storage::url('no-image.jpg');
                }

                $stocks[] = [
                    "option_stock_id" => (string)$stock->id,
                    "stock_id" => (string)$stock->stock_id,
                    "stock_name" => (string)$stock->name,
                    "stock_price" => (string)$stock->stock_price,
                    "image" => $image,
                    "image_name" => $stock->image,
                    "stock_deduction" => (string)$stock->stock_deduction,
                    "status" => (string)$stock->status
                ];
            }

            $options[] = [
                "option_id" => (string)$result->id,
                "business_id" => (string)$result->business_id,
                "name_id" => (string)$result->optionname_id,
                "name" => (string)$result->name,
                "option_min" => (string)$result->option_min,
                "option_max" => (string)$result->option_max,
                "stocks" => $stocks,
                "status" => (string)$result->status];
        }    

        return $this->success([
            'options' => $options,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function info(Request $request)
    {
        $option_id = $request->option_id;
        $result = Options::query()->leftjoin("products_names", 'products_names.id', '=', 'options.optionname_id')->select("options.*","options.id as id","options.status as status","products_names.name as name")->where('options.id', $option_id)->first();
  
                          
//echo $option_id; exit;
        $stocks = [];
        $stocks_list = OptionStock::query()->leftJoin('stocks', 'stocks.id', '=', 'option_stocks.stock_id')->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select("option_stocks.*",'units.code AS code',"option_stocks.id as id","option_stocks.status as status","inventory_names.name as name","inventories.inventory_image as image")->where('option_stocks.option_id', $option_id)->get();

            foreach($stocks_list as $stock){

                if($stock->image){
                    $image = env('APP_URL').Storage::url($stock->image);
                }
                else{
                    $image = env('APP_URL').Storage::url('no-image.jpg');
                }

                $stocks[] = [
                    "option_stock_id" => (string)$stock->id,
                    "stock_id" => (string)$stock->stock_id,
                    "name" => (string)$stock->name,
                    "price" => (string)$stock->stock_price,
                    "stock_image" => $image,
                    "unit_code"  => (string)$stock->code,
                    "image_name" => $stock->image,
                    "stock_deduction" => (string)$stock->stock_deduction,
                    "status" => (string)$stock->status
                ];
            }

        $option_info = [
            "option_id" => (string)$result->id,
            "business_id" => (string)$result->business_id,
            "name_id" => (string)$result->optionname_id,
            "name" => (string)$result->name,
            "option_min" => (string)$result->option_min,
            "option_max" => (string)$result->option_max,
            "stocks" => $stocks,
            "status" => (string)$result->status];
            

        return $this->success([
            'option_info' => $option_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function option_names(Request $request)
    {
        $language_code = strtolower($request->language_code);

        if(!$language_code){ $language_code = 'en';}

        $results = ProductsNames::query()->select("name_".$language_code." as language_name","id")->get();
    
        $options_names = [];
        foreach($results as $result){
            $options_names[] = [
                "name" => $result->language_name,
                "name_id" => $result->id];
        }        

        return $this->success([
            'options_names' => $options_names
        ]);
    }       

    public function option_stocks(Request $request)
    {
        $business_id = $request->business_id;

        $results = Stocks::query()->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->leftjoin('units', 'units.id', '=', 'stocks.unit_id')->select('units.code AS code','inventories.inventory_image as image','inventory_names.name AS name', 'stocks.id as id')->orderBy('inventory_names.name')->get();
        
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
                "unit_code"  => (string)$result->code
            ];
        }

        return $this->success([
            'option_stocks' => $option_stocks
        ]);
    }


}
