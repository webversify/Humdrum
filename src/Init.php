<?php

use App\Core\{
	Swarm,
	Database,
    Configs,
	Model,
	View,
	Controller,
	Helpers
};

class Initialize {

	public static function HumDrum() {
		$load_app = new self;
        return $load_app->LoadApp();
    }

	public function LoadApp() {

		// Load Swarm & Config
		$swarm = Swarm::Instance();

		if (!$swarm->Get('configs')) {			
			$swarm->Set('configs', Configs::Setup());
		}
		if ($swarm->Get('configs')['debug']) {
			error_reporting(E_PARSE);
		}else{
			error_reporting(0);
		}

		// Load Post Swarm
		$swarm->SetPostGet();

		// Set App Controller

		$controller = new Controller;
		$controller->Routing();
		$controller->Apps();
		$controller->Variables();
		$controller->Execute();
		// Set App View

		$view = new View;
		return $view->Render();

	}

}
