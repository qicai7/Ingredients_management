<?php

return array(
    'driver'                 => 'Simpleauth',  // SimpleAuthを使用
    'verify_multiple_logins' => false,
    'salt'                   => 'Thls=mY0Wn_$@|+', // パスワードハッシュ用
    'iterations'             => 10000,        // ハッシュ反復回数
    'user_fields'            => array(
        'username' => 'username',
        'password' => 'password',
    ),
);
