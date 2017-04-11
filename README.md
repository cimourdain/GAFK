# GAFK 

GAFK is a simple PHP framework i wrote for education purpose while learning OOP.

## Features

 * PHP Frawework (OOP)
 * URL rewrite router (regular expressions, folders and subdomains management)
 * Powerful templating management with [Tiwg](https://twig.sensiolabs.org/)
 * Errors and exceptions handling 
 * [TO DO] Backend management (backend creation automation)

## Requirements

 * PHP > 7.0
 * Twig > 2.0

## Download

### GAFK

Download master banch and unzip it on your computer

### TWIG

GAFK is using TWIG as a template renderer, [install Tiwg](https://twig.sensiolabs.org/) with composer in the "lib/vendor/" folder. 

Composer autoload.php file must be in the following folder

```
lib/vendor/autoload.php
```

## Install

### Local install

Setup virtual host

  1. Enable rewrite mode
  2. All request must target /Web/index.php

Example for Apache

```
<VirtualHost *:80>
  ServerAdmin webmaster@localhost
  
  ServerName gafk
  ServerAlias *.gafk

  DocumentRoot C:/Users/YOU/www/GAFK/Web
  <Directory "C:/Users/YOU/www/GAFK/Web">
    Options Indexes FollowSymLinks MultiViews
    
    # Activate .htaccess
    AllowOverride All

    allow from localhost
    FallbackResource /index.php #all requests target to /Web/index.php file
  </Directory>
</VirtualHost>

```

## Basic usage

### Define App setup

In /App/_Config/App.xml, setup the host with your website name

Example
```
<define var="Host" value="mysite.com" />
```

#### Create simple page module

Let's say, we want to create a module named "MY WEBSITE PAGE" and it will be your website homepage.

##### Define route

Define a frontend route in  /App/_Config/Routes.xml

Example
```
<routes>
	<frontend>
		<subdomain value="www,">
			<route URLPattern="/" module="MyWebsitePage" action="index"/>
		</subdomain>
	</frontend>
</routes>
```

Note  : URLPattern allow you to use regular expressions (to do in wiki)


#### Create your module twig template

In the app folder create the following folder structure:

```
/App/_Templates/MyWebsitePage/

```

Then, in the MyWebsitePage folder create a file with your twig template (see Twig documentation). For example, name your template Home.twig


In the twig template, you can use the {{base_dir}} to access site root. This variable is useful to access css files (created in the /Web/css/ folder).

Example of access to css files in your twig template:

```
<link rel="stylesheet" href="{{ base_dir }}/css/styles.css" media="all">
```

#### Create your module class

In the app folder create the following folder structure:

```
/App/MyWebsitePage/Frontend/

```

Then, in the Frontend folder create a php file named MyWebsitePageFrontendManager.php with the following :

```
<?php

namespace MyWebsitePage;

class MyWebsitePageFrontendManager extends \GAFK\Manager
{

	public function executeIndex($vars)
	{
		$this -> setTemplate("./MyWebsitePage/Home.twig");//replace with the location of your template if necessary
	}
}

?>

```

note: your php file name, namespace and class name must be identical (and have to start with a capial letter).

#### That's it

Conntect to your website homepage, you



## License

This project is licensed under the MIT License 