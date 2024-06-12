<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $param = $request->input('param');
        \Log::info('GETパラメータの$paramは、' . $param);
        return response()->json(['message' => 'パラメータが届きました。']);
    }
}