<?php

namespace App\Units;

class Security extends \App\Core\Middleware {

    public $schema = [];

    function __construct() {
        parent::__construct();
        $this->schema = [
            'primary'   => [
                'table'    => 'securities',
                'prefix'   => 'security_',
                'key'      => 'id',
                'titles'   => [
                    'singular' => 'Security',
                    'plural'   => 'Securities'
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
                        'field'    => 'admin',
                        'type'     => 'switch',
                        'list'     => true
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

    public function LoadSecurity() {
        return $this->db->Select()
        ->Table($this->schema['primary']['table'])
        ->WhereSet($this->schema['primary']['prefix'] . $this->schema['primary']['key'], $this->swarm->Get('SESSION.user')['data']['security'])
        ->Where($this->schema['primary']['prefix'] . 'active', '=', true)
        ->Order($this->schema['primary']['order'], $this->schema['primary']['prefix'])
        ->Run();
    }

}
