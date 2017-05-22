<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


require 'vendor/autoload.php';
require 'src/Controller/schoolController.php';
require 'src/Controller/courseController.php';
require 'src/Controller/studentController.php';

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

$container['courseController'] = function ($container) {
	return new courseController($container);
};

$container['studentController'] = function ($container) {
	return new studentController($container);
};

$app->get('/school', 'schoolController:showMain');

$app->get('/school/course/{id}', 'courseController:getCourse');
$app->post('/school/course', 'courseController:addCourse');
$app->delete('/school/course/{id}', 'courseController:deleteCourse');
$app->post('/school/course/update/{id}', 'courseController:updateCourse');

$app->get('/school/student/{id}', 'studentController:getStudent');
$app->delete('/school/student/{id}', 'studentController:deleteStudent');


$app->run();

 ?>