<?php

/**
 * Clase adapter de Zend_Auth para validación integrada de apache
 * 
 * @category LGC
 * @package Auth
 * @author luis
 */
class LGC_Auth_ModApache implements Zend_Auth_Adapter_Interface
{

    public function authenticate() 
    {
        if(!isset($_SERVER['REDIRECT_REMOTE_USER']) 
                || $_SERVER['REDIRECT_REMOTE_USER'] == "") {
            return new Zend_Auth_Result(
                    Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, ""
                    );
        }
        
        $username = $_SERVER['REDIRECT_REMOTE_USER'];

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $username);
    }
    
}
