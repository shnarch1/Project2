<?php 

// include 'baseController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class courseController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function getCourse(Request $request, Response $response, $args){

		$course_id = $args['id'];
		$course = $this->entityManager->getRepository('Course')->find($course_id);
		$enrolled_students = $this->entityManager->createQuery("SELECT s FROM Student s JOIN s.courses c where c.id='{$course_id}'")->getResult();
		$num_of_students = count($enrolled_students);

		$students = [];
		for ($i=0 ; $i < $num_of_students ; $i++) { 
			$student = array("name" => $enrolled_students[$i]->getName(),
							 "phone" => $enrolled_students[$i]->getPhone(),
							 "email" => $enrolled_students[$i]->getEmail(),
							 "image_url" => $enrolled_students[$i]->getImageUrl());
			$students []= $student;
		}

		$response->withHeader('Content-type', 'application/json');
		$data = array("course" => ["name" => $course->getName(),
								   "description" => $course->getDescription(),
								   "image_url" => $course->getImageUrl()],
					  "students" => $students);
		// $data = array('course' => $course, 'students' => $enrolled_students);

		return $response->withJson($data);
		// return $this->view->render($response, "course.html", [
  //          		"course" => $course,
  //          		"students" => $enrolled_students,
  //          		"num_of_students" => $num_of_students
  //           ]
  //       );
	}

}

 ?>