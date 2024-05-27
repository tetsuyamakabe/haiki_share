<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    // フロントエンドのVueRouterのリクエスト処理
    public function index()
    {
        return view('app'); // appビューを返す
    }
}
