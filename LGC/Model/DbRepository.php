<?php

/**
 * Clase abstracta que implementa un repositorio en base de datos relacional
 * usando Zend_Db_Table. Para evitar consultas repetitivas a la base de datos
 * hace uso de un identity map.
 * 
 * @category LGC
 * @package Model
 * @author luis
 */
abstract class LGC_Model_DbRepository 
    implements LGC_Model_RepositoryInterface
{

    /** @var string */
    protected $_defaultObjectClass;
        
    /** @var array of Zend_Db_Table_Abstract */
    protected $_dbTables = array();
    
    /** @var LGC_Model_IdentityMap */
    protected $_identityMap;

    /**
     * 
     * @param string $objectClass clase principal que almacenará el repositorio
     * @param Zend_Db_Table_Abstract $dbTable tabla principal
     */
    protected function __construct($objectClass, Zend_Db_Table_Abstract $dbTable) { 
        $this->_defaultObjectClass = $objectClass;
        
        $this->_setDbTable($objectClass, $dbTable);
        $this->_identityMap = LGC_Model_IdentityMap::getInstance();
    }
    
    /**
     * @param string $objectClass
     * @param Zend_Db_Table_Abstract $dbTable
     */
    protected function _setDbTable($objectClass, Zend_Db_Table_Abstract $dbTable) {
        $this->_dbTables[$objectClass] = $dbTable;
    }
    
    /** @var Zend_Db_Table_Abstract */
    protected function _getDbTable($objectClass) {
        if(isset($this->_dbTables[$objectClass])) {
            return $this->_dbTables[$objectClass];
        } else {
            throw new Oajtm_Model_Exception("No se encuentra dbtable de $objectClass");
        }
    }

    
    /**
     * Utiliza reflexión para fijar el atributo protegido id del objeto pasado
     * 
     * @param LGC_Model_ObjectInterface $object
     * @param int $id
     */
    protected function _setObjectId(LGC_Model_ObjectInterface $object, $id) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $property = $reflectionClass->getProperty("_id");
        $property->setAccessible(true);
        $property->setValue($object, $id);
        $property->setAccessible(false);        

    }

    /**
     * Método abstracto que se debe implementar que implementa la lógica de
     * hidratación del objeto
     * 
     * @param array $data
     * @return Object
     */
    abstract protected function _hydrateObject($data);
    
    /**
     * Método abstracto que se debe implementar que devuelve los datos del 
     * objeto en un array mapeado a los atributos de la tabla
     * 
     * @return array
     */
    abstract protected function _getDbData(LGC_Model_ObjectInterface $object);
    
    
    
    /**
     * @return array of LGC_Model_ObjectInterface
     */
    protected function _findByField($fieldname, $value) {

        $select = $this->_getDbTable($this->_defaultObjectClass)->select()->where("$fieldname = ?", $value);
        $data = $this->_getDbTable($this->_defaultObjectClass)->fetchAll($select);
        
        $return = array();
        foreach($data as $row) {
            $object = $this->_identityMap->get($this->_defaultObjectClass, $row['id']);
            if($object === null) {
                $return[] = $this->_hydrateObject($row);
            } else {
                $return[] = $object;
            }
        }
        return $return;
    }

    /**
     * @return LGC_Model_ObjectInterface
     */
    public function find($id) {
        $identityObject = $this->_identityMap->get($this->_defaultObjectClass, $id);
        if($identityObject !== null) {
            return $identityObject;
        }
        
        $row = $this->_getDbTable($this->_defaultObjectClass)->find($id)->current();
        if(!$row) {
            return null;
        }

        $dbObject = $this->_hydrateObject($row);
        return $dbObject;
    }

    /**
     * 
     * @return LGC_Model_ObjectInterface[]
     */
    public function findAll() {
        $rows = $this->_getDbTable($this->_defaultObjectClass)->fetchAll();
        $return = array();
        foreach($rows as $row) {
            $object = $this->_identityMap->get($this->_defaultObjectClass, $row['id']);
            if($object === null) {
                $return[] = $this->_hydrateObject($row);
            } else {
                $return[] = $object;
            }
        }
        
        return $return;
    }
    
    /**
     * @param LGC_Model_ObjectInterface $object
     * @return int id asignado
     */
    protected function _persistToDb(LGC_Model_ObjectInterface $object) {
        $objectClass = get_class($object);
        $data = $this->_getDbData($object);

        if($object->getId() > 0) {
            $where = $this->_getDbTable($objectClass)->getAdapter()->quoteInto('id = ?', $data['id']);
            return $this->_getDbTable($objectClass)->update($data, $where);
        } else {
            $id = $this->_getDbTable($objectClass)->insert($data);
            if($id > 0) {
                $this->_setObjectId($object, $id);
            }
            return $id;
        }
    }

    /**
     * @param LGC_Model_ObjectInterface $object
     * @return int estado devuelto del delete
     */
    protected function _removeFromDb(LGC_Model_ObjectInterface $object) {
        $objectClass = get_class($object);
        $where = $this->_getDbTable($objectClass)->getAdapter()->quoteInto('id = ?', $object->getId());
        return $this->_getDbTable($objectClass)->delete($where);
    }
    
    /**
     * 
     * @param LGC_Model_ObjectInterface $object
     * @return int id asignado
     */
    public function persist(LGC_Model_ObjectInterface $object) {
        $id = $this->_persistToDb($object);
        $this->_identityMap->add($object);
        return $id;
    }
    
    /**
     * 
     * @param LGC_Model_ObjectInterface $object
     * @return int estado del borrado
     * @throws LGC_Model_Exception
     */
    public function remove(LGC_Model_ObjectInterface $object) {
        if($object->getId() > 0) {
            $ret = $this->_removeFromDb($object);
            $this->_identityMap->remove($object);
            unset($object);
            return $ret;            
        } else {
            throw new LGC_Model_Exception("El objeto no se encuentra en el repositorio");
        }
    }
    
}
