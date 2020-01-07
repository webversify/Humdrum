<?php

namespace App\Core;

use \PDO;

class PdoWrapper {

	public $swarm;

	protected static $instance;
    protected $pdo;
    protected $dsn;
    protected $opt;

	//
	// Load DB Attributes
	//
	protected function __construct() {

        $this->swarm = Swarm::Instance();
        $this->opt 	 = [
            PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE 	=> PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   	=> FALSE
        ];
        $this->dsn	 = $this->swarm->GET('configs')['database']['driver'] . ':host =' . $this->swarm->GET('configs')['database']['host'] .
                       ';dbname=' . $this->swarm->GET('configs')['database']['db'] .
                       ';charset=' . strtolower(str_replace('-', '', $this->swarm->GET('configs')['encoding']));
        $this->pdo	 = new PDO($this->dsn, $this->swarm->GET('configs')['database']['user'], $this->swarm->GET('configs')['database']['password'], $this->opt);

	}

	//
	// Initiate DB Intance
	//
	public static function Instance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	//
	// Callback Method
	//
    public function __call($method, $args) {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

	//
	// Execute SQL Query
	//
    public function RunPdo($sql, $args = []) {
        if (!$args) {
             return $this->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

}
