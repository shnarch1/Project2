<?php 

require_once 'baseController.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// session_start();

class loginController extends baseController {

	function __construct($container){
		parent::__construct($container);
	}
	public function showLogin(Request $request, Response $response){

		return $this->view->render($response, "login.html");
	}

	public function verifyDetails(Request $request, Response $response){

		$content = $request->getParams();
		
		if (!isset($content['email']) || !isset($content['pass'])){
			return $response->withStatus(400)
				   			->withHeader('Content-Type', 'text/html')
				   			->write("Email and Pass sould be set");
		}

		$user = $this->entityManager->getRepository("Administrator")->findOneBy(array('email' => $content['email']));
		if (!isset($user) || !password_verify($content['pass'], $user->getPassword())) {
			return $response->withStatus(401)
				   			->withHeader('Content-Type', 'text/html')
				   			->write("User Or Password is incorrect");
		}
		else{
			$_SESSION['id'] = $user->getId();
			$_SESSION['name'] = $user->getName();
	        $_SESSION['phone'] = $user->getPhone();
	        $_SESSION['email'] = $user->getEmail();
	        $_SESSION['imageUrl'] = $user->getImageUrl();
	        $_SESSION['role'] = $this->entityManager->createQuery("SELECT r.name FROM Administrator a JOIN a.role r where a.id='{$user->getId()}'")->getResult()[0]['name'];
	        $_SESSION['logged_in'] = true;
			}
		return $response->withHeader('Location', '/school');

	}

	public function logout(Request $request, Response $response){

		session_destroy();
		return $response->withHeader('Location', '/login');
	}
}

?>
