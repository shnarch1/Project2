<?php 

class Course
{

	private $_id;
	private $name;
	private $description;
	private $image_url;
	private $num_of_students;
	private $students_list;
	private $table_name = 'courses';
	private $db = null;
	
	function __construct($_id=null, $db=null, $name=null, $description=null, $image_url=null, $num_of_students=null, $students_list=null)
	{
		$this->_id = $_id;
		$this->name = $name;
		$this->description = $description;
		$this->image_url = $image_url;
		$this->num_of_students = $num_of_students;
		$this->students_list = $students_list;
		$this->db = $db;
	}

	public function load()
	{
		// SELECT COUNT(*) as num_of_students FROM school.enrollment where course_id = 4;
		// SELECT student_id FROM school.enrollment where course_id=2;
		$data = $this->selectCourseById();
		$num_of_students = $this->getNumOfStudents();
		$students_list = $this->getListOfStudents();

		$this->name = $data['name'];
    	$this->description = $data['description'];
    	$this->image_url = $data['image_url'];
    	$this->num_of_students = $num_of_students;
    	$this->students_list = $students_list;

	}

	private function selectCourseById()
	{
		try{
			$stmt = $this->db->query("SELECT * FROM {$this->table_name} where id={$this->_id}");
		}
		catch(PDOException $ex) {
    		echo "An Error occured: {$ex->getMessage()} replace with redirect!";
    		die();
		}	
		
		$rows = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    		$rows []=$row;
    	}
    	if (count($rows) != 1){
    		echo "An Error occured: more than one row was returned! replace with redirect!";
    		die();		
    	}
    	else{
    		return $rows[0];
    	}

	}

	private function getNumOfStudents(){
		try{
			$stmt = $this->db->query("SELECT COUNT(*) as num_of_students FROM enrollment where course_id = {$this->_id}");
		}
		catch(PDOException $ex) {
    		echo "An Error occured: {$ex->getMessage()} replace with redirect!";
    		die();
		}

		$rows = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    		$rows []=$row;
    	}
    	if (count($rows) != 1){
    		echo "An Error occured: more than one row was returned! replace with redirect!";
    		die();		
    	}
    	else{
    		return $rows[0]['num_of_students'];
    	}
	}

	private function getListOfStudents(){
		try{
			$stmt = $this->db->query("SELECT student_id FROM enrollment where course_id = {$this->_id}");
		}
		catch(PDOException $ex) {
    		echo "An Error occured: {$ex->getMessage()} replace with redirect!";
    		die();
		}

		$rows = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    		$rows []=$row;
    	}
    	if (empty($rows)){
    		echo "An Error occured: the query was empty! replace with redirect!";
    		die();		
    	}
    	else{
    		$student_list = [];
    		for($i = 0, $count = count($rows); $i < $count; $i++){
    			$student_list []= $rows[$i]["student_id"];
    		}
    		return $student_list;
    	}
	}

	public function insert(){
		// INSERT INTO school.courses (name, description, image_url)
		// 	VALUES ("Calculus", "None", "/home/user/")

		try{
			$stmt = $this->db->query("INSERT INTO {$this->table_name} (name, description, image_url)
				VALUES ('{$this->name}', '{$this->description}', '{$this->image_url}')");
		}
		catch(PDOException $ex) {
    		echo "An Error occured: {$ex->getMessage()} replace with redirect!";
    		die();
		}

	}
}

?>