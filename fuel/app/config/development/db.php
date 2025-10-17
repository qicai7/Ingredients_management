<?php
/**
 * Development database settings.
 * グローバル設定とマージされます。
 */

return array(
    'default' => array(
        'type'        => 'mysqli',
        'connection'  => array(
            'hostname'   => 'db',                      // Docker Compose の MySQL サービス名
            'port'       => '3306',
            'database'   => 'recipe_ingredients_db',  // 使用するデータベース名
            'username'   => 'root',                    // MySQL ユーザー名
            'password'   => 'root',                    // MySQL パスワード
            'persistent' => false,
        ),
        'identifier'   => '`',
        'table_prefix' => '',
        'charset'      => 'utf8',
        'collation'    => 'utf8_unicode_ci',
        'enable_cache' => true,
        'profiling'    => false,
    ),
);

