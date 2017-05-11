<?php 

use \Slim\Container as Container;
require 'src/Entity/Student.php';
require 'src/Entity/Course.php';

class baseController
{
	protected $container;
	protected $view;
	protected $entityManager;
	
	function __construct($container)
	{
		$this->container = $container;
		$this->view = $container['view'];
		$this->entityManager = $container['em'];
	}
}

 ?>