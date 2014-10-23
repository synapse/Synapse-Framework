<?php

/**
 * @package     Synapse
 * @subpackage  Form
 */

defined('_INIT') or die;

class Form {

    protected $path         = null;
    public $name            = null;
    public $enctype         = "application/x-www-form-urlencoded";
    public $method          = "POST";
    public $action          = null;
    public $attributes      = array();
    protected $fieldsets    = array();
    protected $errors       = array();
    public $template        = array("<form {{method}} {{action}} {{enctype}} {{attributes}}>", "{{fieldsets}}", "</form>");


    /**
     * Initialize the Form object with a JSON file
     * @param String $path
     */
    public function __construct($path)
    {
        // generate the form from a JSON file
        if($path) {
            $this->path = $path;

            if (!FS::exists($path)) {
                throw new Error('Form not found at path: ' . $path);
            }
            $this->load();
        }
    }

    /**
     * Loads the content of the JSON file at the given path
     */
    protected function load()
    {
        $json = file_get_contents($this->path);
        $obj = json_decode($json);

        if(json_last_error()){
            throw new Error('The JSON file provided is not valid');
        }

        if(isset($obj->name)){
            $this->setName($obj->name);
        }

        if(isset($obj->action)){
            $this->setAction($obj->action);
        }

        if(isset($obj->method)){
            $this->setMethod($obj->method);
        }

        if(isset($obj->enctype)){
            $this->setEnctype($obj->enctype);
        }

        if(isset($obj->attributes)){
            $this->setAttributes($obj->attributes);
        }

        if(isset($obj->fieldsets) && is_array($obj->fieldsets) && count($obj->fieldsets)){
            $this->loadFieldsets($obj->fieldsets);
        }

        if(isset($obj->template)){
            $this->setTemplate($obj->template);
        }
    }

    /**
     * Generate Fieldset objects based on the passed array
     * @param Array $fieldsets
     */
    protected function loadFieldsets($fieldsets)
    {
        foreach($fieldsets as $fieldset){
            $newFieldset = new Fieldset($fieldset->name, isset($fieldset->label) ? $fieldset->label : null, null, isset($fieldset->attributes) ? $fieldset->attributes : null);
            $newFieldset->setForm($this);
            $newFieldset->loadFields($fieldset->fields);

            if(isset($fieldset->template)){
                $newFieldset->setTemplate($fieldset->template);
            }

            $this->addFieldset($newFieldset);
        }
    }

    /**
     * Add a fieldset to the form collection
     * @param Fieldset $fieldset
     * @return $this
     * @throws Error
     */
    public function addFieldset($fieldset)
    {
        if(get_class($fieldset) != 'Fieldset'){
            throw new Error('addFieldset require an object of type Fieldset, '.get_class($fieldset).' received instead.');
        }

        $this->fieldsets[$fieldset->name] = $fieldset;
        return $this;
    }

    /**
     * Sets the name of the form
     * @param String $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Sets the action url of the form
     * @param String $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Sets the request method of the form
     * @param String $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Sets the encoding type of the form
     * @param String $encoding
     * @return $this
     */
    public function setEnctype($encoding)
    {
        $this->enctype = $encoding;
        return $this;
    }

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
     * Sets the html template splitted in an array
     * @param Array $template
     */
    public function setTemplate($template)
    {
        if(!is_array($template) && !is_string($template)){
            throw new Error('setTemplate expects an array or string, '.gettype($template).' given');
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
        if(!FS::exists($path)){
            throw new Error('Form template file not found at the given path: '.$path);
        }

        $template = array(file_get_contents($path));
        $this->setTemplate($template);
    }

    /**
     * Return the html template
     * @return string
     */
    public function getTemplate()
    {
        if(is_array($this->template)) {
            return implode("\r\n", $this->template);
        }

        return $this->template;
    }

    /**
     * Validates the form fields values
     */
    public function validate()
    {
        $errors = array();

        foreach($this->getFieldsets() as $fieldset){
            if(!$fieldset->validate()){
                $errors[] = $fieldset->getErrors();
            }
        }

        if(count($errors)){
            $this->errors = $errors;
            return false;
        }

        return true;
    }

    /**
     * Sets the form fields values
     * @param Array $data
     * @return $this
     * @throws Error
     */
    public function setData($data = array())
    {
        if(!is_array($data) && !is_object($data)){
            //throw new Error('setData expects an object or an array, '.gettype($data).' given');
            return $this;
        }

        $data = (array)$data;

        foreach($data as $name=>$value){
            $this->setFieldValue($name, $value);
        }

        return $this;
    }

    /**
     * Returns the form fields values
     * @return Array
     */
    public function getData()
    {

    }

    /**
     * Sets the value of a given form field by name
     * @param String $name
     * @param Mixed $value
     * @return $this
     */
    public function setFieldValue($name, $value)
    {
        foreach($this->fieldsets as $fieldset){
            $fieldset->setFieldValue($name, $value);
        }
        return $this;
    }

    /**
     * Returns the fields value
     * @param String $name
     * @return Mixed
     */
    public function getFieldValue($name)
    {
        foreach($this->fieldsets as $fieldset){
            if($fieldset->hasField($name)){
                return $fieldset->getFieldValue($name);
            }
        }

        return null;
    }

    /**
     * Returns the list of fieldsets
     * @return array
     */
    public function getFieldsets()
    {
        return $this->fieldsets;
    }

    /**
     * Return the list of Fieldsets
     * @param String $name
     * @return Array
     */
    public function getFieldset($name)
    {
        return $this->fieldsets[$name];
    }

    /**
     * Returns the list of errors from the validation method
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Renders and echoes out the form in html
     */
    public function render($return = false)
    {
        $template = $this->getTemplate();

        $template = str_replace("{{method}}", 'method="'.$this->method.'"', $template);
        $template = str_replace("{{enctype}}", 'enctype="'.$this->enctype.'"', $template);

        if($this->action){
            $template = str_replace("{{action}}", 'action="' . $this->action . '"', $template);
        } else {
            $template = str_replace("{{action}}", '', $template);
        }

        if($this->name) {
            $template = str_replace("{{name}}", 'name="' . $this->name . '"', $template);
        } else {
            $template = str_replace("{{name}}", '', $template);
        }

        if(count($this->getAttributes())){
            $attributes = array();

            foreach($this->getAttributes() as $attrName => $attrValue){
                $attributes[] = $attrName.'="'.$attrValue.'"';
            }

            $template = str_replace("{{attributes}}", implode(" ", $attributes), $template);
        } else {
            $template = str_replace("{{attributes}}", '', $template);
        }

        if(count($this->fieldsets)){
            $fieldsets = array();

            foreach($this->getFieldsets() as $fieldset){
                ob_start();
                $fieldset->render();
                $fieldsets[] = ob_get_contents();
                ob_end_clean();
            }

            $template = str_replace("{{fieldsets}}", implode("", $fieldsets), $template);
        } else {
            $template = str_replace("{{fieldsets}}", '', $template);
        }

        if($return) return $template;

        echo $template;
    }

    public function __toString()
    {
        return $this->render(true);
    }
}