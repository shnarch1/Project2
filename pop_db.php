<?php 

	require_once "bootstrap.php";

	$role1 = new Role("owner");
	$role2 = new Role("manager");
	$role3 = new Role("sales");

	$entityManager->persist($role1);
	$entityManager->persist($role2);
	$entityManager->persist($role3);
	$entityManager->flush();

	$course1 = new Course("Php", "PHP is a server scripting language, and a powerful tool for making dynamic and interactive Web pages.PHP is a widely-used, free, and efficient alternative to competitors such as Microsoft's ASP.", "/public/images/courses/php7.png");
	$course2 = new Course("JavaScript", "JavaScript is the programming language of HTML and the Web. JavaScript is easy to learn. This tutorial will teach you JavaScript from basic to advanced.", "/public/images/courses/js.png");
	$course3 = new Course("C++", "C++ (pronounced cee plus plus /ˈsiː plʌs plʌs/) is a general-purpose programming language. It has imperative, object-oriented and generic programming features, while also providing facilities for low-level memory manipulation.", "/public/images/courses/cpp.png");
	$course4 = new Course("Docker", "Docker is the world’s leading software container platform. Developers use Docker to eliminate “works on my machine” problems when collaborating on code with co-workers.", "/public/images/courses/docker.png");

	$entityManager->persist($course1);
	$entityManager->persist($course2);
	$entityManager->persist($course3);
	$entityManager->persist($course4);
	$entityManager->flush();

	$student1 = new Student("avi", "0545540121", "avi@gmail.com", "/public/images/students/default.png");
	$student2 = new Student("david", "123456789", "david@gmail.com", "/public/images/students/default.png");
	$student3 = new Student("moshe", "987654321", "moshe@gmail.com", "/public/images/students/default.png");
	$student4 = new Student("dean", "000000000", "dean@gmail.com", "/public/images/students/default.png");

	$entityManager->persist($student1);
	$entityManager->persist($student2);
	$entityManager->persist($student3);
	$entityManager->persist($student4);
	$entityManager->flush();

	$admin1 = new Administrator("admin1", "0545540121", "admin1@gmail.com", "password1", "/public/images/admins/default.jpg");
	$admin2 = new Administrator("admin2", "123456789", "admin2@gmail.com", "password1", "/public/images/admins/default.jpg");
	$admin3 = new Administrator("admin3", "000000000", "admin3@gmail.com", "password1", "/public/images/admins/default.jpg");
	$entityManager->persist($admin1);
	$entityManager->persist($admin2);
	$entityManager->persist($admin3);
	$entityManager->flush();

	$admin = $entityManager->getRepository('Administrator')->find(1);
	$role_manager = $entityManager->getRepository('Role')->find(2);
	$admin->setRole($role_manager);
	$entityManager->flush();

	$course = $entityManager->getRepository('Course')->find(1);
	
	$student = $entityManager->getRepository('Student')->find(1);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(2);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(3);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(4);
	$student->courses->add($course);
	$entityManager->flush();

	$course = $entityManager->getRepository('Course')->find(2);
	
	$student = $entityManager->getRepository('Student')->find(1);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(2);
	$student->courses->add($course);

	$entityManager->flush();

	$course = $entityManager->getRepository('Course')->find(3);
	
	$student = $entityManager->getRepository('Student')->find(1);
	$student->courses->add($course);
	$entityManager->flush();

	$course = $entityManager->getRepository('Course')->find(4);
	
	$student = $entityManager->getRepository('Student')->find(1);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(2);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(3);
	$student->courses->add($course);
	$student = $entityManager->getRepository('Student')->find(4);
	$student->courses->add($course);
	$entityManager->flush();






	// $students = $entityManager->find('Student', 2);
	// $student = $entityManager->getRepository('Student')->findAll();
	// echo $student[3]->getName();
	// $course = $entityManager->getRepository('Course')->find(2);
	// echo $student->getName();
	// echo $course->getName();
	// $course = $entityManager->getReference('Course', 8);
	// $student->courses->add($course);
	// $entityManager->flush();

	//All the students in course id
	//php vendor/bin/doctrine.php orm:run-dql "SELECT s.name FROM Student s JOIN s.courses c where c.id=3"

	//All courses fo students id
	//php vendor/bin/doctrine.php orm:run-dql "SELECT c.name FROM Student s JOIN s.courses c where s.id=1"

	// $query = $entityManager->createQuery('SELECT s, c FROM src/Student s JOIN ENROLLMENT e WITH s.id = e.student_id JOIN src/Course c WITH c.id = e.course_id');
	// $users = $query->getResult();



	// $physics = $entityManager->find("Course", 4);
	// echo $physics->getName();


	// $course = new Course("doc_course", "created by doctrine", "/etc/doctrine");

	// $entityManager->persist($course);
	// $entityManager->flush();

	// $physics = $entityManager->find("Course", 4);
	// echo $physics->getName();

// $query = $entityManager->createQuery('SELECT s.name FROM Student s JOIN s.courses c where c.id=3');

	// $course = $entityManager->getRepository('Course')->find(1);
	
	// $student = $entityManager->getRepository('Student')->find(1);
	// $student->courses->add($course);
	// $student = $entityManager->getRepository('Student')->find(2);
	// $student->courses->add($course);
	// $student = $entityManager->getRepository('Student')->find(3);
	// $student->courses->add($course);
	// $student = $entityManager->getRepository('Student')->find(4);
	// $student->courses->add($course);
	// $entityManager->flush();

 ?>