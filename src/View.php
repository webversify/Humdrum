<?php

namespace App\Core;

use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_Extension_Debug;

class View extends Controller {

	public $swarm;
	public $db;

	public function __construct() {
		$this->swarm = Swarm::Instance();
	}

	public function Render() {
		return self::Twig();
	}

	private function Twig() {

		if ($this->swarm->Get('SESSION.user')) {
			Controller::Minifier('style.min', 'site', 'css');
			Controller::Minifier('style.admin.min', 'admin', 'css');
			Controller::Minifier('script.min', 'site', 'js');
			Controller::Minifier('script.admin.min', 'admin', 'js');
		}else{
			Controller::Minifier('style.min', 'site', 'css');
			Controller::Minifier('script.min', 'site', 'js');
		}

		$options = [];
		$options['cache'] = false;
		$options['debug'] = $this->swarm->Get('configs')['debug'];
		$file_system = new \Twig_Loader_Filesystem($this->swarm->Get('configs')['disks']['theme'] . $this->swarm->Get('configs')['skin'] . '/');
    	$twig_config = new \Twig_Environment($file_system, $options);
        if ($this->swarm->Get('configs')['debug']) {
            $twig_config->addExtension(new \Twig_Extension_Debug());
        }
		$twig_template = $twig_config->loadTemplate('index.tpl');
        header('X-Powered-By: ' . $this->swarm->Get('configs')['title'] . ' - ' . $this->swarm->Get('configs')['description']);
        $twig_render =  $twig_template->render(self::TwigVars());
		echo $twig_render;

	}

	private function TwigVars() {

		if (end($this->swarm->Get('routes')['apps']) == $this->swarm->Get('configs')['defaults']['links']['logout']['slug']) {
			$this->swarm->Set('SESSION.logout_message', $this->swarm->Get('configs')['response_codes'][3]);
			$this->swarm->Clear('SESSION.user');
			header('Location: ' . $this->swarm->Get('vars')['url'] . $this->swarm->Get('configs')['defaults']['links']['login']['slug']);
			exit();
		}
		if (($this->swarm->Get('SESSION.user')) && (($this->swarm->Get('routes')['link'] == $this->swarm->Get('configs')['defaults']['links']['login']['slug']))) {
			header('Location: ' . $this->swarm->Get('vars')['url'] . $this->swarm->Get('configs')['defaults']['links']['admin']['slug']);
			exit();
		}
		if ((!$this->swarm->Get('SESSION.user')) &&
			(($this->swarm->Get('routes')['link'] == $this->swarm->Get('configs')['defaults']['links']['admin']['slug']) ||
			(end($this->swarm->Get('routes')['pages']) == $this->swarm->Get('configs')['defaults']['links']['admin']['slug']))) {
			header('Location: ' . $this->swarm->Get('vars')['url'] . $this->swarm->Get('configs')['defaults']['links']['login']['slug']);
			exit();
		}

		$vars = [];
		$vars['site'] = $this->swarm->Get('vars');
		if ($this->swarm->Get('response')['message']) {
			$vars['site']['response'] = $this->swarm->Get('response');
		}
		if ($this->swarm->Get('SESSION.logout_message')) {
			$vars['site']['response'] = [
				'status'  => 'success',
				'message' => $this->swarm->Get('SESSION.logout_message')
			];
			$this->swarm->Clear('SESSION.logout_message');
		}
		$vars['site']['post'] = $this->swarm->Get('Post');
		$vars['site']['csrf'] = $this->swarm->Get('SESSION.csrf');
		$vars['site']['content'] = 'home.tpl';
		if ($this->swarm->Get('routes')) {
			if ($this->swarm->Get('routes')['ui'] == 'backend') {
				$vars['site']['breadcrumbs']  = $this->swarm->Get('routes')['apps'];
				$vars['site']['app']  = end($this->swarm->Get('routes')['apps']);
				$vars['site']['app_titles'] = $this->swarm->Get('app_titles');
				if ($this->swarm->Get('routes')['apps']) {
					$vars['site']['page'] = $this->swarm->Get('routes')['page'] ? $this->swarm->Get('routes')['page'] : $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug'];
				}
				if ($this->swarm->Get('routes')['paging']) {
					$vars['site']['paging'] = $this->swarm->Get('routes')['paging'];
				}else{
					$vars['site']['paging'] = 1;
				}
				if ($this->swarm->Get('routes')['uuid']) {
					$vars['site']['uuid']  = $this->swarm->Get('routes')['uuid'];
				}
			}else{
				if ($this->swarm->Get('routes')['link']) {
					$vars['site']['page'] = $this->swarm->Get('routes')['link'];
				}else{
					$vars['site']['page'] = $this->swarm->Get('routes')['page'] ? $this->swarm->Get('routes')['page'] : end($this->swarm->Get('routes')['pages']);
				}
				if ((file_exists($this->swarm->Get('vars')['theme_path'] . 'sections/' . $vars['site']['page'] . '.tpl')) && ($vars['site']['page'] != 'index')) {
					$vars['site']['content'] = $vars['site']['page'] . '.tpl';
				}
			}
		}
		if (($this->swarm->Get('SESSION.user')) &&
			($this->swarm->Get('routes')['ui'] == 'backend') &&
			($this->swarm->Get('routes')['page'] == $this->swarm->Get('configs')['defaults']['backend_pages']['list']['slug'])) {
			$this->swarm->Set('SESSION.previous_page', [
				'app'    => $vars['site']['app'],
				'page'   => $vars['site']['page'],
				'paging' => $vars['site']['paging']
			]);
		}else{
			$vars['site']['previous_page'] = $this->swarm->Get('SESSION.previous_page');
		}
		if ($this->swarm->Get('error')) {
			$vars['site']['error']         = true;
			$vars['site']['error_message'] = $this->swarm->Get('error_message');
			$vars['site']['page']          = 'error';
		}

		return $vars;

	}

}
