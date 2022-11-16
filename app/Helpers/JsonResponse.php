<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class JsonResponse extends Response
{
    /**
     * construct the json response class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * resturn success response
     *
     * @param array $data
     * @param string $message
     * @param integer $statusCode
     * @return \Illuminate\Http\Response
     */
    public function success($data = [], $message = 'success', $statusCode = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * return error response
     *
     * @param array $data
     * @param string $message
     * @param integer $statusCode
     * @return \Illuminate\Http\Response
     */
    public function fail($data = [], $message = 'fail', $statusCode = 400)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
