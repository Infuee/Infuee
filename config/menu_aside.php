<?php
// Aside menu
return [

    'items' => [
        // Dashboard
        [
            'title' => 'Dashboard',
            'root' => true,
            'icon' => 'media/svg/icons/Layout/Layout-4-blocks.svg', // or can be 'flaticon-home' or any flaticon-*
            'page' => '/admin/dashboard',
            'new-tab' => false,
        ],

        // Custom
        [
            'section' => 'Management',
        ],
        [
            'title' => 'Manage Users',
            'icon' => 'media/svg/icons/Communication/Group.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/users',
        ],
        [
            'title' => 'Manage Influencers',
            'icon' => 'media/svg/icons/Communication/Group.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/influencers-users',
        ],
        // [
        //     'title' => 'Manage Influencers Requests',
        //     'icon' => 'media/svg/icons/Communication/Group.svg',
        //     'bullet' => 'line',
        //     'root' => true,
        //     'page' => '/admin/influencers',
        // ],
        [
            'title' => 'Manage Plan Categories',
            'icon' => 'media/svg/icons/Shopping/Money.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/categories',
        ],
        [
            'title' => 'Manage Race',
            'icon' => 'media/svg/icons/Shopping/Money.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/race',
        ],
        // Coupons
        [
            'section' => 'Manage Campaigns/Jobs',
        ],
        [
            [
                'title' => 'Manage Categories',
                'icon' => 'media/svg/icons/Shopping/Settings.svg',
                'bullet' => 'line',
                'root' => true,
                'page' => '/admin/manage-categories',
            ],
            [
                'title' => 'Social Platforms',
                'icon' => 'media/svg/icons/Shopping/MC.svg',
                'bullet' => 'line',
                'root' => true,
                'page' => '/admin/social-platforms',
                'active' => ['social-platforms', 'social-platform']
            ],
            [
                'title' => 'Campaigns',
                'icon' => 'media/svg/icons/Shopping/Box2.svg',
                'bullet' => 'line',
                'root' => true,
                'page' => '/admin/campaigns',
                'active' => ['campaigns', 'campaign']
            ],
            [
                'title' => 'Jobs',
                'icon' => 'media/svg/icons/Shopping/Box2.svg',
                'bullet' => 'line',
                'root' => true,
                'page' => '/admin/jobs',
                'active' => ['jobs', 'job']
            ],
            [
                'title' => 'Manage Testimonial',
                'icon' => 'media/svg/icons/Shopping/Box2.svg',
                'bullet' => 'line',
                'root' => true,
                'page' => '/admin/testimonial',
                'active' => ['testimonial', 'testimonial']
            ],
        ],
        [
            'section' => 'Manage Wallets',
        ],
        [
            'title' => 'Wallet',
            'icon' => 'media/svg/icons/Shopping/Wallet2.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/wallets',
        ],
        [
            'title' => 'Wallet Transaction History',
            'icon' => 'media/svg/icons/Shopping/Wallet3.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/wallet/transactions',
        ],
        [
            'title' => 'Commission Wallet Transaction',
            'icon' => 'media/svg/icons/Shopping/Wallet3.svg',
            'bullet' => 'line',
            'root' => true,
            'page' => '/admin/commission/wallet/transactions',
        ],


        // Coupons
        [
            'section' => 'Manage Coupons',
        ],
        [
            'title' => 'Manage Coupons',
            'icon' => 'media/svg/icons/Shopping/Gift.svg',
            'bullet' => 'line',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Coupons',
                    'icon' => 'media/svg/icons/Shopping/Gift.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/coupons',
                ]
            ]
        ],
        

        // User Orders
        [
            'section' => 'Manage Orders',
        ],
        [
            'title' => 'Manage Orders',
            'icon' => 'media/svg/icons/Shopping/Barcode-scan.svg',
            'bullet' => 'line',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Orders',
                    'icon' => 'media/svg/icons/Shopping/Barcode-scan.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/orders',
                ]
            ]
        ],

        // User Transaction
        [
            'section' => 'Manage Transactions',
        ],
        [
            'title' => 'Manage Transactions',
            'icon' => 'media/svg/icons/Shopping/Wallet3.svg',
            'bullet' => 'line',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Transactions History',
                    'icon' => 'media/svg/icons/Shopping/Wallet3.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/transactions',
                ]
            ]
        ],

       
        // Custom
        [
            'section' => 'Content Pages',
        ],
        [
            'title' => 'Content Pages',
            'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
            'bullet' => 'line',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Home page',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/home-page',
                ],
                [
                    'title' => 'How it works',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/how-it-works',
                ],
                [
                    'title' => 'Home page top section',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/home-page-top-section',
                ],
                [
                    'title' => 'Home page middle section',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/home-page-middle-section',
                ],
                [
                    'title' => 'Terms of Services',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/terms-of-service',
                ],
                [
                    'title' => 'Privacy Policy',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/privacy-policy',
                ],
                [
                    'title' => 'FAQ',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/faq-cat-list',
                ],
                [
                    'title' => 'User Agreement',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/user-agreement',
                ],
                [
                    'title' => 'Contact us',
                    'icon' => 'media/svg/icons/Layout/Layout-top-panel-5.svg',
                    'bullet' => 'line',
                    'root' => true,
                    'page' => '/admin/contact-list',
                ]
            ]
        ],

        

    ]

];
