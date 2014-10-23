<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/Text
 */

defined('_INIT') or die;

class FieldTypeText extends FieldType {

    public $template = array(
                            "<div>",
                            "   <label class='{{labelclass}}'>{{label}}</label>",
                            "   <input type='text' {{id}} {{name}} value='{{value}}' {{attributes}} {{required}} />",
                            "</div>"
                        );

    public function render()
    {
        $a = $this->field->getValue();
        $this->replace('name', isset($this->field->name) ? 'name="'.$this->field->name.'"' : '')
            ->replace('id', isset($this->field->id) ? 'id="'.$this->field->id.'"' : '')
            ->replace('labelclass', $this->field->labelclass)
            ->replace('label', $this->field->label)
            ->replace('required', $this->field->required ? 'required=""' : '')
            ->setAttributes('attributes', $this->field->getAttributes())
            ->setValue('value', $this->field->getValue(), $this->field->getDefault());

        return $this->getTemplate();
    }
}