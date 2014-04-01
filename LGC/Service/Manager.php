<?php


/**
 * Description of Manager
 *
 * @author luis
 */
class LGC_Service_Manager 
{
    private static $instance;
    protected $services;
    
    private function __construct() {
        $this->services = array();
    }
    
    /** @return LGC_Service_Manager */
    public static function getInstance() {
        if (!self::$instance instanceof self) {
         self::$instance = new self;
        }
        return self::$instance;
    }
    
    /**
     * 
     * @param String $name
     * @return Object
     * @throws LGC_Service_Exception
     */
    public function getService($name) {
        if(!array_key_exists($name, $this->services)) {
            throw new LGC_Service_Exception("No se encuentra el servicio $name");
        }
        return $this->services[$name];
    }
 
    public function registerService($name, $object) {
        $this->services[$name] = $object;
    }
    
}
