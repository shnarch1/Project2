<?php 

class Administrator
{
	private $id = null;
    private $name;
    private $role;
    private $phone;
    private $email;
    private $password;
    private $image_url;
	
	function __construct($name=null, $role=null, $phone=null, $email=null, $password=null, $image_url=null)
	{
		$this->name = $name;
		$this->role = $role;
		$this->phone = $phone;
		$this->email = $email;
		$this->password = $password;
		$this->image_url = $image_url;
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getRole(){
		return $this->role;
	}

	public function getPhone(){
		return $this->phone;
	}
	
	public function getEmail(){
		return $this->email;
	}

	public function getPassword(){
		return $this->password;
	}

	public function getImageUrl(){
		return $this->image_url;
	}

	public function setName($name){
		return $this->name = $name;
	}
	
	public function setRole($role){
		return $this->role = $role;
	}

	public function setPhone($phone){
		return $this->phone = $phone;
	}

	public function setEmail($email){
		return $this->email = $email;
	}

	public function setPassword($password){
		return $this->password = $password;
	}

	public function setImageUrl($image_url){
		return $this->image_url = $image_url;
	}

}


 ?>