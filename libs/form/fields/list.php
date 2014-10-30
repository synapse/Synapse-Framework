<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType/List
 */

defined('_INIT') or die;

class ListFieldType extends FieldType
{

<<<<<<< HEAD
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
=======
    public function render()
    {
        $html = array();

        $html[] = '<div>';
        $labelClass = $this->field->getOption('labelclass');
        $labelClass = !empty($labelClass) ? 'class="'.$labelClass.'"' : '';
        $html[] = '<label class="'.$labelClass.'">'.$this->field->getLabel().'</label>';


        if ($this->field->hasOption('layout') && $this->field->getOption('layout') == 'checkboxes') {
            $html[] = $this->getCheckboxes();
        } else if ($this->field->hasOption('layout') && $this->field->getOption('layout') == 'radios'){
            $html[] = $this->getRadios();
        } else {
            $html[] = $this->getDropdown();
        }

        $html[] = '</div>';

        return implode("", $html);
    }

    protected function getDropdown()
    {
        $html = array();


        $html[] = '<select name="'.$this->field->getName().'" '.$this->field->getAttributes(true).'>';

        foreach($this->field->getOption('items') as $item){

            $selected = '';
            $found = false;
            if(($item->value == $this->field->getValue()) || ($this->field->hasAttribute('multiple') && in_array($item->value, $this->field->getValue()))){
                $selected = 'selected';

                if(!$this->field->hasAttribute('multiple')) {
                    $found = true;
                }

            } else if ($item->value == $this->field->getDefault() && !$found){
                $selected = 'selected';
            } else {
                $selected = '';
            }


            $html[] = '<option value="'.$item->value.'" '.$selected.'>'.$item->text.'</option>';
        }

        $html[] = '</select>';
        return implode("", $html);
    }

    protected function getCheckboxes()
    {
        $html = array();

        foreach($this->field->getOption('items') as $item){
            $checked = in_array($item->value, $this->field->getValue()) ? 'checked' : '';
            $html[] = '<div>';
            $html[] = '    <label>';
            $html[] = '     <input type="checkbox" name="'.$this->field->getName().'" value="'.$item->value.'" '.$checked.' />'.$item->text;
            $html[] ='    </label>';
            $html[] ='</div>';
        }

        return implode("", $html);
    }

    protected function getRadios()
    {
        $html = array();

        foreach($this->field->getOption('items') as $item){
            $checked = ($item->value == $this->field->getValue()) ? 'checked' : '';
            $html[] = '<div>';
            $html[] = '    <label>';
            $html[] = '     <input type="radio" name="'.$this->field->getName().'" value="'.$item->value.'" '.$checked.' />'.$item->text;
            $html[] ='    </label>';
            $html[] ='</div>';
        }

        return implode("", $html);
>>>>>>> dev
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