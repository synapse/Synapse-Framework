<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/Textarea
 */

defined('_INIT') or die;

class TextareaFieldType extends FieldType {

    public function render()
    {
        $html = array();
        $html[] = '<div>';

        $labelClass = $this->field->getOption('labelclass');
        $labelClass = !empty($labelClass) ? 'class="'.$labelClass.'"' : '';
        $html[] = '<label '.$labelClass.'>'.$this->field->getLabel().'</label>';

        $value = $this->field->getValue() ? $this->field->getValue() : $this->field->getDefault();
        $html[] = '<textarea name="'.$this->field->getName().'" '.$this->field->getAttributes(true).'>'.$value.'</textarea>';
        $html[] = '</div>';

        $html[] = '</div>';

        return implode("", $html);
    }
}