# FastREST - Fastest way to develop php Rest APIs

FastREST is developed after going through multiple php frameworks which are available. To use them you would require some time to learn basics of the framework and then gradually learn by developing on them. 

FastREST makes things easier. Now you don't need to learn how to use frameworks to start with REST API development in PHP. You don't need to learn routing or anything as it takes it to itself and manage the routes for you. That's it for the intro. Lets have a look how to develop in FastREST 

## How to create APIs using FastREST
It is very simple to create API using fast rest. You just need controller class.

### Controller
You need to create a controller class in controllers dir with filename as `nameController.php` for example you want to create controller class for handling APIs related to products then you would need to create ProductController class with filename ProductController.php in controllers dir. The controller name like Product for the above example will act as the route `/product/[actionname]`. 

### Action
Action is a static function in your controller class with name like `indexAction`  which for ProductController will point to route `/product/index` or `/product`. In the route the extra Action in the function name gets trimmed. The action name gets registered by the router if it is created as static function in the controller class and will be called by router automatically. 

Another example for action can be `listAction` in `ProductController` and its route will be registered as `/product/list` automatically.  


## More on FastREST for you
The following classes has been created for helping in faster development of php REST APIs.
### Databases
A singleton database class is created in classes namespace which implements basic db CRUD operations for you with much easier syntax than others. It also provides a method to execute custom queries if you want. Currently basic crud with where, orderby, and limit are added for select query. More customization to Select will be coming later on after it is decided how easier could it be made for anyone to use without much depth knowledge. You can find the example of the usages of db functions in the src/controller/ProductController.php in this repo 

### Logging
A singleton Logger class is made available for users to use and log their messages on stderr/file. In case of files please confirm if you/phpuser have permission to write on location (took much time to realize this was happening for me ;p). Multiple logging levels has been introduced and you can set the timezone to display time along-with the log messages.

