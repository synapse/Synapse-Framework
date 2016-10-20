<?php
/**
 * @package     Synapse
 * @subpackage	Directives/Messages
 * @version		1.0.0
 */

class MessagesDirective extends Directive {
    
    protected $container = false;
    protected $attributes = array();

    public function expand()
    {
        $messages = App::getInstance()->getMessageQueue();
        if(count($messages)){
            $messagesString = '';

            foreach($messages as $type=>$message){
                $messagesString .= '<div class="'.$type.'"><p>'. implode('</p><p>', $message) .'</p></div>';
            }

            $this->replaceTag($messagesString);

            return $this->getView();
        }    
    }

}