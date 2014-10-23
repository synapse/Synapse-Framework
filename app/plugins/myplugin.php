<?php defined('_INIT') or die;


class Myplugin extends Plugin {

	public static $events = array(
		"someEvent" => "someMethod"
	);

	public static function someMethod(&$param1, &$param2, &$param3)
	{
                echo $param1.'<br />';
                echo $param2.'<br />';
                echo $param3.'<br />';
                echo 'Launching someEvent and method someMethod';

                $param1 = 'x';
                $param2 = 'y';
                $param3 = 'z';
	}
}