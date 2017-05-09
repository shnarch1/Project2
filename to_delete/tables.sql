CREATE DATABASE school;

CREATE TABLE school.students (
    id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(20) NOT NULL,
    phone varchar(20) NOT NULL,
    email varchar(254) NOT NULL,
    image_url varchar(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `uq_phone` (phone),
    UNIQUE KEY `uq_email` (email)
    );

INSERT INTO school.students (name, phone, email, image_url)
VALUES ("avi", "0545540121", "avi@gmail.com", "/home/user/"),
	   ("david", "123456789", "david@gmail.com", "/home/user/"),
	   ("moshe", "987654321", "moshe@gmail.com", "/home/user/"),
	   ("dean", "000000000", "dean@gmail.com", "/home/user/");

CREATE TABLE school.courses (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    description varchar(255) NOT NULL,
    image_url varchar(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY `uq_name` (name)
    );

INSERT INTO school.courses (name, description, image_url)
VALUES ("Calculus", "None", "/home/user/"),
	   ("Physics", "None2", "/home/user/"),
	   ("DSP", "None3", "/home/user/"),
	   ("Python", "None4", "/home/user/");

CREATE TABLE school.enrollment (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id MEDIUMINT UNSIGNED NOT NULL,
    course_id SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
    );

INSERT INTO school.enrollment (student_id, course_id)
VALUES (1, 1),
	   (1, 2),
	   (1, 3),
	   (1, 4),
	   (2, 2),
	   (3, 3),
	   (4, 4);

#Join example
SELECT s.name, c.name 
FROM school.students s
JOIN school.enrollment e on s.id = e.student_id
JOIN school.courses c on c.id = e.course_id;

CREATE TABLE school.administrators (
    id int(15) UNSIGNED NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    role varchar(7) NOT NULL,
    phone varchar(20) NOT NULL,
    email varchar(254) NOT NULL,
    password varchar(255) NOT NULL,
    image_url varchar(255) NOT NULL,
    PRIMARY KEY (id)
    );


