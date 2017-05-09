<?php 

class Student
{	
	private $id = null;
    private $name;
    private $phone;
    private $email;
    private $image_url;
    public $courses;
	
	function __construct($name=null, $phone=null, $email=null, $image_url=null)
	{
		$this->name = $name;
		$this->phone = $phone;
		$this->email = $email;
		$this->image_url = $image_url;
		$this->courses = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getPhone(){
		return $this->phone;
	}
	
	public function getEmail(){
		return $this->email;
	}

	public function getImageUrl(){
		return $this->image_url;
	}

	public function setName($name){
		return $this->name = $name;
	}

	public function setPhone($phone){
		return $this->phone = $phone;
	}

	public function setEmail($email){
		return $this->email = $email;
	}

	public function setImageUrl($image_url){
		return $this->image_url = $image_url;
	}

}


 ?>