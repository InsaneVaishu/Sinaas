<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Options;
use App\Models\OrderOptions;
use Illuminate\Http\Request;
use App\Models\OrderProducts;
use App\Traits\HttpResponses;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrdersController extends Controller
{
    use HttpResponses;

    public function list(Request $request)
    {
        $business_id = $request->business_id;
        $frequency = $request->frequency; 

        $pending=0;
        $total = 0.0;
        $count=0;
        $stats = array();
        if($frequency=='1'){ // All          
            $results = Orders::query()->leftjoin("order_statuses", 'order_statuses.id', '=', 'orders.order_status_id')->select("orders.*","orders.id as id","orders.order_status_id as status","order_statuses.name as name")->where('business_id', $business_id)->orderBy('created_at', 'DESC')->get();

            $stats['total'] = Orders::query()->where('business_id', $business_id)->sum('order_total');
            $stats['count'] = count($results);            
            foreach($results as $resultp){
                if($resultp->status==1){
                    $pending = $pending+1;
                }
            }
            $stats['pending'] = $pending;
        }elseif($frequency=='2'){ // Yesterday

            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
            //$countYesterday = Timer::whereDate('created_at', $yesterday )->get();

            $results = Orders::query()->leftjoin("order_statuses", 'order_statuses.id', '=', 'orders.order_status_id')->select("orders.*","orders.id as id","orders.order_status_id as status","order_statuses.name as name")->where('business_id', $business_id)->whereDate('created_at', $yesterday )->orderBy('created_at', 'DESC')->get();

            $stats['total'] = Orders::query()->where('business_id', $business_id)->whereDate('created_at', $yesterday )->sum('order_total');
            $stats['count'] = count($results);            
            foreach($results as $resultp){
                if($resultp->status==1){
                    $pending = $pending+1;
                }
            }
            $stats['pending'] = $pending;

        }elseif($frequency=='3'){ // Last Week
                       
            $results = Orders::query()->leftjoin("order_statuses", 'order_statuses.id', '=', 'orders.order_status_id')->select("orders.*","orders.id as id","orders.order_status_id as status","order_statuses.name as name")->where('business_id', $business_id)->whereBetween('created_at', [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->subDays(7)->endOfWeek()])->orderBy('created_at', 'DESC')->get();

            $stats['total'] = Orders::query()->where('business_id', $business_id)->whereBetween('created_at', [Carbon::now()->subDays(7)->startOfWeek(), Carbon::now()->subDays(7)->endOfWeek()])->sum('order_total');
            $stats['count'] = count($results);            
            foreach($results as $resultp){
                if($resultp->status==1){
                    $pending = $pending+1;
                }
            }
            $stats['pending'] = $pending;

        }elseif($frequency=='4'){ // This week
                       
            $results = Orders::query()->leftjoin("order_statuses", 'order_statuses.id', '=', 'orders.order_status_id')->select("orders.*","orders.id as id","orders.order_status_id as status","order_statuses.name as name")->where('business_id', $business_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])->orderBy('created_at', 'DESC')->get();

            $stats['total'] = Orders::query()->where('business_id', $business_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])->sum('order_total');
            $stats['count'] = count($results);            
            foreach($results as $resultp){
                if($resultp->status==1){
                    $pending = $pending+1;
                }
            }
            $stats['pending'] = $pending;

        }elseif($frequency=='5'){ // last Month

            //$fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            //$tillDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();
                       
            $results = Orders::query()->leftjoin("order_statuses", 'order_statuses.id', '=', 'orders.order_status_id')->select("orders.*","orders.id as id","orders.order_status_id as status","order_statuses.name as name")->where('business_id', $business_id)->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth()->toDateString(), Carbon::now()->subMonth()->endOfMonth()->toDateString()])->orderBy('created_at', 'DESC')->get();

            $stats['total'] = Orders::query()->where('business_id', $business_id)->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth()->toDateString(), Carbon::now()->subMonth()->endOfMonth()->toDateString()])->sum('order_total');
            $stats['count'] = count($results);            
            foreach($results as $resultp){
                if($resultp->status==1){
                    $pending = $pending+1;
                }
            }
            $stats['pending'] = $pending;
            
        }       


        $orders = [];
        foreach($results as $result){             
            
                $payment_type_icon = env('APP_URL').Storage::url('icon/payment/'.$result->payment_type.'.png');
                $shipping_type_icon = env('APP_URL').Storage::url('icon/shipping/'.$result->order_type.'.png');   

                $payment_type = ['0' => '',
                                '1' => 'Cash',
                                '2' => 'UPI',
                                '3' => 'Net banking',
                                '4' => 'Check',
                                '5' => 'POS'];

                $order_type = ['1' => 'All',
                                '2' => 'Eat-in',
                                '3' => 'Pickup',
                                '4' => 'Delivery'];

                $orders[] = [
                    "order_id" => (string)$result->id,
                    "business_id" => (string)$result->business_id,
                    "price" => $result->order_total,
                    "payment_type_icon" => $payment_type_icon,
                    "payment_type" => $payment_type[$result->payment_type],
                    "shipping_type_icon" => $shipping_type_icon,
                    "shipping_type" => $order_type[$result->order_type],
                    "order_satatus" => (string)$result->business_id,
                    "order_date" => (string)$result->created_at,
                    "status" => (string)$result->status];
            
        }    

        return $this->success([
            'orders' => $orders,
            'stats' => $stats,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function info(Request $request)
    {
        $order_id = $request->order_id;
                   
        
        $result = Orders::query()->leftjoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')->leftjoin('currencies', 'currencies.id', '=', 'orders.order_currency')->select('orders.*','orders.id as id','orders.order_status_id as status','order_statuses.name as name','currencies.left_symbol as left_symbol')->where('orders.id', $order_id)->where('business_id', $request->business_id)->first();


        $orders = [];
                     
            
            $payment_type_icon = env('APP_URL').Storage::url('icon/payment/'.$result->payment_type.'.png');
            $shipping_type_icon = env('APP_URL').Storage::url('icon/shipping/'.$result->order_type.'.png');   

            $payment_type = ['0' => '',
                            '1' => 'Cash',
                            '2' => 'UPI',
                            '3' => 'Net banking',
                            '4' => 'Check',
                            '5' => 'POS'];

            $order_type = ['1' => 'All',
                            '2' => 'Eat-in',
                            '3' => 'Pickup',
                            '4' => 'Delivery'];           


            $products = OrderProducts::query()->leftjoin("products", 'products.id', '=', 'order_products.product_id')->leftjoin("products_names", 'products_names.id', '=', 'products.productname_id')->select("products.*","products.id as id","products.status as status","products_names.name as name",)->where('order_products.order_id', $order_id)->get();

            $products_list = [];
            foreach($products as $product){
                //print_r($product); exit;
                if($product->image){
                    $image = env('APP_URL').Storage::url($product->image);
                }
                else{
                    $image = env('APP_URL').Storage::url('no-image.jpg');
                }

                $options = OrderOptions::query()->leftjoin("options", 'options.id', '=', 'order_options.product_option_id')->leftjoin("products_names", 'products_names.id', '=', 'order_options.name_id')->select("options.*","options.id as id","products_names.name as name")->where('order_id', $order_id)->where('order_product_id', $product->id)->get();
  
        //print_r($product); exit;
                $options_list = [];
                foreach($options as $option){
                    $options_list[] = [
                        "option_id" => (string)$option->id,
                        "name" => (string)$option->name,
                        "price" => (string)$option->value,
                        "currency" => $result->left_symbol,
                        //"status" => (string)$option->status
                    ];
                }


                $products_list[] = [
                    "product_id" => (string)$product->id,
                    "name" => $product->name,
                    "image" => $image,
                    "quantity" => $product->quantity,
                    "price" => $product->price,
                    "currency" => $result->left_symbol,
                    "options" => $options_list
                ];

            }

            $order_date =  date('d/m/Y',strtotime($result->created_at));
            $order_time =   date('H:i A',strtotime($result->created_at));
            $modified_date = date('d/m/Y',strtotime($result->updated_at));
            $modified_time = date('H:i A',strtotime($result->updated_at));


            $order_info = [
                "order_id" => (string)$result->id,
                "business_id" => (string)$result->business_id,
                "price" => $result->order_total,
                "currency" => $result->left_symbol,
                "payment_type_icon" => $payment_type_icon,
                "payment_type" => $payment_type[$result->payment_type],
                "shipping_type_icon" => $shipping_type_icon,
                "shipping_type" => $order_type[$result->order_type],
                "order_satatus" => (string)$result->business_id,
                "order_date" => (string)$order_date,
                "order_time" => (string)$order_time,
                "modified_date" => (string)$modified_date,
                "modified_time" => (string)$modified_time,
                "status" => (string)$result->status,
                "products" => $products_list
            ];
                      

        return $this->success([
            'order_info' => $order_info,
            //'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}
