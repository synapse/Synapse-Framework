<?php

/**
 * @package     Synapse
 * @subpackage  URI/Request
 */

defined('_INIT') or die;

class Request {

    protected $params       = '';
    protected $type         = null;
    protected $slugs        = null;
    protected $ajax         = true;
    protected $files        = array();
    protected $contentType  = null;
    protected $origin       = null;
    protected $userAgent    = null;

    public function __construct()
    {
        $this->params = new stdClass();

        $request     = null;
        $this->type = $this->getType();

        switch($this->type){
            case 'GET':
            case 'DELETE':
                $request = $_GET;
                break;
            case 'POST':
            case 'PUT':
                $request = $_POST;

                if(count($_FILES))
                {
                    $this->files = $_FILES;
                }

                break;
        }

        $this->contentType  = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
        $this->origin       = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
        $this->userAgent    = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

        if(count($request)){
            foreach($request as $k=>$v){
                if($k === 'slug'){
                    $this->slugs = $v;
                    continue;
                }
                $this->params->$k = $v;
                $this->$k = $v;
            }
        }

        if(in_array($this->type, array('POST','PUT')) && count($this->getFiles()))
        {
            $this->params->files = $this->getFiles();
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            $this->ajax = true;
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getSlugs()
    {
        return $this->slugs;
    }

    public function getValue($key)
    {
        return isset($this->params->$key)?$this->params->$key:null;
    }

    public function setValue($key, $value)
    {
        $this->$key = $value;
    }

    public function parseJSON()
    {
        if(strpos($this->contentType, 'application/json') === false){
            return null;
        }

        $rawJSON = file_get_contents('php://input');
        return json_decode($rawJSON);
    }

    /**
     * Returns the request type. Example GET POST
     * @return mixed
     */
    public function getType()
    {
        if($this->type) return $this->type;

        if(isset($_POST['_METHOD']) && !empty($_POST['_METHOD'])){
            $method = $_POST['_METHOD'];
            unset($_POST['_METHOD']);
            if(strtolower($method) == 'put'){
                return 'PUT';
            } else if (strtolower($method) == 'delete'){
                return 'DELETE';
            }
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Check if the request is AJAX based
     * @return bool
     */
    public function isAjax()
    {
        return $this->ajax;
    }

    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Returns the request IP address
     * @return mixed
     */
    public function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Returns the request language
     * @param $long | bool
     * @return String | null
     */
    public function getLanguage($long = false)
    {
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
            $lng = $this->parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
            return $long ? $lng : substr($lng, 0, 2);
        } else {
            $lng = $this->parseDefaultLanguage(NULL);
            return $long ? $lng : substr($lng, 0, 2);
        }
    }

    /**
     * Parses the request language and returns the one with the highest Q value
     * @param $http_accept
     * @param string $deflang
     * @return int|string
     */
    protected function parseDefaultLanguage($http_accept, $deflang = "en-US") {
        if(isset($http_accept) && strlen($http_accept) > 1)  {
            # Split possible languages into array
            $x = explode(",",$http_accept);
            foreach ($x as $val) {
                #check for q-value and create associative array. No q-value means 1 by rule
                if(preg_match("/(.*);q=([0-1]{0,1}.d{0,4})/i",$val,$matches))
                    $lang[$matches[1]] = (float)$matches[2];
                else
                    $lang[$val] = 1.0;
            }

            #return default language (highest q-value)
            $qval = 0.0;
            foreach ($lang as $key => $value) {
                if ($value > $qval) {
                    $qval = (float)$value;
                    $deflang = $key;
                }
            }
        }

        if(strlen($deflang) == 2){
            return $deflang.'-'.strtoupper($deflang);
        }

        return $deflang;
    }

    /**
     * Return the request content type
     * @return String | null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Returns the request user agent
     * @return String | null
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Returns the request origin
     * @return String | null
     */
    public function getOrigin()
    {
        return $this->origin;
    }
}

?>