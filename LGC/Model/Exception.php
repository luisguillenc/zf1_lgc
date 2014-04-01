<?php

class LGC_Model_Exception
    extends Exception
{
    /**
     * @var string
     */
    protected $_field;


    public function  __construct($message, $field = "", $code = 0,
                                Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_field = $field;
    }

    public function getField()
    {
        return $this->_field;
    }
}
