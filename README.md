## Installation

1. Download this repository using 'git clone' command.

2. Make sure you have up and running MySQL instance.

3. Create a new database called 'school' - 

	```
	CREATE DATABASE school;
	```

4. Make sure you have [Composer](https://getcomposer.org/download/) installed.

5. Open your console and navigate to the pacakge folder

6. Install the composer packages by running 'composer install' command

7. Create the schema using doctrine cli command:

	```
	vendor/bin/doctrine orm:schema-tool:create
	```

	##### NOTE: If you are a Windows user, please create a new 'cli-config.php' file in the vendor\bin directory.
	##### It should be look like:
	```
	<?php
	// cli-config.php
	require_once "../../bootstrap.php";

	return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);

	```
8. Populate the database using 'pop_db.php' script - 

	```
	php pop_db.php
	```
9. Start the php built-in web server from your terminal in the project’s root directiry:
	```
	php -S localhost:8080
	```
10. You can now access the web app through your web browser -
	```
	localhost:8080/school
	```

11. You can find the administrative users and passwords in the 'pop_db.php' script


## Create / Drop Database

vendor/bin/doctrine orm:schema-tool:create

vendor/bin/doctrine orm:schema-tool:drop --force
