<?php

namespace App\Units;

class Settings extends \App\Core\Middleware {

    public $schema    = [];

    function __construct() {
        parent::__construct();
        $this->schema = [
            'primary'   => [
                'table'   => 'settings',
                'prefix'  => 'setting_',
                'key'     => 'id',
                'titles'  => [
                    'singular' => 'Setting',
                    'plural'   => 'Settings'
                ],
                'sortable' => true,
                'display'  => [
                    [
                        'field'    => 'name',
                        'type'     => 'textbox',
                        'required' => true,
                        'unique'   => true,
                        'list'     => true,
                        'delete'   => true
                    ],
                    [
                        'field'    => 'value',
                        'type'     => 'textbox',
                        'required' => true,
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
                'order'   => [
                    [ 'position', 'ASC' ]
                ]
            ],
        ];
    }

    public function LoadSiteInfo() {
        return false;
    }

}

?>
