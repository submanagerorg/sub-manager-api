<?php

trait FormatApiResponse
{
    public function formatApiResponse($status, $message, $data = [], $errors = []){
        return response()->json([
            'data' => $data,
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ]);
    }
}