<?php 

require_once 'baseController.php';
require 'src/Entity/Administrator.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class adminController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showMain(Request $request, Response $response){

		$admins = $this->entityManager->getRepository('Administrator')->findAll();

		return $this->view->render($response, "main.html", [
           		"sections" => ['admins.html', 'view.html'],
           		"admins" => $admins
            ]
        );
	}

	public function getAdmin(Request $request, Response $response, $args){
		$admin_id = $args['id'];

		$admin = $this->entityManager->getRepository('Administrator', '*')->find($admin_id);

		$data = array("admin" => ["id" => $admin->getId(),
								  "name" => $admin->getName(),
								  "role" => $admin->getRole(),
								  "phone" => $admin->getPhone(),
								  "email" => $admin->getEmail(),
								  "image_url" => $admin->getImageUrl()]);

		return $response->withJson($data);
	}

}

 ?>