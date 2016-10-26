<?php
/**
 * @package     Synapse
 * @subpackage	Directives/If
 * @version		1.0.0
 */

class IfDirective extends Directive {
    
    protected $container = true;
    protected $attributes = array(
        "condition" => true
    );

    public function render()
    {
        foreach($this->getData() as $data){
            if(is_object($data)){
                $data = get_object_vars($data);
            }
            extract($data);
        }

        $truthful = eval('return '.$this->condition.';');
        $content = $this->getContent();

        preg_match('/<else\\/>/si', $content, $else);
        preg_match_all('/<elseif [^>]*\\/>/si', $this->getTag(), $elseifs);

        if($truthful) 
        {
            if(count($elseifs[0]))
            {
                preg_match('/<if [^>]*>(.*?)<elseif [^>]*\\/>/si', $this->getTag(), $ifelseif);
                return $ifelseif[1];
            }

            if(count($else))
            {
                preg_match('/<if [^>]*>(.*?)<else\\/>/si', $this->getTag(), $ifelse);
                return $ifelse[1];
            }

            
            return $content;
        }

        else
        {
            //preg_match_all('/<elseif[^>]*\\/>(.*?)<\\/if>/si', $this->getTag(), $elseif);
            // there are else if statements
            if(count($elseifs[0]))
            {
                // there are multiple else if statements
                if(count($elseifs[0]) > 1)
                {

                }
                // there is only one else if statements
                else
                {
                    if(count($else))
                    {
                        preg_match_all('/<elseif [^>]*\\/>(.*?)<else\\/>/si', $this->getTag(), $elseifWithElse);
                        

                        foreach($elseifWithElse[0] as $ei)
                        {
                            $eiDOM = new DOM($ei);
                            $eiCondition = $eiDOM->root->firstChild()->getAttribute('condition');
                        }
                    }

                    else 
                    {

                    }
                }
            }

            // there are no else if statements
            else
            {
                if(count($else))
                {
                    preg_match('/<else\\/>(.*?)<\\/if>/si', $this->getTag(), $elsesec);
                    return $elsesec[1];
                }
            }
        }

        
        return '';
    }
}