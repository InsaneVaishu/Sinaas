<?php

namespace App\Traits;

trait HttpResponses {
    
    protected function success($data, string $message = '', int $code = 200)
    {
        if(!$message){$message ='Your request was successful!';}
        return response()->json([
            "success"=> [
                "message"=> $message,
                "status"=> $code,
            ],
            "data" => $data
        ]);
    }
    
    protected function error($data, string $message = '', int $code = 400 )
    {
        if(!$message){$message ='Your request was error!';}
        return response()->json([
            "error"=> [
                "message"=> $message,
                "status"=> $code,
            ]
        ]);
    }
}