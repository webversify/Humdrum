<?php

namespace App\Core;

class Configs {

    public static function Setup() {

        return [

            'title'       => 'Humdrum',

            'description' => 'The Monotonous Lightweight CMS',

            'encoding'    => 'UTF-8',

            'locale' 	  => 'EN',

            'skin'        => 'humdrum',

            'disks' 	  => [

                'theme'     => __DIR__ . '/../' . 'theme' . '/',
                'cache'     => __DIR__ . '/../' . 'cache' . '/',
                'storage'   => __DIR__ . '/../' . 'storage' . '/',

            ],

            'database'    => [

                'driver'   => 'mysql',
                'host'     => 'localhost',
                'user'     => 'username',
                'password' => 'password',
                'db'       => 'humdrum',
                'prefix'   => 'hdm_'

            ],

            'scss'        => [
                'common' => [
                    'variables',
                    'mixins',
                    'init'
                ],
                'build'  => [
                    'header',
                    'navigation',
                    'forms',
                    'content',
                    'footer',
                    'custom'
                ]
    		],

            'js'          => [
                'common' => [
                    'variables',
                    'functions',
                    'init'
                ],
                'build'  => [
                    'header',
                    'navigation',
                    'forms',
                    'content',
                    'footer',
                    'custom'
                ]
            ],

            'defaults'    => [

                'links' => [
                    'admin'          => [
                        'slug'  => 'admin',
                        'title' => 'Account'
                    ],
                    'account'        => [
                        'slug'  => 'account',
                        'title' => 'Account'
                    ],
                    'login'          => [
                        'slug'  => 'login',
                        'title' => 'Sign In'
                    ],
                    'signup'         => [
                        'slug'  => 'sign-up',
                        'title' => 'Sign Up'
                    ],
                    'forgotpassword' => [
                        'slug'  => 'forgot-password',
                        'title' => 'Forgot Password'
                    ],
                    'search'          => [
                        'slug'  => 'search',
                        'title' => 'Search'
                    ],
                    'logout'         => [
                        'slug'  => 'logout',
                        'title' => 'Sign Out'
                    ]
                ],

                'backend_pages' => [
                    'list' => [
                        'slug'  => 'list',
                        'title' => 'List'
                    ],
                    'search' => [
                        'slug'  => 'search',
                        'title' => 'Search'
                    ],
                    'edit' => [
                        'slug'  => 'edit',
                        'title' => 'Edit'
                    ],
                    'delete' => [
                        'slug'  => 'delete',
                        'title' => 'Delete'
                    ]
                ],

                'apps' => [
                    'frontend' => [
                        'security' => [
                            'setup' => 'LoadSecurity',
                        ],
                        'settings' => [
                            'info' => 'LoadSiteInfo',
                        ],
                        'pages'        => [
                            'navbar'  => 'LoadNavbar'
                        ],
                        /*
                        'products'   => [
                            'sidebar'  => 'LoadSidebar',
                        ],
                        */
                        'users'        => [
                            'allowusers' => 'AllowUsers'
                        ]
                    ],
                    'backend' => [
                        'security' => [
                            'setup' => 'LoadSecurity'
                        ],
                        'applications' => [
                            'sidebar' => 'LoadSidebar'
                        ]
                    ],
                ]

            ],

            'users' => [
                'enabled' => true
            ],

            'response_codes' => [
                1 => 'Login Is Successfull',
                2 => 'Are You Sure To Delete [%NAME%] In [%APP%] Application?',
                3 => 'Successfully Logged Off'
            ],

            'error_codes' => [
                1 => 'Application Not Found',
                2 => 'Application Is Inaccessible',
                3 => 'Login Or Password Is Incorrect',
                4 => 'Token Is Invalid'
            ],

            'paging'      => 50,
            'debug'       => true

        ];

    }

}
