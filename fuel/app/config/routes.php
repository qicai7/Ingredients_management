<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	'user/login' => 'user/login',
        'register' => 'user/register',

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
