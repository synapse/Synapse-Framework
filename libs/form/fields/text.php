<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/Text
 */

defined('_INIT') or die;

class TextFieldType extends FieldType {

<<<<<<< HEAD
    public $template ='<div>
                            <label class="{{labelclass}}">{{label}}</label>
                            <input name="{{name}}" type="text" value="{{value}}" {{attributes}} />
                        </div>';

    public function render()
    {
        $this
            ->replace('label', $this->field->getLabel())
            ->replace('name', $this->field->getName())
            ->replace('labelclass', $this->field->getOption('labelclass'))
            ->setAttributes('attributes', $this->field->getAttributes())
            ->setValue('value', $this->field->getValue(), $this->field->getDefault());

        return $this->getTemplate();
    }
=======
    public function render()
    {
        $html = array();
        $html[] = '<div>';

        $labelClass = $this->field->getOption('labelclass');
        $labelClass = !empty($labelClass) ? 'class="'.$labelClass.'"' : '';
        $html[] = '<label '.$labelClass.'>'.$this->field->getLabel().'</label>';

        $value = $this->field->getValue() ? $this->field->getValue() : $this->field->getDefault();
        $html[] = '<input name="'.$this->field->getName().'" type="text" value="'.$value.'" '.$this->field->getAttributes(true).' />';
        $html[] = '</div>';

        return implode("", $html);
    }

>>>>>>> dev
}