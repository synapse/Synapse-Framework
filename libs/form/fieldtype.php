<?php

/**
 * @package     Synapse
 * @subpackage  Form/FieldType
 */

defined('_INIT') or die;

class FieldType {

    protected $template = array();
    protected $field = null;

    public function __construct($field)
    {
        $this->field = $field;
    }

    public function getTemplate()
    {
        if(is_array($this->template)) {
            return implode("\r\n", $this->template);
        }

        return $this->template;
    }

    public function setTemplate($template)
    {
        if(!is_array($template)){
            throw new Error('setTemplate expects an array, '.gettype($template).' given');
        }

        $this->template = $template;
        return $this;
    }

    public function render()
    {
        throw new Error('Render method must be overwritten');
    }

    protected function replace($key, $value, $range = false)
    {
        $template = $this->getTemplate();


        if($range){
            $startTag       = "{{".$key."}}";
            $endTag         = "{{/".$key."}}";

            $startTagPos    = strrpos($template, $startTag);
            $endTagPos      = strrpos($template,$endTag);
            $tagLength      = $endTagPos - $startTagPos + strlen($endTag);

            $template = substr_replace($template, $value ? $value : '', $startTagPos, $tagLength);
        } else {
            if ($value !== null || $value !== false) {
                $template = str_replace("{{" . $key . "}}", $value, $template);
            } else {
                $template = str_replace("{{" . $key . "}}", '', $template);
            }
        }

        $this->template = $template;

        return $this;
    }

    protected function setAttributes($key, $attributes)
    {
        if(!count($attributes)){
            $this->replace($key, null);
            return $this;
        }

        $attr = array();
        foreach($attributes as $name=>$val){
            $attr[] = $name.'="'.$val.'"';
        }

        $attributes = implode(" ", $attr);

        $this->replace($key, $attributes);

        return $this;
    }

    protected function setValue($key, $value, $default)
    {
        if($value !== null || $value !== false) {
            $this->replace($key, $value);
        } else {
            if($default){
                $this->replace($key, $default);
            } else {
                $this->replace($key, null);
            }
        }

        return $this;
    }

    protected function repeat($key, $values)
    {
        if(!count($values)){
            $this->replace($key, null, true);
            return $this;
        }

        $template = $this->getTemplate();

        $startTag       = "{{".$key."}}";
        $endTag         = "{{/".$key."}}";

        $startTagPos    = strrpos($template, $startTag);
        $endTagPos      = strrpos($template, $endTag);
        $tagLength      = $endTagPos - $startTagPos - strlen($endTag);

        $repeater = substr($template, $startTagPos + strlen($startTag), $tagLength);
        $items = array();

        foreach($values as $value){
            $repeat = $repeater;
            foreach($value as $k=>$v){

                $repeat = str_replace("{{".$k."}}", $v, $repeat);
            }
            $items[] = $repeat;
        }

        $this->replace($key, implode("\r\n", $items), true);

        return $this;
    }

    public function validate()
    {
        return true;
    }
}