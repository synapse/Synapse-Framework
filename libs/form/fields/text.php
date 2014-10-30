<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/Text
 */

defined('_INIT') or die;

class TextFieldType extends FieldType {

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

}