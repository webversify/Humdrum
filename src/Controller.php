<?php

namespace App\Core;

use \Leafo\ScssPhp\Compiler;

class Controller extends Model {

	public $swarm;
	public $routes;
	public $model;
	public $middleware;
	public $helpers;

	public function __construct() {
		$this->swarm 	  = Swarm::Instance();
		$this->routes	  = array_values(
			array_filter(
				explode('/', str_replace(
						(dirname(trim($_SERVER['PHP_SELF'])) != '/') ? dirname(trim($_SERVER['PHP_SELF'])) : '', '', trim($_SERVER['REQUEST_URI'])
					)
				)
			)
		);
		$this->model	  = Model::Instance();
		$this->middleware = Middleware::Instance();
		$this->helpers 	  = Helpers::Instance();
	}

	public function Routing() {
		$format_routes = [];
		if (!$this->swarm->Get('routes')) {
			if ($this->routes) {
				foreach($this->routes as $route_key => $route_val) {
					if ($route_key != 0) {
						$default_backend_page = false;
						foreach($this->swarm->Get('configs')['defaults']['backend_pages'] as $bp_key => $bp_val) {
							if ($bp_val['slug'] == $route_val) {
								$default_backend_page = true;
							}
						}
						if ($default_backend_page) {
							$format_routes['page'] = $route_val;
						}else{
							if (is_numeric($route_val)) {
								$format_routes['paging'] = $route_val;
							}else{
								if ($format_routes['ui'] == 'frontend') {
									if ($format_routes['page']) {
										$format_routes['pages'][] = $format_routes['page'];
										$format_routes['pages'][] = $route_val;
										unset($format_routes['page']);
									}else{
										if ($format_routes['pages']) {
											$format_routes['pages'][] = $route_val;
											unset($format_routes['page']);
										}else{
											$format_routes['page'] = $route_val;
										}
									}
								}else{
									if ($this->helpers->UuidValidate($route_val)) {
										$format_routes['uuid'] = $route_val;
									}else{
										$format_routes['apps'][] = $route_val;
									}
								}
							}
						}
					}else{
						if (($route_val == $this->swarm->Get('configs')['defaults']['links']['admin']['slug']) && ($this->swarm->Get('SESSION.user'))) {
							$format_routes['ui'] = 'backend';
						}else{
							$format_routes['ui'] = 'frontend';
							$default_link = false;
							foreach($this->swarm->Get('configs')['defaults']['links'] as $link_key => $link_val) {
								if ($link_val['slug'] == $route_val) {
									$default_link = true;
								}
							}
							if ($default_link) {
								$format_routes['link'] = $route_val;
							}else{
								if (!is_numeric($route_val)) {
									$format_routes['page'] = $route_val;
								}
							}
						}
					}
				}
				if ($format_routes['ui'] == 'backend') {
					if (!$format_routes['page']) {
						$format_routes['page'] = $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug'];
					}
					if ($format_routes['uuid']) {
						if ($format_routes['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug']) {
							unset($format_routes['uuid']);
						}
					}
				}
			}else{
				$format_routes['ui'] = 'frontend';
			}
			$this->swarm->Set('routes', $format_routes);
		}
		return true;
	}

	public function Apps() {
		$loaded_apps = [];
		if ($this->swarm->Get('configs')['defaults']['apps']) {
			if ($this->swarm->Get('configs')['defaults']['apps'][$this->swarm->Get('routes')['ui']]) {
				foreach($this->swarm->Get('configs')['defaults']['apps'][$this->swarm->Get('routes')['ui']] as $app_key => $app_items) {
					if ($app_items) {
						$app_class = trim(ucwords($app_key));
						foreach($app_items as $app_tag => $app_method) {
							$app_conf = $this->model->PopulateData($this->middleware->Integrate($app_class, $app_method));
							if ($app_conf) {
								$loaded_apps[$app_key][$app_tag] = $app_conf;
							}
						}
					}
				}
			}
		}
		if ($this->swarm->Get('routes')['ui'] == 'backend') {
			if ($this->swarm->Get('routes')['apps']) {
				$app_class  = str_replace(' ', '', ucwords(str_replace('-', ' ', end($this->swarm->Get('routes')['apps']))));
				$page_title = ucwords(str_replace('-', ' ', end($this->swarm->Get('routes')['apps'])));
				$load_app   = true;
				$init_app = $this->middleware->Integrate($app_class);
				if (!$this->swarm->Get('SESSION.user')['data']['administrator']) {
					$load_app = $this->model->VerifyApp($app_class, $init_app['vars']);
				}
				if ($load_app) {
					$page_data  = $this->model->PopulateData($init_app);
					if (!$init_app) {
						$this->swarm->Set('error', true);
						$this->swarm->Set('error_message', $this->swarm->Get('configs')['error_codes'][1]);
					}else{
						if ($page_data) {
							if ($loaded_apps[strtolower($app_class)]) {
								$loaded_apps[strtolower($app_class)] = array_merge($loaded_apps[strtolower($app_class)], $page_data);
							}else{
								$loaded_apps[strtolower($app_class)] = $page_data;
							}
							if ($init_app['vars']->schema['primary']['titles']) {
								$this->swarm->Set('app_titles', $init_app['vars']->schema['primary']['titles']);
							}else{
								$this->swarm->Set('app_titles', $page_title);
							}
						}
					}
				}else{
					if (!$init_app) {
						$this->swarm->Set('error', true);
						$this->swarm->Set('error_message', $this->swarm->Get('configs')['error_codes'][1]);
					}else{
						$this->swarm->Set('error', true);
						$this->swarm->Set('error_message', $this->swarm->Get('configs')['error_codes'][2]);
					}
				}
			}
		}
		if ($loaded_apps) {
			$this->swarm->Set('apps', $loaded_apps);
			unset($loaded_apps);
		}
		return true;
	}

	public function Variables() {

		$vars = [];
		$conf = [];

		// Load Site URL
		if (dirname(trim($_SERVER['PHP_SELF']))) {
			$vars['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname(trim($_SERVER['PHP_SELF'])), '/') . '/';
		}else{
			$vars['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . rtrim($_SERVER['HTTP_HOST'], '/') . '/';
		}

		// Load Site Configs
		foreach($this->swarm->Get('configs') as $config_key => $config_val) {
			if (!is_array($config_val)) {
				if ($config_key == 'skin') {
					if (($this->swarm->Get('SESSION.user')) &&
						($this->swarm->Get('routes')['ui'] == 'backend') &&
						(!file_exists($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/' . $this->swarm->Get('configs')['defaults']['links']['admin']['slug'] . '/' ))) {
						$vars[$config_key] = 'humdrum';
					}else{
						$vars[$config_key] = $config_val;
					}
				}else{
					$vars[$config_key] = $config_val;
				}
			}
			if ($config_key == 'defaults') {
				foreach($config_val as $app_key => $app_value) {
					if (($app_key == 'links') ||
					 	(($app_key == 'backend_pages') && ($this->swarm->Get('SESSION.user')))) {
						foreach($app_value as $item_key => $item_value) {
							if ($item_value) {
								$vars[$app_key][$item_key] = [
									'slug'  => $item_value['slug'],
									'title' => $item_value['title'],
								];
							}
						}
					}
				}
			}
			if ($config_key == 'users') {
				$vars[$config_key]['class']   = $config_key;
				$vars[$config_key]['enabled'] = $config_val['enabled'];
				if ($config_val['enabled']) {
					$vars[$config_key]['info']   = false;
					$vars[$config_key]['logged'] = false;
					if (($this->swarm->Get('SESSION.user')) && ($this->swarm->Get('routes')['ui'] == 'backend')) {
						$vars[$config_key]['info'] = $this->swarm->Get('SESSION.user');
						$vars[$config_key]['logged'] = true;
					}else{
						if ($this->swarm->Get('SESSION.user')) {
							$vars[$config_key]['logged'] = true;
						}
					}
				}
			}
			if (($config_key == 'response_codes') ||
			 	($config_key == 'error_codes')) {
				$vars[$config_key] = $config_val;
			}
			$conf[$config_key] = $vars[$config_key] ? $vars[$config_key] : $config_val;
		}

		// Load App Configs
		if ($this->swarm->Get('apps')) {
			$vars['apps'] = $this->swarm->Get('apps');
		}

		// Load Paths && Other Variables
		$vars['theme_path'] = (string) trim($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/' . $this->swarm->Get('configs')['theme']);

		$this->swarm->Set('vars', $vars);
		if ($conf) {
			$this->swarm->Set('configs', $conf);
		}
		return true;

	}

	public function Execute() {
		if (($this->swarm->Get('POST.app') && $this->swarm->Get('POST.exe') && $this->swarm->Get('POST.csrftoken'))) {
			if (($this->swarm->Get('POST.csrftoken') == $this->swarm->Get('SESSION.csrf'))) {
				$app_class    = str_replace(' ', '', ucwords(str_replace('-', ' ', $this->swarm->Get('POST.app'))));
				$app_method   = str_replace(' ', '', ucwords(str_replace('-', ' ', $this->swarm->Get('POST.exe'))));
				$app_response = $this->middleware->Integrate($app_class, $app_method);
				$this->swarm->Set('response', $app_response['value']);
				if ($this->swarm->Get('response')['status'] == 'success') {
					$this->swarm->Set('SESSION.csrf', bin2hex(random_bytes(32)));
					$this->model->LogInfo($app_class, $app_method);
				}
			}else{
                $this->swarm->Set('response', [
                    'status'  => 'error',
                    'message' => 'Unable To Login, Token Invalid!'
                ]);
            }
		}else{
			if (!$this->swarm->Get('SESSION.csrf')) {
				$this->swarm->Set('SESSION.csrf', bin2hex(random_bytes(32)));
			}
		}
	}

	public function Minifier($file, $folder, $type) {

		if ($file && $folder && $type) {
			if ($type == 'css') {
				$compile_extension = 'scss';
			}else{
				$compile_extension = 'js';
			}
			if (file_exists($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $compile_extension )) {
				$contents 		 = '';
				$generation	     = $this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $type . '/' . $file . '.' . $type;
				$file_revisions  = [];
				foreach($this->swarm->Get('configs')['assets']['common'] as $key => $val) {
					if (file_exists($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $compile_extension .'/common/_' . $val . '.' . $compile_extension)) {
						$filename = $this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $compile_extension .'/common/' . '_' . $val . '.' . $compile_extension;
						$fh = fopen($filename, "r");
						if (filesize($filename) != 0) {
							$file_revisions[] = filemtime($filename);
							$contents 		 .= preg_replace('!/\*.*?\*/!s', '', fread($fh, filesize($filename)));
						}
						fclose($fh);
					}
				}
				foreach($this->swarm->Get('configs')['assets']['build'] as $key => $val) {
					if (file_exists($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $compile_extension . '/' . $folder . '/_' . $val . '.' . $compile_extension)) {
						$filename = $this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $compile_extension . '/' . $folder . '/' . '_' . $val . '.' . $compile_extension;
						$fh = fopen($filename, "r");
						if (filesize($filename) != 0) {
							$file_revisions[] =  filemtime($filename);
							$contents 		 .=   preg_replace('!/\*.*?\*/!s', '', fread($fh, filesize($filename)));
						}
						fclose($fh);
					}
				}
				if (($contents) || (!file_exists($generation))) {
					if (filemtime($generation) < max($file_revisions)) {
						if ($compile_extension == 'scss') {
							$scss = new Compiler();
							$scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
							$scss->setImportPaths($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/assets/' . $compile_extension . '/' . $folder . '/');
							$compiled =  $scss->compile($contents);
						}else{
							$compiled = \JShrink\Minifier::minify($contents);
						}
						if  ($compiled) {
							$handler = fopen($generation, 'w+');
							fwrite($handler, trim($compiled));
							fclose($handler);
						}
					}
				}
			}
		}

	}

}
