<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/Hidden
 */

defined('_INIT') or die;

class FieldTypeHidden extends FieldType {

    public $template = array("<input type='hidden' {{id}} {{name}} value='{{value}}' {{attributes}} {{required}} />");

    public function render()
    {
        $this->replace('name', isset($this->field->name) ? 'name="'.$this->field->name.'"' : '')
            ->replace('id', isset($this->field->id) ? 'id="'.$this->field->id.'"' : '')
            ->replace('required', $this->field->required ? 'required=""' : '')
            ->setAttributes('attributes', $this->field->getAttributes())
            ->setValue('value', $this->field->getValue(), $this->field->getDefault());

        return $this->getTemplate();
    }
}