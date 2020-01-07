<?php

namespace App\Units;

class Applications extends \App\Core\Middleware {

    public $schema = [];

    function __construct() {
        parent::__construct();
        $this->schema = [
            'primary'   => [
                'table'    => 'applications',
                'prefix'   => 'app_',
                'key'      => 'id',
                'parent'   => 'parent',
                'titles'  => [
                    'singular' => 'Application',
                    'plural'   => 'Applications'
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
                        'type'     => 'select'                        
                    ],
                    [
                        'field'    => 'icon',
                        'type'     => 'icon',
                        'required' => true,
                        'list'     => true,
                    ],
                    [
                        'field'    => 'description',
                        'type'     => 'textarea',
                    ],
                    [
                        'field'    => 'class',
                        'type'     => 'textbox',
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
            'security'  => [
                'table'   => 'applications_security',
                'prefix'  => 'security_',
                'index'   => 'app',
                'key'     => 'key',
            ]
        ];
    }

    public function LoadSidebar() {
        $sidebars = $this->db->Select()
        ->Table($this->schema['primary']['table'])
        ->LeftJoin(
            $this->schema['security']['table'],
            $this->schema['primary']['prefix'] . $this->schema['primary']['key'],
            $this->schema['security']['prefix'] . $this->schema['security']['index']
        )
        ->Where($this->schema['primary']['prefix'] . 'active', '=', true)
        ->Where($this->schema['primary']['prefix'] . $this->schema['primary']['parent'], '=', null)
        ->Order($this->schema['primary']['order'], $this->schema['primary']['prefix'])
        ->Group(
            $this->swarm->Get('SESSION.user')['data']['administrator'] ? $this->schema['primary']['prefix'] . $this->schema['primary']['key'] : false
        )
        ->Run();
        if (!$this->swarm->Get('SESSION.user')['data']['administrator']) {
            foreach($sidebars as $sb_key => $sb_val) {
                if ($sb_val) {
                    foreach($this->swarm->Get('SESSION.user')['data']['security'] as $user_security_key => $user_security_val) {
                        if ($sb_val[ $this->schema['security']['prefix'] . $this->schema['security']['key'] ] != $user_security_val) {
                            unset($sidebars[$sb_key]);
                        }
                    }
                }
            }
        }
        return $sidebars;
    }

}

?>
