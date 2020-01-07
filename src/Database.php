<?php

namespace App\Core;

class Database extends PdoWrapper {

	public  $swarm;
    private $queries;
    private $db;
    protected static $instance;

	//
	// Load DB Attributes
	//
	protected function __construct() {
        $this->swarm   = Swarm::Instance();
        $this->db      = PdoWrapper::Instance();
        $this->queries = [];
        return $this;
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

	public function ClearQueries() {
		$this->queries = [];
	}

	public function Select($fields = []) {
		self::ClearQueries();
        if ($fields) {
            $this->queries['select'] = func_get_args();
        }else{
            $this->queries['select'] = true;
        }
        return $this;
    }

	public function Count() {
		self::ClearQueries();
		$this->queries['count'] = true;
		return $this;
	}

	public function Describe() {
		self::ClearQueries();
		$this->queries['describe'] = true;
		return $this;
	}

    public function Table($table) {
        if (!empty($table)) {
            $this->queries['table'] = $this->swarm->Get('configs')['database']['prefix'] . $table;
        }
        return $this;
    }

	public function LeftJoin($relation_table, $primary_foreign_key, $relation_primary_key) {
		if ($relation_table && $primary_foreign_key && $relation_primary_key) {
			$this->queries['left_join'][] = [
				'table'   => $this->swarm->Get('configs')['database']['prefix'] . $relation_table,
				'parent'  => $primary_foreign_key,
				'related' => $relation_primary_key
			];
		}
		return $this;
	}

	public function InnerJoin($relation_table, $primary_foreign_key, $relation_primary_key) {
		$this->queries['inner_join'][] = [
			'table'   => $this->swarm->Get('configs')['database']['prefix'] . $relation_table,
			'parent'  => $primary_foreign_key,
			'related' => $relation_primary_key
		];
		return $this;
	}

	public function Where($field, $operator, $value) {
		if ($field && $operator && ($value || is_bool($value) || $value == null)) {
			if ($value == null) {
				$this->queries['where'][] = $field . ' ' . $operator . ' \'\' ';
			}else{
				$this->queries['where'][] = $field . ' ' . $operator . ' ? ';
				$this->queries['binds'][] = $value;
			}
		}
		return $this;
	}

	public function WhereSet($field, $values = []) {
		if ($field && $values) {
			$this->queries['where_set'][] = ' FIND_IN_SET(' . $field . ', \'' . implode(',', $values) . '\') ';
		}
		return $this;
	}

	public function Group($field) {
		if ($field) {
			$this->queries['group'][] = $field;
		}
		return $this;
	}

	public function Order($field, $prefix_or_sorting = 'ASC') {
		if (is_array($field)) {
			foreach($field as $f_key => $f_val) {
				$this->queries['order'][]   = $prefix_or_sorting . $f_val[0];
				$this->queries['sorting'][] = $f_val[1];
			}
		}else{
        	if (!empty($field)) {
			 	if (($prefix_or_sorting != 'ASC') || ($prefix_or_sorting != 'DESC')) {
					$prefix_or_sorting = 'ASC';
				}
	            $this->queries['order']   = $field;
				$this->queries['sorting'] = $prefix_or_sorting;
			}
        }
		return $this;
    }
    public function Limit($min = 0, $max = '') {
        if (!empty($max)) {
            $this->queries['limit'] = [
                'min' => (int) $min,
                'max' => (int) $max
            ];
        }else{
			if (is_numeric($min)) {
	            $this->queries['limit'] = [
	                'min' => (int) $min,
	                'max' => false
	            ];
			}
        }
        return $this;
    }

	public function Debug() {
		$this->queries['debug'] = true;
		return $this;
	}

    public function Run() {

        $build_query   = [];
		$fetch_as_rows = true;

        if ((isset($this->queries['select'])) || (isset($this->queries['count']))) {
            if (is_array($this->queries['select'])) {
                $build_query[] = ' SELECT ' . implode(', ', $this->queries['select']) . ' ';
            }else{
				if ($this->queries['count']) {
					$build_query[] = ' SELECT COUNT(*) as tcount ';
				}else{
                	$build_query[] = ' SELECT * ';
				}
            }
            if ($this->queries['table']) {
                $build_query[] = ' FROM ' . $this->queries['table'] . ' ';
            }
			if ($this->queries['left_join']) {
				foreach($this->queries['left_join'] as $lj_key => $lj_val) {
					$build_query[] = ' LEFT JOIN ' . $lj_val['table'] . ' ON ' . $lj_val['parent'] . ' = ' . $lj_val['related'] . ' ';
				}
			}
			if ($this->queries['inner_join']) {
				foreach($this->queries['inner_join'] as $lj_key => $lj_val) {
					$build_query[] = ' INNER JOIN ' . $lj_val['table'] . ' ON ' . $lj_val['parent'] . ' = ' . $lj_val['related'] . ' ';
				}
			}
			if ($this->queries['where']) {
				$build_query[] = ' WHERE ' . implode(' AND ', $this->queries['where']) . ' ';
			}
			if ($this->queries['where_set']) {
				if ($this->queries['where']) {
					$build_query[] = ' AND ' . implode(' AND ', $this->queries['where_set']) . ' ';
				}else{
					$build_query[] = ' WHERE ' . implode(' AND ', $this->queries['where_set']) . ' ';
				}

			}
			if ($this->queries['group']) {
				$build_query[] = ' Group By ' . implode(',', $this->queries['group']);
			}
			if (isset($this->queries['order'])) {
				if (is_array($this->queries['order'])) {
					foreach($this->queries['order'] as $order_key => $order_val) {
						$build_query[] = ' ORDER BY ' . $order_val . ' ' . $this->queries['sorting'][$order_key];
					}
				}else{
					$build_query[] = ' ORDER BY ' . $this->queries['order'] . ' ' . $this->queries['sorting'];
				}
			}
            if (isset($this->queries['limit'])) {
				if ($this->queries['limit']['max']) {
                	$build_query[] = ' LIMIT ' . $this->queries['limit']['min'] . ', ' . $this->queries['limit']['max'];
				}else{
					$build_query[] = ' LIMIT ' . $this->queries['limit']['min'];
					$fetch_as_rows = false;
				}
            }
        }
		if (isset($this->queries['describe'])) {
			if ($this->queries['table']) {
				$build_query[] = ' DESCRIBE ' . $this->queries['table'] . ' ';
			}
		}

        $pdo_query = '';
        $pdo_binds = [];
        if ($build_query) {
            $pdo_query .= trim(str_replace('  ', ' ', implode('', $build_query)));
			$pdo_binds = $this->queries['binds'];
        }

		if ($this->queries['debug']) {
			echo $pdo_query . '<br />';
			echo '<pre>';
			print_r($pdo_binds);
			echo '</pre>';
			return true;
		}

		try {
            if ($pdo_query) {
				if ($fetch_as_rows) {
                	return $this->db->RunPdo($pdo_query, $pdo_binds)->fetchAll();
				}else{
					return $this->db->RunPdo($pdo_query, $pdo_binds)->fetch();
				}
            }else{
                if ($this->swarm->Get('configs')['debug']) {
                    return 'Error: Query Invalid';
                }else{
                    return false;
                }
            }
        } catch (Exception $e) {
            if ($this->swarm->Get('configs')['debug']) {
                return 'Caught exception: ' . $e->getMessage();
                exit();
            }else{
                return false;
            }
        }

    }


}
