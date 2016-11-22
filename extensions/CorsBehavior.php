<?php

/*
 * CorsBehavior class file
 * 
 * @author Igor Manturov, Jr. <im@youtu.me>
 */

/**
 * CorsBehavior Automatically adds the Access-Control-Allow-Origin response 
 * header for specific routes.
 * 
 * @version 1.0
 *
 */
class CorsBehavior extends CBehavior
{
    
    private $_allowOrigin;
    
    private $_route = array();
    
    
    public function events()
    {
        return array_merge(parent::events(), 
                array('onBeginRequest' => 'onBeginRequestHandler'));
    }
    
    
    public function onBeginRequestHandler($event)
    {
        if (is_null($this->_allowOrigin))
        {
            return;
        }
        
        $route = Yii::app()->getUrlManager()
                ->parseUrl(Yii::app()->getRequest());
        
        if (in_array($route, $this->_route))
        {
            
            $origin = $this->parseHeaders();
            
            if ($origin !== false)
            {
                $this->setAllowOriginHeader($origin);
            }
        }
    }
    
    
    /**
     * Sets list of routes for CORS-requests.
     * @param array $route list of routes (controllerID/actionID)
     * @throws CException
     */
    public function setRoute($route)
    {
        if (!is_array($route))
        {
            throw new CException('The value of the "route" property must be an '
                    . 'array.');
        }
        
        $this->_route = $route;
    }
    
    
    /**
     * Sets the allowed origin.
     * @param string $origin The origin that is allowed to access the resource.
     * A "*" can be specified to enable access to resource from any origin. 
     * A wildcard can be used to specify list of allowed origins, 
     * e.g. "*.yourdomain.com" (sub.yourdomain.com, yourdomain.com, 
     * sub.sub.yourdomain.com will be allowed origins in that case)
     * @throws CExecption
     */
    public function setAllowOrigin($origin)
    {
        if (!is_string($origin))
        {
            throw new CExecption('The value of the "allowOrigin" property must be '
                    . 'a string.');
        }
        
        $this->_allowOrigin = $origin;
    }
    
    
    /**
     * Parses headers and returns the value of the Origin request header.
     * @return mixed The origin that is allowed to access the resource. 
     * (the value of the Origin request header), otherwise false. 
     * @throws CException
     */
    protected function parseHeaders()
    {
        if (!function_exists('getallheaders'))
        {
            throw new CException('PHP version must be greater than or equal to '
                    . '5.4.0, or PHP should be installed as an Apache module.');
        }
        
        $headers = getallheaders();
        
        if ($headers === false)
        {
            return false;
        }
        
        $headers = array_change_key_case($headers, CASE_LOWER);
        
        if (!key_exists('origin', $headers))
        {
            return false;
        }
        
        $origin = $headers['origin'];
        $origin = parse_url($origin, PHP_URL_HOST);
        
        if (is_null($origin))
        {
            return false;
        }
        
        if(strlen($this->_allowOrigin) === 1)
        {
            return $headers['origin'];
        }
        
        if (stripos($this->_allowOrigin, '*') === false)
        {
            return $origin === $this->_allowOrigin ? $headers['origin'] : false;
        }
        
        $pattern = '/' . substr($this->_allowOrigin, 1) . '$/';
        
        if (substr($this->_allowOrigin, 2) === $origin
                || preg_match($pattern, $origin) === 1)
        {
            return $headers['origin'];
        }
        
    }
    
    
    /**
     * Sets Access-Control-Allow-Origin response header.
     * @param string $origin the value of the Access-Control-Allow-Origin response 
     * header.
     */
    protected function setAllowOriginHeader($origin)
    {
        header('Access-Control-Allow-Origin: ' . $origin);
    }
   
}
