<?php

namespace App\Core;

class Model extends Database {

	public $swarm;
	public $db;
	public $middleware;

	protected static $instance;

	public function __construct() {
		$this->swarm      = Swarm     ::Instance();
		$this->db         = Database  ::Instance();
		$this->middleware = Middleware::Instance();
	}

	public static function Instance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

	public function PopulateData($app) {
		$data = [];
		if ($app) {
			$schema = self::TableStructure($app['vars'], $app['method']);
			if (!$app['value'] && !$app['method']) {
				$paging_init  = self::PagingInit();
				$app['value'] = self::TableData($app['vars'], $app['method'], $paging_init);
				$app_count    = self::TableCount($app['vars']);
				if ($app_count) {
					$paging_setup = self::PagingSetup($app_count['tcount'], $paging_init['max']);
				}
			}
			if ($schema) {
				if (($this->swarm->Get('routes')['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['new']['slug']) && (!$app['method'])) {
					return [
						'schema' => $schema['schema'] ? $schema['schema'] : false
					];
				}else{
					foreach($schema['forms'] as $schema_key => $schema_val) {
						if (($this->swarm->Get('routes')['uuid']) && (!$app['method'])) {
							foreach($app['value'] as $val_key => $val_item) {
								if ($val_key == $schema_val['field']) {
									$data[$val_key] = [
										'field'  => $val_key,
										'value'  => $val_item,
										'schema' => $schema_val
									];
								}
							}
						}else{
							foreach($app['value'] as $val_key => $val_item) {
								foreach($val_item as $row_key => $row_item) {
									if ($row_key == $schema_val['field']) {
										$data[$val_key][$row_key] = [
											'field'  => $row_key,
											'value'  => $row_item,
											'schema' => $schema_val
										];
									}
								}
							}
						}
					}
					return [
						'headers'  => $schema['headers'] ? $schema['headers']                                                   : false,
						'rows'     => $data ? $data                                                                             : false,
						'sortable' => $app['vars']->schema['primary']['sortable'] ? $app['vars']->schema['primary']['sortable'] : false,
						'paging'   => $paging_setup ? $paging_setup                                                             : false
					];
				}
			}
		}
		return false;
	}

	public function VerifyApp($app_init, $app_vars) {
		if ($app_init && $app_vars) {
			$app = $this->middleware->Integrate('Applications');
			$main_app = $this->db->Select()
			->Table($app['vars']->schema['primary']['table'])
			->LeftJoin(
				$app['vars']->schema['security']['table'],
				$app['vars']->schema['primary']['prefix'] . $app['vars']->schema['primary']['key'],
				$app['vars']->schema['security']['prefix'] . $app['vars']->schema['security']['index']
			)
			->Where($app['vars']->schema['primary']['prefix'] . 'class', '=', $app_init)
			->Order(
				$app['vars']->schema['primary']['order'],
				$app['vars']->schema['primary']['prefix']
			)
			->Run();
			if ($main_app) {
				foreach($main_app as $main_key => $main_val) {
					if ($main_val) {
						foreach($this->swarm->Get('SESSION.user')['data']['security'] as $user_security_key => $user_security_val) {
							if ($main_val[ $app['vars']->schema['security']['prefix'] . $app['vars']->schema['security']['key'] ] != $user_security_val) {
								unset($main_app[$main_key]);
							}
						}
					}
				}
				if ($main_app) {
					return true;
				}
			}
		}
		return false;
	}

	public function LogInfo($class, $method) {
		return true;
	}

	private function TableStructure($app, $method = false) {
		if ($app->schema['primary']) {
			$tbl_structure = $this->db->Describe()
			->Table($app->schema['primary']['table'])
			->Run();
			if ($tbl_structure) {
				$form_setup   = [];
				$header_setup = [];
				foreach($tbl_structure as $tbls_key => $tbls_val) {
					if ($tbls_val['Key']) {
						$form_setup[0]['key']   = true;
						$form_setup[0]['field'] = $tbls_val['Field'];
						$form_setup[0]['label'] = ucwords(trim($app->schema['primary']['key']));
					}
				}
				foreach($app->schema['primary']['display'] as $display_key => $display_val) {
					$display_key++;
					foreach($tbl_structure as $tbls_key => $tbls_val) {
						if ($tbls_val['Field'] == $app->schema['primary']['prefix'] . $display_val['field']) {
							$form_setup[$display_key]['field']              = $tbls_val['Field'];
							$form_setup[$display_key]['label']              = ucwords(trim($display_val['field']));
							$form_setup[$display_key]['type']               = trim($display_val['type']);
							$header_setup[$tbls_val['Field']]['label']      = ucwords(trim($display_val['field']));
							$header_setup[$tbls_val['Field']]['type']       = trim($display_val['type']);
							$header_setup[$tbls_val['Field']]['form']       = substr(md5($tbls_val['Field']), 0, 15);
							$form_setup[$display_key]['field']              = $tbls_val['Field'];
							$form_setup[$display_key]['form']               = substr(md5($tbls_val['Field']), 0, 15);
							if ($display_val['required']) {
								$form_setup[$display_key]['required']       = true;
							}
							if ($display_val['unique']) {
								$form_setup[$display_key]['unique']         = true;
							}
							if (isset($display_val['list'])) {
								$form_setup[$display_key]['list']           = true;
								$header_setup[$tbls_val['Field']]['list']   = true;
							}else{
								$form_setup[$display_key]['list']           = false;
								$header_setup[$tbls_val['Field']]['list']   = false;
							}
							if (isset($display_val['delete'])) {
								$form_setup[$display_key]['delete']         = true;
								$header_setup[$tbls_val['Field']]['delete'] = true;
							}else{
								$form_setup[$display_key]['delete']         = false;
								$header_setup[$tbls_val['Field']]['delete'] = false;
							}
							if (isset($display_val['childbtn'])) {
								$form_setup[$display_key]['childbtn']       = true;
							}else{
								$form_setup[$display_key]['childbtn']       = false;
							}
						}
					}
				}
				if (($this->swarm->Get('routes')['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['new']['slug']) && (!$method)) {
					return [
						'schema' => $header_setup
					];
				}else{
					return [
						'headers' => $header_setup,
						'forms'   => $form_setup
					];
				}
			}
		}
		return false;
	}

	private function TableData($app, $method = false, $paging = false) {
		$table_rows = false;
		if ($app->schema['primary']) {
			$table_rows = $this->db->Select()
			->Table($app->schema['primary']['table'])
			->LeftJoin(
				$app->schema['security'] ? $app->schema['security']['table'] : false,
				$app->schema['security'] ? $app->schema['primary']['prefix'] . $app->schema['primary']['key'] : false,
				$app->schema['security'] ? $app->schema['security']['prefix'] . $app->schema['security']['index'] : false
			)
			->Where(
				$app->schema['primary']['parent'] ? $app->schema['primary']['prefix'] . $app->schema['primary']['parent'] : false,
				$app->schema['primary']['parent'] ? '=' : false,
				$app->schema['primary']['parent'] ? null : false
			)
			->Where(
				$this->swarm->Get('routes')['uuid'] ? $app->schema['primary']['prefix'] . $app->schema['primary']['key'] : false,
				$this->swarm->Get('routes')['uuid'] ? '=' : false,
				$this->swarm->Get('routes')['uuid'] ? $this->swarm->Get('routes')['uuid'] : false
			)
			->Group(
	            $this->swarm->Get('SESSION.user')['data']['administrator'] ? $app->schema['primary']['prefix'] . $app->schema['primary']['key'] : false
	        )
			->Order(
				$app->schema['primary']['order'] ? $app->schema['primary']['order'] : false,
				$app->schema['primary']['order'] ? $app->schema['primary']['prefix'] : false
			)
			->Limit(
				($paging['min'] ? $paging['min'] : ($this->swarm->Get('routes')['uuid'] ? '1' : false)),
				$paging['max'] ? $paging['max'] : false
			)
			->Run();
			if ($table_rows) {
				if (!$this->swarm->Get('SESSION.user')['data']['administrator']) {
					if ($app->schema['security']) {
						foreach($table_rows as $table_key => $table_val) {
			                if ($table_val) {
			                    foreach($this->swarm->Get('SESSION.user')['data']['security'] as $user_security_key => $user_security_val) {
			                        if ($table_val[ $app->schema['security']['prefix'] . $app->schema['security']['key'] ] != $user_security_val) {
			                            unset($table_rows[$table_key]);
			                        }
			                    }
			                }
			            }
					}
		        }
				return $table_rows;
			}
		}
		return false;
	}

	private function TableCount($app) {
		if ($this->swarm->Get('routes')['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug']) {
			if ($app->schema['primary']) {
				return array_shift($this->db->Count()
				->Table($app->schema['primary']['table'])
				->Where(
					$app->schema['primary']['parent'] ? $app->schema['primary']['prefix'] . $app->schema['primary']['parent'] : false,
					$app->schema['primary']['parent'] ? '=' : false,
					$app->schema['primary']['parent'] ? null : false
				)
				->Where(
					$this->swarm->Get('routes')['uuid'] ? $app->schema['primary']['prefix'] . $app->schema['primary']['key'] : false,
					$this->swarm->Get('routes')['uuid'] ? '=' : false,
					$this->swarm->Get('routes')['uuid'] ? $this->swarm->Get('routes')['uuid'] : false
				)
				->Order(
					$app->schema['primary']['order'] ? $app->schema['primary']['order'] : false,
					$app->schema['primary']['order'] ? $app->schema['primary']['prefix'] : false
				)
				->Run());
			}
		}
		return false;
	}

	private function PagingInit() {
		if ($this->swarm->Get('routes')['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug']) {
			if (($this->swarm->Get('routes')['paging'] == 1) || (!$this->swarm->Get('routes')['paging'])) {
				$min_rows = 0;
				$max_rows = $this->swarm->Get('configs')['paging'];
			}else{
				$min_rows = (($this->swarm->Get('routes')['paging'] - 1) * $this->swarm->Get('configs')['paging']);
                $max_rows = $this->swarm->Get('configs')['paging'];
			}
			return [
				'min' => $min_rows,
				'max' => $max_rows
			];
		}
		return false;
	}

	private function PagingSetup($row_count, $max_rows) {
		if ($this->swarm->Get('routes')['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug']) {
            if ($row_count != 0) {
                if ($max_rows != 0) {
                    $max_paging = ceil($row_count / $max_rows);
                }else{
                    $max_paging = 1;
                }
                if ($this->swarm->Get('routes')['paging']) {
                    $min_paging = $this->swarm->Get('routes')['paging'];
                }else{
                    $min_paging = 1;
                }
                if ($this->swarm->Get('routes')['paging'] > $max_paging) {
                    $min_paging = $max_paging;
                }
                if ($this->swarm->Get('routes')['paging'] < 1) {
                    $min_paging = 1;
                }
                $paging_range = 5;
                $paging_setup = [];
                for ($paging = ($min_paging - $paging_range); $paging < (($min_paging + $paging_range) + 1); $paging++) {
                    if (($paging > 0) && ($paging <= $max_paging)) {
                        if ($paging == $min_paging) {
                            $paging_setup[] = [
                                'page'   => $paging,
                                'active' => true
                            ];
                        }else{
                            $paging_setup[] = [
                                'page' => $paging,
                            ];
                        }
                    }
                }
                if ($min_paging != 1) {
                    $previous_page = ($min_paging - 1);
                }
                if ($min_paging != $max_paging) {
                    $next_page = ($min_paging + 1);
                }
                return [
                    'count'    => $row_count ? $row_count       : 0,
                    'current'  => $min_paging > 1 ? $min_paging : 1,
                    'max'      => $max_paging > 1 ? $max_paging : 1,
                    'previous' => $previous_page,
                    'next'     => $next_page,
                    'setup'    => $paging_setup ? $paging_setup : 1
                ];
            }
        }
		return false;
	}

}
