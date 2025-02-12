<?php

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagsController;

//use App\Http\Controllers\TasksController;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CuisinesController;
use App\Http\Controllers\KitchensController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomizesController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/dashboard/login', [AuthController::class, 'login']);
//Route::post('/register', [AuthController::class, 'register']);


//Route::resource('/dashboard/settings/language', SettingsController::class);


Route::controller(SettingsController::class)->group(function () {
    Route::get('/dashboard/settings/language', 'language');
    Route::get('/dashboard/settings/taxes', 'taxes');
    Route::get('/dashboard/settings/countries', 'countries');
    Route::get('/dashboard/settings/units', 'units');
   // Route::post('/orders', 'store');
});



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/dashboard/logout', [AuthController::class, 'logout']);

    Route::post('/dashboard/profile/edit', [AuthController::class, 'edit']);
    Route::get('/dashboard/profile/view', [AuthController::class, 'view']);
    
    Route::resource('/dashboard/business/list', BusinessController::class);    
    Route::post('/dashboard/business/store', [BusinessController::class, 'store']);
    Route::post('/dashboard/business/edit', [BusinessController::class, 'edit']);
    Route::post('/dashboard/business/info', [BusinessController::class, 'info']);
    Route::post('/dashboard/business/update_status', [BusinessController::class, 'update_status']);
    Route::post('/dashboard/business/update_billing', [BusinessController::class, 'update_billing']);    
    Route::post('/dashboard/business/get_billing', [BusinessController::class, 'get_billing']);

    Route::post('/dashboard/business/update_working', [BusinessController::class, 'update_working']);
    Route::post('/dashboard/business/get_working', [BusinessController::class, 'get_working']);
    Route::post('/dashboard/business/update_delivery', [BusinessController::class, 'update_delivery']);
    Route::post('/dashboard/business/get_delivery', [BusinessController::class, 'get_delivery']);

    Route::post('/dashboard/catagory/list',  [CategoriesController::class, 'list']);
    Route::post('/dashboard/catagory/names',  [CategoriesController::class, 'names']);
    Route::post('/dashboard/catagory/add',  [CategoriesController::class, 'add']);
    Route::post('/dashboard/catagory/edit',  [CategoriesController::class, 'edit']);
    Route::post('/dashboard/catagory/info',  [CategoriesController::class, 'info']);
    Route::post('/dashboard/catagory/update_status', [CategoriesController::class, 'update_status']);
    Route::post('/dashboard/catagory/sub_update_status', [CategoriesController::class, 'sub_update_status']);
    Route::post('/dashboard/catagory/delete',  [CategoriesController::class, 'delete']);
    Route::post('/dashboard/catagory/sub_delete',  [CategoriesController::class, 'sub_delete']);
    Route::post('/dashboard/catagory/sub_list',  [CategoriesController::class, 'sub_list']);
    Route::post('/dashboard/catagory/sub_info',  [CategoriesController::class, 'sub_info']);

    Route::post('/dashboard/cuisine/list',  [CuisinesController::class, 'list']);

    Route::post('/dashboard/product/list',  [ProductsController::class, 'list']);
    Route::post('/dashboard/product/names',  [ProductsController::class, 'names']);
    Route::post('/dashboard/product/add',  [ProductsController::class, 'add']);
    Route::post('/dashboard/product/edit',  [ProductsController::class, 'edit']);
    Route::post('/dashboard/product/info',  [ProductsController::class, 'info']);
    Route::post('/dashboard/product/delete',  [ProductsController::class, 'delete']);
    Route::post('/dashboard/product/update_status',  [ProductsController::class, 'update_status']);
    Route::post('/dashboard/product/get_product_count',  [ProductsController::class, 'get_product_count']);
    Route::post('/dashboard/product/product_stocks_list',  [ProductsController::class, 'product_stocks_list']);

    Route::post('/dashboard/product/add_product_category',  [ProductsController::class, 'add_product_category']);
    Route::post('/dashboard/product/add_product_kitchen',  [ProductsController::class, 'add_product_kitchen']);
    Route::post('/dashboard/product/add_product_option',  [ProductsController::class, 'add_product_option']);
    Route::post('/dashboard/product/add_product_tag',  [ProductsController::class, 'add_product_tag']);
    Route::post('/dashboard/product/add_product_stock',  [ProductsController::class, 'add_product_stock']);  
    Route::post('/dashboard/product/add_product_options',  [ProductsController::class, 'add_product_options']);
    Route::post('/dashboard/product/edit_product_options',  [ProductsController::class, 'edit_product_options']);  
    
    Route::post('/dashboard/product/delete_product_option',  [ProductsController::class, 'delete_product_option']);  
    
    
    
    Route::post('/dashboard/orders/list',  [OrdersController::class, 'list']);
    Route::post('/dashboard/orders/info',  [OrdersController::class, 'info']);

    Route::post('/dashboard/options/list',  [OptionsController::class, 'list']);
    Route::post('/dashboard/options/info',  [OptionsController::class, 'info']);
    Route::post('/dashboard/options/stock_list',  [OptionsController::class, 'stock_list']);
    Route::post('/dashboard/options/option_names',  [OptionsController::class, 'option_names']);
    Route::post('/dashboard/options/stock_names',  [OptionsController::class, 'stock_names']); // not in use    
    Route::post('/dashboard/options/option_stocks',  [OptionsController::class, 'option_stocks']);


    Route::post('/dashboard/stocks/list',  [StocksController::class, 'list']);
    Route::post('/dashboard/stocks/add',  [StocksController::class, 'add']);
    Route::post('/dashboard/stocks/edit',  [StocksController::class, 'edit']);
    Route::post('/dashboard/stocks/info',  [StocksController::class, 'info']);
    Route::post('/dashboard/stocks/delete',  [StocksController::class, 'delete']);
    Route::post('/dashboard/stocks/update_quantity',  [StocksController::class, 'update_quantity']);
    Route::post('/dashboard/stocks/stocks_name_list',  [StocksController::class, 'stocks_name_list']);

    Route::post('/dashboard/stocks/inventory_list',  [StocksController::class, 'inventory_list']);


    Route::post('/dashboard/tags/list',  [TagsController::class, 'list']);
    Route::post('/dashboard/tags/names',  [TagsController::class, 'names']);

    Route::post('/dashboard/kitchens/list',  [KitchensController::class, 'list']);
    Route::post('/dashboard/kitchens/names',  [KitchensController::class, 'names']);

    Route::post('/dashboard/customizes/list',  [CustomizesController::class, 'list']);
    Route::post('/dashboard/customizes/info',  [CustomizesController::class, 'info']);
    Route::post('/dashboard/customizes/names',  [CustomizesController::class, 'names']);

    Route::post('/dashboard/product/add_product_customize',  [CustomizesController::class, 'add_product_customize']);
    Route::post('/dashboard/product/edit_product_customize',  [CustomizesController::class, 'edit_product_customize']);  
    
    Route::post('/dashboard/product/delete_product_customize',  [CustomizesController::class, 'delete_product_customize']);

    Route::post('/dashboard/business/image',[ImageController::class, 'imageStore']);

});