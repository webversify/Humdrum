<?php

namespace App\Core;

class Middleware extends Controller {

    public $swarm;
    public $db;
    protected static $instance;

    public function __construct() {
        $this->swarm = Swarm::Instance();
        $this->db    = new Database;
    }

    public static function Instance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function Integrate($class, $method = false, $arguments = []) {
        if ($class) {
            if (file_exists($this->swarm->Get('configs')['disks']['apps'] . $class . '.php')) {
                $namespaces = 'App\\Units\\' . $class;
                $loaded_app = new $namespaces;
                if ($method) {
                    if (!$arguments) {
                        return [
                            'class'  => str_replace(' ', '-', strtolower($class)),
                            'vars'   => $loaded_app,
                            'value'  => $loaded_app->$method(),
                            'method' => true
                        ];
                    }else{
                        return [
                            'class'  => str_replace(' ', '-', strtolower($class)),
                            'vars'   => $loaded_app,
                            'value'  => $loaded_app->$method($arguments),
                            'method' => true
                        ];
                    }
                }else{
                    return [
                        'class'  => str_replace(' ', '-', strtolower($class)),
                        'vars'   => $loaded_app,
                        'value'  => false,
                        'method' => false
                    ];
                }
            }
        }
        return false;
    }

    public function SecurityCheck($value) {
        $verify_user_status = [];
        foreach($this->swarm->Get('apps')['security']['setup']['rows'] as $security_key => $security_val) {
            if ($security_val['security_id']['value'] == $value) {
                if ($security_val['security_admin']['value']) {
                    $verify_user_status['is_admin'] = true;
                }
            }
        }
        if ($verify_user_status) {
            return $verify_user_status;
        }
        return false;
    }

}
