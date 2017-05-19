<?php 

// include 'baseController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class studentController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}

	public function getStudent(Request $request, Response $response, $args){
		$student_id = $args['id'];

		$student = $this->entityManager->getRepository('Student')->find($student_id);
		$enrolled_courses = $this->entityManager->createQuery("SELECT c.name, c.description, c.image_url FROM Student s JOIN s.courses c where s.id='{$student_id}'")->getResult();
		$num_of_courses = count($enrolled_courses);

		$courses = [];
		for ($i=0 ; $i < $num_of_courses ; $i++) { 
			$course = array("name" => $enrolled_courses[$i]['name'],
							"description" => $enrolled_courses[$i]['description'],
							"image_url" => $enrolled_courses[$i]['image_url']);
			$courses []= $course;
		}

		$response->withHeader('Content-type', 'application/json');
		$data = array("student" => ["id" => $student->getId(),
								    "name" => $student->getName(),
								    "phone" => $student->getPhone(),
								    "email" => $student->getEmail(),
								    "image_url" => $student->getImageUrl()],
					  "courses" => $courses);

		return $response->withJson($data);
	}
}