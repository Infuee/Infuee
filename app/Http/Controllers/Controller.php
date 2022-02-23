<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     protected function respondWithToken($token, $user=false,$code,$message){
        $response = [
            'message' => $message,  
            'code' => $code, 
            'token' => $token,
            'token_type' => 'bearer',            
        ];

        if($user){
            $response['user'] = $user;
        }

        return response()->json($response, 200);
    }

    protected function respondWithoutToken($code,$message){
        $response = [
            'message' => $message,  
            'code' => $code,                       
        ];
        return response()->json($response, 200);
    }
    protected function respondData($code,$message,$data, $index = 'data', $moreData = false){
        $response = [
            'message' => $message,  
            'code' => $code,           
            $index => $data,                    
        ];
        if($moreData){
            $response = array_merge($response,$moreData) ;
        }
        return response()->json($response, 200);
    }
}
