<?php
return array(
	'_root_'  => 'home/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	'user/login' => 'user/login',
        'register' => 'user/register',
	'delete/(:num)' => 'recipe/confirm_delete/$1',
	'delete_recipe/(:num)' => 'recipe/delete/$1',

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
