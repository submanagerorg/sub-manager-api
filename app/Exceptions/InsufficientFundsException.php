<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InsufficientFundsException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        return response()->json([
            'status' => 500,
            'message' => $this->getMessage(),
        ], 500);
    }
}
