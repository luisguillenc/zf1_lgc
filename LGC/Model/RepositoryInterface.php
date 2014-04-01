<?php

/**
 * Interfaz con los métodos que debe implementar todo repositorio
 * 
 * @category OAJogm
 * @package Core
 * @subpackage Models
 * @author luis
 */

interface LGC_Model_RepositoryInterface {
    /**
     * @param int $id
     * @return LGC_Model_ObjectInterface
     */
    public function find($id);

    /**
     * @return LGC_Model_ObjectInterface[]
     */
    public function findAll();

    /**
     * @param LGC_Model_ObjectInterface $object
     * @return int
     */
    public function persist(LGC_Model_ObjectInterface $object);

    /**
     * @param LGC_Model_ObjectInterface $object
     * @return int
     */
    public function remove(LGC_Model_ObjectInterface $object);
}
