<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionCheckController extends Controller
{
    // セッションチェック
    public function index(Request $request)
   {
       logger(session()->all());
          //TODO: 期限チェック
          return !empty(session('user_data'));
   }
}
