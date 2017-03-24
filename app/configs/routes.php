<?php

return array(
    'config.routes' => array(
    	array(
            'name' => 'home',
            'pattern' => '/',
            'controller' => 'Lsystems\Controllers\IndexController::homeAction',
            'method' => 'get'
        ),
        array(
            'name' => 'index',
            'pattern' => '/{_locale}/',
            'controller' => 'Lsystems\Controllers\IndexController::indexAction',
            'method' => 'get'
        ),
        array(
            'name' => 'create',
            'pattern' => '/{_locale}/create/',
            'controller' => 'Lsystems\Controllers\IndexController::createAction',
            'method' => 'post'
        )
    ),
);