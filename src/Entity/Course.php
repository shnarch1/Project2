<?php 

class Course
{

	private $id = null;
	private $name;
	private $description;
	private $image_url;
	private $students_list;
	
	function __construct($name=null, $description=null, $image_url=null, $students_list=null)
	{
		$this->name = $name;
		$this->description = $description;
		$this->image_url = $image_url;
		$this->students_list = $students_list;
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getDescription(){
		return $this->description;
	}

	public function getImageUrl(){
		return $this->image_url;
	}

	public function setName($name){
		return $this->name = $name;
	}

	public function setDescription($description){
		return $this->description = $description;
	}

	public function setImageUrl($image_url){
		return $this->image_url = $image_url;
	}
}