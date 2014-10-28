<?php

/**
 * @package     Synapse
 * @subpackage  Form Element
 */

defined('_INIT') or die;


class FormElement {

    protected $attributes   = null;
    protected $errors       = array();
    protected $template     = "";


    /**
     * Sets the attributes for the form
     * @param Array|Object $attributes
     * @return $this
     * @throws Error
     */
    public function setAttributes($attributes)
    {
        if(!is_array($attributes) && !is_object($attributes)){
            throw new Error('setAttributes expects an object or an array, '.gettype($attributes).' given');
        }

        $this->attributes = (object)$attributes;
        return $this;
    }

    /**
     * Returns the form attributes
     * @return Object
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets a specific form attribute
     * @param String $name
     * @param String $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes->$name = $value;
        return $this;
    }

    /**
     * Returns a specific form attribute
     * @param String $name
     * @return mixed
     */
    public function getAttribute($name)
    {
        if(!property_exists($this, $name)) return null;

        return $this->attributes->$name;
    }


    /**
     * Sets the html template splitted in an array
     * @param Array $template
     */
    public function setTemplate($template)
    {
        if(!is_string($template)){
            throw new Error('setTemplate expects a string, '.gettype($template).' given');
        }

        $this->template = $template;
        return $this;
    }

    /**
     * Loads a form template from a file
     * @param String $path
     */
    public function loadTemplate($path)
    {
        if(!File::exists($path)){
            throw new Error('Form template file not found at the given path: '.$path);
        }

        $template = file_get_contents($path);
        $this->setTemplate($template);
    }

    /**
     * Return the html template
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }


    /**
     * Returns the list of errors from the validation method
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}