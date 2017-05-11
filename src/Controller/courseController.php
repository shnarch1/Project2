<?php 

// include 'baseController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class courseController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showCourse(Request $request, Response $response, $args){

		$course_id = $args['id'];
		$course = $this->entityManager->getRepository('Course')->find($course_id);
		$enrolled_students = $this->entityManager->createQuery("SELECT s FROM Student s JOIN s.courses c where c.id='{$course_id}'")->getResult();
		$num_of_students = count($enrolled_students);

		return $this->view->render($response, "course.html", [
           		"course" => $course,
           		"students" => $enrolled_students,
           		"num_of_students" => $num_of_students
            ]
        );
	}

}

 ?>