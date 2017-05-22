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

		$student = $this->entityManager->getRepository('Student', '*')->find($student_id);

		$all_courses = $this->entityManager->createQuery("SELECT c FROM Course c")->getArrayResult();

		$enrolled_courses = $this->entityManager->createQuery("SELECT c.id, c.name, c.description, c.image_url FROM Student s JOIN s.courses c where s.id='{$student_id}'")->getResult();
		
		$num_of_courses = count($enrolled_courses);

		$courses = [];
		for ($i=0 ; $i < $num_of_courses ; $i++) { 
			$course = array("id" => $enrolled_courses[$i]['id'],
							"name" => $enrolled_courses[$i]['name'],
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
					  "enrolled_courses" => $courses,
					  "all_courses" => $all_courses);

		return $response->withJson($data);
	}

	public function deleteStudent(Request $request, Response $response, $args){
		$student_id = $args['id'];
		$student= $this->entityManager->getRepository('Student')->find($student_id);
		$this->entityManager->remove($student);
		$this->entityManager->flush();
	}

	public function updateStudent(Request $request, Response $response, $args){
	}
}
