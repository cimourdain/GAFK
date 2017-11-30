# GAFK
## Introduction

GAFK is a PHP framework/lib developed for educational purposes. The main principles applied in this tools are:

* **KISS**: This framework/lib fully includes all functionalities required for full usage. No additional module is required.
* **MVC**: This framework/lib implements a basic MVC pattern with an App, a Router, controllers, models and templating management.
* **Object oriented**: GAFK is developed in an object oriented way

## Presentation

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
* Routes names have to be unique
* If no route is found the \App\Controllers\ErrorController will be called, as a consequence it has be be implemented in your project.
* The controller name and the action name will be transformed with the PHP ucfirst function to get the controller. As a consequence casing is not important when defining your routes.

### Define your controllers

You will create all controller of your website in the /App/Controllers/ folder. Every controller defined in the Router.json have to be implemented.

**Naming**:
* The controller "test" class have to be nammed with the following pattern: TestController (only first letter in uppercase).
* The controller file will be created with the controller name followed by .class.php. Example TestController.class.php.

**Namepace** : All controllers must be included in the __App\Controllers__ namespace.

**Inheritance** : All your controllers class will inherit from the abstract /Core/Controller class.

**Methods**:
* Controllers have to implements execute methods for every action defined in the Router. For each action, create a method with the following naming convention "execute+(action name with first letter in uppercase)" example: __executeMyaction()__
* Controllers can implements before() and/or after() method. These method will be called respectively before and after the execution of the executeAction method.

**URL params**: Url params fetched by the router pattern regex will be available in your controller in the _params attribute. Example: the first catched attribute can be used with $this->_params[0]


Basic example of controller initalization:

```php
<?php
// /App/Controllers/TestController.class.php

namespace App\Controllers;

class TestController extends AppController{

  /* Execute action Index */
  protected function executeIndex($params = null){
      //call Model
      //render templates
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

**Filename**: Model filename is almost free (until it ends with .class.php). It is recommended that you name them with the following pattern:
(PDO + Name + Manager.class.php) example: PDOTestmodelManager.class.php

**Namespace**: Model class must be included in the namesapce App\Model

**Naming**: The model name have to be consistent with the filename.

**Inheritance** : Model class must inherit from the Core absract class \Core\PDOManager

**Methods**: Model classes can use the following methods inherited from \Core\PDOManager
* connect_db(): automatically uses params defined in the /App/Config.class.php
* executePDO(): simple method taking a pdo object and a data array as an input, execute the request and return the resulting PDO object (or null if fail).
* addMessage(): if you provide a Logger object on object instantiation, this method allow to addMessages to the logger.


#### Usage

Basic example of 
