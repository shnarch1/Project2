<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";
include 'src/Entity/Course.php';
include 'src/Entity/Administrator.php';
include 'src/Entity/Student.php';
include 'src/Entity/Role.php';

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
// // $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);
// $config = new \Doctrine\DBAL\Configuration();
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

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
// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);