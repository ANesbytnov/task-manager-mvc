<?php
	
return [
	'/' => [
		'controller' => 'main',
		'action' => 'index'
	],
	'/main/addtask' => [
		'controller' => 'main',
		'action' => 'addtask'
	],
	'/admin/login' => [
		'controller' => 'admin',
		'action' => 'login'
	],
	'/admin/logout' => [
		'controller' => 'admin',
		'action' => 'logout'
	],
	'/admin/updatetask' => [
		'controller' => 'admin',
		'action' => 'updatetask'
	],
];