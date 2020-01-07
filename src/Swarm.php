<?php

namespace App\Core;

class Swarm {

	public $globals;
	public $sessions;
	public $cookies;
	public $post;
	public $get;

	protected static $instance;

	public function __construct() {
		if (!isset($GLOBALS['Swarm'])) {
			$GLOBALS['Swarm'] = [];
		}
		if (!isset($_SESSION['Swarm'])) {
			$_SESSION['Swarm'] = [];
		}
		if (!isset($_COOKIE['Swarm'])) {
			$_COOKIE['Swarm'] = [];
		}
		$this->globals 	= &$GLOBALS;
		$this->sessions = &$_SESSION;
		$this->cookies 	= &$_COOKIE;
		$this->post 	= $_POST;
		$this->get 		= $_GET;
	}

	public static function Instance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	public function Get($key) {
		if ($key) {
			if (strpos($key, 'SESSION') !== false) {
				$key = trim(str_replace('.', '', str_replace('SESSION', '', $key)));
				if (isset($this->sessions['Swarm'][$key])) {
					return $this->sessions['Swarm'][$key];
				}
			}else if (strpos($key, 'COOKIE') !== false) {
				$key = trim(str_replace('.', '', str_replace('COOKIE', '', $key)));
				if (isset($this->cookies['Swarm'][$key])) {
					return $this->cookies['Swarm'][$key];
				}
			}else if (strpos($key, 'POST') !== false) {
				$key = trim(str_replace('.', '', str_replace('POST', '', $key)));
				if (isset($this->globals['Swarm']['Post'][$key])) {
					return $this->globals['Swarm']['Post'][$key];
				}else{
					return $this->globals['Swarm']['Post'];
				}
			}else if (strpos($key, 'GET') !== false) {
				$key = trim(str_replace('.', '', str_replace('GET', '', $key)));
				if (isset($this->globals['Swarm']['Get'][$key])) {
					return $this->globals['Swarm']['Get'][$key];
				}else{
					return $this->globals['Swarm']['Get'];
				}
			}else{
				if (isset($this->globals['Swarm'][$key])) {
					return $this->globals['Swarm'][$key];
				}
			}
		}
		return false;
	}

	public function Set($key, $value) {
		if ($key && $value) {
			if (strpos($key, 'SESSION') !== false) {
				$key = trim(str_replace('.', '', str_replace('SESSION', '', $key)));
				$this->sessions['Swarm'][$key] = $value;
			}else if (strpos($key, 'COOKIE') !== false) {
				$key = trim(str_replace('.', '', str_replace('COOKIE', '', $key)));
				$this->cookies['Swarm'][$key] = $value;
			}else{
				$this->globals['Swarm'][$key] = $value;
			}
			return true;
		}
		return false;
	}

	public function Clear($key) {
		if ($key) {
			if (strpos($key, 'SESSION') !== false) {
				$key = trim(str_replace('.', '', str_replace('SESSION', '', $key)));
				if ($this->sessions['Swarm'][$key]) {
					unset($this->sessions['Swarm'][$key]);
				}
			}else if (strpos($key, 'COOKIE') !== false) {
				$key = trim(str_replace('.', '', str_replace('COOKIE', '', $key)));
				if ($this->cookies['Swarm'][$key]) {
					unset($this->cookies['Swarm'][$key]);
				}
			}else{
				if ($this->globals['Swarm'][$key]) {
					unset($this->globals['Swarm'][$key]);
				}
			}
		}
		return false;
	}

	public function SetPostGet() {
		if ($this->post) {
			foreach($this->post as $post_key => $post_val) {
				$this->globals['Swarm']['Post'][$post_key] = trim($post_val);
			}
			return true;
		}
		if ($this->get) {
			foreach($this->get as $get_key => $get_val) {
				$this->globals['Swarm']['Get'][$get_key] = trim($get_val);
			}
			return true;
		}
		return false;
	}

	public function ExplodeTree(&$array_ptr, $key, $value) {
		if ($key) {
			$keys = explode('.', $key);
			// extract the last key
			$last_key = array_pop($keys);
			// walk/build the array to the specified key
			while ($arr_key = array_shift($keys)) {
				if (!array_key_exists($arr_key, $array_ptr)) {
					$array_ptr[$arr_key] = array();
				}
				$array_ptr = &$array_ptr[$arr_key];
			}
			// set the final key
			$array_ptr[$last_key] = $value;
		}
		return true;
	}

}
