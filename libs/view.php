<?php

/**
 * @package     Synapse
 * @subpackage	View
 * @version		1.0.3
 */

defined('_INIT') or die;
defined('LIBXML_HTML_NODEFDTD') || define ('LIBXML_HTML_NODEFDTD', 4);

class View extends Object {

    private $templates      = array();
    private $_data          = array();
    private $templatePath   = null;
    private $directives     = array("include", "decorate", "messages");

	public function __construct($path = null)
	{
        if(!$path) $this->templatePath = VIEWS;

        return $this;
	}

    /**
     * Set the html template for the view
     * @param String|Array $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $paths = array();

        if(is_array($template)) {
            $paths = array_merge($paths, $template);
        } else {
            $paths[] = $template;
        }
        
        foreach($paths as $path)
        {
            $path = $this->templatePath .'/'. $path .'.php';
            if(!file_exists($path)){
                throw new Error( __('Template file not found!').' '.$path );
            }

            $this->templates[] = $path;
        }
                
        return $this;
    }

    /**
     * Add data to the view
     * @param Object|Array $data
     * @param String $name
     * @return $this
     */
    public function setData($_data, $name = null)
    {
        if($name)
        {
            $this->_data[] = array($name => $_data);
        }
        else
        {
            $this->_data[] = $_data;
        }

        return $this;
    }

    /**
     * Sets the path were the templates file are located
     * @param String $path
     */
    public function setTemplatePath($path)
    {
        $this->templatePath = $path;

        return $this;
    }

    protected function addIncludes(&$view, $data)
    {
        if(empty($view)) return;
		// convert html to UTF-8
		$view = mb_convert_encoding($view, 'HTML-ENTITIES', "UTF-8");

        

        $config = App::getConfig();

        if(isset($config->directives) && is_array($config->directives))
        {
            $this->directives = array_merge($this->directives, $config->directives); 
        }

        // check if directive class exists and include it
        foreach($this->directives as $directiveName)
        {
            // check if the directive class exists
            $directiveName = strtolower($directiveName);
            $directiveFileName = $directiveName.'.php';
            $directiveAppPath = DIRECTIVES.'/'.$directiveFileName;
            $directiveLibPath = LIBRARY.'/directives/'.$directiveFileName;
            $directivePath = null;

            if(file_exists($directiveAppPath)){
                $directivePath = $directiveAppPath;
            } else if (file_exists($directiveLibPath)){
                $directivePath = $directiveLibPath;
            } else {
                throw new Error( __('Directive file not found: {1}', $directiveFileName), null );
            }

            require_once($directivePath);

            $directiveSegments = explode("/", $directiveName);
            $directiveTag = array_pop($directiveSegments);
            $directiveClass  = ucfirst($directiveTag).'Directive';

            if(!class_exists($directiveClass)){
                throw new Error( __('Directive class not found!').' '.$directiveClass );
            }
        }

        $searching = true;
        while($searching)
        {
            $counter = 0;
            // for each directive
            foreach($this->directives as $directiveName)
            {
                $directiveClass = ucfirst($directiveName).'Directive';
                $directive = NULL;
                $directive = new $directiveClass();
                $container = $directive->isContainer();

                $pattern = $container ? ('/<'.$directiveName.'[^>]*>(.*?)<\\/'.$directiveName.'>/si') : ('/<'.$directiveName.'[^>]*>/si');  
                preg_match_all($pattern, $view, $directivesList);

                // check if there's at least one directive with the current name
                if(count($directivesList[0])){
                    for($i = count($directivesList[0]) - 1; $i >= 0; $i--)
                    {
                        $directive->reset()
                            ->setView($view)
                            ->setData($data);
                        
                        if($container) {
                            $directive->setTag($directivesList[0][$i])
                                ->setContent($directivesList[1][$i]);
                        } else {
                            $directive->setTag($directivesList[0][$i]);
                        }

                        $view = $directive->expand();
                    }

                    $counter++;
                }
            }
            if(!$counter) $searching = false;
        }
    }

    /**
     * Echo out the rendered HTML template
     */
    public function render()
	{
        foreach($this->_data as $_data){
            if(is_object($_data)){
                $_data = get_object_vars($_data);
            }
            extract($_data);
        }

        if(!count($this->templates)){
            throw new Error( __('Templates not defined!') );
        }

		ob_start();

        foreach($this->templates as $template)
        {
            require($template);
        }

		$view = ob_get_clean();

        $this->addIncludes($view, $this->_data);

        echo $view;
	}

}
