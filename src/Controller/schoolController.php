<?php 

include 'baseController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class schoolController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showMain(Request $request, Response $response){

		$students = $this->entityManager->getRepository('Student')->findAll();
		$courses = $this->entityManager->getRepository('Course')->findAll();

		return $this->view->render($response, "main.html", [
           		"sections" => ['entities.html', 'view.html'],
           		"courses" => $courses,
           		"students" => $students
            ]
        );
	}


}

 ?>