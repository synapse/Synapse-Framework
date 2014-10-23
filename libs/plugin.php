<?php

/**
 * @package     Synapse
 * @subpackage  Plugin
 */

defined('_INIT') or die;


class Plugin {

    public static $events = array();
    
    public static function dispatch($evt, $par)
    {
        foreach(static::$events as $event=>$method)
        {
            if($evt == $event)
            {
                call_user_func_array(array(get_called_class(), $method), $par);
                return true;
            }
        }

        return false;
    }
}