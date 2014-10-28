<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/List
 */

defined('_INIT') or die;

class ListFieldType extends FieldType
{

    public $template = "<div>
                            <label class='{{labelclass}}'>{{label}}</label>
                            <select {{name}} {{attributes}}>
                            {{repeat}}
                            <option value='{{value}}' {{selected}}>{{text}}</option>
		                    {{/repeat}}
                            </select>
                        </div>";

    public function render()
    {

        $this->replace('name', $this->field->getName())
            ->replace('labelclass', $this->field->getOption('labelclass'))
            ->replace('label', $this->field->getLabel())
            ->setAttributes('attributes', $this->field->getAttributes())
            ->setValue('value', $this->field->getValue(), $this->field->getDefault());


        $items = $this->field->getOption('items');
        if(isset($items) && count($items)){

            $selected = false;

            foreach($items as &$item){
                if($item->value == $this->field->getValue()){
                    $item->selected = 'selected';
                    $selected = true;
                } else if ($item->value == $this->field->getDefault() && !$selected){
                    $item->selected = 'selected';
                } else {
                    $item->selected = '';
                }
            }

            $this->repeat('repeat', $items);
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