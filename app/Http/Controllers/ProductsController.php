<?php

namespace App\Http\Controllers;

use App\Models\Options;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Customize;
use App\Models\ProductTax;
use App\Models\Description;

use App\Models\OptionStock;
use App\Models\ProductTags;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use App\Models\ProductsNames;
use App\Models\ProductStatus;
use App\Models\ProductStocks;
use App\Models\SubCategories;
use App\Traits\HttpResponses;
use App\Models\ProductKitchens;
use App\Models\ProductCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $business_id = $request->business_id;
       // $results = Products::query()->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->select("products.*","products.id as id","products.status as status","products_names.name as name")->where('business_id', $business_id)->get();
        
        $results = Products::query()->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->leftjoin("descriptions", 'descriptions.id', '=', 'products.description_id')->select("products.*","descriptions.*","products.id as id","products.status as status","products_names.name as name","descriptions.description as desc")->where('business_id', $business_id)->get();

        $products = [];
        foreach($results as $result){
            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }           

           // $results = Products::query()->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->leftjoin("descriptions", 'descriptions.id', '=', 'products.description_id')->select("products.*","descriptions.*","products.id as id","products.status as status","products_names.name as name","descriptions.description as desc")->where('business_id', $business_id)->get();

            $product_id = $result->id;

            $taxes_result = ProductTax::where('product_id', $product_id)->get();
            $taxess = array();        
            foreach($taxes_result as $tax){
                $taxess[] = [
                    "id" => (string)$tax->id,
                    "tax_percentage" => (string)$tax->tax_percentage,
                    "tax_rate" => (string)$tax->tax_rate,
                    "type_name"  => (string)$tax->type_name,
                    "tax_type" => (string)$tax->tax_type];
            }        
            $arr = array();
            foreach ($taxess as $key => $item) {
                $arr[$item['tax_type']][$key] = $item;
            }
        
            $taxes = array();
            foreach($arr as $arr1){
                $tax_arr = array();
                foreach($arr1 as $tax){
                    $tax_arr[] = [
                        "id" => (string)$tax['id'],
                        "tax_percentage" => (string)$tax['tax_percentage'],
                        "tax_rate" => (string)$tax['tax_rate'],
                        "type_name"  => (string)$tax['type_name'],
                        "tax_type" => (string)$tax['tax_type']
                        ];
                }
                $taxes[]['product_tax'] = $tax_arr;
                
            }

            $price_result = ProductPrice::where('product_id', $product_id)->get();
            $prices = array();
            foreach($price_result as $price){
                $prices[$price->day][] = [
                    "day" => (string)$price->day,
                    "price_type" => (string)$price->price_type,
                    "price" => (string)$price->price,
                    "end_time" => (string)$price->end_time,
                    "start_time" => (string)$price->start_time
                ];
            }

            if(!empty($prices)){
                $prices = array($prices);
            }
        
            $status_result = ProductStatus::where('product_id', $product_id)->get();
            $statuses = [];
            foreach($status_result as $status){
                $statuses[$price->day][] = [
                    "day" => (string)$price->day,
                    "status_type" => $status->status_type,
                    "status" => $status->status,
                    "end_time" => $status->end_time,
                    "start_time" => $status->start_time
                ];
            }

            if(!empty($statuses)){
                $statuses = array($statuses);
            }

            $products[] = [
                "product_id" => (string)$result->id,
                "business_id" => (string)$result->business_id,
                "name_id" => $result->productname_id,
                "name" => $result->name,
                "description_id" => (string)$result->description_id,
                "description" => (string)$result->desc,
                "image" => $image,
                "price" => $result->price,
                "tax_id" => $result->tax_id,
                "advanced_tax" => $taxes,
                "advanced_price" => $prices,
                "advanced_status" => $statuses,
                "status" => (string)$result->status];
        }    

        return $this->success([
            'products' => $products,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function names(Request $request)
    {
        $language_code = strtolower($request->language_code);

        if(!$language_code){ $language_code = 'en';}

        $results = ProductsNames::query()->select("name_".$language_code." as language_name","id")->get();
    
        $products_names = [];
        foreach($results as $result){
            $products_names[] = [
                "name" => $result->language_name,
                "name_id" => $result->id];
        }        

        return $this->success([
            'products_names' => $products_names
        ]);
    }

    
    public function add(Request $request)
    {
       // $request->validated(); 
        $user_id = Auth::user()->id;    
        
        /*foreach($request->product_tax as $tax){
        print_r($tax); 
        echo $tax['tax_percentage']; 
        }exit;*/
            $name_id = $request->name_id;
            if($name_id==''){
                $name = ProductsNames::create([      
                    'name' => $request->name,
                    'name_en' => $request->name,
                    'name_es' => $request->name,
                ]);
                $name_id = $name->id;
            }

            $description_id = $request->description_id;
            if($description_id==''){ 
                $description = Description::create([      
                    'description' => $request->description,
                    'description_en' => $request->description,
                    'description_es' => $request->description,
                ]);
                $description_id = $description->id;
            }

            $product = Products::create([            
                'business_id' => $request->business_id,
                'productname_id' => $name_id,
                'description_id' => $description_id,
                'price' => $request->price,
                'tax_id' => $request->tax_id,
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $product_id = $product->id;

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'products/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Products::where('id', $product_id)->update(array('image' => $filename));
                }
            }
            
            if($request->product_tax){
                foreach($request->product_tax as $tax){
                    ProductTax::create([
                        "product_id" => $product_id,
                        "tax_percentage" => $tax['tax_percentage'],
                        "tax_rate" => $tax['tax_rate'],
                        "type_name"  => $tax['type_name'],
                        "tax_type" => $tax['tax_type']
                    ]);
                }
            }

            if($request->product_price){
                foreach($request->product_price as $price_day=>$price_day_info){
                    foreach($price_day_info as $price){
                        ProductPrice::create([
                            "product_id" => $product_id,
                            "day"   => $price_day,
                            "price_type" => $price['price_type'],                        
                            "price" => $price['price'],
                            "end_time" => $price['end_time'],
                            "start_time" => $price['start_time']
                        ]);
                    }
                }
            }

            if($request->product_status){
                foreach($request->product_status as $status_day=>$status_day_info){
                    foreach($status_day_info as $status){
                        ProductStatus::create([
                            "product_id" => $product_id,
                            "day"   => $status_day,
                            "status_type" => $status['status_type'],
                            "status" => $status['status'],
                            "end_time" => $status['end_time'],
                            "start_time" => $status['start_time']
                        ]);
                    }
                }
            }        

            $result = Products::query()->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->where('products.id', $product->id)->first();

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }                  

            $product_info = ["product_id" => (string)$product_id,
                    "business_id" => (string)$result->business_id,
                    "name_id"  => (string)$result->productname_id,
                    "name"  => (string)$result->name,
                    "price"  => (string)$result->price,
                    "image" => $image,
                    "tax_id"  => (string)$result->tax_id,
                    "status" => (string)$result->status];

            return $this->success([
                'product_info' => $product_info
            ],'Product Added successfully!');        
    }

    public function edit(Request $request)
    {
       // $request->validated(); 
        $user_id = Auth::user()->id;         

            $name_id = $request->name_id;
            $product_id = $request->product_id;
            if($name_id==''){
                $name = ProductsNames::create([      
                    'name' => $request->name,
                    'name_en' => $request->name,
                    'name_es' => $request->name,
                ]);
                $name_id = $name->id;
            }

            $description_id = $request->description_id;
            if($description_id==''){ 
                $description = Description::create([      
                    'description' => $request->description,
                    'description_en' => $request->description,
                    'description_es' => $request->description,
                ]);
                $description_id = $description->id;
            }

            $product = Products::where('id', $product_id)->update([            
                'business_id' => $request->business_id,
                'productname_id' => $name_id,
                'description_id' => $description_id,
                'price' => $request->price,
                'tax_id' => $request->tax_id,
                'status' => $request->status,
                'updated_at' => now()
            ]);

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'products/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Products::where('id', $product_id)->update(array('image' => $filename));
                }
            }    
            
            
            if($request->product_tax){
                ProductTax::where('product_id', $product_id)->delete();
                foreach($request->product_tax as $tax){
                    ProductTax::create([
                        "product_id" => $product_id,
                        "tax_percentage" => $tax['tax_percentage'],
                        "tax_rate" => $tax['tax_rate'],
                        "type_name"  => $tax['type_name'],
                        "tax_type" => $tax['tax_type']
                    ]);
                }
            }

            if($request->product_price){
                ProductPrice::where('product_id', $product_id)->delete(); //DELETE
                foreach($request->product_price as $price_day=>$price_day_info){
                    foreach($price_day_info as $price){
                        ProductPrice::create([
                            "product_id" => $product_id,
                            "day"   => $price_day,
                            "price_type" => $price['price_type'],                        
                            "price" => $price['price'],
                            "end_time" => $price['end_time'],
                            "start_time" => $price['start_time']
                        ]);
                    }
                }
            }

            if($request->product_status){
                ProductStatus::where('product_id', $product_id)->delete(); //DELETE
                foreach($request->product_status as $status_day=>$status_day_info){
                    foreach($status_day_info as $status){
                        ProductStatus::create([
                            "product_id" => $product_id,
                            "day"   => $status_day,
                            "status_type" => $status['status_type'],
                            "status" => $status['status'],
                            "end_time" => $status['end_time'],
                            "start_time" => $status['start_time']
                        ]);
                    }
                }
            }   

            $result = Products::query()->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->where('products.id', $product_id)->first();

            if($result->image){
                $image = env('APP_URL').Storage::url($result->image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }                  

            $product_info = ["product_id" => (string)$product_id,
                    "business_id" => (string)$result->business_id,
                    "name_id"  => (string)$result->productname_id,
                    "name"  => (string)$result->name,
                    "price"  => (string)$result->price,
                    "image" => $image,
                    "tax_id"  => (string)$result->tax_id,
                    "status" => (string)$result->status];

            return $this->success([
                'product_info' => $product_info
            ],'Product Edited successfully!');        
    }



    public function info(Request $request)
    {
       // $request->validated();
        $user_id = Auth::user()->id;      
        
       $product_id = $request->product_id;

        $result = Products::query()->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->leftjoin("descriptions", 'descriptions.id', '=', 'products.description_id')->select("products.*","descriptions.*","products.id as id","products.status as status","products_names.name as name","descriptions.description as desc")->where('products.id', $product_id)->first();

        if($result->image){
            $image = env('APP_URL').Storage::url('products/'.$result->image);
        }
        else{
            $image = env('APP_URL').Storage::url('no-image.jpg');
        }  

        $taxes_result = ProductTax::where('product_id', $product_id)->get();
        $taxess = array();        
        foreach($taxes_result as $tax){
            $taxess[] = [
                "id" => (string)$tax->id,
                "tax_percentage" => (string)$tax->tax_percentage,
                "tax_rate" => (string)$tax->tax_rate,
                "type_name"  => (string)$tax->type_name,
                "tax_type" => (string)$tax->tax_type];
        }        
        $arr = array();
        foreach ($taxess as $key => $item) {
            $arr[$item['tax_type']][$key] = $item;
        }
       
        $taxes = array();
        foreach($arr as $arr1){
            $tax_arr = array();
            foreach($arr1 as $tax){
                $tax_arr[] = [
                    "id" => (string)$tax['id'],
                    "tax_percentage" => (string)$tax['tax_percentage'],
                    "tax_rate" => (string)$tax['tax_rate'],
                    "type_name"  => (string)$tax['type_name'],
                    "tax_type" => (string)$tax['tax_type']
                    ];
            }
            $taxes[]['product_tax'] = $tax_arr;
            
        }
//print_r($taxes); exit;
        

        $price_result = ProductPrice::where('product_id', $product_id)->get();
        $prices = array();
        foreach($price_result as $price){
            $prices[$price->day][] = [
                "day" => (string)$price->day,
                "price_type" => (string)$price->price_type,
                "price" => (string)$price->price,
                "end_time" => (string)$price->end_time,
                "start_time" => (string)$price->start_time
            ];
        }

        if(!empty($prices)){
            $prices = array($prices);
        }

        $status_result = ProductStatus::where('product_id', $product_id)->get();
        $statuses = array();
        foreach($status_result as $status){
            $statuses[$status->day][] = [
                "day" => (string)$status->day,
                "status_type" => (string)$status->status_type,
                "status" => (string)$status->status,
                "end_time" => (string)$status->end_time,
                "start_time" => (string)$status->start_time
            ];
        }

        if(!empty($statuses)){
            $statuses = array($statuses);
        }

        $product_info = [
            "product_id" => (string)$result->id,
            "business_id" => (string)$result->business_id,
            "name_id" => $result->productname_id,
            "name" => $result->name,
            "description_id" => (string)$result->description_id,
            "description" => (string)$result->desc,
            "image" => $image,
            "price" => $result->price,
            "tax_id" => $result->tax_id,
            "advanced_tax" => $taxes,
            "advanced_price" => $prices,
            "advanced_status" => $statuses,
            "status" => (string)$result->status];

    
        return $this->success([
            'product_info' => $product_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
        
    }


    public function get_product_count(Request $request)
    {       
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        $results = array();

        $categories = ProductCategories::where('product_id', $product_id)->get("category_id");
        $category_list = array();
        foreach($categories as $category){
            $category_list[] = [
                "category_id" => (string)$category->category_id
            ];
        }
        $results['categories'] = $category_list;

        $stocks = ProductStocks::where('product_id', $product_id)->get("stock_id");
        $stock_list = array();
        foreach($stocks as $stock){
            $stock_list[] = [
                "stock_id" => (string)$stock->stock_id
            ];
        }
        $results['stocks'] = $stock_list;

        $tags = ProductTags::where('product_id', $product_id)->get("tag_id");
        $tags_list = array();
        foreach($tags as $tag){
            $tags_list[] = [
                "tag_id" => (string)$tag->tag_id
            ];
        }
        $results['tags'] = $tags_list;

        $kitchens = ProductKitchens::where('product_id', $product_id)->get("kitchen_id");
        $kitchen_list = array();
        foreach($kitchens as $kitchen){
            $kitchen_list[] = [
                "kitchen_id" => (string)$kitchen->kitchen_id
            ];
        }
        $results['kitchens'] = $kitchen_list;


        $customizes = Customize::where('product_id', $product_id)->get("id");
        $customize_list = array();
        foreach($customizes as $customize){
            $customize_list[] = [
                "customize_id" => (string)$customize->id
            ];
        }
        $results['customizes'] = $customize_list;

        
        $options = Options::where('product_id', $product_id)->get("id");
        $option_list = array();
        foreach($options as $option){
            $option_list[] = [
                "option_id" => (string)$option->id
            ];
        }
        $results['options'] = $option_list;



        return $this->success([
            'product_info' => $results,
        ],'Products dependency counts success!');
    }


    public function delete(Request $request)
    {       
        //$user_id = Auth::user()->id;
        $product_id = $request->product_id;

        $product = Products::where('id', $product_id)->first();
        //dd($category); exit;
        Storage::disk('public')->delete($product->image);

        Products::where('id', $product_id)->delete();
        ProductCategories::where('product_id', $product_id)->delete();
        ProductStocks::where('product_id', $product_id)->delete();
        ProductTags::where('product_id', $product_id)->delete();
        ProductKitchens::where('product_id', $product_id)->delete();
        //ProductStocks::where('product_id', $product_id)->delete();

        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product deleted successfully!');
    }


    public function update_status(Request $request)
    {       
        $user_id = Auth::user()->id;
         $category_id = $request->category_id;
         $status = $request->status; 
                        
        $update = Categories::where('id', $category_id)->update(['status' => $status]);        
        
        return $this->success([
            'status' => (string)$status,
        ],'Category status updated successfully!');
    }


    public function add_product_category(Request $request)
    {       
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        if($request->categories){
            ProductCategories::where('product_id', $product_id)->delete();
            foreach($request->categories as $cat){
                ProductCategories::create([
                    "product_id" => $product_id,
                    "category_id" => $cat['category_id']
                ]);
            }
        }
        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product Categories updated successfully!');
    }

    /*public function add_product_customize(Request $request)
    {       
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        if($request->stocks){
            ProductCustomize::where('product_id', $product_id)->delete();
            foreach($request->stocks as $stock){
                ProductCustomize::create([
                    "product_id" => $product_id,
                    "stock_id" => $stock['stock_id'],
                    "default_quantity" => $stock['default_quantity'],
                    "quantity_status" => $stock['quantity_status'],
                    "price" => $stock['price']                    
                ]);
            }
        }
        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product Stocks updated successfully!');
    }*/

    public function add_product_kitchen(Request $request)
    {       
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        if($request->kitchens){
            ProductKitchens::where('product_id', $product_id)->delete();
            foreach($request->kitchens as $kit){
                ProductKitchens::create([
                    "product_id" => $product_id,
                    "kitchen_id" => $kit['kitchen_id']
                ]);
            }
        }
        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product Kitchens updated successfully!');
    }

    public function add_product_option(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        if($request->kitchens){
            ProductKitchens::where('product_id', $product_id)->delete();
            foreach($request->kitchens as $kit){
                ProductKitchens::create([
                    "product_id" => $product_id,
                    "kitchen_id" => $kit['kitchen_id']
                ]);
            }
        }
        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product Options updated successfully!');
    }

    public function add_product_tag(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        if($request->tags){
            ProductTags::where('product_id', $product_id)->delete();
            foreach($request->tags as $tag){
                ProductTags::create([
                    "product_id" => $product_id,
                    "tag_id" => $tag['tag_id']
                ]);
            }
        }
        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product Tags updated successfully!');
    }

    public function add_product_stock(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        if($request->stocks){
            ProductStocks::where('product_id', $product_id)->delete();
            foreach($request->stocks as $stock){
                ProductStocks::create([
                    "product_id" => $product_id,
                    "stock_id" => $stock['stock_id']
                ]);
            }
        }
        return $this->success([
            'product_id' => (string)$product_id,
        ],'Product Stocks updated successfully!');
    }

    public function add_product_options(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request->product_id;

        $optionname_id = $request->optionname_id;
        if($optionname_id==''){
            $name = ProductsNames::create([      
                'name' => $request->optionname,
                'name_en' => $request->optionname,
                'name_es' => $request->optionname,
            ]);
            $optionname_id = $name->id;
        }

        $option = Options::create([
            "product_id" => $product_id,
            "business_id" => $request->business_id,
            "optionname_id" => $optionname_id,
            "option_min" => $request->option_min,
            "option_max" => $request->option_max,
            "status" => $request->status,
        ]);

        $product_option_id = $option->id;

        if($request->option_stocks){
            //OptionStock::where('option_id', $option_id)->delete();
            foreach($request->option_stocks as $stock){
                OptionStock::create([             
                    "option_id" => $product_option_id,       
                    "stock_id" => $stock['stock_id'],
                    "stock_price" => $stock['stock_price'],
                    "stock_deduction" => '0',//$stock['stock_deduction'],
                    "status" => $stock['status'],                    
                ]);
            }
        }
        return $this->success([
            'option_id' => (string)$product_option_id,
        ],'Product Option added successfully!');
    }


    public function edit_product_options(Request $request)
    {
        $user_id = Auth::user()->id;
        $option_id = $request->option_id;

        $optionname_id = $request->optionname_id;
        if($optionname_id==''){
            $name = ProductsNames::create([      
                'name' => $request->optionname,
                'name_en' => $request->optionname,
                'name_es' => $request->optionname,
            ]);
            $optionname_id = $name->id;
        }

        $option = Options::where('id', $option_id)->update([
            "optionname_id" => $optionname_id,
            "option_min" => $request->option_min,
            "option_max" => $request->option_max,
            "status" => $request->status,
        ]);

        if($request->option_stocks){
            OptionStock::where('option_id', $option_id)->delete();
            foreach($request->option_stocks as $stock){
                OptionStock::create([             
                    "option_id" => $option_id,       
                    "stock_id" => $stock['stock_id'],
                    "stock_price" => $stock['stock_price'],
                    "stock_deduction" => '0',//$stock['stock_deduction'],
                    "status" => $stock['status'],                    
                ]);
            }
        }
        return $this->success([
            'option_id' => (string)$option_id,
        ],'Product Option edited successfully!');
    }

    public function delete_product_option(Request $request)
    {       
        //$user_id = Auth::user()->id;
        $option_id = $request->option_id;       

        Options::where('id', $option_id)->delete();
        OptionStock::where('option_id', $option_id)->delete();

        return $this->success([
            'option_id' => (string)$option_id,
        ],'Product Option deleted successfully!');
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


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
