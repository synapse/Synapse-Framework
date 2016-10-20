<?php
/**
 * @package     Synapse
 * @subpackage	Directives/Include
 * @version		1.0.0
 */

class IncludeDirective extends Directive {
    
    protected $container = false;
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

        $this->replaceTag($html);

        return $this->getView();
    }

}