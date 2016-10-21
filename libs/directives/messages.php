<?php
/**
 * @package     Synapse
 * @subpackage	Directives/Messages
 * @version		1.0.0
 */

class MessagesDirective extends Directive {
    
    protected $container = false;
    protected $attributes = array();

    public function render()
    {
        $messages = App::getInstance()->getMessageQueue();
        $messagesString = '';
        if(count($messages)){
            foreach($messages as $type=>$message){
                $messagesString .= '<div class="'.$type.'"><p>'. implode('</p><p>', $message) .'</p></div>';
            }
        }   

        return $messagesString;
    }

}