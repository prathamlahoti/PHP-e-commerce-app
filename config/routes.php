<?php
    return [
        'product/([0-9]+)'                => 'product/view/$1',
        'catalog/page-([0-9]+)'           => 'catalog/index/$1',
        'recommended/page-([0-9]+)'       => 'catalog/recommended/$1',
        'new/page-([0-9]+)'               => 'catalog/new/$1',
        'category/([0-9]+)/page-([0-9]+)' => 'catalog/category/$1/$2',
        'category/([0-9]+)'               => 'catalog/category/$1',

        //User
        'user/register' => 'user/register',
        'user/login'    => 'user/login',
        'user/logout'   => 'user/logout',
        'cabinet/edit'  => 'cabinet/edit',
        'cabinet'       => 'cabinet/index',
        // Product control

        'admin/product/create'          => 'adminProduct/create',
        'admin/product/update/([0-9]+)' => 'adminProduct/update/$1',
        'admin/product/delete/([0-9]+)' => 'adminProduct/delete/$1',
        'admin/product'                 => 'adminProduct/index',

        // Categories control
        'admin/category/create'          => 'adminCategory/create',
        'admin/category/update/([0-9]+)' => 'adminCategory/update/$1',
        'admin/category/delete/([0-9]+)' => 'adminCategory/delete/$1',
        'admin/category'                 => 'adminCategory/index',

        // Orders control
        'admin/order/update/([0-9]+)' => 'adminOrder/update/$1',
        'admin/order/delete/([0-9]+)' => 'adminOrder/delete/$1',
        'admin/order/view/([0-9]+)'   => 'adminOrder/view/$1',
        'admin/order'                 => 'adminOrder/index',

        // Administrator
        'admin' => 'admin/index',

        // Cart
        'cart/delete([0-9]+)'  => 'cart/delete/$1',
        'cart/add/([0-9]+)'    => 'cart/add/$1', // actionAdd в CartController
        'cart/checkout'        => 'cart/checkout',
        'cart/delete/([0-9]+)' => 'cart/delete/$1',
        'cart'                 => 'cart/index',

        // Another
        'about'    => 'site/about',
        'faq'      => 'site/faq',
        'contacts' => 'site/contacts',

        // Main
        '' => 'site/index',
    ];
