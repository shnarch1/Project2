<?php

session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


require 'vendor/autoload.php';
require 'src/Controller/schoolController.php';
require 'src/Controller/courseController.php';
require 'src/Controller/studentController.php';
require 'src/Controller/adminController.php';
require 'src/Controller/loginController.php';

$app = new \Slim\App;
$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('view', array(
        'cache' => false,
        'deubg' => true
    ));

    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

$container['em'] = function($container) {

	$isDevMode = true;
	$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

	// database configuration parameters
	$db_config = new \Doctrine\DBAL\Configuration();
	$dbParams = array(
	    'dbname' => 'school',
	    'user' => 'root',
	    'password' => '',
	    'host' => '127.0.0.1',
	    'driver' => 'pdo_mysql',
	);


	$conn = \Doctrine\DBAL\DriverManager::getConnection($dbParams, $db_config);
	$em = EntityManager::create($conn, $config);
	return $em;
};

$container['schoolController'] = function ($container) {
	return new schoolController($container);
};

$container['adminController'] = function ($container) {
	return new adminController($container);
};

$container['courseController'] = function ($container) {
	return new courseController($container);
};

$container['studentController'] = function ($container) {
	return new studentController($container);
};

$container['loginController'] = function ($container) {
	return new loginController($container);
};

$app->get('/login', 'loginController:showLogin');
$app->post('/login', 'loginController:verifyDetails');
$app->get('/logout', 'loginController:logout');

$app->get('/school', 'schoolController:showMain');
$app->get('/administration', 'adminController:showMain');

$app->get('/school/course/{id}', 'courseController:getCourse');
$app->get('/school/course', 'courseController:getAllCourses');
$app->post('/school/course', 'courseController:addCourse');
$app->delete('/school/course/{id}', 'courseController:deleteCourse');
$app->post('/school/course/update/{id}', 'courseController:updateCourse');

$app->get('/school/student/{id}', 'studentController:getStudent');
$app->delete('/school/student/{id}', 'studentController:deleteStudent');
$app->post('/school/student/update/{id}', 'studentController:updateStudent');
$app->post('/school/student', 'studentController:addStudent');

$app->get('/school/admin/{id}', 'adminController:getAdmin');
$app->post('/school/admin', 'adminController:addAdmin');
$app->delete('/school/admin/{id}', 'adminController:deleteAdmin');
$app->post('/school/admin/update/{id}', 'adminController:updateAdmin');


$app->run();

 ?>