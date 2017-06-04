## Installation

1. Download this repository using 'git clone' command.

2. Make sure you have up and running MySQL insance.

3. Make sure you have [Composer](https://getcomposer.org/download/) installed.

4. Open your console and navigate to the pacakge folder

5. Install the composer packages by running 'composer install' command

6. Create the schema using doctrine cli command:

	```
	vendor/bin/doctrine orm:schema-tool:create
	```
7. Populate the database using pop_db.php script - 

	```
	php pop_db.php
	```
8. Start the php built-in web server from your terminal in the project’s root directiry:
	```
	php -S localhost:8080
	```
9. You can now access the web app through your web browser -
	```
	localhost:8080/school
	```


## Create / Drop Database

vendor/bin/doctrine orm:schema-tool:create

vendor/bin/doctrine orm:schema-tool:drop --force
