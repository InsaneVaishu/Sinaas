<?php

namespace App\Http\Controllers;

use App\Models\Taxes;
use App\Models\Settings;
use App\Models\Countries;
use App\Models\Languages;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\TaxesResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\LanguageResource;
use App\Models\Units;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use HttpResponses;

    public function index()
    {
        return response()->json('Settings'); 
    }

    public function language()
    {
        /*return LanguageResource::collection(
            Languages::where('status', '1')->get()
        ); */
        $results = Languages::where('status', '1')->get();
        $languages = [];
        foreach($results as $result){
            $languages[] = ["id" => $result->id,
            "name"  => $result->name,
            "code"  => $result->code,                
            "status" => (string)$result->status];
        } 
        return $this->success([
            'lanuages' => $languages,
        ]);
    }
    
    public function taxes()
    {
        $results = Taxes::where('status', '1')->get();
        $taxes = [];
        foreach($results as $result){
            $taxes[] = ["id" => $result->id,
            "name"  => $result->name,
            "value" => $result->tax_value,  
            "type"  => $result->type,              
            "status"=> (string)$result->status];
        } 
        return $this->success([
            'taxes' => $taxes,
        ]);
    }

    public function units()
    {
        $results = Units::where('status', '1')->get();
        $units = [];
        foreach($results as $result){
            $units[] = ["id" => $result->id,
            "name"  => $result->name,
            "code" => $result->code,  
            "symbol"  => $result->symbol,              
            "value"=> (string)$result->value];
        } 
        return $this->success([
            'units' => $units,
        ]);
    }


    public function countries()
    {
        $results = Countries::where('status', '1')->get();
        $countries = [];
        foreach($results as $result){
            $countries[] = ["id" => $result->id,
            "name"  => $result->name,
            "code" => $result->iso,  
            "phonecode"  => (string)$result->phonecode,
            "flag" => env('APP_URL').Storage::url('flags/'.strtolower($result->iso).'.png'),              
            "status"=> (string)$result->status];
        } 
        return $this->success([
            'countries' => $countries,
        ]);
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show(string $id)
    {
        //
    }

    
    public function edit(string $id)
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
