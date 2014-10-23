<?php

/**
 * @package     Synapse
 * @subpackage  Form/Validate/Password
 */

defined('_INIT') or die;

class FieldValidatePassword {

    public function test($value, $field = null, $validator = null)
    {
        $length = strlen($value);

        if($length < $validator->min || $length > $validator->max){
            return false;
        }

        return true;
    }

}