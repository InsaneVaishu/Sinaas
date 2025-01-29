<?php

namespace App\Http\Controllers;

use App\Models\Kitchens;
use App\Models\KitchenNames;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\ProductKitchens;

class KitchensController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $product_id = $request->product_id;
        $results = Kitchens::query()->leftjoin("kitchen_names", 'kitchen_names.id', '=', 'kitchens.kitchenname_id')->select("kitchens.*","kitchens.id as id","kitchens.status as status","kitchen_names.name as name")->where('kitchens.status','1')->get();

        $product_tags = ProductKitchens::where('product_id', $product_id)->get();
        $selected_list = [];
        foreach($product_tags as $sel){ 
            array_push($selected_list,$sel->tag_id);
        }
    
        $kitchens = [];
        foreach($results as $result){  
            
            $selected = '0';
            if (in_array($result->id, $selected_list)){
                $selected = '1';
            }
            
            $kitchens[] = [
                "kitchen_id" => (string)$result->id,
                "name_id" => (string)$result->kitchenname_id,
                "name" => (string)$result->name,
                "selected" => $selected,
                "status" => (string)$result->status];
        }    

        return $this->success([
            'kitchens' => $kitchens,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function names(Request $request)
    {
        $language_code = strtolower($request->language_code);

        if(!$language_code){ $language_code = 'en';}

        $results = KitchenNames::query()->select("name_".$language_code." as language_name","id")->get();
    
        $kitchens_names = [];
        foreach($results as $result){
            $kitchens_names[] = [
                "name" => $result->language_name,
                "name_id" => $result->id];
        }        

        return $this->success([
            'kitchens_names' => $kitchens_names
        ]);
    }
}
