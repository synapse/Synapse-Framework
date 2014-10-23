<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/List
 */

defined('_INIT') or die;

class FieldTypeList extends FieldType
{

    public $template = array(
        "<div>",
        "   <label class='{{labelclass}}'>{{label}}</label>",
        "   <select {{id}} {{name}} {{attributes}} {{required}}>",
        "{{repeat}}",
        "   <option value='{{value}}' {{selected}}>{{text}}</option>",
		"{{/repeat}}",
        "   </select>",
        "</div>"
    );

    public function render()
    {
        $this->replace('name', isset($this->field->name) ? 'name="'.$this->field->name.'"' : '')
            ->replace('id', isset($this->field->id) ?  'id="'.$this->field->id.'"' : '')
            ->replace('labelclass', $this->field->labelclass)
            ->replace('label', $this->field->label)
            ->replace('required', $this->field->required ? 'required=""' : '')
            ->setAttributes('attributes', $this->field->getAttributes());


        if(isset($this->field->options) && count($this->field->options->items)){
            foreach($this->field->options->items as &$item){
                $item->selected = ($item->value == $this->field->getValue()) ? 'selected=""' : '';
            }
            $this->repeat('repeat', $this->field->options->items);
        }

        return $this->getTemplate();
    }

    public function validate()
    {
        $valid = false;

        if(isset($this->field->options) && count($this->field->options->items)) {
            foreach($this->field->options->items as $item){
                if($item->value == $this->field->getValue()){
                    $valid = true;
                }
            }
        }

        return $valid;
    }

}