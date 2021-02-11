<?php 
return [
    'LAST_FM_API_KEY' => env('LAST_FM_API_KEY', null),
    'LAST_FM_SECRET' => env('LAST_FM_SECRET', null),
    'URL' => [
        'LAST_FM_AUTH_URL' => 'https://www.last.fm/api/auth',       // Last.fm連携許可
        'LAST_FM_API_URL' => 'http://ws.audioscrobbler.com/2.0/'    // APIルートURL
    ],
    'METHOD' => [
        // 認証情報
        'LAST_FM_GET_SESSION' => 'auth.getSession',      // セッションキーを取得
        // 取得情報
        'LAST_FM_TRACK_SIMILAR' => 'track.getSimilar',      // 曲として似てるもの
        'LAST_FM_ARTIST_SIMILAR' => 'artist.getSimilar',     // アーティストとして似てるもの
        // 設定元情報
        'LAST_FM_ARTIST_RANK' => 'user.getTopArtists',     // [指定ユーザのアーティストリスト(ランキング)
        'LAST_FM_PLAY_RANK' => 'user.gettoptracks',     // 指定ユーザの再生リスト(ランキング)
        'LAST_FM_LOVE_TRACK' => 'user.getLovedTracks',     // [ユーザのLoveトラック](最新50)
        'LAST_FM_ARTIST_LIST' => 'library.getartists',     // アーティストリスト
    ],
    'LAST_FM' => [
        // DIGタイプ
        'DIG_TYPE' => [
            'ARTISTS' => 'artists',
            'SONGS'   => 'songs'
        ],
        // 連携タイプ
        'LINK_TYPE' => [
            'ARTISTS' => 'artists',
            'SONGS'   => 'songs',
            'LOVES'   => 'loves'
        ],
        // 連携期間
        'LINK_PERIOD' => [
            'OVERALL' => 'overall',
            '7DAY'   => '7day',
            '1MONTH'   => '1month',
            '3MONTH'   => '3month',
            '6MONTH'   => '6month',
            '12MONTH'   => '12month'
        ]

    ]

];