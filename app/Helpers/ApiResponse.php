<?php

namespace App\Helpers;

class ApiResponse {
    static function sendResponse($message = null , $code = 200 , $data = null) {
        $response = [
            "status" => $code ,
            "message" => $message ,
            "data" => $data
        ];

        return response()->json($response,$code);
    }
}