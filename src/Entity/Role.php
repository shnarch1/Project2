<?php 

class Role
{
	private $id = null;
    private $name;

	
	function __construct($name=null)
	{
		$this->name = $name;
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		return $this->name = $name;
	}
}

?>