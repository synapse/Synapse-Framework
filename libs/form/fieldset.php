<?php

/**
 * @package     Synapse
 * @subpackage  Form/Fieldset
 */

defined('_INIT') or die;

class Fieldset {

    public $name        = null;
    public $label       = null;
    protected $fields   = array();
    public $attributes  = array();
    protected $errors   = array();
    protected $form     = null;
    public $template    = array("<fieldset {{name}} {{attributes}}>", "<legend>{{label}}</legend>", "{{fields}}", "</fieldset>");

    /**
     * Initializes the fieldset with a name, label and fields list
     * @param String $name
     * @param String $label
     * @param Array $fields
     */
    public function __construct($name, $label = null, $fields = array(), $attributes = array())
    {
        if($name) {
            $this->name = $name;
        } else {
            throw new Error('Fieldset must have a name');
        }

        if($label) {
            $this->label = $label;
        }

        if(count($fields)){
            $this->loadFields($fields);
        }

        if(is_object($attributes) || is_array($attributes)){
            $this->setAttributes((object)$attributes);
        }
    }

    /**
     * Parse the array of fields
     */
    public function loadFields($fields = array())
    {
        foreach($fields as $field){
            $this->addField(new Field($field));
        }
        return $this;
    }

    /**
     * Add a new field to the current Fieldset
     * @param Field $field
     * @return $this
     * @throws Error
     */
    public function addField($field)
    {
        if(get_class($field) != 'Field'){
            throw new Error('addFieldset require an object of type Field, '.gettype($field).' received instead.');
        }

        if($this->getForm()) {
            $field->setForm($this->getForm());
        }

        $this->fields[$field->name] = $field;
        return $this;
    }

    /**
     * Sets the attributes of the fieldset
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
     * Returns the fieldset attributes
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
     * Loads a fieldset template from a file
     * @param String $path
     */
    public function loadTemplate($path)
    {
        if(!FS::exists($path)){
            throw new Error('Fieldset template file not found at the given path: '.$path);
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
     * Return an array of Field type objects
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Sets the value of a given Field
     * @param String $name
     * @param Mixed $value
     * @return $this
     */
    public function setFieldValue($name, $value)
    {
        if(!isset($this->fields[$name])) return $this;

        $this->fields[$name]->setValue($value);
        return $this;
    }

    /**
     * Returns the fields value
     * @param String $name
     * @return Mixed
     */
    public function getFieldValue($name)
    {
        if(!isset($this->fields[$name])) return null;

        return $this->fields[$name]->getValue();
    }

    /**
     * Check if the fieldset contains a field
     * @param String $name
     * @return bool
     */
    public function hasField($name)
    {
        if(isset($this->fields[$name])) return true;

        return false;
    }

    /**
     * Validates the fieldset fields values
     */
    public function validate()
    {
        $errors = array();

        foreach($this->getFields() as $field){
            if(!$field->validate()){

                $error = new stdClass();
                $error->field = $field;
                $error->message = $field->getError();

                $errors[] = $error;
            }
        }

        if(count($errors)){
            $this->errors = $errors;
            return false;
        }

        return true;
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
     * Sets the current reference of the form
     * @param Form $form
     * @return $this
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Returns the current form
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Renders and echoes out the fieldset in html
     */
    public function render()
    {
        $template = $this->getTemplate();

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

        if($this->label) {
            $template = str_replace("{{label}}", $this->label, $template);
        } else {
            $template = str_replace("{{label}}", '', $template);
        }

        if(count($this->fields)){
            $fields = array();

            foreach($this->getFields() as $field){
                ob_start();
                $field->render();
                $fields[] = ob_get_contents();
                ob_end_clean();
            }

            $template = str_replace("{{fields}}", implode("", $fields), $template);
        } else {
            $template = str_replace("{{fields}}", '', $template);
        }

        echo $template;
    }
}