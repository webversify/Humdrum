<?php

namespace App\Units;

class Users extends \App\Core\Middleware {

    public $schema = [];

    function __construct() {
        parent::__construct();
        $this->schema = [
            'primary'   => [
                'table'    => 'users',
                'prefix'   => 'user_',
                'key'      => 'id',
                'titles'   => [
                    'singular' => 'User',
                    'plural'   => 'Users'
                ],
                'sortable' => false,
                'display'  => [
                    [
                        'field'    => 'lastname',
                        'type'     => 'textbox',
                        'required' => true,
                        'list'     => true,
                        'delete'   => true
                    ],
                    [
                        'field'    => 'firstname',
                        'type'     => 'textbox',
                        'required' => true,
                        'list'     => true,
                        'delete'   => true
                    ],
                    [
                        'field'    => 'email',
                        'type'     => 'textbox',
                        'required' => true,
                        'unique'   => true,
                        'list'     => true
                    ],
                    [
                        'field'    => 'password',
                        'type'     => 'password',
                        'required' => true
                    ],
                    [
                        'field'    => 'active',
                        'type'     => 'switch',
                        'list'     => true,
                    ],
                ],
                'order'    => [
                    [ 'lastname', 'ASC' ]
                ]
            ],
            'security'  => [
                'table'   => 'users_security',
                'prefix'  => 'security_',
                'index'   => 'user',
                'key'     => 'key',
            ]
        ];
    }

    public function Login() {
        if (($this->swarm->Get('POST.userlogin')) &&
            ($this->swarm->Get('POST.userpassword'))) {
            $verify_user = $this->db->Select()
            ->Table($this->schema['primary']['table'])
            ->Where($this->schema['primary']['prefix'] . 'email', '=', $this->swarm->Get('POST.userlogin'))
            ->Where($this->schema['primary']['prefix'] . 'active', '=', true)
            ->Limit(1)
            ->Run();
            if ($verify_user) {
                if (password_verify($this->swarm->Get('POST.userpassword'), $verify_user[$this->schema['primary']['prefix'] . 'password'])) {
                    $load_user_security = $this->db->Select()
                    ->Table($this->schema['security']['table'])
                    ->Where($this->schema['security']['prefix'] . $this->schema['security']['index'], '=', $verify_user[$this->schema['primary']['prefix'] . $this->schema['primary']['key']])
                    ->Run();
                    if ($load_user_security) {
                        $recast_security = [];
                        $admin_security  = false;
                        foreach($load_user_security as $usec_key => $usec_val) {
                            $security_check  = parent::SecurityCheck($usec_val[$this->schema['security']['prefix'] . $this->schema['security']['key']]);
                            if ($security_check['is_admin']) {
                                $admin_security = true;
                            }
                            $recast_security[] = $usec_val[$this->schema['security']['prefix'] . $this->schema['security']['key']];
                        }
                        $this->swarm->Set('SESSION.user', [
                            'id'    => $verify_user[$this->schema['primary']['prefix'] . $this->schema['primary']['key']],
                            'data'  => [
                                'email'         => $verify_user[$this->schema['primary']['prefix'] . 'email'],
                                'firstname'     => $verify_user[$this->schema['primary']['prefix'] . 'firstname'],
                                'lastname'      => $verify_user[$this->schema['primary']['prefix'] . 'lastname'],
                                'administrator' => $admin_security,
                                'security'      => $recast_security
                            ]
                        ]);
                        return [
                            'status'  => 'success'
                        ];
                    }
                }
            }
            return [
                'status'  => 'error',
                'message' => $this->swarm->Get('configs')['error_codes'][3]
            ];
        }
        return false;
    }

    public function SignUp() {

    }

}
