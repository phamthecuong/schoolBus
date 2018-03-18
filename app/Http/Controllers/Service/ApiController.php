<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformer\TripTransformer;


class ApiController extends Controller
{
    protected function responses(TripTransformer $data)
    {
        // return response($data->result(), 200);
        return response([
            'data' => $data->result()
        ]);
    }

    protected function errorNotFound($message = null)
    {
        return response($message, 404);
    }

    protected function errorInvalid($message = null)
    {
        return response($message, 422);
    }

    protected function errorInternalError()
    {
        return response('', 500);
    }

    protected function errorAuthenticationFailed($message)
    {
        return response($message, 403);
    }

    protected function errorBadRequest($message)
    {
        return response($message, 400);
    }

    protected function responseNoContent()
    {
        return response('', 204);
    }
}

