<?php

/**
 * @package     Synapse
 * @subpackage  Plugin
 * @version     1.1
 */

defined('_INIT') or die;


class Plugin {

    public static $events = array();
    
    public static function dispatch($evt, $par)
    {
        if(!is_array($par)){
            throw new Error('dispatch expects an Array, '.gettype($par).' given');
        }

        foreach(static::$events as $event=>$method)
        {
            if($evt == $event)
            {
                if(!method_exists(get_called_class(), $method))
                {
                    return false;
                }

                call_user_func_array(array(get_called_class(), $method), $par);
                return true;
            }
        }

        return false;
    }
}