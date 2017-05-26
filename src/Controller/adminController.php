<?php 

require_once 'baseController.php';
require 'src/Entity/Administrator.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class adminController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showMain(Request $request, Response $response){

		$admins = $this->entityManager->getRepository('Administrator')->findAll();

		return $this->view->render($response, "main.html", [
           		"sections" => ['admins.html', 'view.html'],
           		"admins" => $admins
            ]
        );
	}

	public function getAdmin(Request $request, Response $response, $args){
		$admin_id = $args['id'];

		$admin = $this->entityManager->getRepository('Administrator', '*')->find($admin_id);

		$data = array("admin" => ["id" => $admin->getId(),
								  "name" => $admin->getName(),
								  "role" => $admin->getRole(),
								  "phone" => $admin->getPhone(),
								  "email" => $admin->getEmail(),
								  "image_url" => $admin->getImageUrl()]);

		return $response->withJson($data);
	}


	public function deleteAdmin(Request $request, Response $response, $args){
		$admin_id = $args['id'];
		$admin= $this->entityManager->getRepository('Administrator')->find($admin_id);
		$this->entityManager->remove($admin);
		$this->entityManager->flush();
	}

	public function updateAdmin(Request $request, Response $response, $args){
		
		$files = $request->getUploadedFiles();

		$content = $request->getParams();

		$admin_id = $args['id'];
		$admin_name = $content['admin_name'];
		$admin_phone = $content['admin_phone'];
		$admin_email = $content['admin_email'];
		$admin_role = $content['admin_role'];

		$admin = $this->entityManager->find('Administrator', $admin_id);

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
		$admin->setRole($admin_role);
		$this->entityManager->flush();

		return $response->withRedirect("/administration");
	}

		public function addAdmin(Request $request, Response $response){

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

}

 ?>