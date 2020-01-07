<?php

namespace App\Units;

class Pages extends \App\Core\Middleware {

    public $schema = [];

    function __construct() {
        parent::__construct();
        $this->schema = [
            'primary'   => [
                'table'    => 'pages',
                'prefix'   => 'page_',
                'key'      => 'id',
                'parent'   => 'parent',
                'titles'   => [
                    'singular' => 'Page',
                    'plural'   => 'Pages'
                ],
                'sortable' => true,
                'display'  => [
                    [
                        'field'    => 'name',
                        'type'     => 'textbox',
                        'required' => true,
                        'unique'   => true,
                        'list'     => true,
                        'delete'   => true,
                        'childbtn' => true
                    ],
                    [
                        'field'    => 'parent',
                        'type'     => 'select',
                        'relation' => [
                            'primary'   => [
                                'table'    => 'pages',
                                'prefix'   => 'page_',
                                'key'      => 'id',
                                'parent'   => 'parent',
                                'sortable' => true,
                                'display'  => [
                                    [
                                        'field'    => 'name',
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'field'    => 'slug',
                        'type'     => 'textbox',
                        'required' => true,
                        'unique'   => true,
                        'list'     => true,
                    ],
                    [
                        'field'    => 'home',
                        'type'     => 'switch',
                        'list'     => true
                    ],
                    [
                        'field'    => 'menu',
                        'type'     => 'switch',
                        'list'     => true,
                    ],
                    [
                        'field'    => 'position',
                        'type'     => 'position',
                        'list'     => true,
                    ],
                    [
                        'field'    => 'active',
                        'type'     => 'switch',
                        'list'     => true,
                    ],
                ],
                'order'    => [
                    [ 'position', 'ASC' ]
                ]
            ]
        ];
    }

    public function LoadNavbar() {
        return $this->db->Select()
        ->Table($this->schema['primary']['table'])
        ->Where($this->schema['primary']['prefix'] . 'active', '=', true)
        ->Where($this->schema['primary']['prefix'] . $this->schema['primary']['parent'], '=', null)
        ->Order($this->schema['primary']['order'], $this->schema['primary']['prefix'])
        ->Run();
    }

    public function LoadSidebar() {
        return true;
    }

}
