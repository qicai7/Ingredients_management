return array(
    'default' => array(
        'type'        => 'mysql',
        'connection'  => array(
            'hostname'   => 'db',                   // Docker Compose の MySQL サービス名
            'port'       => '3306',
            'database'   => 'recipe_ingredients_db', // データベース名
            'username'   => 'root',                 // MySQL ユーザー名
            'password'   => 'root',                 // MySQL パスワード
            'persistent' => false,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => false,
        'profiling'    => true,
    ),
);

