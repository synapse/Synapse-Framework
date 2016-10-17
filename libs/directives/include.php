<?php

/**
 * @package     Synapse
 * @subpackage	Directives/Include
 * @version		1.0.0
 */

defined('LIBXML_HTML_NODEFDTD') || define ('LIBXML_HTML_NODEFDTD', 4);

class IncludeDirective extends Directive {
    
    protected $attributes = array('template', 'text');

    public function expand()
    {
        if(!file_exists(VIEWS .'/'. $this->template .'.php')) {
            return;
        }

        foreach($this->getData() as $data){
            if(is_object($data)){
                $data = get_object_vars($data);
            }
            extract($data);
        }

        ob_start();
        require(VIEWS .'/'. $this->template .'.php');
        $html = ob_get_clean();

        $dom = new DOMDocument("1.0", "utf-8");
        $dom->resolveExternals = true;
        $dom->substituteEntities = false;
        $dom->loadHTML($html);
        $nodes = $dom->getElementsByTagName('body')->item(0)->childNodes;
        
        foreach($nodes as $node) {
            $snippet = $this->_dom->importNode($node, true);   
            $this->_tag->parentNode->insertBefore($snippet, $this->_tag);   
        }
    }

}