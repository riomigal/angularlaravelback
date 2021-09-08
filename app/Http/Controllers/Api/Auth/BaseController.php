<?php


namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use stdClass;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result = null, $message)
    {

        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }


    /**
     * return error response. Objects -> Replicate Laravel form validation errors
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages, $code = 404)
    {

        $response = new stdClass();
        $response->message = $error;

        if (!empty($errorMessages)) {
            $errors = new stdClass();
            foreach ($errorMessages as $key => $value) {
                $errors->$key = $value;
            }
            $response->errors = $errors;
        }

        return response()->json($response, $code);
    }
}