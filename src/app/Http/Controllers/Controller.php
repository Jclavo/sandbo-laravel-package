<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message, $records = 0)
    {
        $response = [
            'status' => true,
            'result' => $result,
            'message' => $message,
            'records' => is_countable($result) ? count($result) : 1
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 500)
    {
        $response = ['status' => false, 'result' => $errorMessages, 'message' => $error, 'records' => 0];

        return response()->json($response, $code);
    }
}
