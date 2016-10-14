<?php

/**
 * @package     Synapse
 * @subpackage	Directive
 * @version		1.0.0
 */


class Directive extends Object {
    
    protected $attributes = array();
    protected $_dom = null;
    protected $_tag = null; 
    protected $_data = null;

    public function __construct(&$dom, &$tag, $data)
    {
        $this->_dom = $dom;
        $this->_tag = $tag;
        $this->_data = $data;

        $attributes = $tag->attributes;

        // for every predefined attribute
        foreach($this->attributes as $attr)
        {
            $this->$attr = $attributes->getNamedItem($attr)->nodeValue;
        }
    }

    public function getAttributes() 
    {
        return $this->attributes;
    }

    protected function getData()
    {
        return $this->_data;
    }

    public function expand()
    {
        
    }
}