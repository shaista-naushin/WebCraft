<?php

return [
    'name' => 'Website',
    'description' => 'Manage your website',
    'admin' => [
        'menu' => [
            [
                'title' => 'Website',
                'url' => '/admin/modules/website/settings',
                'fa-icon' => 'fas fa-globe'
            ]
        ],
        'module_menu' => [
            [
                'title' => 'Settings',
                'url' => '/admin/modules/website/settings'
            ]
        ]
    ],
    'user' => [
        'menu' => [

        ]
    ]
];
