<?php

function isLoggedIn(){

	if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']){
		return false;
	}
	else{
		return true;
	}
}

function unauthorized($response){

	return $response->withStatus(401)
							->withHeader('Content-Type', 'text/html')
				   			->write("Unauthorized!");

}

?>