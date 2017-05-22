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
		$data = array("course" => ["id" => $course->getId(),
								   "name" => $course->getName(),
								   "description" => $course->getDescription(),
								   "image_url" => $course->getImageUrl()],
					  "students" => $students);

		return $response->withJson($data);

	}

	public function deleteCourse(Request $request, Response $response, $args){

		$course_id = $args['id'];
		$course = $this->entityManager->getRepository('Course')->find($course_id);
		$this->entityManager->remove($course);
		$this->entityManager->flush();
	}

	public function updateCourse(Request $request, Response $response, $args){

		$files = $request->getUploadedFiles();

		$content = $request->getParams();

		$course_id = $args['id'];
		$course_name = $content['course_name'];
		$course_description = $content['course_description'];
		
		$course = $this->entityManager->getRepository('Course')->find($course_id);

		$new_image = $files['new_course_image'];
		if ($new_image->file != ''){
			$new_image_file_name = $new_image->getClientFilename();
    		$new_image_url = "public/images/course/" + $new_image_file_name;
    		$new_image->moveTo($new_image_url);	    		
			$course->setImageUrl($new_image_url);
		}

		$course->setName($course_name);
		$course->setDescription($course_description);
		$this->entityManager->flush();

		return $response->withRedirect("/school");
	}

	public function addCourse(Request $request, Response $response){
		$files = $request->getUploadedFiles();
		$content = $request->getParams();

		$new_image = $files['new_course_image'];

		$new_image_file_name = $new_image->getClientFilename();
    	$new_image_url = "public/images/course/" + $new_image_file_name;
    	$new_image->moveTo($new_image_url);

		$course = new Course();
		$course->setName($content['course_name']);
		$course->setDescription($content['course_description']);
		$course->setImageUrl($new_image_url);

		$this->entityManager->persist($course);
		$this->entityManager->flush();

		return $response->withRedirect("/school");
	}
}

 ?>