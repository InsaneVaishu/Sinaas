<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\ProductTags;
use Illuminate\Http\Request;
use App\Models\ProductsNames;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Storage;

class TagsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $product_id = $request->product_id;
        $results = Tags::query()->leftjoin("products_names", 'products_names.id', '=', 'tags.tagname_id')->select("tags.*","tags.id as id","tags.status as status","products_names.name as name")->where('status','1')->get();

        $product_tags = ProductTags::where('product_id', $product_id)->get();
        $selected_list = [];
        foreach($product_tags as $sel){ 
            array_push($selected_list,$sel->tag_id);
        }

        //print_r($selected_list); exit;

        $tags = [];
        foreach($results as $result){   
            
            if($result->image){
                $image = env('APP_URL').Storage::url($result->tag_image);
            }
            else{
                $image = env('APP_URL').Storage::url('no-image.jpg');
            }  
            $selected = '0';
            if (in_array($result->id, $selected_list)){
                $selected = '1';
            }           

            $tags[] = [
                "tag_id" => (string)$result->id,
                "name_id" => (string)$result->tagname_id,
                "name" => (string)$result->name,
                "image" => $image,
                "selected" => $selected,
                "status" => (string)$result->status];
        }    

        return $this->success([
            'tags' => $tags,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function names(Request $request)
    {
        $language_code = strtolower($request->language_code);

        if(!$language_code){ $language_code = 'en';}

        $results = ProductsNames::query()->select("name_".$language_code." as language_name","id")->get();
    
        $tags_names = [];
        foreach($results as $result){
            $tags_names[] = [
                "name" => $result->language_name,
                "name_id" => $result->id];
        }        

        return $this->success([
            'tags_names' => $tags_names
        ]);
    }
}
