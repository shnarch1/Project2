<?php 

	require_once "bootstrap.php";
	include 'src/Entity/Student.php';
	include 'src/Entity/Course.php';

	// $course1 = new Course("Calculus", "Created Bt Doctrine", "/home/user/");
	// $course2 = new Course("Physics", "Created Bt Doctrine", "/home/user/");
	// $course3 = new Course("DSP", "Created Bt Doctrine", "/home/user/");
	// $course4 = new Course("Python", "Created Bt Doctrine", "/home/user/");

	// $entityManager->persist($course1);
	// $entityManager->persist($course2);
	// $entityManager->persist($course3);
	// $entityManager->persist($course4);
	// $entityManager->flush();

	// $student1 = new Student("avi", "0545540121", "avi@gmail.com", "/home/user/");
	// $student2 = new Student("david", "123456789", "david@gmail.com", "/home/user/");
	// $student3 = new Student("moshe", "987654321", "moshe@gmail.com", "/home/user/");
	// $student4 = new Student("dean", "000000000", "dean@gmail.com", "/home/user/");

	// $entityManager->persist($student1);
	// $entityManager->persist($student2);
	// $entityManager->persist($student3);
	// $entityManager->persist($student4);
	// $entityManager->flush();

	// $students = $entityManager->find('Student', 2);
	$student = $entityManager->getRepository('Student')->findAll();
	echo $student[3]->getName();
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


 ?>