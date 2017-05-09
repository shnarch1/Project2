<?php 

include 'course.php';

$db = new PDO('mysql:host=localhost;dbname=school;charset=utf8mb4', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$c = new Course(2, $db);

$c->load();
var_dump($c);

 // $c = new Course(null, $db, "Linear Algebra", "None", "/etc/test/", null, null);
 // $c->insert();

 ?>