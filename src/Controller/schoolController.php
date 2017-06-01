<?php 

require_once 'baseController.php';
require 'functions.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

class schoolController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showMain(Request $request, Response $response){

		if (!isLoggedIn()){
			return $response->withHeader('Location', '/login');
		}

		$user = ["name" => $_SESSION['name'],
				 "role" => $_SESSION['role']];
		$students = $this->entityManager->getRepository('Student')->findAll();
		$courses = $this->entityManager->getRepository('Course')->findAll();

		return $this->view->render($response, "main.html", [
           		"sections" => ['entities.html', 'view.html'],
           		"courses" => $courses,
           		"students" => $students,
           		"user" => $user
            ]
        );
	}

}

 ?>