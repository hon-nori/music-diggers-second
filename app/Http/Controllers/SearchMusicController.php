<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NoticeService;

// 入力内容を元に該当する音楽情報を取得する
class SearchMusicController extends Controller
{
    protected $noticeService;

    public function __construct(NoticeService $notice_service)
    {
        $this->noticeService = $notice_service;
    }
    public function index(Request $request)
    {
        logger($request);

        $mypage_input = $request->all();

        // APIのベースとなるURL
        $api_base_url = config('const.URL.LAST_FM_API_URL');
        // DIG報用
        $dig_curl = curl_init();
        curl_setopt($dig_curl, CURLOPT_VERBOSE, true);
        curl_setopt ($dig_curl, CURLOPT_RETURNTRANSFER, TRUE);

        // 通知タイプ
        $dig_type = $mypage_input['dig_type'];
        // 連携情報
        $link_type = $mypage_input['link_type'];
        // 連携期間
        $link_period = $mypage_input['link_period'];

        // 連携情報
        if($link_type == config('const.LAST_FM.LINK_TYPE.ARTISTS')) {
            // アーティストから取得する場合
            $artist_info = $this->getArtistLinks($link_period);
            $message = $this->getSimilarArtist($artist_info['name']);
            //$this->send($message);
        } else {
            // 曲から取得する場合
            $message = $this->getTrackLinks($link_type, $link_period);
        }

        $this->noticeService->send_text_mail($message);

    }

    /**
     * 連携情報を取得する(アーティスト)
     * ランダムで１アーティスト取得する
     *
     * @param [String] $link_period
     * @return artist_info
     */
    private function getArtistLinks(String $link_period) {

        $link_curl = curl_init();
        curl_setopt($link_curl, CURLOPT_VERBOSE, true);
        curl_setopt ($link_curl, CURLOPT_RETURNTRANSFER, TRUE);

        $params = Array(
            'method' => config('const.METHOD.LAST_FM_ARTIST_RANK'),
            'api_key'=> config('const.LAST_FM_API_KEY'),
            'user'  => session('user_data.name'),
            'limit'  => 100,
            'period' => $link_period,
            'format' => 'json'
        );
        curl_setopt($link_curl, CURLOPT_URL,
        config('const.URL.LAST_FM_API_URL') . '?' . http_build_query($params));

        $res = curl_exec($link_curl);
        $header = curl_getinfo($link_curl);
        $code = $header["http_code"];
        if ($code >= 400) {
            header("HTTP", true, $code);
            logger('auth_error:' . $code);
        }
        $get_links_response = json_decode($res, true);
        $artist_info = $get_links_response['topartists']['artist'];
        shuffle($artist_info);
        return $artist_info[0];
    }

    /**
     * 連携情報を取得する
     * ランダムで１曲取得する
     *
     * @param String $link_type
     * @param String $link_period
     * @return song_info
     */
    private function getTrackLinks(String $link_type, String $link_period) {

        $link_curl = curl_init();
        $links_array = array();

        curl_setopt($link_curl, CURLOPT_VERBOSE, true);
        curl_setopt ($link_curl, CURLOPT_RETURNTRANSFER, TRUE);
        switch ($link_type) {
            case config('const.LAST_FM.LINK_TYPE.SONGS'):
                $params = Array(
                    'method' => config('const.METHOD.LAST_FM_PLAY_RANK'),
                    'api_key'=> config('const.LAST_FM_API_KEY'),
                    'user'  => session('user_data.name'),
                    'limit'  => 30,
                    'period' => $link_period,
                    'format' => 'json');
                break;
            case config('const.LAST_FM.LINK_TYPE.LOVES'):
                $params = Array(
                    'method' => config('const.METHOD.LAST_FM_LOVE_TRACK'),
                    'api_key'=> config('const.LAST_FM_API_KEY'),
                    'user'  => session('user_data.name'),
                    'format' => 'json');
                break;
        }
        //
        curl_setopt($link_curl, CURLOPT_URL,
        config('const.URL.LAST_FM_API_URL') . '?' . http_build_query($params));

        $res = curl_exec($link_curl);
        $header = curl_getinfo($link_curl);
        $code = $header["http_code"];
        if ($code >= 400) {
            header("HTTP", true, $code);
            logger('auth_error:' . $code);
        }
        $get_links_response = json_decode($res, true);
        switch ($link_type) {
            case config('const.LAST_FM.LINK_TYPE.SONGS'):
                $links_array = $get_links_response['toptracks']['track'];
                break;
            case config('const.LAST_FM.LINK_TYPE.LOVES'):
                $links_array = $get_links_response['lovedtracks']['track'];
                break;
        }
        shuffle($links_array);
        // 連続でAPIを投げると上手く結果が返ってこない為、やむなくsleep
        switch ($link_type) {
            case config('const.LAST_FM.LINK_TYPE.SONGS'):
                sleep(15);
                break;
            case config('const.LAST_FM.LINK_TYPE.LOVES'):
                sleep(10);
                break;
            }
        return $this->similarTracks($links_array);
    }

    /**
     * Undocumented function
     *
     * @param array $links_array
     * @return string $message
     */
    private function similarTracks(array $links_array) 
    {
        $message = "オススメ楽曲の一覧です\n";
        $link_curl = curl_init();
        curl_setopt($link_curl, CURLOPT_VERBOSE, true);
        curl_setopt ($link_curl, CURLOPT_RETURNTRANSFER, TRUE);
        $fp = fopen(storage_path('logs/curl.log'), 'a');
        curl_setopt ($link_curl, CURLOPT_STDERR, $fp);
        $params = Array(
            'method' => config('const.METHOD.LAST_FM_TRACK_SIMILAR'),
            'api_key'=> config('const.LAST_FM_API_KEY'),
            'artist'  => $links_array[0]['artist']['name'],
            'track'  => $links_array[0]['name'],
            'limit'  => 30,
            'format' => 'json'
        );
        curl_setopt($link_curl, CURLOPT_URL,
        config('const.URL.LAST_FM_API_URL') . '?' . http_build_query($params));

        $res = curl_exec($link_curl);
        $header = curl_getinfo($link_curl);
        $code = $header["http_code"];
        if ($code >= 400) {
            header("HTTP", true, $code);
            logger('auth_error:' . $code);
        }
        $get_song_response = json_decode($res, true);
        logger($get_song_response);
        foreach($get_song_response['similartracks']['track'] as $key => $track) {
            $message .= "name:" . $track['name'] . "\n" . "artist:" . $track['artist']['name'] . "\n" . "url:" . $track['url'] . "\n" . '----' . "\n";
        }
        return $message;
    }

    /**
     * オススメのアーティスト情報を取得する
     * 通知用のメッセージを返す
     *
     * @param [String] $artist_name
     * @return [String] $message
     */
    private function getSimilarArtist(String $artist_name) {

        $response_artist_List = array();
        $message = "オススメアーティストの一覧です\n";
        $link_curl = curl_init();
        curl_setopt($link_curl, CURLOPT_VERBOSE, true);
        curl_setopt ($link_curl, CURLOPT_RETURNTRANSFER, TRUE);

        $params = Array(
            'method' => config('const.METHOD.LAST_FM_ARTIST_SIMILAR'),
            'api_key'=> config('const.LAST_FM_API_KEY'),
            'artist' => $artist_name,
            'limit'  => 100,
            'format' => 'json'
        );
        curl_setopt($link_curl, CURLOPT_URL,
        config('const.URL.LAST_FM_API_URL') . '?' . http_build_query($params));

        $res = curl_exec($link_curl);
        $header = curl_getinfo($link_curl);
        $code = $header["http_code"];
        if ($code >= 400) {
            header("HTTP", true, $code);
            logger('auth_error:' . $code);
        }
        $get_artist_response = json_decode($res, true);

        foreach($get_artist_response['similarartists']['artist'] as $key => $artist) {
            $message .= "name:" . $artist['name'] . "\n" . "url:" . $artist['url'] . "\n" . '----' . "\n";
        }
        return $message;
    }
}
