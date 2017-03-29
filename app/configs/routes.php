<?php

return [
    'config.routes' => [
    	[
            'name' => 'home',
            'pattern' => '/',
            'controller' => 'Lsystems\Controllers\IndexController::homeAction',
            'method' => 'get'
        ],
        [
            'name' => 'index',
            'pattern' => '/{_locale}/',
            'controller' => 'Lsystems\Controllers\IndexController::indexAction',
            'method' => 'get'
        ],
        [
            'name' => 'create',
            'pattern' => '/{_locale}/create/',
            'controller' => 'Lsystems\Controllers\IndexController::createAction',
            'method' => 'post'
        ]
    ],
];