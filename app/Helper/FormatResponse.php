<?php

namespace App\Helper;

class FormatResponse
{
    public static function send($success, $data, $message, $code)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message
        ], $code)->header('Content-Type', 'application/json');
    }
}