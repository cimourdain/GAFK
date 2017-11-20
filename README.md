# GAFK
## Introduction

GAFK is a PHP framework/lib developed for educational purposes. The main principles applied in this tools are:

* **KISS**: This framework/lib fully includes all functionalities required for full usage. No additional module is required.
* **MVC**: This framework/lib implements a basic MVC pattern with an App, a Router, controllers and model management.
* **Object oriented**: GAFK is developed in an object oriented way

## Presentation

### Main folders
GAFK uses two main folder:

* **/Core**: This folder contains all core classes, you probably do not have to modify them
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

* **Controllers**: This folder contains all the controllers classes of your website. (All controllers inherit from the controller class in the /core folder.)
* **Model**: This folder contains all the model classes of your website. (All models inherit from the PDOManager class in the /core folder.)
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


 
