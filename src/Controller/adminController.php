<?php 

require_once 'baseController.php';
require 'src/Entity/Administrator.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

class adminController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showMain(Request $request, Response $response){

		if(!isLoggedIn()  || !$this->isAuth(__FUNCTION__)){
			return unauthorized($response);
		}

		$user = ["name" => $_SESSION['name'],
				 "role" => $_SESSION['role']];

		$admins = $this->entityManager->getRepository('Administrator')->findAll();

		if($user['role'] == "manager"){
			for($i = 0, $count=count($admins); $i<$count; $i++){
				if ($admins[$i]->getRole()->getName() == "owner"){
					array_splice($admins, $i, 1);
					break;
				}
			}
		}

		return $this->view->render($response, "main.html", [
           		"sections" => ['admins.html', 'view.html'],
           		"admins" => $admins,
           		"user" => $user
            ]
        );
	}

	public function getAdmin(Request $request, Response $response, $args){
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$admin_id = $args['id'];
		$admin = $this->entityManager->getRepository('Administrator', '*')->find($admin_id);

		if(!$this->isAuth(__FUNCTION__, $admin)){
			return unauthorized($response);
		}

		$data = array("admin" => ["id" => $admin->getId(),
								  "name" => $admin->getName(),
								  "role" => $admin->getRole()->getName(),
								  "phone" => $admin->getPhone(),
								  "email" => $admin->getEmail(),
								  "image_url" => $admin->getImageUrl()]);

		return $response->withJson($data);
	}


	public function deleteAdmin(Request $request, Response $response, $args){
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$admin_id = $args['id'];
		$admin= $this->entityManager->getRepository('Administrator')->find($admin_id);
		if(!$this->isAuth(__FUNCTION__, $admin)){
			return unauthorized($response);
		}
		else{
			$this->entityManager->remove($admin);
			$this->entityManager->flush();		
		}
	}

	public function updateAdmin(Request $request, Response $response, $args){
		
		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$admin_id = $args['id'];
		$admin = $this->entityManager->find('Administrator', $admin_id);

		if(!$this->isAuth(__FUNCTION__, $admin)){
			return unauthorized($response);
		}

		$files = $request->getUploadedFiles();

		$content = $request->getParams();

		$admin_name = $content['admin_name'];
		$admin_phone = $content['admin_phone'];
		$admin_email = $content['admin_email'];
		$admin_role = $this->entityManager->getRepository('Role')->findOneBy(Array("name" => $content['admin_role']));

		

		$new_image = $files['new_admin_image'];
		if ($new_image->file != ''){
			$new_image_file_name = $admin_id;
    		$new_image_url = "public/images/admin/" . $admin_id;
    		$new_image->moveTo($new_image_url);	    		
			$student->setImageUrl($new_image_url);
		}

		$admin->setName($admin_name);
		$admin->setPhone($admin_phone);
		$admin->setEmail($admin_email);
		if ($_SESSION['role'] == "manager" && $_SESSION['id'] != $admin->getId() ){
			$admin->setRole($admin_role);
		}
		$this->entityManager->flush();

		return $response->withRedirect("/administration");
	}

	public function addAdmin(Request $request, Response $response){

		if(!isLoggedIn()){
			return unauthorized($response);
		}

		$files = $request->getUploadedFiles();
		$content = $request->getParams();

		$admin = new Administrator();
		$admin->setName($content['admin_name']);
		$admin->setPhone($content['admin_phone']);
		$admin->setEmail($content['admin_email']);
		$admin->setRole($content['admin_role']);

		$new_image = $files['new_admin_image'];
		if ($new_image->file != ''){
			$new_image_file_name = time();
    		$new_image_url = "public/images/admins/" . $new_image_file_name;
    		$new_image->moveTo($new_image_url);	    		
			$admin->setImageUrl($new_image_url);
		}

		$this->entityManager->persist($admin);
		$this->entityManager->flush();

		return $response->withRedirect("/administration");
	}

	private function isAuth($method, $admin=null){
		switch ($method) {
		    case 'showMain':
		        if ($_SESSION['role'] != "sales"){
		        	return true;
		        }
		        else{
		        	return false;
		        }
	        case 'getAdmin':
		        if ($_SESSION['role'] != "sales"){
		        	if (($_SESSION['role'] == "manager")){
		        		if($admin->getRole()->getName() == 'owner'){
		        			return false;
		        		}
		        		else{
		        			return true;
		        		}
		        	}
		        	else{
		        		return true;
		        	}
		        }
		        else{
		        	return false;
		        }
	        case 'deleteAdmin':
	        	if ($_SESSION['role'] != "sales"){
		        	if (($_SESSION['role'] == "manager")){
		        		if($_SESSION['id'] == $admin->getId() || $admin->getRole()->getName() == 'owner'){
		        			return false;
		        		}
		        		else{
		        			return true;
		        		}	
		        	}
		        	else{
		        		return true;
		        	}
		        }
		        else{
		        	return false;
		        }
	        case 'updateAdmin':
	        	if ($_SESSION['role'] != "sales"){
		        		if($_SESSION['role'] == "manager"){
		        			if ($admin->getRole()->getName() == "owner"){
		        				return false;
		        			}
		        			else{
		        				return true;
		        			}
		        		}
		        	return true;
		        }
		        else{
		        	return false;
		        }
	        case 'addAdmin':
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