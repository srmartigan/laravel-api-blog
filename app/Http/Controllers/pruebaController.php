<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class pruebaController extends Controller
{
    function index(Request $request)
    {
        $jwtAuth = new JwtAuth();

        if ($jwtAuth->checkToken($request->header('token'))){
            return view('welcome');
        }
        return response([
            'status' => 'error',
            'code' => '400',
            'message' => 'No estas Autenticado'
        ]);
    }
}
