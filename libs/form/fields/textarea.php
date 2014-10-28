<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/Textarea
 */

defined('_INIT') or die;

class FieldTypeTextarea extends FieldType {

    public $template = array(
                            "<div>",
                            "   <label class='{{labelclass}}'>{{label}}</label>",
                            "   <textarea {{id}} {{name}} {{attributes}} {{required}}>{{value}}</textarea>",
                            "</div>"
                        );

    public function render()
    {
        $this->replace('name', isset($this->field->name) ? 'name="'.$this->field->name.'"' : '')
            ->replace('id', isset($this->field->id) ? 'id="'.$this->field->id.'"' : '')
            ->replace('labelclass', isset($this->field->labelclass) ? $this->field->labelclass : '')
            ->replace('label', $this->field->label)
            ->replace('required', $this->field->required ? 'required=""' : '')
            ->setAttributes('attributes', $this->field->getAttributes())
            ->setValue('value', $this->field->getValue(), $this->field->getDefault());

        return $this->getTemplate();
    }
}