<?php

/**
 * @package     Synapse
 * @subpackage	Directive
 * @version		1.0.0
 */


class Directive extends Object {
    
    protected $attributes = array();
    protected $_dom = null;
    protected $_tag = ''; 

    public function __construct(&$dom, $tag)
    {
        $this->_dom = $dom;
        $this->_tag = $tag;
    }

    public function getAttributes() 
    {
        return $this->attributes;
    }

    public function expand()
    {
        // check the DOM for the requested directive
        $directivesDOM = $this->_dom->getElementsByTagName($this->_tag);

        // check if there's at least one directive with the current name
        if($directivesDOM->length)
        {
            // for every copy of the same directive inside the DOM
            foreach($directivesDOM as $directiveDOM)
            {
                $attributes = $directiveDOM->attributes;
                
                // for every predefined attribute
                foreach($this->attributes as $attr)
                {
                    $this->$attr = $attributes->getNamedItem($attr)->nodeValue;
                }
            }
        }
    }
}