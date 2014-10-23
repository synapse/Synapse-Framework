<?php

/**
 * @package     Synapse
 * @subpackage  Form/Validate/Email
 */

defined('_INIT') or die;

class FieldValidateEmail {

    public function test($value, $field = null, $validator = null)
    {
        $regex = '^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]{2,})$';
        return preg_match(chr(1) . $regex . chr(1), $value);
    }

}