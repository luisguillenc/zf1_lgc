<?php


/**
 * Clase que sirve de almacen de repositorio para las diversas entidades.
 * También puede emplearse para guardar directamente los objetos sin necesidad
 * de obtener el repositorio correspondiente.
 * 
 * @category LGC
 * @package Model
 * @author luis
 */
class LGC_Model_EntityManager {

    /** @var LGC_Model_RepositoryInterface[] */
    protected $repositories;
    
    public function __construct() {
        $this->repositories = array();
    }
    
    /**
     * 
     * @param string $className
     * @param LGC_Model_RepositoryInterface $repository
     */
    public function registerRepository($className, LGC_Model_RepositoryInterface $repository) {
        $this->repositories[$className] = $repository;
    }

    /**
     * 
     * @param string $className
     * @return type
     * @throws Oajtm_Service_Exception
     */
    public function getRepository($className) {
        if(!array_key_exists($className, $this->repositories)) {
            throw new LGC_Model_Exception("No se encontró el repositorio de $className");
        }
        return $this->repositories[$className];
    }
    
    /**
     * 
     * @param LGC_Model_ObjectInterface $object
     * @return int
     * @throws LGC_Model_Exception
     */
    public function persist(LGC_Model_ObjectInterface $object) {
        if(!is_object($object)) {
            throw new LGC_Model_Exception("No es un objeto");
        }

        $className = get_class($object);
        $repo = $this->getRepository($className);
        return $repo->persist($object);
    }
    
    /**
     * 
     * @param LGC_Model_ObjectInterface $object
     * @return int
     * @throws LGC_Model_Exception
     */
    public function remove(LGC_Model_ObjectInterface $object) {
        if(!is_object($object)) {
            throw new LGC_Model_Exception("No es un objeto");
        }
        
        $className = get_class($object);
        $repo = $this->getRepository($className);
        return $repo->remove($object);
    }
    
    public function flush() {
        //no hago nada
    }

}

