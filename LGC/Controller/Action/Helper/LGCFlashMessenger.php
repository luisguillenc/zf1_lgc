<?php

/**
 * Luis Guillén Civera Library
 *
 * LICENSE
 *
 *    This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @category   LGC
 * @package    Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2010 Luis Guillén Civera
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt
 * @author     Luis Guillén Civera
 * @version    0.1
 */


/*
 *  Extraido de de:
 * http://bolsadeideas.cl/zsamer/2008/07/extendiendo-flashmessenger-action-helper/
 */

require_once 'Zend/Controller/Action/Helper/FlashMessenger.php';


class LGC_Controller_Action_Helper_LGCFlashMessenger extends Zend_Controller_Action_Helper_FlashMessenger
{

    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const SUCCESS   = 'success';

    /**
    * $_namespace - Instance namespace, default is 'default'
    *
    * @var string
    */
    protected $_namespace = 'default';


    public function addError($message, $class = null, $method = null)
    {
      return $this->_addMessage($message, self::ERROR, $class, $method);
    }

    public function addSuccess($message, $class = null, $method = null)
    {
      return $this->_addMessage($message, self::SUCCESS, $class, $method);;
    }

    public function addWarning($message, $class = null, $method = null)
    {
      return $this->_addMessage($message, self::WARNING, $class, $method);;
    }

    public function addNotice($message, $class = null, $method = null)
    {
      return $this->_addMessage($message, self::NOTICE, $class, $method);;
    }

    protected function _addMessage($message, $type, $class = null, $method = null)
    {
        if (self::$_messageAdded === false) {
            self::$_session->setExpirationHops(1, null, true);
        }

        if (!is_array(self::$_session->{$this->_namespace})) {
            self::$_session->{$this->_namespace}[$type] = array();
        }

        self::$_session->{$this->_namespace}[$type][] = $this->_factory($message, $type, $class, $method);

        return $this;
    }

    protected function _factory($message, $type, $class = null, $method = null)
    {
        $messg = new stdClass();
        $messg->message = $message;
        $messg->type = $type;
        $messg->class = $class;
        $messg->method = $method;
        return $messg;
    }

    /**
    * getMessages() - Get messages from a specific namespace
    *
    * @param unknown_type $namespace
    * @return array
    */
    public function getMessages($type = null)
    {
        if($type === null){
            return parent::getMessages();
        }

        if (isset(self::$_messages[$this->_namespace][$type])) {
            return self::$_messages[$this->_namespace][$type];
        }

        return array();
    }

    /**
    * getCurrentMessages() - get messages that have been added to the current
    * namespace within this request
    *
    * @return array
    */
    public function getCurrentMessages($type = null)
    {
        if($type === null){
            return parent::getCurrentMessages();
        }

        if (isset(self::$_session->{$this->_namespace}[$type])) {
            return self::$_session->{$this->_namespace}[$type];
        }

        return array();
    }
}
