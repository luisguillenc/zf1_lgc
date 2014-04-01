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
 * @package    controller
 * @copyright  Copyright (c) 2010 Luis Guillén Civera
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt
 * @author     Luis Guillén Civera
 * @version    0.1
 */


abstract class LGC_Controller_DownloadController extends Zend_Controller_Action
{
    protected $_filesPath;
    public function init()
    {
        if(!$this->_filesPath) {
            throw new Exception("La trayectoria al directorio del controlador es requerida");
        }
    }

    public function indexAction()
    {
        $file = $this->getRequest()->getParam('file');
        if (!$file) {
            throw new Exception("Falta parámetro requerido");
        }
        //TODO: sanitizar más la entrada
        if(preg_match('[\.\/]', $file)) {
            throw new Exception("Caracteres no válidos");
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $file_path = $this->_filesPath."/".$file;
        if(!file_exists($file_path)) {
            $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
            return;
        }

        // required for IE, otherwise Content-disposition is ignored
        if(ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');
        
        //TODO: agregar comprobación de mime
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$file.'"');
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".@filesize($file_path));
        // por ficheros grandes
        set_time_limit(0);
        ob_end_flush();
        @readfile($file_path);
    }
}