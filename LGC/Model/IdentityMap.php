<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IdentityMap
 *
 * @author luis
 */
class LGC_Model_IdentityMap {
    
    private static $_instance;
    protected $_identities = array();
    
    private function __construct() { }
    
    /** @return LGC_Model_IdentityMap */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
         self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function add(LGC_Model_ObjectInterface $object) {
        if($object->getId() > 0) {
            $className = get_class($object);
            if(isset($this->_identities[$className][$object->getId()])) {
                return false;
            }
            $this->_identities[$className][$object->getId()] = $object;
        } else {
            throw new Oajtm_Model_Exception("El objeto no tiene identificación");
        }
    }
    
    public function get($className, $id) {
        if(isset($this->_identities[$className][$id])) {
            return $this->_identities[$className][$id];
        } else {
            return null;
        }
    }
    
    public function remove(LGC_Model_ObjectInterface $object) {
        if($object->getId() > 0) {
            $className = get_class($object);
            if(isset($this->_identities[$className][$object->getId()])) {
                unset($this->_identities[$className][$object->getId()]);
            }
        }

    }
}
