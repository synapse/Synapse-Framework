<?php

/**
 * @package     Synapse
 * @subpackage	Directive
 * @version		1.0.0
 */


class Directive extends Object {
    
    protected $attributes = array();
    protected $_view = null;
    protected $_tag = null; 
    protected $_content = null;
    protected $_data = null;

    public function __construct($view = null, $tag = null, $content = null, $data = null)
    {
        if($view) $this->_view = $view;
        if($tag) 
        {
            $this->setTag($tag);
        }
        if($content) $this->_content = $content;
        if($data) $this->_data = $data;

        return $this;
    }

    protected function getTag()
    {
        return $this->_tag;
    }

    public function setTag($tag)
    {
        $this->_tag = $tag;
        $dom = new DOM($tag);

        if($dom)
        {
            $attributes = $dom->root->firstChild()->getAllAttributes();
            foreach($this->attributes as $attribute => $required)
            {
                if(!isset($attributes[$attribute]) || empty($attributes[$attribute]))
                {
                    throw new Error( __('Missing required attribute `{1}` for directive `<{2}>`', $attribute, $dom->root->firstChild()->tag), null );
                }

                $this->$attribute = $attributes[$attribute];
            }
            
            $dom->clear();
        }
        
        return $this;
    }

    protected function getView()
    {
        return $this->_view;
    }

    public function setView($view)
    {
        $this->_view = $view;
        return $this;
    }

    protected function replaceTag($content)
    {
        $this->_view = str_replace($this->getTag(), $content, $this->_view);
        return $this;
    }

    protected function getContent()
    {
        return $this->_content;
    }

    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    protected function getData()
    {
        return $this->_data;
    }

    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    public function expand()
    {
        return $this->_view;
    }
}