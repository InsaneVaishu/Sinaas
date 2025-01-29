<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ProductCategories;
use Illuminate\Http\Request;
use App\Models\ProductsNames;
use App\Models\SubCategories;

use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request) 
    {
        $business_id = $request->business_id;
        $results = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->select("categories.*","categories.id as id","categories.status as status","products_names.name as name")->where('business_id', $business_id)->get();
        
        $selected_list = [];
        if($request->product_id){
        $product_id = $request->product_id;
            $product_categories = ProductCategories::where('product_id', $product_id)->get();
            foreach($product_categories as $sel){ 
                array_push($selected_list,$sel->category_id);
            }
        }

        $categories = [];
        foreach($results as $result){
            if($result->category_image){
                $image = env('APP_URL').Storage::url($result->category_image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }

            if($result->categorybg_image){
                $imagebg = env('APP_URL').Storage::url($result->categorybg_image);
            }
            else{
                $imagebg = env('APP_URL').Storage::url('no-image.jpg');
            }

            $selected = '0';
            if (in_array($result->id, $selected_list)){
                $selected = '1';
            } 

            $categories[] = [
                "category_id" => (string)$result->id,
                "business_id" => (string)$result->business_id,
                "name" => $result->name,
                "image" => $image,
                "image_bg" => $imagebg,
                "selected" => $selected,
                "status" => (string)$result->status];
        }        

        return $this->success([
            'categories' => $categories,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }


    public function names(Request $request)
    {
        $language_code = strtolower($request->language_code);

        if(!$language_code){ $language_code = 'en';}

        $results = ProductsNames::query()->select("name_".$language_code." as language_name","id")->get();
    
        $categories_names = [];
        foreach($results as $result){
            $categories_names[] = [
                "name" => $result->language_name,
                "name_id" => $result->id];
        }        

        return $this->success([
            'categories_names' => $categories_names
        ]);
    }

    
    public function add(Request $request)
    {
       // $request->validated(); 
        $user_id = Auth::user()->id;              

        if($request->parent_id=='0'){

            $name_id = $request->name_id;
            if($name_id==''){
                $name = ProductsNames::create([      
                    'name' => $request->name,
                    'name_en' => $request->name,
                    'name_es' => $request->name,
                ]);
                $name_id = $name->id;
            }

            $category = Categories::create([            
                'business_id' => $request->business_id,
                'categoryname_id' => $name_id,
                'status' => $request->status,
            ]);

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'categories/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Categories::where('id', $category->id)->update(array('category_image' => $filename));
                }
            }

            if($request->image_bg){
                $img =  $request->image_bg;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'categories_bg/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Categories::where('id', $category->id)->update(array('categorybg_image' => $filename));
                }
            }

            $result = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->where('categories.id', $category->id)->first();

            if($result->category_image){
                $category_image = env('APP_URL').Storage::url($result->category_image);
            }
            else{
                $category_image = env('APP_URL').Storage::url('no-image.jpg');
            }

            if($result->categorybg_image){
                $categorybg_image = env('APP_URL').Storage::url($result->categorybg_image);
            }
            else{
                $categorybg_image = env('APP_URL').Storage::url('no-image.jpg');
            }       

            $category_info = ["category_id" => (string)$category->id,
                    "business_id" => (string)$result->business_id,
                    "name_id"  => (string)$result->categoryname_id,
                    "name"  => (string)$result->name,
                    "image" => $category_image,    
                    "image_bg" => $categorybg_image,                                
                    "status" => (string)$result->status,
                    "sub_categories" => []];

            return $this->success([
                'category_info' => $category_info,
                //'token' => $user->createToken('API Token')->plainTextToken
            ],'Category Added successfully!');


        }else{

            $name_id = $request->name_id;
            $parent_id = $request->parent_id;
            if($name_id==''){
                $name = ProductsNames::create([      
                    'name' => $request->name,
                    'name_en' => $request->name,
                    'name_es' => $request->name,
                ]);
                $name_id = $name->id;
            }            

            $subcategory = SubCategories::create([            
                'category_id' => $parent_id,
                'subcategoryname_id' => $name_id,
                'status' => 1,
            ]);

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'subcategories/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = SubCategories::where('id', $subcategory->id)->update(array('subcategory_image' => $filename));
                }
            }

            $subcatgeorieslist = SubCategories::query()->leftjoin("products_names", 'products_names.id', '=', 'sub_categories.subcategoryname_id')->select("sub_categories.*","sub_categories.id as id","sub_categories.status as status","products_names.name as name")->where('category_id', $parent_id)->get();
           

            $subcategories = [];
            foreach($subcatgeorieslist as $sub){
                if($sub->subcategory_image){
                    $subcategory_image = env('APP_URL').Storage::url($sub->subcategory_image);
                }
                else{
                    $subcategory_image = env('APP_URL').Storage::url('no-image.jpg');
                }                

                $subcategories[] = [
                    "category_id" => (string)$sub->id,
                    "name" => $sub->name,
                    "name_id"  => (string)$sub->subcategoryname_id,
                    "image" => $subcategory_image,
                    "status" => (string)$sub->status];
            }
            
            $category_detail = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->where('categories.id', $parent_id)->first();

            if($category_detail->category_image){
                $category_image = env('APP_URL').Storage::url($category_detail->category_image);
            }
            else{
                $category_image = env('APP_URL').Storage::url('no-image.jpg');
            }

            if($category_detail->categorybg_image){
                $categorybg_image = env('APP_URL').Storage::url($category_detail->categorybg_image);
            }
            else{
                $categorybg_image = env('APP_URL').Storage::url('no-image.jpg');
            }       

            $category_info = ["category_id" => $category_detail->id,
                    "business_id" => (string)$category_detail->business_id,
                    "name_id"  => (string)$category_detail->categoryname_id,
                    "name"  => $category_detail->name,
                    "image" => $category_image,    
                    "image_bg" => $categorybg_image,
                    "sub_categories" => $subcategories,            
                    "status" => (string)$category_detail->status];

            return $this->success([
                'category_info' => $category_info,
                //'token' => $user->createToken('API Token')->plainTextToken
            ],'Sub Category Added successfully!');

        }
    }    

    public function edit(Request $request)
    {
       // $request->validated(); 
        $user_id = Auth::user()->id;              
        $parent_id = $request->parent_id;
        if($parent_id=='0'){

            $name_id = $request->name_id;
            if($name_id==''){
                $name = ProductsNames::create([      
                    'name' => $request->name,
                    'name_en' => $request->name,
                    'name_es' => $request->name,
                ]);
                $name_id = $name->id;
            } 
           

            $update = Categories::where('id', $request->category_id)->update([            
                'business_id' => $request->business_id,
                'categoryname_id' => $name_id,
                'status' => $request->status]);


            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'categories/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Categories::where('id', $request->category_id)->update(array('category_image' => $filename));
                }
            }

            if($request->image_bg){
                $img =  $request->image_bg;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'categories_bg/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = Categories::where('id', $request->category_id)->update(array('categorybg_image' => $filename));
                }
            }

            $result = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->where('categories.id', $request->category_id)->first();

            if($result->category_image){
                $category_image = env('APP_URL').Storage::url($result->category_image);
            }
            else{
                $category_image = env('APP_URL').Storage::url('no-image.jpg');
            }

            if($result->categorybg_image){
                $categorybg_image = env('APP_URL').Storage::url($result->categorybg_image);
            }
            else{
                $categorybg_image = env('APP_URL').Storage::url('no-image.jpg');
            }       

            $category_info = ["category_id" => $result->id,
                    "business_id" => (string)$result->business_id,
                    "name_id"  => (string)$result->categoryname_id,
                    "name"  => $result->name,
                    "image" => $category_image,    
                    "image_bg" => $categorybg_image,                                
                    "status" => (string)$result->status,
                    "sub_categories" => []];

            return $this->success([
                'category_info' => $category_info,
                //'token' => $user->createToken('API Token')->plainTextToken
            ],'Category Added successfully!');


        }else{            


            $name_id = $request->name_id;
            if($name_id==''){
                $name = ProductsNames::create([      
                    'name' => $request->name,
                    'name_en' => $request->name,
                    'name_es' => $request->name,
                ]);
                $name_id = $name->id;
            } 

            $update = SubCategories::where('id', $request->category_id)->update([            
                'category_id' => $parent_id,
                'subcategoryname_id' => $name_id,
                'status' => $request->status]);

            if($request->image){
                $img =  $request->image;
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $filename = 'subcategories/' . uniqid() . '.png';
                $file = storage_path("app/public/"). $filename;        
                if (file_put_contents($file, $data)) {
                    $update = SubCategories::where('id', $request->category_id)->update(array('subcategory_image' => $filename));
                }
            }

            $subcatgeorieslist = SubCategories::query()->leftjoin("products_names", 'products_names.id', '=', 'sub_categories.subcategoryname_id')->select("sub_categories.*","sub_categories.id as id","sub_categories.status as status","products_names.name as name")->where('category_id', $parent_id)->get();
           

            $subcategories = [];
            foreach($subcatgeorieslist as $sub){
                if($sub->subcategory_image){
                    $subcategory_image = env('APP_URL').Storage::url($sub->subcategory_image);
                }
                else{
                    $subcategory_image = env('APP_URL').Storage::url('no-image.jpg');
                }                

                $subcategories[] = [
                    "category_id" => (string)$sub->id,
                    "name" => $sub->name,
                    "name_id"  => (string)$sub->subcategoryname_id,
                    "image" => $subcategory_image,
                    "status" => (string)$sub->status];
            }
            
            $category_detail = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->where('categories.id', $parent_id)->first();

            if($category_detail->category_image){
                $category_image = env('APP_URL').Storage::url($category_detail->category_image);
            }
            else{
                $category_image = env('APP_URL').Storage::url('no-image.jpg');
            }

            if($category_detail->categorybg_image){
                $categorybg_image = env('APP_URL').Storage::url($category_detail->categorybg_image);
            }
            else{
                $categorybg_image = env('APP_URL').Storage::url('no-image.jpg');
            }       

            $category_info = ["category_id" => $category_detail->id,
                    "business_id" => (string)$category_detail->business_id,
                    "name_id"  => (string)$category_detail->categoryname_id,
                    "name"  => $category_detail->name,
                    "image" => $category_image,    
                    "image_bg" => $categorybg_image,
                    "sub_categories" => $subcategories,            
                    "status" => (string)$category_detail->status];

            return $this->success([
                'category_info' => $category_info,
                //'token' => $user->createToken('API Token')->plainTextToken
            ],'Sub Category Added successfully!');

        }
    }


    public function info(Request $request)
    {
       // $request->validated();
        $user_id = Auth::user()->id;        

        $result = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->where('categories.id', $request->category_id)->first();

        if($result->category_image){
            $category_image = env('APP_URL').Storage::url($result->category_image);
        }
        else{
            $category_image = env('APP_URL').Storage::url('no-image.jpg');
        }

        if($result->categorybg_image){
            $categorybg_image = env('APP_URL').Storage::url($result->categorybg_image);
        }
        else{
            $categorybg_image = env('APP_URL').Storage::url('no-image.jpg');
        }


        $subcatgeorieslist = SubCategories::query()->leftjoin("products_names", 'products_names.id', '=', 'sub_categories.subcategoryname_id')->select("sub_categories.*","sub_categories.id as id","sub_categories.status as status","products_names.name as name")->where('category_id', $request->category_id)->get();
           

        $subcategories = [];
        foreach($subcatgeorieslist as $sub){
            if($sub->subcategory_image){
                $subcategory_image = env('APP_URL').Storage::url($sub->subcategory_image);
            }
            else{
                $subcategory_image = env('APP_URL').Storage::url('no-image.jpg');
            }                

            $subcategories[] = [
                "category_id" => (string)$sub->id,
                "name" => $sub->name,
                "name_id"  => (string)$sub->subcategoryname_id,
                "image" => $subcategory_image,
                "status" => (string)$sub->status];
        }

        $category_info = ["category_id" => $result->id,
                "business_id" => (string)$result->business_id,
                "name_id"  => (string)$result->categoryname_id,
                "name"  => $result->name,
                "image" => $category_image,    
                "image_bg" => $categorybg_image,            
                "status" => (string)$result->status,
                "sub_categories" => $subcategories];

    
        return $this->success([
            'category_info' => $category_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
        
    }

    public function sub_info(Request $request)
    {
        $user_id = Auth::user()->id;
        $sub = SubCategories::query()->leftjoin("products_names", 'products_names.id', '=', 'sub_categories.subcategoryname_id')->select("sub_categories.*","sub_categories.id as id","sub_categories.status as status","products_names.name as name")->where('category_id', $request->category_id)->first();

        if($sub->subcategory_image){
            $subcategory_image = env('APP_URL').Storage::url($sub->subcategory_image);
        }
        else{
            $subcategory_image = env('APP_URL').Storage::url('no-image.jpg');
        } 
        
            
        $category_info = ["category_id" => $sub->id,
                "name_id"  => (string)$sub->subcategoryname_id,
                "name"  => $sub->name,
                "image" => $subcategory_image,             
                "status" => (string)$sub->status];

    
        return $this->success([
            'category_info' => $category_info
        ]);
        
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

    public function sub_update_status(Request $request)
    {       
        //$user_id = Auth::user()->id;
         $category_id = $request->category_id;
         $status = $request->status; 
                        
        $update = SubCategories::where('id', $category_id)->update(['status' => $status]);        
        
        return $this->success([
            'status' => (string)$status,
        ],'Sub Category status updated successfully!');
    }

    public function delete(Request $request)
    {       
        //$user_id = Auth::user()->id;
        $category_id = $request->category_id;

        $category = Categories::where('id', $category_id)->first();
        //dd($category); exit;
        Storage::disk('public')->delete($category->category_image);
        Storage::disk('public')->delete($category->categorybg_image);
        $delete = Categories::where('id', $category_id)->delete();

        $subcategory = SubCategories::where('category_id', $category_id)->get();
        foreach($subcategory as $sub){
            if($sub->subcategory_image){                
                Storage::disk('public')->delete($sub->subcategory_image);
            }
        }
        $subdelete = SubCategories::where('category_id', $category_id)->delete();

        return $this->success([
            'category_id' => (string)$category_id,
        ],'Category deleted successfully!');
    }

    public function sub_delete(Request $request)
    {       
        //$user_id = Auth::user()->id;
        $category_id = $request->category_id;

        $subcategory = SubCategories::where('id', $category_id)->first();
        if($subcategory->subcategory_image){                
            Storage::disk('public')->delete($subcategory->subcategory_image);
        }       

        $update = SubCategories::where('id', $category_id)->delete();             
        
        return $this->success([
            'category_id' => (string)$category_id,
        ],'Category deleted successfully!');
    }


    public function sub_list(Request $request)
    {
        $category_id = $request->category_id;
        $results = SubCategories::query()->leftjoin("products_names", 'products_names.id', '=', 'sub_categories.subcategoryname_id')->select("sub_categories.*","sub_categories.id as id","sub_categories.status as status","products_names.name as name")->where('category_id', $category_id)->get();
    
        $categories = [];
        foreach($results as $result){
            if($result->subcategory_image){
                $image = env('APP_URL').Storage::url($result->subcategory_image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }           

            $categories[] = [
                "category_id" => (string)$result->id,
                "name" => $result->name,
                "name_id" => $result->subcategoryname_id,
                "image" => $image,
                "status" => (string)$result->status];
        }        

        return $this->success([
            'sub_categories' => $categories
        ]);
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
