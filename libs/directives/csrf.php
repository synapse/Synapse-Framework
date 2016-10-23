<?php
/**
 * @package     Synapse
 * @subpackage	Directives/If
 * @version		1.0.0
 */

class CsrfDirective extends Directive {
    
    protected $container = false;
    protected $attributes = array();

    public function render()
    {
        $session = App::getInstance()->getSession();
        $token = $session->getToken();
        return '<input type="hidden" name="'. $token .'" value="1" />';
    }
}