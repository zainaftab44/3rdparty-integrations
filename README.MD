# FastREST - Fastest way to develop php Rest APIs

FastREST is developed after going through multiple php frameworks which are available. To use them you would require some time to learn basics of the framework and then gradually learn by developing on them.

FastREST makes things easier. Now you don't need to learn how to use frameworks to start with REST API development in PHP. You don't need to learn routing or anything as it takes it to itself and manage the routes for you. That's it for the intro. 

Lets have a look at how easy it is to use FastREST.
  

## Basics of FastREST

### How to run
So FastREST is much easier to execute/deploy than other php frameworks. You can even use the basic PHP server to use FastREST to its full executing following command in FastRest dir.

    $ php -S localhost:8000 index.php

Now you can start working with controllers and actions for further development.

### Controller

You need to create a controller class in controllers dir with filename as `nameController.php` for example you want to create controller class for handling APIs related to products then you would need to create ProductController class with filename ProductController.php in controllers dir. The controller name like Product for the above example will act as the route `/product/[actionname]`. The namespace for  controller needs to be src\controllers for router to auto create route for it. 

A basic skeleton of controller is as follows        

    <?php
    namespace  src\controllers;
    class  ProductController{
    }
    ?>

### Action

Action is a static function in your controller class with name like `indexAction` which for ProductController will point to route `/product/index` or `/product`. In the route the extra Action in the function name gets trimmed. The action name gets registered by the router if it is created as static function in the controller class and will be called by router automatically. 

**Note:** Actions must return an array to be sent as response. If no array is returned from action then default response is returned. 

    <?php
    namespace src\controllers;
    
    use src\classes\Database;
    use src\classes\Logger;
    
    class ProductController
    {
        public static function indexAction()
        {
            $db         = Database::getInstance();
            $logger = Logger::getInstance();
            $result  = $db->query("Select * from first_table where name like 'z%' order by name desc");
            $logger->info("Select * from first_table where name like 'z%' order by name desc", $result);
            if (!empty($result)) {
                return array('status' => "success", 'data' => $result);
            } else {
                return array('status' => 'failed');
            }
        }
    }
    ?>

Another example for action can be `listAction` in `ProductController` and its route will be registered as `/product/list` automatically. The code for this example would be like this.

      <?php
    namespace src\controllers;
    
    use src\classes\Database;
    use src\classes\Logger;
    
    class ProductController
    {
	    public static function listAction()
	    {
	        $db		= Database::getInstance();
	        $logger 	= Logger::getInstance();
	        $selected 	= $db->select("first_table", null, array('email' => '1613423153@gmail.com'), array('name' => 'desc'), 1);
	        $logger->info("listing from db", $selected);
	        if (!empty($selected)) {
	            return array('status' => "success", 'data' => $selected);
	        } else {
	            return array('status' => 'failed');
	        }
	    }
    }
    ?>
  

## More on FastREST for you

The following classes has been created for helping in faster development of php REST APIs.

### Config file
A config file is created in config/config.php for keeping constants that would be used in multiple locations for easier manipulation in FastREST. 

1. First of all there is php default error_reporting which is disabled in config you can set it enabled as per your use case.
2. env variable is added to allow you to add different configurations for different environments your APIs will be placed in.
3. Logging constants for location where the logger should output and the timezone are defined here. You can change the timezone as per your need and the log location from here.
4. Default status/message returned in case the route is not found, some exception occurred or nothing is returned from Action.
5.  Database constants are added for creating db connection string for PHP PDO. You can use any db for DBTYPE (default is mysql) that is compatible with PDO.

### Databases

A singleton database class is created in classes namespace which implements basic db CRUD operations for you with much easier syntax than others. It also provides a method to execute custom queries if you want. Currently basic crud with where, orderby, and limit are added for select query. More customization to Select will be coming later on after it is decided how easier could it be made for anyone to use without much depth knowledge. 

The examples for database are shared in example for [Action](#action). For more you can check [`src/controllers/ProductController.php`](https://github.com/zainaftab44/FastREST/blob/main/src/controllers/ProductController.php).

### Logging

A singleton Logger class is made available for users to use and log their messages on stderr/file. In case of files please confirm if you/phpuser have permission to write on location (took much time to realize this was happening for me ;p). Multiple logging levels has been introduced and you can set the timezone to display time along-with the log messages.  The Logger currently logs the remote ip:port, time stamp, log level, filename, class name, action and line number from where it is called for each time it logs any message/array/object.

The examples for usage of logger are shared in sample codes of [Action](#action). For more you can check [`src/controllers/ProductController.php`](https://github.com/zainaftab44/FastREST/blob/main/src/controllers/ProductController.php).


## Future prospects
Following are some things that might be updated for making FastREST more easier and efficient for devs  to create REST APIs in PHP.

 - [ ] Database: Add all operators support in where clauses for CRUD methods.
 - [ ] Database: Add support for joins for CRUD methods.
 - [ ] Database: Add support for NoSQL
 - [ ] Config: Get env from system/apache conf

