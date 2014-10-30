<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType
 */

defined('_INIT') or die;

class FieldType {

    protected $field = null;

    public function __construct($field)
    {
        $this->field = $field;
    }

    public function validate()
    {
        return true;
    }
}