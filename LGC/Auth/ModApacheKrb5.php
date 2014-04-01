<?php

/**
 * Clase adapter de Zend_Auth para validación integrada de apache con kerberos
 * 
 * @category LGC
 * @package Auth
 * @author luis
 */
class LGC_Auth_ModApacheKrb5 implements Zend_Auth_Adapter_Interface
{

    /** @var string */
    protected $_realm = "";
    
    public function __construct($realm) {
        $this->_realm = $realm;
    }
    
    public function authenticate() 
    {
        if(!isset($_SERVER['REDIRECT_REMOTE_USER']) 
                || $_SERVER['REDIRECT_REMOTE_USER'] == "") {
            return new Zend_Auth_Result(
                    Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, ""
                    );
        }
        
        $principal = $_SERVER['REDIRECT_REMOTE_USER'];
        
        if($this->_realm != "") {
            //si no es del dominio
            if(strpos($principal, "@".$this->_realm) === FALSE) {
                return new Zend_Auth_Result(
                    Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, ""
                    );
            }
            
            $username = substr($principal, 0, strpos($principal, "@"));
            
        } else {
            
            $username = $_SERVER['REDIRECT_REMOTE_USER'];
        }

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $username);
    }
    
}
