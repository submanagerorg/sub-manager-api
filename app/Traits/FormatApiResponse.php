<?php

namespace App\Traits;
trait FormatApiResponse
{
    public function formatApiResponse($status, $message, $data = [], $errors = []){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ], $status);
    }
}