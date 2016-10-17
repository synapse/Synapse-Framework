<?php

/**
 * @package     Synapse
 * @subpackage	Directives/Decorate
 * @version		1.0.0
 */


class DecorateDirective extends Directive {
    
    protected $attributes = array('template');

    public function expand()
    {
        if(!file_exists(VIEWS .'/'. $this->template .'.php')) {
            return;
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

            $replaceTags = $snippet->getElementsByTagName('replace'); 
            
            
            if($replaceTags->length)
            {
                $replaceTag = $replaceTags->item(0);
                $a =  $this->_tag->nodeValues();
                foreach($this->_tag->childNodes as $child)
                {                    
                //     $replaceTag->parentNode->insertBefore($child, $replaceTag);
                }

                // $replaceTag->parentNode->removeChild($replaceTag);
            }

            $this->_tag->parentNode->insertBefore($snippet, $this->_tag);   
        }
    }
}