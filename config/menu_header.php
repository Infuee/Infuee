<?php
// Header menu
return [

    'items' => [
        [],
        [
            'title' => 'Dashboard',
            'root' => true,
            'page' => '/admin/dashboard',
            'new-tab' => false,
        ],
        [
            'title' => 'Manage Users',
            'root' => true,
            'new-tab' => false,
            'page' => '/admin/users',
        ],
        [
            'title' => 'Manage Influencers Requests',
            'root' => true,
            'new-tab' => false,
            'page' => '/admin/influencers',
        ]
    ]

];
