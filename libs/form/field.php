<?php

/**
 * @package     Synapse
 * @subpackage  Form/Field
 */

defined('_INIT') or die;

class Field extends FormElement {

    protected $label        = null;
    protected $type         = null;
    protected $filter       = null;
    protected $validations  = array();

    public function __construct($options = array())
    {
        $this->attributes = new stdClass();

        if(array_key_exists('attributes', $options) && (is_array($options['attributes']) || is_object($options['attributes']))){
            $attributes = (object)$options['attributes'];
            $this->setAttributes($attributes);
        }

        if(array_key_exists('label', $options) && is_string($options['label'])){
            $this->setLabel($options['label']);
        }

        if(array_key_exists('type', $options) && is_string($options['type'])){
            $this->setType($options['type']);
        }

        if(array_key_exists('filter', $options) && is_string($options['filter'])){
            $this->setFilter($options['filter']);
        }

        if(array_key_exists('message', $options) && is_string($options['message'])){
            $this->setMessage($options['message']);
        }

        if(array_key_exists('validate', $options) && is_array($options['validate']) && count($options['validate'])){
            $this->setValidations($options['validate']);
        }

        return $this;
    }


    public function setLabel($label)
    {
        if(!is_string($label)){
            throw new Error('setLabel expects a string, '.gettype($label).' given');
        }

        $this->label = $label;

        return $this;
    }

    public function setType($type)
    {
        if(!is_string($type)){
            throw new Error('setType expects a string, '.gettype($type).' given');
        }

        $this->type = $type;

        return $this;
    }

    public function setFilter($filter)
    {
        if(!is_string($filter)){
            throw new Error('setFilter expects a string, '.gettype($filter).' given');
        }

        $this->filter = $filter;

        return $this;
    }

    public function setValidations($validations = array())
    {
        if(!is_array($validations)){
            throw new Error('setValidations expects an array, '.gettype($validations).' given');
        }

        foreach($validations as $validation){
            $this->setValidation($validation);
        }

        return $this;
    }

    public function setValidation($validation)
    {
        if(!is_array($validation) && !is_object($validation)){
            throw new Error('setValidations expects an array or object, '.gettype($validation).' given');
        }

        $validation = (object)$validation;

        if(!property_exists($validation, 'type')){
            throw new Error('Validation must have a type');
        }

        $this->validations[] = $validation;

        return $this;
    }

    public function setMessage($message)
    {
        if(!is_string($message)){
            throw new Error('setMessage expects a string, '.gettype($message).' given');
        }

        $this->message = $message;

        return $this;
    }


    public function render()
    {

    }
}















