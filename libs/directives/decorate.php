<?php
/**
 * @package     Synapse
 * @subpackage	Directives/Decorate
 * @version		1.0.0
 */

class DecorateDirective extends Directive {
    
    protected $container = true;
    protected $attributes = array(
        'template' => true
    );

    public function expand()
    {
        if(!file_exists(VIEWS .'/'. $this->template .'.php')) {
            return;
        }

        ob_start();
        require(VIEWS .'/'. $this->template .'.php');
        $html = ob_get_clean();

        preg_match('/<replace[^>]*>/si', $html, $replace);  

        if(count($replace))
        {
            $html = str_replace($replace[0], $this->getContent(), $html);
        }
        
        $this->replaceTag($html);

        return $this->getView();
    }
}