# GAFK
## Introduction

GAFK is a PHP framework/lib developed for educational purposes. The main principles applied in this tools are:

* **KISS**: This framework/lib fully includes all functionalities required for full usage. No additional module is required.
* **MVC**: This framework/lib implements a basic MVC pattern with an App, a Router, controllers, models and templating management.
* **Object oriented**: GAFK is developed in an object oriented way

## Presentation

### Features

* Easy Routes management
* Easy Form validation
* Cache management
* Advanced Logging
* Maintenance mode

### Main folders
GAFK uses two main folder:

* **/Core**: This folder contains all Core classes, you probably do not have to modify them
* **/App** : This folder contains all files specific to your application

### /Core

The Core folder contains the following classes

* **App**: This class call the router. Based on the output of the router, it call the right controller with the action and params as parameter.
* **Router**: This class analyses the URL, match it with the url patterns of /App/Router.json and then returns the controller, action & params found in URL.
* **Controller** : This abstract class contains mainly the constructor and the execution method of the controllers. In the application, all controllers must inherit from it.
* **Template** : The template class is set to be used as static. It cannot be implemented. In controllers, binding values and defining templates are done statically (it is used as a singleton).
* **PDOManager**: This abstract class defines the db connection and requests execution method. All model class in App will inherit it.
* **Logger**: The Logger class simply manage an array of logs. It is used by Controllers and Model classes.
* **Cache**: This class handle cache management.
* **FormValidator** : This class simplify the form validation by providing helpers to check forms. (this class is optionnal)
* **FileManager**: This class provides helpers to create and delete directories (this class is optionnal).

Finally the classloader.php files provides an spl autoload function to load all project classes.

### /App

The App folder contains the following files :

**Config.class.php** : This files contains all global const. In addition to the basic const, you can freely add the classes you want.
**Router.json**: This json file contains all routes associated with the controller and the action to perform.

The App folder contains the following folders:

* **Controllers**: This folder contains all the controllers classes of your website. (All controllers inherit from the controller class in the /Core folder.)
* **Model**: This folder contains all the model classes of your website. (All models inherit from the PDOManager class in the /Core folder.)
* **Views**: This folder contains all views of your websites. Views are html files with embedded php variables. Note: you can define freely the organization of subfolders and files in this directory.
* **Cache**: Folder used to store cached pages

## Usage

### Pre-requisites

GAFK require to enable URL Rewriting on your server. All request must point to the root index.html file.
*An example of a .htaccess is provided in the repository.*

### Init your config file

The first step is to init your application values in the /App/Config.class.php files. You probably want to update your website address, your site name, your db access params.

### Define your routes

In the /App/Routes.json file, define your website routes. Each routes is defined by a name (only for your usage) witch is associated a url pattern, a controller and and action.

Example:

```javascript
{
  "home": {
    "regex_pattern": "/?",
    "controller": "main",
    "action": "Index"
  },
  "page2": {
    "regex_pattern": "/page2/(\\w*)/?",
    "controller": "main",
    "action": "page2"
  },
  "admin": {
    "regex_pattern": "/second_ctrl/?",
    "controller": "second",
    "action": "Index"
  }
}
```

Notes :
* Patterns are regular expressions. As a consequence, you can "catch" elements in your pattern (using parenthesis) to use them in your controller (see controller section below).
* Routes names have to be unique!
* If no route is found the \App\Controllers\ErrorController will be called, as a consequence it has be be implemented in your project.
* The controller name and the action name will be transformed with the PHP ucfirst function to get the controller. As a consequence casing is not important when defining your routes.
* Routes are also used to define caching (see section caching below)

### Define your controllers

You will create all controller of your website in the /App/Controllers/ folder. Every controller defined in the Router.json have to be implemented.

. | Folder | File naming | Class naming | Namespace | Inheritance
------------ | -------------  | -------------  | -------------  | -------------  | -------------
Specification | Controllers have to be placed in App/Controllers | Controllers have to be named with the following format NameController.class.php | Controller must have the same name as the file (without .class.php) | All controllers must be included in the __App\Controllers__ namespace. | Controller must inherit either from the abstract /Core/Controller or from an other controller inheriting from from the abstract /Core/Controller
Example | App/Controllers | MynewctrlController.class.php | class MynewctrlController {} | namespace App\Controllers; | class MynewctrlController extends \Core\Controller{}


**Methods**:
* Controllers have to implements execute methods for every action defined in the Router. For each action, create a method with the following naming convention "execute+(action name with first letter in uppercase)" example: __executeMyaction()__
* Controllers can implements before() and/or after() method. These method will be called respectively before and after the execution of the executeAction method.

**URL params**: Url params fetched by the router pattern regex will be available in your controller in the _params attribute. Example: the first catched attribute can be used with $this->_params[0]


Basic example of controller initalization:

```php
<?php
// /App/Controllers/TestController.class.php

namespace App\Controllers;

class TestController extends \Core\Controller{

  /* Execute action Index */
  protected function executeIndex($params = null){
      //call Model (see section below)
      $um = new \App\Model\PDOUsersManager();
      $users = $um->getAllUsers();
      //render templates (see section below)
  }

  /* Execute action Page2 */
  protected function executePage2($params = null){
    //usage of param fetched in the URL regex pattern by the router
    echo "first param: ".$this->_params[0];
    //call Model
    //render templates
  }

  /* (optional) function called before execution of action method */
  protected function before(){

  }

  /* (optional) function called after execution of action method */
  protected function after(){

  }

}
?>
```

Details of using model and templates in controllers will be detailed in the following sections


### Models
#### Definition

Model classes are used to communicate with Database.

In your controllers you can call models. Models are classes stored in /App/Model folder

. | Folder | File naming | Class naming | Namespace | Inheritance
------------ | -------------  | -------------  | -------------  | -------------  | -------------
Specification |  Model filename is almost free (until it ends with .class.php). It is recommended that you name them with the following pattern:
(PDO + Name[ as ucfirst] + Manager.class.php) | The model name have to be consistent with the filename | Model class must be included in the namesapce App\Model | Model class must inherit from the Core absract class \Core\PDOManager
Example | PDOTestmodelManager.class.php | class PDOTestmodelManager{} | namespace App\Model; |class PDOTestmodelManager extends \Core\PDOManager{}


**Methods**: Model classes can use the following methods inherited from \Core\PDOManager
* [static]connect_db(): automatically uses params defined in the /App/Config.class.php, this method is automatically called with the first request executed by the model
* executePDO(): simple method taking a pdo object and a data array as an input, execute the request and return the resulting PDO object (or null if fail).
* addMessage(): if you provide a Logger object on object instantiation, this method allow to addMessages to the logger.

#### Usage

Basic example of Model

File: App/Model/PDOUserManager.class.php
```php
<?php
namespace App\Model;

class PDOUserManager extends \Core\PDOManager{

  public function getAllUsers(){
    //call the executePDO method
    $s = $this->executePDO("SELECT * FROM Users");
    if($s != null)
      return $s;
    return [];//return empty array if DB request failed
  }

  public function getUser($id){
    //call the executePDO method
    $s = $this->executePDO("SELECT * FROM Users WHERE id = :id", ["id"=>$id]);
    if($s != null)
      return $s;
    return [];//return empty array if DB request failed  
  }

}
?>
```

Usage of model in controller example:
```php

$um = new \App\Model\PDOUserManager($this->_logger); // see logging section to unerstand the logging parameter
$users = $um -> getAllUsers();

```


### Views
#### Definition
Views are handled by the Temlpate class (Core/Template.class.php).This class is a "singleton", it cannot be instanced, all its method have to be called in a static way.

To perform templating, two actions are provided:
* **Injecting**: With the static method setStatic(), templates variables can be added to template previously to rendering
* **Rendering**: With the static method render(), template files can be converted to HTML (by integration of data injected with the setStatic method). The final page rendering have to be provided to the controller setHTML() method to be rendered with cache management handling.

Note: Template files have to be created in the App/Views/ folder

#### Usage

If the two following template files are created

App/Views/pages/articles.php
```php
<h1>Article list</h1>

<?php
foreach($articles as $a){
  echo "<h2>".$a["title"]."</h2>";
  echo "<p>".$a["text"]."</p>";
}

?>
```

App/Views/partials/main.php
```php
<html>
<head>
</head>

<body>
  <header><?php echo $site_name;?>
  <?php echo $content; ?>
</body>
</html>

?>
```

in the controller, the following code allow to inject and render

```php
<?php
  protected function before(){
      \Core\Template::setStatic("site_name", App\Config::SITE_NAME);
  }

  function executeIndex(){
    //get user data with a model
    $am = new \App\Model\PDOArticlesManager();
    $articles = $um->getAllArticles();

    //inject articles array into template
    \Core\Template::setStatic("articles", $articles);
    //resolve articles.php template & set result of rendering as content (for future resolution of main.php)
    \Core\Template::setStatic("content", \Core\Template::render("pages/articles.php"));

  }

  protected function after(){
      //send final rendering to setHTML method
      this->setHTML(\Core\Template::render("partials/main.php"));
  }

?>
```

### Logging

Logging is handled by the Core\Logger class. At any point of your controller/models you can add logging messages.

Log messages are defined with a type (eg. error, success, ...) and a level (eg. dev, user, ...). Types and levels can be updated in the config file.

Examples of adding log in your models/controllers:
 ```php
 <?php
 $this->addMessage("My message"); //default type and level will be applied
 $this->addMessage("My message", "error", "dev");
 ?>
 ```

 All log messages are stored in an array in the Logger class. Messages can be fetched in array form, pretty array printing or in html form.

 Examples of usage:
 ```php
 <?php

 $messages = $this->getMessages(); //fetch all messages in an array
 \Core\Template::("messages_array", $messages);//messages array can be used in your templates

 $this->prettyPrintMessages(["error", "info"], ["dev"]);//print a pretty formatted array of logs

 $messages_html = $messages = $this->getMessagesHTML(["success","errors"], ["user"]); //get messages in a string as HTML format
 \Core\Template::("messages",  $messages_html );//messages array can be used in your templates

 ?>
 ```


### Extra

The following features can be optionally used.

#### Form validation

In your controllers you can use the Formvalidator provided in Core.

After instanciation, the form validator provide the following methods

Method | Parameters | Description
------------ | ------------- | -------------
formSubmitted() | (optionnal) Array of field names | check if form was submitted ( =>_POST is empty?)
fieldSbmitted() | field name | check if field was submitted form was submitted ( =>_POST[field] defined?)
setPostData() | data array | Can be used to perform validation on another array than $_POST
resetFormContent() | none | Reset internal array of data of the
getFieldValue() | field name, (optional) default value | return value of the field (or default if provided)
getFieldValueSecure() | field name, (optional) default value | return value after trim & htmlentities of the field (or default if provided) [only applied for strings]
getFieldAsHashedPassword() | password value | get hashed value of a password
checkFieldFormat() | field name, format array | check field format, see details below
checkFieldsFormat() | format array | check multiple fields format, see details below

Methods checkFieldFormat() and checkFieldsFormat() take arrays of key/values as a format parameter. These array of format allow to centralize field checking.

Usage example in a controller method to check a login and a password field:
```php
<?php

//format login and password keys are the fields name in $_POST
$fields_format = ["login" => ["min" => 3, "max" => 10], "login" => ["min" => 8, "max" => 16]];

//instaciate a new controller
$fv = new \Core\FormValidator();
if($fv->formSubmitted() && checkFieldFormat($fields_format))
  //call model applying getFieldValueSecure() on fields
}

?>
```php

Details of available controls in format

Name | Parameter | Description
------------ | -------------
Min | (int) | Check if field length is strictly superior to parameter
Max | (int) | Check if field length is strictly inferior to parameter
Email | true | Check if field has an email format
Identical | other_field_name | Check if field content is identical to other_field value
Int | true | Check that field is an int

#### Caching

#### Maintenance mode
