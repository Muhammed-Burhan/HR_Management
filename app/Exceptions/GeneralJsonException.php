<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Exception;

class GeneralJsonException extends Exception
{
     protected $code = 422;

   
    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     * @param \Illuminate\Http\Request $request
     */
    public function render($request)
    {   
        $message='Record not found.';
        return new JsonResponse([
            'error' => [
                'message' => $this->getMessage()
            ]
        ], $this->code);
    }
}
