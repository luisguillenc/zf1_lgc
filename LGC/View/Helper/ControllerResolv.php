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
 * @package    view
 * @copyright  Copyright (c) 2010 Luis Guillén Civera
 * @license    http://www.gnu.org/licenses/agpl-3.0.txt
 * @author     Luis Guillén Civera
 * @version    0.1
 */


class LGC_View_Helper_ControllerResolv extends Zend_View_Helper_FormElement
{
    public function ControllerResolv($object)
    {
        return LGC_Controller_Resolver::getControllerPath($object);
    }

}

