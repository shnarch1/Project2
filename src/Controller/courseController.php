<?php 

// include 'baseController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// require 'functions.php';

// session_start();

class courseController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function getCourse(Request $request, Response $response, $args){

		if(!isLoggedIn()){
			return unauthorized($response);
		}

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
								   "image_url" => $course->getImageUrl(),
								   ],
					  "students" => $students,
					  "role" => $_SESSION['role']);

		return $response->withJson($data);

	}


	public function getAllCourses(Request $request, Response $response){
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$all_courses = $this->entityManager->getRepository('Course')->findAll();
		$data = [];
		for ($i=0, $count = count($all_courses); $i < $count; $i++ ){
			$data []= array("id" => $all_courses[$i]->getId(),
							"name" => $all_courses[$i]->getName(),
							"description" => $all_courses[$i]->getDescription(),
							"image_url" => $all_courses[$i]->getImageUrl()
							);
		}
		return $response->withJson($data);
	}


	public function deleteCourse(Request $request, Response $response, $args){

		if(!isLoggedIn() || !$this->isAuth(__FUNCTION__)){
			return unauthorized($response);
		}

		$course_id = $args['id'];
		$course = $this->entityManager->getRepository('Course')->find($course_id);
		$this->entityManager->remove($course);
		$this->entityManager->flush();
	}

	public function updateCourse(Request $request, Response $response, $args){

		if(!isLoggedIn() || !$this->isAuth(__FUNCTION__)){
			return unauthorized($response);
		}

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
		
		if(!isLoggedIn() || !$this->isAuth(__FUNCTION__)){
			return unauthorized($response);
		}

		$course = new Course();

		$files = $request->getUploadedFiles();
		$content = $request->getParams();

		$new_image = $files['new_course_image'];
		if ($new_image->file != ''){
			$new_image_file_name = time();
			$new_image_url = "public/images/courses/" . $new_image_file_name;
			$new_image->moveTo($new_image_url);
			$course->setImageUrl($new_image_url);
		}
		
		$course->setName($content['course_name']);
		$course->setDescription($content['course_description']);

		$this->entityManager->persist($course);
		$this->entityManager->flush();

		return $response->withRedirect("/school");
	}

	private function isAuth($method){
		switch ($method) {
		    case 'deleteCourse':
		        if ($_SESSION['role'] != "sales"){
		        	return true;
		        }
		        else{
		        	return false;
		        }
	        case 'updateCourse':
		        if ($_SESSION['role'] != "sales"){
		        	return true;
		        }
		        else{
		        	return false;
		        }	        
	        case 'addCourse':
		        if ($_SESSION['role'] != "sales"){
		        	return true;
		        }
		        else{
		        	return false;
		        }
	        default:
	        	return false;
		       
		}
	}
}

 ?>