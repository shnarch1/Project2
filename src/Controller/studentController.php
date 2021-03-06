<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class studentController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}

	public function getStudent(Request $request, Response $response, $args){
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

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
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$student_id = $args['id'];
		$student= $this->entityManager->getRepository('Student')->find($student_id);
		$this->entityManager->remove($student);
		$this->entityManager->flush();
	}

	public function updateStudent(Request $request, Response $response, $args){

		if(!isLoggedIn()){
			return unauthorized($response);
		}
		
		$files = $request->getUploadedFiles();

		$content = $request->getParams();

		$student_id = $args['id'];
		$student_name = $content['student_name'];
		$student_phone = $content['student_phone'];
		$student_email = $content['student_email'];
		
		if (array_key_exists ('courses' , $content)){
			$courses = $content['courses'];
		}
		else{
			$courses = null;
		}

		$student = $this->entityManager->find('Student', $student_id);

		$enrolled_courses = $student->courses->getValues();

		for($i=0, $count=count($enrolled_courses); $i < $count; $i++){
			$student->courses->removeElement($enrolled_courses[$i]);
		}


		for($i=0, $count=count($courses); $i < $count; $i++){
			$new_course = $this->entityManager->find('Course', $courses[$i]);
			$student->courses->add($new_course);
		}

		$new_image = $files['new_student_image'];
		if ($new_image->file != ''){
			$new_image_file_name = $student_id;
    		$new_image_url = "public/images/students/" . $student_id;
    		$new_image->moveTo($new_image_url);	    		
			$student->setImageUrl($new_image_url);
		}

		$student->setName($student_name);
		$student->setPhone($student_phone);
		$student->setEmail($student_email);
		$this->entityManager->flush();

		return $response->withRedirect("/school");
	}

	public function addStudent(Request $request, Response $response){
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$files = $request->getUploadedFiles();
		$content = $request->getParams();

		$student = new Student();
		$student->setName($content['student_name']);
		$student->setPhone($content['student_phone']);
		$student->setEmail($content['student_email']);

		if (array_key_exists ('courses' , $content)){
			$courses = $content['courses'];
		}
		else{
			$courses = null;
		}

		for($i=0, $count=count($courses); $i < $count; $i++){
			$new_course = $this->entityManager->find('Course', $courses[$i]);
			$student->courses->add($new_course);
		}

		$new_image = $files['new_student_image'];
		if ($new_image->file != ''){
			$new_image_file_name = time();
    		$new_image_url = "public/images/students/" . $new_image_file_name;
    		$new_image->moveTo($new_image_url);	    		
			$student->setImageUrl($new_image_url);
		}
		else
		{
			$student->setImageUrl('public/images/students/default.png');
		}

		$this->entityManager->persist($student);
		$this->entityManager->flush();

		return $response->withRedirect("/school");
	}
}
