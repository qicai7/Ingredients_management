<?php
return array(
	'_root_'  => 'home/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

	//  修正後: 短縮形 ('POST' => 'Controller/Method')
    'api/user/register' => array(
        'POST' => 'api/user/register',
    ),
    
    // フォーム表示用 - user/register へのGETアクセスを明確にする
    // FuelPHPはデフォルトでルーティングを解決しますが、明記することで安全性が高まります
    'user/register' => 'user/register',

	'user/login' => 'user/login',
        // 'register' => 'user/register',
	'delete/(:num)' => 'recipe/confirm_delete/$1',
	'delete_recipe/(:num)' => 'recipe/delete/$1',

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
