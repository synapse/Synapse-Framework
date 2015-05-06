Middlewares are classes that are called before a Controller is executed. You may hook one or more then one middleware to any given route. The order of the called middlewares is defined by the order in which you put them in the array. If the middleware won't redirect the page to another url than the normal execution of the Crontroller will follow.

### When to use a middleware
* middlewares are usefull when a piece of code is often used in several Controller
* check the login state of the user
* intercepting a request and change params before passing it to the Controller
* and much more

***

### Adding a middleware to the route
Inside your `routes.php` file

```php
<?php defined('_INIT') or die;

$router = $app->getRouter();

$router->get('/', 'Main', 'MyFirstMiddleware');
$router->get('/articles', 'Articles', 'MySecondMiddleware');
```

or adding multiple cascading middlewares:

```php
<?php defined('_INIT') or die;

$router = $app->getRouter();

$router->get('/', 'Main', array('MyLogin', 'MyOther'));
```

> If the method of the middleware is not specified then it will automatically presume there's a `index()` method and try to execute it

To execute a different method the middleware should be called like so:

```php
<?php defined('_INIT') or die;

$router = $app->getRouter();

$router->get('/', 'Main', 'MyLogin.checkLogin');
```

***

### Creating a new middleware
The middleware class extend the [Controller](https://github.com/synapse/Synapse-MVC/wiki/Controllers) class so it inherits all methods and properties from it. To create a middleware create a php file inside the 'middlewares' folder in your app folder.

```php
<?php defined('_INIT') or die;

Class NameMiddleware extends Middleware {

	public function someMethod()
	{
		
	}
}

// in the routes is called like so
$router->get('/', 'Main', 'Name.someMethod');
```