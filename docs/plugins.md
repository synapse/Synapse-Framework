Plugins are actions called by a given event. Every plugin should contain a list of events to whom it responds and the relative method to execute when the given event is fired.

> There can be more then one plugin that responds to the same event name

### When to us a Plugin
* plugins are useful when you want to execute the same code when a given event happens
* useful when for example you need to send some notification when something happens

***

### Creating a plugin
To create a plugin create a php file inside the 'plugins' folder in your app folder.

```php
<?php defined('_INIT') or die;

class MypluginPlugin extends Plugin {

	public static $events = array(
		"someEvent" => "someMethod"
	);

	public static function someMethod(&$param1, &$param2, &$param3)
	{

	}
}
```

***

### Executing a plugin
From anywhere inside your application call the following method

```php
App::trigger('someEvent', array(&$param1, &$param2, &$param3, ...));
```

or the short version

```php
_trigger('someEvent', array(&$param1, &$param2, &$param3, ...));
_hook('someEvent', array(&$param1, &$param2, &$param3, ...));
```
