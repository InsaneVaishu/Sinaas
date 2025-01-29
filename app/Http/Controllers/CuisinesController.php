<?php

namespace App\Http\Controllers;

use App\Models\Cuisines;
use Illuminate\Http\Request;

use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CuisinesController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        //$business_id = $request->business_id;
        $results = Cuisines::where('status', 1)->get();
    
        $cuisines = [];
        foreach($results as $result){          

            $cuisines[] = [
                "cusinie_id" => (string)$result->id,
                "name" => $result->name,
                "status" => (string)$result->status];
        }        

        return $this->success([
            'cuisines' => $cuisines,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }   


    public function info(Request $request)
    {
       // $request->validated();
        $user_id = Auth::user()->id;        

        $result = Categories::query()->leftjoin("products_names", 'products_names.id', '=', 'categories.categoryname_id')->where('categories.id', $request->category_id)->first();

        if($result->category_image){
            $category_image = env('APP_URL').Storage::url('categories/'.$result->category_image);
        }
        else{
            $category_image = env('APP_URL').Storage::url('no-image.jpg');
        }

        if($result->categorybg_image){
            $categorybg_image = env('APP_URL').Storage::url('categories_bg/'.$result->categorybg_image);
        }
        else{
            $categorybg_image = env('APP_URL').Storage::url('no-image.jpg');
        }


        $subcatgeorieslist = SubCategories::query()->leftjoin("products_names", 'products_names.id', '=', 'sub_categories.subcategoryname_id')->select("sub_categories.*","sub_categories.id as id","sub_categories.status as status","products_names.name as name")->where('category_id', $request->category_id)->get();
           

        $subcategories = [];
        foreach($subcatgeorieslist as $sub){
            if($sub->subcategory_image){
                $subcategory_image = env('APP_URL').Storage::url('subcategories/'.$sub->subcategory_image);
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
