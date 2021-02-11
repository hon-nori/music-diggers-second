<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        // Last.fm認証情報
        $tokenInfo = $request->validate([
            'token' => 'required'
        ]);

        // 認証処理
        $api_key = config('const.LAST_FM_API_KEY');
        $get_session_method = config('const.METHOD.LAST_FM_GET_SESSION');
        $token = $tokenInfo['token'];
        $sig = md5('api_key' . $api_key . 'method' . $get_session_method . 'token' . $token . config('const.LAST_FM_SECRET'));

        //auth.getSession：URL
        $get_api_url = config('const.URL.LAST_FM_API_URL');
        
        //auth.getSession：パラメータ
        $params = Array('method' => $get_session_method,
            'api_key' => $api_key,
            'token' => $token,
            'api_sig' => $sig,
            'format' => 'json');
        
        // API送信
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,
        $get_api_url . '?' . http_build_query($params));
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE);
        
        $res = curl_exec($curl);
        $header = curl_getinfo($curl);
        $code = $header["http_code"];
        if ($code >= 400) {
            header("HTTP", true, $code);
            logger('auth_error:' . $code);
        }
        $get_session_response = json_decode($res, true);
        logger($get_session_response);

        // 正常にセッションが確率された場合
        if ($get_session_response['session']['subscriber'] === 0) {
            session(['user_data.name' => $get_session_response['session']['name']]);
            session(['user_data.key' => $get_session_response['session']['key']]);
            
            return redirect('/mypage');
        }
 
        throw ValidationException::withMessages([
            'subscriber:' => $get_session_response['session']['subscriber'],
        ]);
    }
}
